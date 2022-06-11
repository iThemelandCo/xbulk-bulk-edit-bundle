<?php

namespace wccbef\classes\controllers;

use wccbef\classes\helpers\Meta_Fields;
use wccbef\classes\helpers\Render;
use wccbef\classes\helpers\Filter_Helper;
use wccbef\classes\helpers\Sanitizer;
use wccbef\classes\repositories\Column;
use wccbef\classes\repositories\Coupon;
use wccbef\classes\repositories\History;
use wccbef\classes\repositories\Meta_Field;
use wccbef\classes\repositories\Product;
use wccbef\classes\repositories\Search;
use wccbef\classes\repositories\Setting;

class WCCBEF_Ajax
{
    private static $instance;
    private $coupon_repository;
    private $history_repository;

    public static function register_callback()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->coupon_repository = new Coupon();
        $this->history_repository = new History();
        add_action('wp_ajax_wccbef_inline_edit', [$this, 'inline_edit']);
        add_action('wp_ajax_wccbef_add_meta_keys_by_coupon_id', [$this, 'add_meta_keys_by_coupon_id']);
        add_action('wp_ajax_wccbef_column_manager_add_field', [$this, 'column_manager_add_field']);
        add_action('wp_ajax_wccbef_edit_by_calculator', [$this, 'edit_by_calculator']);
        add_action('wp_ajax_wccbef_column_manager_get_fields_for_edit', [$this, 'column_manager_get_fields_for_edit']);
        add_action('wp_ajax_wccbef_coupons_filter', [$this, 'coupons_filter']);
        add_action('wp_ajax_wccbef_save_filter_preset', [$this, 'save_filter_preset']);
        add_action('wp_ajax_wccbef_coupons_bulk_edit', [$this, 'coupons_bulk_edit']);
        add_action('wp_ajax_wccbef_create_new_coupon', [$this, 'create_new_coupon']);
        add_action('wp_ajax_wccbef_delete_coupons', [$this, 'delete_coupons']);
        add_action('wp_ajax_wccbef_duplicate_coupon', [$this, 'duplicate_coupon']);
        add_action('wp_ajax_wccbef_load_filter_profile', [$this, 'load_filter_profile']);
        add_action('wp_ajax_wccbef_get_text_editor_content', [$this, 'get_text_editor_content']);
        add_action('wp_ajax_wccbef_change_count_per_page', [$this, 'change_count_per_page']);
        add_action('wp_ajax_wccbef_filter_profile_change_use_always', [$this, 'filter_profile_change_use_always']);
        add_action('wp_ajax_wccbef_get_default_filter_profile_coupons', [$this, 'get_default_filter_profile_coupons']);
        add_action('wp_ajax_wccbef_sort_by_column', [$this, 'sort_by_column']);
        add_action('wp_ajax_wccbef_get_products', [$this, 'get_products']);
        add_action('wp_ajax_wccbef_get_categories', [$this, 'get_categories']);
        add_action('wp_ajax_wccbef_get_coupon_products', [$this, 'get_coupon_products']);
        add_action('wp_ajax_wccbef_get_coupon_categories', [$this, 'get_coupon_categories']);
        add_action('wp_ajax_wccbef_get_coupon_used_in', [$this, 'get_coupon_used_in']);
        add_action('wp_ajax_wccbef_get_coupon_used_by', [$this, 'get_coupon_used_by']);
    }

    public function get_default_filter_profile_coupons()
    {
        $filter_data = Filter_Helper::get_active_filter_data();
        $result = $this->coupon_repository->get_coupons_list($filter_data, 1);
        $this->make_response([
            'success' => true,
            'filter_data' => $filter_data,
            'coupons_list' => $result->coupons_list,
            'product_ids' => $result->product_ids,
            'exclude_product_ids' => $result->exclude_product_ids,
            'product_categories' => $result->product_categories,
            'exclude_product_categories' => $result->exclude_product_categories,
            'pagination' => $result->pagination,
            'status_filters' => $result->status_filters,
            'coupons_count' => $result->count,
        ]);
    }

    public function get_products()
    {
        if (!isset($_POST['search'])) {
            return false;
        }

        $list = [];
        $product_repository = new Product();
        $products = $product_repository->get_products([
            'posts_per_page' => '-1',
            'post_type' => ['product', 'product_variation'],
            's' => strtolower(sanitize_text_field($_POST['search'])),
        ]);

        if (!empty($products)) {
            foreach ($products as $product) {
                $list['results'][] = [
                    'id' => $product->ID,
                    'text' => $product->post_title,
                ];
            }
        }

        $this->make_response($list);
    }

    public function get_categories()
    {
        $list = [];
        $product_repository = new Product();
        $categories = $product_repository->get_categories_by_name(sanitize_text_field($_POST['search']));
        if (!empty($categories)) {
            foreach ($categories as $category) {
                if ($category instanceof \WP_Term) {
                    $list['results'][] = [
                        'id' => $category->term_id,
                        'text' => $category->name
                    ];
                }
            }
        }
        $this->make_response($list);
    }

    public function get_coupon_products()
    {
        if (empty($_POST['coupon_id']) || empty($_POST['field'])) {
            return false;
        }

        $coupon = $this->coupon_repository->get_coupon(intval($_POST['coupon_id']));
        if (!($coupon instanceof \WC_Coupon)) {
            return false;
        }

        $coupon_products = [];
        $getter_method = ($_POST['field'] == 'product_ids') ? 'get_product_ids' : 'get_excluded_product_ids';
        $product_ids = method_exists($coupon, $getter_method) ? $coupon->{$getter_method}() : [];
        $product_repository = new Product();
        $products = $product_repository->get_products([
            'posts_per_page' => '-1',
            'post_status' => 'any',
            'post_type' => ['product', 'product_variation'],
            'post__in' => (!empty($product_ids)) ? array_map('intval', $product_ids) : [0],
        ]);

        if (!empty($products)) {
            foreach ($products as $product) {
                if ($product instanceof \WP_Post) {
                    $coupon_products[$product->ID] = $product->post_title;
                }
            }
        }
        $this->make_response([
            'success' => true,
            'coupon_products' => $coupon_products
        ]);
    }

    public function get_coupon_categories()
    {
        if (empty($_POST['coupon_id']) || empty($_POST['field'])) {
            return false;
        }

        $coupon = $this->coupon_repository->get_coupon(intval($_POST['coupon_id']));
        if (!($coupon instanceof \WC_Coupon)) {
            return false;
        }

        $getter_method = ($_POST['field'] == 'product_categories') ? 'get_product_categories' : 'get_excluded_product_categories';
        $category_ids = method_exists($coupon, $getter_method) ? $coupon->{$getter_method}() : [];
        $product_categories = [];
        if (!empty($category_ids) && is_array($category_ids)) {
            $product_categories = get_terms([
                'taxonomy' => 'product_cat',
                'include' => $category_ids,
                'hide_empty' => false,
                'fields' => 'id=>name'
            ]);
        }

        $this->make_response([
            'success' => true,
            'product_categories' => $product_categories
        ]);
    }

    public function get_coupon_used_in()
    {
        if (empty($_POST['coupon_code'])) {
            return false;
        }

        $order_ids = $this->coupon_repository->get_order_ids_by_coupon(sanitize_text_field($_POST['coupon_code']));

        $orders = [];
        if (!empty($order_ids) && is_array($order_ids)) {
            foreach ($order_ids as $order) {
                if (!empty($order['order_id'])) {
                    $orders[$order['order_id']] = esc_url(admin_url("post.php?post={$order['order_id']}&action=edit"));
                }
            }
        }

        $this->make_response([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function get_coupon_used_by()
    {
        if (empty($_POST['coupon_id'])) {
            return false;
        }

        $coupon = $this->coupon_repository->get_coupon(intval($_POST['coupon_id']));

        if (!($coupon instanceof \WC_Coupon)) {
            return false;
        }

        $user_ids = $coupon->get_used_by();
        $users = get_users([
            'include' => (!empty($user_ids)) ? $user_ids : [0],
            'fields' => array('ID', 'display_name')
        ]);

        $used_by = [];
        if (!empty($users) && is_array($users)) {
            foreach ($users as $user) {
                if (!empty($user->display_name)) {
                    $used_by[] = [
                        'link' => esc_url(admin_url("user-edit.php?user_id={$user->ID}")),
                        'name' => $user->display_name
                    ];
                }
            }
        }

        $this->make_response([
            'success' => true,
            'users' => !empty($used_by) ? $used_by : false
        ]);
    }

    public function inline_edit()
    {
        if (isset($_POST)) {
            $result = false;
            if (!is_array($_POST['coupons_ids'])) {
                return false;
            }

            $coupon_ids = array_map('intval', $_POST['coupons_ids']);
            if (is_array($_POST['field']) && isset($_POST['field'][1])) {
                $field_type = 'custom_field';
                $field = sanitize_text_field($_POST['field'][1]);
                $operator = 'taxonomy_replace';
                $field_for_history = [sanitize_text_field($_POST['field'][0]) => sanitize_text_field($_POST['field'][1])];
            } else {
                $field_type = 'main_field';
                $field = sanitize_text_field($_POST['field']);
                $field_for_history = [sanitize_text_field($_POST['field'])];
                $operator = null;
            }

            $this->save_history($coupon_ids, $field_for_history, Sanitizer::array($_POST['value']), History::INLINE_OPERATION);

            $result = $this->coupon_repository->update($coupon_ids, [
                'field_type' => $field_type,
                'field' => $field,
                'value' => Sanitizer::array($_POST['value']),
                'operator' => $operator,
            ]);

            if ($result) {
                $histories = $this->history_repository->get_histories();
                $reverted = $this->history_repository->get_latest_reverted();
                $histories_rendered = Render::html(WCCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
                $this->make_response([
                    'success' => true,
                    'message' => esc_html__('Success !', WBEBL_NAME),
                    'history_items' => $histories_rendered,
                    'reverted' => !empty($reverted),
                    'edited_ids' => $coupon_ids,
                ]);
            }
        }
        return false;
    }

    public function coupons_filter()
    {
        if (isset($_POST['filter_data'])) {
            $data = Sanitizer::array($_POST['filter_data']);
            $current_page = !empty($_POST['current_page']) ? intval($_POST['current_page']) : 1;
            $filter_result = $this->coupon_repository->get_coupons_list($data, $current_page);
            $this->make_response([
                'success' => true,
                'coupons_list' => $filter_result->coupons_list,
                'product_ids' => $filter_result->product_ids,
                'exclude_product_ids' => $filter_result->exclude_product_ids,
                'product_categories' => $filter_result->product_categories,
                'exclude_product_categories' => $filter_result->exclude_product_categories,
                'pagination' => $filter_result->pagination,
                'status_filters' => $filter_result->status_filters,
                'coupons_count' => $filter_result->count,
            ]);
        }
        return false;
    }

    public function add_meta_keys_by_coupon_id()
    {
        if (isset($_POST)) {
            $coupon_id = intval($_POST['coupon_id']);
            $coupon = $this->coupon_repository->get_coupon($coupon_id);
            if (!($coupon instanceof \WC_Coupon)) {
                die();
            }
            $meta_keys = Meta_Fields::remove_default_meta_keys(array_keys(get_post_meta($coupon_id)));
            $output = "";
            if (!empty($meta_keys)) {
                foreach ($meta_keys as $meta_key) {
                    $meta_field['key'] = $meta_key;
                    $meta_fields_main_types = Meta_Field::get_main_types();
                    $meta_fields_sub_types = Meta_Field::get_sub_types();
                    $output .= Render::html(WCCBEF_VIEWS_DIR . "meta_field/meta_field_item.php", compact('meta_field', 'meta_fields_main_types', 'meta_fields_sub_types'));
                }
            }

            $this->make_response($output);
        }
        return false;
    }

    public function edit_by_calculator()
    {
        if (isset($_POST)) {
            if (is_array($_POST['coupon_ids'])) {
                $coupon_ids = array_map('intval', $_POST['coupon_ids']);
                $field = sanitize_text_field($_POST['field']);
                $operator = sanitize_text_field($_POST['operator']);
                $type = !empty($_POST['operator_type']) ? sanitize_text_field($_POST['operator_type']) : 'n';
                $round_item = intval($_POST['round_item']);
                if (!empty($coupon_ids)) {
                    foreach ($coupon_ids as $coupon_id) {
                        $price = 0;
                        $value = floatval($_POST['value']);
                        $coupon = $this->coupon_repository->get_coupon(intval($coupon_id));
                        if (!($coupon instanceof \WC_Coupon)) {
                            return false;
                        }
                        if (is_array($field) && isset($field[1])) {
                            $coupon_fields = $this->coupon_repository->coupon_to_array($coupon);
                            switch ($field[0]) {
                                case 'custom_field':
                                    $price = (isset($coupon_fields['custom_field'][$field[1]][0])) ? floatval($coupon_fields['custom_field'][$field[1]][0]) : 0;
                                    break;
                            }
                            $field_for_history = [$field[0] => $field[1]];
                            $field_type = 'custom_field';
                            $field_name = sanitize_text_field($field[1]);
                        } else {
                            $regular_price = 0;
                            $sale_price = 0;
                            $coupon_fields = $this->coupon_repository->coupon_to_array($coupon);
                            $price = $coupon_fields[$field];
                            $field_for_history = [$field];
                            $field_type = 'main_field';
                            $field_name = sanitize_text_field($field);
                        }

                        switch ($type) {
                            case 'n':
                                switch ($operator) {
                                    case '+':
                                        $value += $price;
                                        break;
                                    case '-':
                                        $value = $price - $value;
                                        break;
                                    case 'sp+':
                                        $value += $sale_price;
                                        break;
                                    case 'rp-':
                                        $value = $regular_price - $value;
                                        break;
                                }
                                break;
                            case '%':
                                switch ($operator) {
                                    case '+':
                                        $value = $price + ($price * $value / 100);
                                        break;
                                    case '-':
                                        $value = $price - ($price * $value / 100);
                                        break;
                                    case 'sp+':
                                        $value = $sale_price + ($sale_price * $value / 100);
                                        break;
                                    case 'rp-':
                                        $value = $regular_price - ($regular_price * $value / 100);
                                        break;
                                }
                                break;
                        }
                        if (!empty($round_item)) {
                            $value = \wccbef\classes\helpers\Coupon_Helper::round($value, $round_item);
                        }

                        $this->save_history($coupon_ids, $field_for_history, $value, History::INLINE_OPERATION);

                        $result = $this->coupon_repository->update([$coupon_id], [
                            'field_type' => $field_type,
                            'field' => $field_name,
                            'value' => $value,
                            'operator' => null,
                        ]);
                    }
                }

                $histories = $this->history_repository->get_histories();
                $reverted = $this->history_repository->get_latest_reverted();
                $histories_rendered = Render::html(WCCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
                $edited_ids = $coupon_ids;
                $edited_ids[] = intval($_POST['coupon_id']);
                if ($result) {
                    $this->make_response([
                        'success' => true,
                        'message' => esc_html__('Success !', WBEBL_NAME),
                        'history_items' => $histories_rendered,
                        'reverted' => !empty($reverted),
                        'edited_ids' => $edited_ids,
                    ]);
                }
            }
        }
        return false;
    }

    public function column_manager_add_field()
    {
        if (isset($_POST)) {
            if (isset($_POST['field_name']) && is_array($_POST['field_name']) && !empty($_POST['field_name'])) {
                $output = '';
                $field_action = sanitize_text_field($_POST['field_action']);
                for ($i = 0; $i < count($_POST['field_name']); $i++) {
                    $field_name = sanitize_text_field($_POST['field_name'][$i]);
                    $field_label = (!empty($_POST['field_label'][$i])) ? sanitize_text_field($_POST['field_label'][$i]) : $field_name;
                    $field_title = (!empty($_POST['field_label'][$i])) ? sanitize_text_field($_POST['field_label'][$i]) : $field_name;
                    $output .= Render::html(WCCBEF_VIEWS_DIR . "column_manager/field_item.php", compact('field_name', 'field_label', 'field_action', 'field_title'));
                }
                $this->make_response($output);
            }
        }

        return false;
    }

    public function column_manager_get_fields_for_edit()
    {
        if (isset($_POST['preset_key'])) {
            $preset = (new Column())->get_preset(sanitize_text_field($_POST['preset_key']));
            if ($preset) {
                $output = '';
                $fields = [];
                if (isset($preset['fields'])) {
                    foreach ($preset['fields'] as $field) {
                        $field_info = [
                            'field_name' => $field['name'],
                            'field_label' => $field['label'],
                            'field_title' => $field['title'],
                            'field_background_color' => $field['background_color'],
                            'field_text_color' => $field['text_color'],
                            'field_action' => "edit",
                        ];
                        $fields[] = sanitize_text_field($field['name']);
                        $output .= Render::html(WCCBEF_VIEWS_DIR . 'column_manager/field_item.php', $field_info);
                    }
                }

                $this->make_response([
                    'html' => $output,
                    'fields' => implode(',', $fields),
                ]);
            }
        }

        return false;
    }

    public function save_filter_preset()
    {
        if (!empty($_POST['preset_name'])) {
            $data = Sanitizer::array($_POST['filter_data']);
            $filter_item['name'] = sanitize_text_field($_POST['preset_name']);
            $filter_item['date_modified'] = date('Y-m-d H:i:s');
            $filter_item['key'] = 'preset-' . rand(1000000, 9999999);
            $filter_item['filter_data'] = $data;
            $save_result = (new Search())->update($filter_item);
            if (!$save_result) {
                return false;
            }
            $new_item = Render::html(WCCBEF_VIEWS_DIR . 'modals/filter_profile_item.php', compact('filter_item'));
            $this->make_response([
                'success' => $save_result,
                'new_item' => $new_item,
            ]);
        }
        return false;
    }

    public function coupons_bulk_edit()
    {
        if (!empty($_POST['new_data']) && is_array($_POST['new_data'])) {
            if (!empty($_POST['coupon_ids'])) {
                $coupon_ids = array_map('intval', $_POST['coupon_ids']);
            } elseif (!empty($_POST['filter_data'])) {
                $args = \wccbef\classes\helpers\Coupon_Helper::set_filter_data_items(Sanitizer::array($_POST['filter_data']), [
                    'posts_per_page' => '-1',
                    'fields' => 'ids',
                    'post_status' => 'any',
                    'post_type' => ['shop_coupon'],
                ]);
                $coupon_ids = ($this->coupon_repository->get_coupons($args))->posts;
            } else {
                return false;
            }

            $new_data = Sanitizer::array($_POST['new_data']);
            $fields = [];
            $new_value = [];
            foreach ($new_data as $field => $data_item) {
                if (!empty($data_item)) {
                    if ($field == 'custom_field') {
                        foreach ($data_item as $item) {
                            if (!empty($item['value']) || $item['value'] == '0') {
                                $new_value[$item['field']] = $item['value'];
                                $fields['custom_field'][] = $item['field'];
                            }
                        }
                    } else {
                        if (!empty($data_item['value']) || $data_item['value'] == '0') {
                            $fields[] = $field;
                            $new_value[$field] = $data_item['value'];
                        }
                    }
                }
            }
            $this->save_history($coupon_ids, $fields, $new_value, History::BULK_OPERATION);

            foreach ($new_data as $field => $data_item) {
                if (!empty($data_item)) {
                    if ($field == 'custom_field') {
                        foreach ($data_item as $item) {
                            if (!empty($item['value'])) {
                                $this->coupon_repository->update($coupon_ids, [
                                    'field_type' => $field,
                                    'field' => $item['field'],
                                    'value' => $item['value'],
                                    'operator' => (!empty($item['operator'])) ? $item['operator'] : null,
                                    'replace' => (!empty($item['replace'])) ? $item['replace'] : null,
                                    'sensitive' => (!empty($item['sensitive'])) ? $item['sensitive'] : null,
                                    'round_item' => (!empty($item['round_item'])) ? $item['round_item'] : null,
                                    'used_for_variations' => (!empty($item['used_for_variations'])) ? $item['used_for_variations'] : null,
                                ]);
                            }
                        }
                    } else {
                        if (!empty($data_item['value']) || $data_item['value'] == '0' || (!empty($data_item['operator']) && in_array($data_item['operator'], ['text_remove_duplicate', 'number_clear']))) {
                            $this->coupon_repository->update($coupon_ids, [
                                'field_type' => 'main_field',
                                'field' => $field,
                                'value' => $data_item['value'],
                                'operator' => (!empty($data_item['operator'])) ? $data_item['operator'] : null,
                                'replace' => (!empty($data_item['replace'])) ? $data_item['replace'] : null,
                                'sensitive' => (!empty($data_item['sensitive'])) ? $data_item['sensitive'] : null,
                                'round_item' => (!empty($data_item['round_item'])) ? $data_item['round_item'] : null,
                            ]);
                        }
                    }
                }
            }

            $histories = $this->history_repository->get_histories();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(WCCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'coupon_ids' => $coupon_ids,
                'history_items' => $histories_rendered,
                'reverted' => !empty($reverted),
            ]);
        }
        return false;
    }

    public function create_new_coupon()
    {
        if (isset($_POST) && !empty($_POST['count'])) {
            $coupons = [];
            for ($i = 1; $i <= intval($_POST['count']); $i++) {
                $coupons[] = $this->coupon_repository->create();
            }
            $this->make_response([
                'success' => true,
                'coupon_ids' => $coupons,
            ]);
        }
    }

    public function delete_coupons()
    {
        if (isset($_POST['coupon_ids']) && is_array($_POST['coupon_ids']) && !empty($_POST['delete_type'])) {
            $coupons_ids = array_map('intval', $_POST['coupon_ids']);
            switch ($_POST['delete_type']) {
                case 'trash':
                    foreach ($coupons_ids as $coupon_id) {
                        $this->save_history_for_delete($coupon_id);
                        wp_trash_post(intval($coupon_id));
                    }
                    break;
                case 'permanently':
                    foreach ($coupons_ids as $coupon_id) {
                        wp_delete_post(intval($coupon_id));
                    }
                    break;
            }

            $histories = $this->history_repository->get_histories();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(WCCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', WBEBL_NAME),
                'history_items' => $histories_rendered,
                'reverted' => !empty($reverted),
                'edited_ids' => $coupons_ids,
            ]);
        }
        return false;
    }

    private function save_history_for_delete($coupon_id)
    {
        $create_history = $this->history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize(['coupon_delete']),
            'operation_type' => 'bulk',
            'operation_date' => date('Y-m-d H:i:s'),
        ]);
        if (!$create_history) {
            return false;
        }
        $this->history_repository->create_history_item([
            'history_id' => intval($create_history),
            'historiable_id' => intval($coupon_id),
            'field' => serialize(['coupon_delete']),
            'prev_value' => null,
            'new_value' => null,
        ]);
    }

    public function duplicate_coupon()
    {
        $message = esc_html__('Error !', WBEBL_NAME);
        if (isset($_POST['coupon_ids']) && !empty($_POST['coupon_ids']) && !empty($_POST['duplicate_number'])) {
            $result = $this->coupon_repository->duplicate(array_map('intval', $_POST['coupon_ids']), intval($_POST['duplicate_number']));
            if ($result) {
                $message = esc_html__('Success !', WBEBL_NAME);
            }
        }

        $this->make_response([
            'success' => $message,
        ]);
    }

    public function load_filter_profile()
    {
        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();

            $preset = $search_repository->get_preset(sanitize_text_field($_POST['preset_key']));
            if (!isset($preset['filter_data'])) {
                return false;
            }
            $search_repository = new Search();
            $search_repository->update_current_data([
                'last_filter_data' => $preset['filter_data']
            ]);
            $result = $this->coupon_repository->get_coupons_list($preset['filter_data'], 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $preset['filter_data'],
                'coupons_list' => $result->coupons_list,
                'product_ids' => $result->product_ids,
                'exclude_product_ids' => $result->exclude_product_ids,
                'product_categories' => $result->product_categories,
                'exclude_product_categories' => $result->exclude_product_categories,
                'pagination' => $result->pagination,
                'status_filters' => $result->status_filters,
                'coupons_count' => $result->count,
            ]);
        }
        return false;
    }

    public function update_coupon_taxonomy()
    {
        if (isset($_POST['coupon_ids']) && is_array($_POST['coupon_ids'])) {
            $coupon_ids = array_map('intval', $_POST['coupon_ids']);
            if (is_array($_POST['field']) && isset($_POST['field'][0]) && isset($_POST['field'][1])) {
                $field = sanitize_text_field($_POST['field'][0]);
                $taxonomy = sanitize_text_field($_POST['field'][1]);
            } else {
                $field = 'taxonomy';
                $taxonomy = sanitize_text_field($_POST['field']);
            }

            $values = Sanitizer::array($_POST['values']);
            $this->save_history($coupon_ids, [$field => $taxonomy], $values, History::INLINE_OPERATION);

            $result = $this->coupon_repository->update($coupon_ids, [
                'field_type' => $field,
                'field' => $taxonomy,
                'value' => $values,
                'operator' => 'taxonomy_replace',
            ]);
            if (!$result) {
                return false;
            }

            $histories = $this->history_repository->get_histories();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(WCCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', WBEBL_NAME),
                'history_items' => $histories_rendered,
                'reverted' => !empty($reverted),
                'edited_ids' => $coupon_ids,
            ]);
        }
        return false;
    }

    public function get_text_editor_content()
    {
        if (isset($_POST['coupon_id']) && isset($_POST['field'])) {
            $field = sanitize_text_field($_POST['field']);
            $field_type = sanitize_text_field($_POST['field_type']);

            $coupon_object = $this->coupon_repository->get_coupon(intval($_POST['coupon_id']));
            if (!($coupon_object instanceof \WC_Coupon)) {
                return false;
            }
            $coupon = $this->coupon_repository->coupon_to_array($coupon_object);
            switch ($field_type) {
                case 'meta_field':
                case 'custom_field':
                    $value = (isset($coupon[$field_type][$field])) ? $coupon[$field_type][$field][0] : '';
                    break;
                default:
                    $value = $coupon[$field];
                    break;
            }

            $this->make_response([
                'success' => true,
                'content' => $value,
            ]);
        }
        return false;
    }

    public function change_count_per_page()
    {
        if (isset($_POST['count_per_page'])) {
            $setting_repository = new Setting();
            $setting_repository->update_current_settings([
                'count_per_page' => intval($_POST['count_per_page'])
            ]);
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function filter_profile_change_use_always()
    {
        if (isset($_POST['preset_key'])) {
            (new Search())->update_use_always(sanitize_text_field($_POST['preset_key']));
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function sort_by_column()
    {
        if (!empty($_POST['column_name']) && !empty($_POST['sort_type']) && !empty($_POST['filter_data'])) {
            $filter_data = Sanitizer::array($_POST['filter_data']);
            $setting_repository = new Setting();
            $setting_repository->update_current_settings([
                'sort_by' => sanitize_text_field($_POST['column_name']),
                'sort_type' => sanitize_text_field(strtoupper($_POST['sort_type'])),
            ]);
            $result = $this->coupon_repository->get_coupons_list($filter_data, 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $filter_data,
                'coupons_list' => $result->coupons_list,
                'product_ids' => $result->product_ids,
                'exclude_product_ids' => $result->exclude_product_ids,
                'product_categories' => $result->product_categories,
                'exclude_product_categories' => $result->exclude_product_categories,
                'pagination' => $result->pagination,
                'status_filters' => $result->status_filters,
                'coupons_count' => $result->count,
            ]);
        }
        return false;
    }

    private function save_history($coupon_ids, $fields, $new_value, $operation_type)
    {
        $create_history = $this->history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize($fields),
            'operation_type' => sanitize_text_field($operation_type),
            'operation_date' => date('Y-m-d H:i:s'),
        ]);

        if (!$create_history) {
            return false;
        }

        foreach ($coupon_ids as $coupon_id) {
            $coupon_object = $this->coupon_repository->get_coupon(intval($coupon_id));
            if (!($coupon_object instanceof \WC_Coupon)) {
                return false;
            }
            $coupon_item = $this->coupon_repository->coupon_to_array($coupon_object);
            if (!empty($fields)) {
                foreach ($fields as $field_type => $field) {
                    if (is_array($field)) {
                        $new_val = [];
                        $prev_val = [];
                        foreach ($field as $filed_name) {
                            $encoded_field = strtolower(urlencode($filed_name));
                            switch ($field_type) {
                                case 'custom_field':
                                    $new_val['custom_field'][$encoded_field] = $new_value[$encoded_field];
                                    $prev_val['custom_field'][$encoded_field] = (isset($coupon_item[$field_type][$encoded_field][0])) ? $coupon_item[$field_type][$encoded_field][0] : '';
                                    break;
                                case 'taxonomy':
                                    $new_val['taxonomy'][$encoded_field] = $new_value[$encoded_field];
                                    $prev_val['taxonomy'][$encoded_field] = ($encoded_field == 'coupon_tag') ? wp_get_post_terms($coupon_item['id'], $encoded_field, ['fields' => 'names']) : wp_get_post_terms($coupon_item['id'], $encoded_field, ['fields' => 'ids']);
                                    break;
                                default:
                                    break;
                            }
                        }
                    } else {
                        $encoded_field = strtolower(urlencode($field));
                        if (is_numeric($field_type)) {
                            $prev_val = (isset($coupon_item[$field])) ? $coupon_item[$field] : '';
                            if ($field == '_thumbnail_id') {
                                $new_val = [
                                    'id' => intval($new_value),
                                    'small' => wp_get_attachment_image_src(intval($new_value), [40, 40]),
                                    'big' => wp_get_attachment_image_src(intval($new_value), [600, 600]),
                                ];
                            } else {
                                $new_val = (!empty($new_value[$field])) ? $new_value[$field] : $new_value;
                            }
                        } else {
                            switch ($field_type) {
                                case 'custom_field':
                                    $new_val['custom_field'][$encoded_field] = $new_value;
                                    $prev_val['custom_field'][$encoded_field] = (isset($coupon_item[$field_type][$encoded_field][0])) ? $coupon_item[$field_type][$encoded_field][0] : '';
                                    break;
                                case 'taxonomy':
                                    $new_val['taxonomy'][$encoded_field] = $new_value;
                                    $prev_val['taxonomy'][$encoded_field] = ($encoded_field == 'coupon_tag') ? wp_get_post_terms($coupon_item['id'], $encoded_field, ['fields' => 'names']) : wp_get_post_terms($coupon_item['id'], $encoded_field, ['fields' => 'ids']);
                                    break;
                                default:
                                    break;
                            }
                        }
                    }

                    $this->history_repository->create_history_item([
                        'history_id' => intval($create_history),
                        'historiable_id' => intval($coupon_id),
                        'field' => (!empty($field_type) && !is_numeric($field_type)) ? serialize([$field_type => $field]) : serialize([$field]),
                        'prev_value' => serialize($prev_val),
                        'new_value' => serialize($new_val),
                    ]);
                }
            }
        }
        return true;
    }

    private function make_response($data)
    {
        echo (is_array($data)) ? json_encode($data) : sprintf('%s', $data);
        die();
    }
}
