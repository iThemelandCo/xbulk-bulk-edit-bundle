<?php

namespace wcbef\classes\controllers;

use wcbef\classes\helpers\Meta_Fields;
use wcbef\classes\helpers\Others;
use wcbef\classes\helpers\Render;
use wcbef\classes\helpers\Sanitizer;
use wcbef\classes\helpers\Session;
use wcbef\classes\helpers\Taxonomy;
use wcbef\classes\repositories\Column;
use wcbef\classes\repositories\History;
use wcbef\classes\repositories\Meta_Field;
use wcbef\classes\repositories\Product;
use wcbef\classes\repositories\Search;

class WCBEF_Ajax
{
    private static $instance;
    private $product_repository;
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
        $this->product_repository = new Product();
        $this->history_repository = new History();
        add_action('wp_ajax_wcbef_inline_edit', [$this, 'wcbef_inline_edit']);
        add_action('wp_ajax_wcbef_add_meta_keys_by_product_id', [$this, 'add_meta_keys_by_product_id']);
        add_action('wp_ajax_wcbef_add_meta_keys_manual', [$this, 'add_meta_keys_manual']);
        add_action('wp_ajax_wcbef_column_manager_add_field', [$this, 'column_manager_add_field']);
        add_action('wp_ajax_wcbef_edit_by_calculator', [$this, 'edit_by_calculator']);
        add_action('wp_ajax_wcbef_column_manager_get_fields_for_edit', [$this, 'column_manager_get_fields_for_edit']);
        add_action('wp_ajax_wcbef_products_filter', [$this, 'products_filter']);
        add_action('wp_ajax_wcbef_save_filter_preset', [$this, 'save_filter_preset']);
        add_action('wp_ajax_wcbef_products_bulk_edit', [$this, 'products_bulk_edit']);
        add_action('wp_ajax_wcbef_get_products_name', [$this, 'get_products_name']);
        add_action('wp_ajax_wcbef_create_new_product', [$this, 'create_new_product']);
        add_action('wp_ajax_wcbef_get_attribute_values', [$this, 'get_attribute_values']);
        add_action('wp_ajax_wcbef_get_attribute_values_for_delete', [$this, 'get_attribute_values_for_delete']);
        add_action('wp_ajax_wcbef_get_attribute_values_for_attach', [$this, 'get_attribute_values_for_attach']);
        add_action('wp_ajax_wcbef_get_product_variations', [$this, 'get_product_variations']);
        add_action('wp_ajax_wcbef_get_product_variations_for_attach', [$this, 'get_product_variations_for_attach']);
        add_action('wp_ajax_wcbef_set_products_variations', [$this, 'set_products_variations']);
        add_action('wp_ajax_wcbef_delete_products_variations', [$this, 'delete_products_variations']);
        add_action('wp_ajax_wcbef_delete_products', [$this, 'delete_products']);
        add_action('wp_ajax_wcbef_duplicate_product', [$this, 'duplicate_product']);
        add_action('wp_ajax_wcbef_add_product_taxonomy', [$this, 'add_product_taxonomy']);
        add_action('wp_ajax_wcbef_add_product_attribute', [$this, 'add_product_attribute']);
        add_action('wp_ajax_wcbef_load_filter_profile', [$this, 'load_filter_profile']);
        add_action('wp_ajax_wcbef_delete_filter_profile', [$this, 'delete_filter_profile']);
        add_action('wp_ajax_wcbef_save_column_profile', [$this, 'save_column_profile']);
        add_action('wp_ajax_wcbef_get_text_editor_content', [$this, 'get_text_editor_content']);
        add_action('wp_ajax_wcbef_update_product_taxonomy', [$this, 'update_product_taxonomy']);
        add_action('wp_ajax_wcbef_update_product_attribute', [$this, 'update_product_attribute']);
        add_action('wp_ajax_wcbef_history_filter', [$this, 'history_filter']);
        add_action('wp_ajax_wcbef_change_count_per_page', [$this, 'change_count_per_page']);
        add_action('wp_ajax_wcbef_filter_profile_change_use_always', [$this, 'filter_profile_change_use_always']);
        add_action('wp_ajax_wcbef_get_default_filter_profile_products', [$this, 'get_default_filter_profile_products']);
        add_action('wp_ajax_wcbef_get_taxonomy_parent_select_box', [$this, 'get_taxonomy_parent_select_box']);
        add_action('wp_ajax_wcbef_get_product_data', [$this, 'get_product_data']);
        add_action('wp_ajax_wcbef_get_product_files', [$this, 'get_product_files']);
        add_action('wp_ajax_wcbef_add_new_file_item', [$this, 'add_new_file_item']);
        add_action('wp_ajax_wcbef_sort_by_column', [$this, 'sort_by_column']);
    }

    public function wcbef_inline_edit()
    {
        if (isset($_POST)) {
            $result = false;
            if (!is_array($_POST['products_ids'])) {
                return false;
            }
            $product_ids = array_map('intval', $_POST['products_ids']);

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

            $this->save_history($product_ids, $field_for_history, sanitize_text_field($_POST['value']), History::INLINE_OPERATION);

            $result = $this->product_repository->update($product_ids, [
                'field_type' => $field_type,
                'field' => $field,
                'value' => sanitize_text_field($_POST['value']),
                'operator' => $operator,
            ]);

            if ($result) {
                $histories = $this->history_repository->get_histories();
                $reverted = $this->history_repository->get_latest_reverted();
                $histories_rendered = Render::html(WCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
                $this->make_response([
                    'success' => true,
                    'message' => esc_html__('Success !', WBEBL_NAME),
                    'history_items' => $histories_rendered,
                    'reverted' => !empty($reverted),
                    'edited_ids' => $product_ids,
                ]);
            }
        }
        return false;
    }

    public function add_meta_keys_by_product_id()
    {
        if (isset($_POST)) {
            $product_id = intval($_POST['product_id']);
            $product = wc_get_product($product_id);
            if (!($product instanceof \WC_Product)) {
                die();
            }
            $meta_keys = Meta_Fields::remove_default_meta_keys(array_keys(get_post_meta($product_id)));
            $output = "";
            if (!empty($meta_keys)) {
                foreach ($meta_keys as $meta_key) {
                    $meta_field['key'] = $meta_key;
                    $meta_fields_main_types = Meta_Field::get_main_types();
                    $meta_fields_sub_types = Meta_Field::get_sub_types();
                    $output .= Render::html(WCBEF_VIEWS_DIR . "meta_fields/meta_field_item.php", compact('meta_field', 'meta_fields_main_types', 'meta_fields_sub_types'));
                }
            }

            $this->make_response($output);
        }
        return false;
    }

    public function add_meta_keys_manual()
    {
        if (isset($_POST)) {
            $meta_field['key'] = sanitize_text_field($_POST['meta_key_name']);
            $meta_fields_main_types = Meta_Field::get_main_types();
            $meta_fields_sub_types = Meta_Field::get_sub_types();
            $output = Render::html(WCBEF_VIEWS_DIR . "meta_fields/meta_field_item.php", compact('meta_field', 'meta_fields_main_types', 'meta_fields_sub_types'));
            $this->make_response($output);
        }
        return false;
    }

    public function edit_by_calculator()
    {
        if (isset($_POST)) {
            if (is_array($_POST['product_ids'])) {
                $product_ids = array_map('intval', $_POST['product_ids']);
                $field = sanitize_text_field($_POST['field']);
                $operator = sanitize_text_field($_POST['operator']);
                $type = !empty($_POST['operator_type']) ? sanitize_text_field($_POST['operator_type']) : 'n';
                $round_item = intval($_POST['round_item']);
                if (!empty($product_ids)) {
                    foreach ($product_ids as $product_id) {
                        $price = 0;
                        $value = floatval($_POST['value']);
                        $product = $this->product_repository->get_product(intval($product_id));
                        if (!($product instanceof \WC_Product)) {
                            return false;
                        }
                        if (is_array($field) && isset($field[1])) {
                            $product_fields = $this->product_repository->get_product_fields($product);
                            switch ($field[0]) {
                                case 'meta_field':
                                    $price = (isset($product_fields['meta_field'][$field[1]][0])) ? floatval($product_fields['meta_field'][$field[1]][0]) : 0;
                                    break;
                            }
                            $field_for_history = [$field[0] => $field[1]];
                            $field_type = 'custom_field';
                            $field_name = sanitize_text_field($field[1]);
                        } else {
                            $regular_price = floatval($product->get_regular_price());
                            $sale_price = floatval($product->get_sale_price());
                            $product_fields = $this->product_repository->get_product_fields($product);
                            $price = $product_fields[$field];
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
                            $value = \wcbef\classes\helpers\Product::round($value, $round_item);
                        }

                        $this->save_history($product_ids, $field_for_history, $value, History::INLINE_OPERATION);

                        $result = $this->product_repository->update([$product_id], [
                            'field_type' => $field_type,
                            'field' => $field_name,
                            'value' => $value,
                            'operator' => null,
                        ]);
                    }
                }

                $histories = $this->history_repository->get_histories();
                $reverted = $this->history_repository->get_latest_reverted();
                $histories_rendered = Render::html(WCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
                $edited_ids = $product_ids;
                $edited_ids[] = intval($_POST['product_id']);
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
                    $output .= Render::html(WCBEF_VIEWS_DIR . "column_manager/field_item.php", compact('field_name', 'field_label', 'field_action', 'field_title'));
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
                        $output .= Render::html(WCBEF_VIEWS_DIR . 'column_manager/field_item.php', $field_info);
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
            $filter_item['name'] = sanitize_text_field($_POST['preset_name']);
            $filter_item['date_modified'] = date('Y-m-d H:i:s');
            $filter_item['key'] = 'preset-' . rand(1000000, 9999999);
            $filter_item['filter_data'] = sanitize_text_field($_POST['filter_data']);
            $save_result = (new Search())->update($filter_item);
            if (!$save_result) {
                return false;
            }
            $new_item = Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/filter_profile_item.php', compact('filter_item'));
            $this->make_response([
                'success' => $save_result,
                'new_item' => $new_item,
            ]);
        }
        return false;
    }

    public function products_filter()
    {
        if (isset($_POST['filter_data'])) {
            $data = Sanitizer::array($_POST['filter_data']);
            $current_page = !empty($_POST['current_page']) ? intval(sanitize_text_field($_POST['current_page'])) : 1;
            $filter_result = $this->product_repository->get_products_list($data, $current_page);
            $this->make_response([
                'success' => true,
                'products_list' => $filter_result->products_list,
                'pagination' => $filter_result->pagination,
                'products_count' => $filter_result->count,
            ]);
        }
        return false;
    }

    public function products_bulk_edit()
    {
        if (!empty($_POST['new_data']) && is_array($_POST['new_data'])) {
            if (!empty($_POST['product_ids'])) {
                $product_ids = array_map('intval', $_POST['product_ids']);
            } elseif (!empty($_POST['filter_data'])) {
                $args = \wcbef\classes\helpers\Product::set_filter_data_items($_POST['filter_data'], [
                    'posts_per_page' => '-1',
                    'fields' => 'ids',
                    'post_type' => ['product'],
                ]);
                $product_ids = ($this->product_repository->get_products($args))->posts;
            } else {
                return false;
            }
            $fields = [];
            $new_value = [];
            foreach ($_POST['new_data'] as $field => $data_item) {
                if (in_array($field, ['taxonomy', 'custom_field']) && !empty($data_item)) {
                    foreach ($data_item as $item) {
                        if (!empty($item['value'])) {
                            switch ($field) {
                                case 'taxonomy':
                                    $new_value[$item['taxonomy']] = sanitize_text_field($item['value']);
                                    $fields['attribute'] = sanitize_text_field($item['taxonomy']);
                                    break;
                                case 'custom_field':
                                    $new_value[$item['field']] = sanitize_text_field($item['value']);
                                    $fields['meta_field'] = sanitize_text_field($item['field']);
                            }
                        }
                    }
                } else {
                    if (!empty($data_item['value'])) {
                        $fields[] = sanitize_text_field($field);
                        $new_value[$field] = sanitize_text_field($data_item['value']);
                    }
                }
            }

            $this->save_history($product_ids, $fields, $new_value, History::BULK_OPERATION);

            foreach ($_POST['new_data'] as $field => $data_item) {
                if (in_array($field, ['taxonomy', 'custom_field']) && !empty($data_item)) {
                    foreach ($data_item as $item) {
                        if (!empty($item['value'])) {
                            $this->product_repository->update($product_ids, [
                                'field_type' => sanitize_text_field($field),
                                'field' => sanitize_text_field($item['field']),
                                'value' => sanitize_text_field($item['value']),
                                'operator' => (!empty($item['operator'])) ? sanitize_text_field($item['operator']) : null,
                                'replace' => (!empty($item['replace'])) ? sanitize_text_field($item['replace']) : null,
                                'sensitive' => (!empty($item['sensitive'])) ? sanitize_text_field($item['sensitive']) : null,
                                'round_item' => (!empty($item['round_item'])) ? sanitize_text_field($item['round_item']) : null,
                            ]);
                        }
                    }
                } else {
                    if (!empty($data_item['value']) || (!empty($data_item['operator']) && in_array($data_item['operator'], ['text_remove_duplicate', 'number_clear']))) {
                        $this->product_repository->update($product_ids, [
                            'field_type' => 'main_field',
                            'field' => sanitize_text_field($field),
                            'value' => sanitize_text_field($data_item['value']),
                            'operator' => (!empty($data_item['operator'])) ? sanitize_text_field($data_item['operator']) : null,
                            'replace' => (!empty($data_item['replace'])) ? sanitize_text_field($data_item['replace']) : null,
                            'sensitive' => (!empty($data_item['sensitive'])) ? sanitize_text_field($data_item['sensitive']) : null,
                            'round_item' => (!empty($data_item['round_item'])) ? sanitize_text_field($data_item['round_item']) : null,
                        ]);
                    }
                }
            }

            $histories = $this->history_repository->get_histories();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(WCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'product_ids' => $product_ids,
                'history_items' => $histories_rendered,
                'reverted' => !empty($reverted),
            ]);
        }
        return false;
    }

    public function get_products_name()
    {
        $list = [];
        if (!empty($_POST['search'])) {
            $args = [
                'posts_per_page' => '-1',
                'post_type' => ['product'],
                'wcbef_general_column_filter' => [
                    [
                        'field' => 'post_title',
                        'value' => sanitize_text_field($_POST['search']),
                        'operator' => 'like',
                    ],
                ],
            ];

            $products = $this->product_repository->get_products($args);
            if (!empty($products->posts)) {
                foreach ($products->posts as $post) {
                    $product = $this->product_repository->get_product($post->ID);
                    if ($product instanceof \WC_Product) {
                        $list['results'][] = [
                            'id' => $product->get_id(),
                            'text' => $product->get_title(),
                        ];
                    }
                }
            }
        }

        $this->make_response($list);
    }

    public function create_new_product()
    {
        if (isset($_POST) && !empty($_POST['count'])) {
            $products = [];
            for ($i = 1; $i <= intval(sanitize_text_field($_POST['count'])); $i++) {
                $products[] = $this->product_repository->create();
            }
            $this->make_response([
                'success' => true,
                'product_ids' => $products,
            ]);
        }
    }

    public function get_attribute_values()
    {
        if (isset($_POST['attribute_name'])) {
            $output = '';
            $attribute_name = sanitize_text_field($_POST['attribute_name']);
            $values = get_terms([
                'taxonomy' => "pa_{$attribute_name}",
                'hide_empty' => false,
            ]);

            if (!empty($values) && count($values) > 0) {
                $output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/attribute_item.php', compact('values', 'attribute_name'));
            }

            $this->make_response([
                'success' => true,
                'attribute_item' => $output,
            ]);
        }
        return false;
    }

    public function get_attribute_values_for_delete()
    {
        if (isset($_POST['attribute_name'])) {
            $output = '';
            $attribute_name = sanitize_text_field($_POST['attribute_name']);
            $values = get_terms([
                'taxonomy' => "pa_{$attribute_name}",
                'hide_empty' => false,
            ]);

            if (!empty($values) && count($values) > 0) {
                $output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/attribute_item_for_delete.php', compact('values', 'attribute_name'));
            }

            $this->make_response([
                'success' => true,
                'attribute_item' => $output,
            ]);
        }
        return false;
    }

    public function get_attribute_values_for_attach()
    {
        if (isset($_POST['attribute_name'])) {
            $output = '';
            $attribute_name = sanitize_text_field($_POST['attribute_name']);
            $values = get_terms([
                'taxonomy' => "pa_{$attribute_name}",
                'hide_empty' => false,
            ]);

            if (!empty($values) && count($values) > 0) {
                $output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/attribute_item_for_attach.php', compact('values', 'attribute_name'));
            }

            $this->make_response([
                'success' => true,
                'attribute_items' => $output,
            ]);
        }
        return false;
    }

    public function get_product_variations()
    {
        if (isset($_POST['product_id'])) {
            $variations_output = '';
            $attributes_output = '';
            $individual_output = '';
            $variations_single_delete_output = '';
            $product = $this->product_repository->get_product(intval(sanitize_text_field($_POST['product_id'])));
            if (!($product instanceof \WC_Product) || $product->get_type() != 'variable') {
                return false;
            }

            $product_attributes = $product->get_attributes();
            if (!empty($product_attributes)) {
                foreach ($product_attributes as $key => $product_attribute) {
                    $selected_values = [];
                    $selected_items[] = urldecode(mb_substr($key, 3));
                    $attribute_selected_items = get_the_terms($product->get_id(), urldecode($key));
                    $attribute_name = mb_substr(urldecode($key), 3);
                    if (is_array($attribute_selected_items)) {
                        $individual_output .= "<div data-id='wcbef-variation-bulk-edit-attribute-item-{$attribute_name}'><select class='wcbef-variation-bulk-edit-manual-item' data-attribute-name='{$attribute_name}'>";
                        foreach ($attribute_selected_items as $attribute_selected_item) {
                            $selected_values[] = urldecode($attribute_selected_item->slug);
                            $individual_output .= "<option value='" . urldecode($attribute_selected_item->slug) . "'>{$attribute_selected_item->name}</option>";
                        }
                        $individual_output .= '</select></div>';
                    }
                    $values = get_terms(['taxonomy' => urldecode($key), 'hide_empty' => false]);
                    $attributes_output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/attribute_item.php', compact('selected_values', 'attribute_name', 'values'));
                }
            }
            $product_children = $product->get_children();
            if ($product_children > 0) {
                $default_variation = implode(' | ', array_map('urldecode', $product->get_default_attributes()));
                $i = 1;
                foreach ($product_children as $child) {
                    $variation = $this->product_repository->get_product(intval($child));
                    $variation_id = $variation->get_id();
                    $attributes = $variation->get_attributes();
                    $val = [];
                    $variation_attributes_labels = [];
                    if (!empty($attributes)) {
                        foreach ($attributes as $key => $attribute) {
                            $val[] = str_replace('pa_', '', $key) . ',' . $attribute;
                            $variation_attributes_labels[] = (!empty($attribute)) ? urldecode($attribute) : 'Any ' . urldecode($key);
                        }
                    }
                    $variation_attributes = (!empty($variation_attributes_labels)) ? implode(' | ', $variation_attributes_labels) : '';
                    $attribute_value = implode('&&', $val);
                    $variations_output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/variation_item.php', compact('variation_attributes', 'default_variation', 'attribute_value', 'variation_id'));
                    $variations_single_delete_output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/variation_item_single_delete.php', compact('variation_attributes', 'variation_id'));
                    $i++;
                }
            }

            $this->make_response([
                'success' => true,
                'variations' => $variations_output,
                'attributes' => $attributes_output,
                'individual' => $individual_output,
                'selected_items' => $selected_items,
                'variations_single_delete' => $variations_single_delete_output,
            ]);
        }
        return false;
    }

    public function set_products_variations()
    {
        if (isset($_POST['product_ids']) && is_array($_POST['product_ids'])) {
            foreach ($_POST['product_ids'] as $product_id) {
                $product = $this->product_repository->get_product(intval(sanitize_text_field($product_id)));
                if (!($product instanceof \WC_Product_Variable) || $product->get_type() != 'variable') {
                    $product = new \WC_Product_Variable($product->get_id());
                    $product->save();
                }

                if (!empty($_POST['variations']) && is_array($_POST['variations'])) {
                    $new_attributes = isset($_POST['attributes']) ? Sanitizer::array($_POST['attributes']) : null;
                    if (!empty($new_attributes)) {
                        foreach ($new_attributes as $attribute_item) {
                            if (!isset($attribute_item[0]) && !isset($attribute_item[1])) {
                                return false;
                            }
                            $params = [
                                'taxonomy' => 'pa_' . sanitize_text_field($attribute_item[0]),
                                'value' => sanitize_text_field($attribute_item[1]),
                                'operator' => 'taxonomy_append',
                            ];
                            $this->product_repository->product_attribute_update($product->get_id(), $params);
                        }
                    }

                    $var = [];
                    $old_variation_ids = [];
                    $product_variations = $product->get_children();
                    $menu_order = 0;
                    foreach ($_POST['variations'] as $variations_item) {
                        if (isset($variations_item[0]) && !empty($variations_item[0])) {
                            if (isset($variations_item[1]) && !empty($variations_item[1])) {
                                if (count($product_variations) > 0 && in_array($variations_item[1], $product_variations)) {
                                    $variation = $this->product_repository->get_product(intval($variations_item[1]));
                                    if ($variation instanceof \WC_Product_Variation) {
                                        $variation->set_menu_order(intval($menu_order));
                                        $variation->save();
                                    }
                                    $old_variation_ids[] = intval($variations_item[1]);
                                }
                            } else {
                                $variations = explode('&&', $variations_item[0]);
                                if (!is_array($variations) && empty($variations)) {
                                    return false;
                                }
                                $new_variation = new \WC_Product_Variation();
                                $new_variation->set_parent_id($product->get_id());
                                foreach ($variations as $variation_item) {
                                    $variation = explode(',', $variation_item);
                                    if (isset($variation[0]) && isset($variation[1])) {
                                        $key = strtolower(urlencode($variation[0]));
                                        $var["attribute_pa_{$key}"] = strtolower(urlencode($variation[1]));
                                    }
                                }
                                $new_variation->set_attributes($var);
                                $new_variation->set_menu_order($menu_order);
                                $new_variation->save();
                            }
                        }
                        $menu_order++;
                    }

                    if (!empty($product_variations) && is_array($product_variations)) {
                        $diff = array_diff($product_variations, $old_variation_ids);
                        if (!empty($diff)) {
                            foreach ($diff as $delete_id) {
                                wp_delete_post(intval($delete_id));
                            }
                        }
                    }

                    $default_var = [];
                    $default_variations = (isset($_POST['default_variation'])) ? $_POST['default_variation'] : null;
                    if (!empty($default_variations)) {
                        $default_variation_items = Sanitizer::array(explode('&&', $default_variations));
                        if (!is_array($default_variation_items) && empty($default_variation_items)) {
                            return false;
                        }
                        foreach ($default_variation_items as $default_variation_item) {
                            $default_variation = explode(',', $default_variation_item);
                            if (isset($default_variation[0]) && isset($default_variation[1])) {
                                $key = strtolower(urlencode($default_variation[0]));
                                $default_var["attribute_pa_{$key}"] = strtolower(urlencode($default_variation[1]));
                            }
                        }

                        $product->set_default_attributes($default_var);
                        $product->save();
                    }
                }
            }

            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function delete_products_variations()
    {
        if (isset($_POST['product_ids']) && is_array($_POST['product_ids']) && !empty($_POST['variations']) && !empty($_POST['delete_type'])) {
            $variations = Sanitizer::array($_POST['variations']);
            foreach ($_POST['product_ids'] as $product_id) {
                $product = $this->product_repository->get_product(intval(sanitize_text_field($product_id)));
                if (!($product instanceof \WC_Product_Variable) || $product->get_type() != 'variable') {
                    return false;
                }
                $product_variations = $product->get_children();
                if (count($product_variations) > 0) {
                    foreach ($product_variations as $variation_id) {
                        $variation = $this->product_repository->get_product(intval($variation_id));
                        if (!($variation instanceof \WC_Product_Variation)) {
                            return false;
                        }
                        switch ($_POST['delete_type']) {
                            case 'all_variations':
                                wp_delete_post(intval($variation->get_id()));
                                break;
                            case 'single_product':
                                if (is_array($variations) && in_array($variation_id, $variations)) {
                                    wp_delete_post(intval($variation->get_id()));
                                }
                                break;
                            case 'multiple_product':
                                $delete_variation = Others::array_flatten($variations);
                                $product_variation = $variation->get_variation_attributes();
                                if (Others::array_equal($delete_variation, $product_variation)) {
                                    wp_delete_post(intval($variation->get_id()));
                                }
                                break;
                        }
                    }
                }
                $this->make_response([
                    'success' => true,
                ]);
            }
        }
        return false;
    }

    public function delete_products()
    {
        if (isset($_POST['product_ids']) && is_array($_POST['product_ids']) && !empty($_POST['delete_type'])) {
            switch ($_POST['delete_type']) {
                case 'trash':
                    foreach ($_POST['product_ids'] as $product_id) {
                        wp_trash_post(intval(sanitize_text_field($product_id)));
                    }
                    break;
                case 'permanently':
                    foreach ($_POST['product_ids'] as $product_id) {
                        wp_delete_post(intval(sanitize_text_field($product_id)));
                    }
                    break;
            }
            $this->make_response([
                'success' => esc_html__('Success !', WBEBL_NAME),
            ]);
        }
        return false;
    }

    public function duplicate_product()
    {
        if (isset($_POST['product_ids']) && !empty($_POST['product_ids']) && !empty($_POST['duplicate_number'])) {
            foreach ($_POST['product_ids'] as $product_id) {
                $product = $this->product_repository->get_product(intval(sanitize_text_field($product_id)));
                if (!($product instanceof \WC_Product)) {
                    return false;
                }

                for ($i = 1; $i <= intval($_POST['duplicate_number']); $i++) {
                    $new_product = new \WC_Admin_Duplicate_Product();
                    $new_product->product_duplicate($product);
                }
            }

            $this->make_response([
                'success' => esc_html__('Success !', WBEBL_NAME),
            ]);
        }
        return false;
    }

    public function add_product_taxonomy()
    {
        if (!empty($_POST['taxonomy_info']) && !empty($_POST['taxonomy_name']) && !empty($_POST['taxonomy_info']['name'])) {
            $result = wp_insert_category([
                'taxonomy' => sanitize_text_field($_POST['taxonomy_name']),
                'cat_name' => sanitize_text_field($_POST['taxonomy_info']['name']),
                'category_nicename' => sanitize_text_field($_POST['taxonomy_info']['slug']),
                'category_description' => sanitize_text_field($_POST['taxonomy_info']['description']),
                'category_parent' => intval(sanitize_text_field($_POST['taxonomy_info']['parent'])),
            ]);
            $checked = wp_get_post_terms(intval(sanitize_text_field($_POST['taxonomy_info']['product_id'])), sanitize_text_field($_POST['taxonomy_name']), [
                'fields' => 'ids',
            ]);
            if (!empty($result)) {
                $taxonomy_items = Taxonomy::wcbef_product_taxonomy_list(sanitize_text_field($_POST['taxonomy_name']), $checked);
                $this->make_response([
                    'success' => true,
                    'product_id' => intval(sanitize_text_field($_POST['taxonomy_info']['product_id'])),
                    'taxonomy_items' => $taxonomy_items,
                ]);
            }
        }
    }

    public function add_product_attribute()
    {
        if (!empty($_POST['attribute_info']) && !empty($_POST['attribute_name']) && !empty($_POST['attribute_info']['name'])) {
            $result = wp_insert_category([
                'taxonomy' => sanitize_text_field($_POST['attribute_name']),
                'cat_name' => sanitize_text_field($_POST['attribute_info']['name']),
                'category_nicename' => sanitize_text_field($_POST['attribute_info']['slug']),
                'category_description' => sanitize_text_field($_POST['attribute_info']['description']),
            ]);
            $items = get_terms([
                'taxonomy' => sanitize_text_field($_POST['attribute_name']),
                'hide_empty' => false,
            ]);
            $product_terms = wp_get_post_terms(intval(sanitize_text_field($_POST['attribute_info']['product_id'])), sanitize_text_field($_POST['attribute_name']), [
                'fields' => 'ids',
            ]);
            $attribute_items = '';
            if (!empty($items)) {
                foreach ($items as $item) {
                    $checked = (is_array($product_terms) && in_array($item->term_id, $product_terms)) ? 'checked="checked"' : '';
                    $attribute_items .= "<div><label><input type='checkbox' class='wcbef-inline-edit-tax' value='{$item->term_id}' {$checked}>{$item->name}</label></div>";
                }
            }
            if (!empty($result)) {
                $this->make_response([
                    'success' => true,
                    'product_id' => intval(sanitize_text_field($_POST['category_info']['product_id'])),
                    'attribute_items' => $attribute_items,
                ]);
            }
        }
        return false;
    }

    public function load_filter_profile()
    {
        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();
            $preset = $search_repository->get_preset(sanitize_text_field($_POST['preset_key']));
            if (!isset($preset['filter_data'])) {
                return false;
            }
            $result = $this->product_repository->get_products_list($preset['filter_data'], 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $preset['filter_data'],
                'products_list' => $result->products_list,
                'pagination' => $result->pagination,
                'products_count' => $result->count,
            ]);
        }
        return false;
    }

    public function delete_filter_profile()
    {
        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();
            $delete_result = $search_repository->delete(sanitize_text_field($_POST['preset_key']));
            if (!$delete_result) {
                return false;
            }
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function save_column_profile()
    {
        if (isset($_POST['preset_key']) && isset($_POST['type'])) {
            $column_repository = new Column();
            $fields = $column_repository->get_fields();
            $preset['date_modified'] = date('Y-m-d H:i:s', time());

            switch ($_POST['type']) {
                case 'save_as_new':
                    $preset['name'] = "Preset " . rand(100, 999);
                    $preset['key'] = 'preset-' . rand(1000000, 9999999);
                    break;
                case 'update_changes':
                    $preset_item = $column_repository->get_preset(sanitize_text_field($_POST['preset_key']));
                    if (!$preset_item) {
                        return false;
                    }
                    $preset['name'] = sanitize_text_field($preset_item['name']);
                    $preset['key'] = sanitize_text_field($preset_item['key']);
                    break;
            }

            foreach ($_POST['items'] as $item) {
                if (isset($fields[$item])) {
                    $preset['fields'][$item] = [
                        'name' => sanitize_text_field($item),
                        'label' => sanitize_text_field($fields[$item]['label']),
                        'title' => sanitize_text_field($fields[$item]['label']),
                        'editable' => $fields[$item]['editable'],
                        'content_type' => $fields[$item]['content_type'],
                        'allowed_type' => $fields[$item]['allowed_type'],
                        'background_color' => '#fff',
                        'text_color' => '#444',
                    ];
                    if (isset($fields[$item]['sortable'])) {
                        $preset["fields"][$item]['sortable'] = $fields[$item]['sortable'];
                    }
                    if (isset($fields[$item]['options'])) {
                        $preset["fields"][$item]['options'] = $fields[$item]['options'];
                    }
                    if (isset($fields[$item]['field_type'])) {
                        $preset["fields"][$item]['field_type'] = $fields[$item]['field_type'];
                    }
                    $preset['checked'][] = $item;
                }
            }

            $column_repository->update($preset);
            Session::set('wcbef_active_columns_key', $preset['key']);
            Session::set('wcbef_active_columns', $preset['fields']);
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function update_product_taxonomy()
    {
        if (isset($_POST['product_ids']) && is_array($_POST['product_ids'])) {
            $product_ids = array_map('intval', $_POST['product_ids']);
            $field = sanitize_text_field($_POST['field']);
            $values = Sanitizer::array($_POST['values']);
            $this->save_history($product_ids, [$field], $values, History::INLINE_OPERATION);
            $result = $this->product_repository->update($product_ids, [
                'field_type' => 'taxonomy',
                'taxonomy' => $field,
                'value' => $values,
                'operator' => 'taxonomy_replace',
            ]);
            if (!$result) {
                return false;
            }

            $histories = $this->history_repository->get_histories();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(WCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', WBEBL_NAME),
                'history_items' => $histories_rendered,
                'reverted' => !empty($reverted),
                'edited_ids' => $product_ids,
            ]);
        }
        return false;
    }

    public function update_product_attribute()
    {
        if (isset($_POST['product_ids']) && is_array($_POST['product_ids'])) {
            $product_ids = array_map('intval', $_POST['product_ids']);
            $field = sanitize_text_field($_POST['field']);
            $values = Sanitizer::array($_POST['values']);
            $this->save_history($product_ids, ['attribute' => $field], $values, History::INLINE_OPERATION);
            $result = $this->product_repository->update($product_ids, [
                'field_type' => 'taxonomy',
                'taxonomy' => $field,
                'value' => $values,
                'operator' => 'taxonomy_replace',
            ]);
            if (!$result) {
                return false;
            }

            $histories = $this->history_repository->get_histories();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(WCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', WBEBL_NAME),
                'history_items' => $histories_rendered,
                'reverted' => !empty($reverted),
                'edited_ids' => $product_ids,
            ]);
        }
        return false;
    }

    public function get_text_editor_content()
    {
        if (isset($_POST['product_id']) && isset($_POST['field'])) {
            $field = sanitize_text_field($_POST['field']);
            $field_type = sanitize_text_field($_POST['field_type']);
            $product_object = $this->product_repository->get_product(intval(sanitize_text_field($_POST['product_id'])));
            if (!($product_object instanceof \WC_Product)) {
                return false;
            }
            $product = $this->product_repository->get_product_fields($product_object);
            if ($field_type == 'meta_field') {
                $value = (isset($product['meta_field'][$field])) ? $product['meta_field'][$field][0] : '';
            } else {
                $value = $product[$field];
            }
            $this->make_response([
                'success' => true,
                'content' => $value,
            ]);
        }
        return false;
    }

    public function history_filter()
    {
        if (isset($_POST['filters'])) {
            $where = [];
            if (isset($_POST['filters']['operation']) && !empty($_POST['filters']['operation'])) {
                $where['operation_type'] = sanitize_text_field($_POST['filters']['operation']);
            }
            if (isset($_POST['filters']['author']) && !empty($_POST['filters']['author'])) {
                $where['user_id'] = sanitize_text_field($_POST['filters']['author']);
            }
            if (isset($_POST['filters']['fields']) && !empty($_POST['filters']['fields'])) {
                $where['fields'] = sanitize_text_field($_POST['filters']['fields']);
            }
            if (isset($_POST['filters']['date'])) {
                $where['operation_date'] = sanitize_text_field($_POST['filters']['date']);
            }

            $histories = $this->history_repository->get_histories($where);
            $histories_rendered = Render::html(WCBEF_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'history_items' => $histories_rendered,
            ]);
        }
        return false;
    }

    public function change_count_per_page()
    {
        if (isset($_POST['count_per_page'])) {
            Session::set('wcbef_count_per_page', intval(sanitize_text_field($_POST['count_per_page'])));
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

    public function get_default_filter_profile_products()
    {
        $search_repository = new Search();
        $preset = $search_repository->get_preset($search_repository->get_use_always());
        if (!isset($preset['filter_data'])) {
            $preset['filter_data'] = [];
        }
        $result = $this->product_repository->get_products_list($preset['filter_data'], 1);
        $this->make_response([
            'success' => true,
            'filter_data' => $preset['filter_data'],
            'products_list' => $result->products_list,
            'pagination' => $result->pagination,
            'products_count' => $result->count,
        ]);
    }

    public function get_taxonomy_parent_select_box()
    {
        if (isset($_POST['taxonomy']) && $_POST['taxonomy'] != 'product_tag') {
            $taxonomies = get_terms(['taxonomy' => sanitize_text_field($_POST['taxonomy']), 'hide_empty' => false]);
            $options = '<option value="-1">None</option>';
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $taxonomy) {
                    $term_id = intval($taxonomies->term_id);
                    $taxonomy_name = sanitize_text_field($taxonomy->name);
                    $options .= "<option value='{$term_id}'>{$taxonomy_name}</option>";
                }
            }
            $this->make_response([
                'success' => true,
                'options' => $options,
            ]);
        }
        return false;
    }

    public function get_product_data()
    {
        if (isset($_POST['product_id'])) {
            $product_object = $this->product_repository->get_product(intval(sanitize_text_field($_POST['product_id'])));
            $product_data = $this->product_repository->get_product_fields($product_object);
            $attributes = [];
            if (!empty($product_data['attribute'])) {
                foreach ($product_data['attribute'] as $attribute) {
                    $attributes[$attribute['name']] = (!empty($attribute['options'])) ? $attribute['options'] : [];
                }
            }
            $product_data['attribute'] = $attributes;

            $this->make_response([
                'success' => true,
                'product_data' => $product_data,
            ]);
        }
        return false;
    }

    public function get_product_variations_for_attach()
    {
        if (isset($_POST['product_id'])) {
            $product = $this->product_repository->get_product(intval(sanitize_text_field($_POST['product_id'])));
            if (!($product instanceof \WC_Product_Variable)) {
                return false;
            }

            $variations = '';
            $attribute_items = get_terms(['taxonomy' => sanitize_text_field('pa_' . $_POST['attribute'])]);
            $attribute_item = sanitize_text_field($_POST['attribute_item']);
            $product_children = $product->get_children();
            if ($product_children > 0) {
                foreach ($product_children as $child) {
                    $variation = $this->product_repository->get_product(intval($child));
                    $variation_id = $variation->get_id();
                    $attributes = $variation->get_attributes();
                    $variation_attributes_labels = [];
                    if (!empty($attributes)) {
                        foreach ($attributes as $key => $attribute) {
                            $variation_attributes_labels[] = (!empty($attribute)) ? urldecode($attribute) : 'Any ' . urldecode($key);
                        }
                    }
                    $variation_attributes = (!empty($variation_attributes_labels)) ? implode(' | ', $variation_attributes_labels) : '';
                    $variations .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/variation_item_for_attach.php', compact('variation_attributes', 'variation_id', 'attribute_items', 'attribute_item'));
                }
            }

            $this->make_response([
                'success' => true,
                'variations' => $variations,
            ]);
        }
        return false;
    }

    public function get_product_files()
    {
        if (!empty($_POST['product_id'])) {
            $output = '';
            $product = $this->product_repository->get_product(intval(sanitize_text_field($_POST['product_id'])));
            if (!($product instanceof \WC_Product)) {
                return false;
            }
            $files = $product->get_downloads();
            if (!empty($files)) {
                foreach ($files as $file_item) {
                    $file_id = $file_item->get_id();
                    $output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/file_item.php', compact('file_item', 'file_id'));
                }

                $this->make_response([
                    'success' => true,
                    'files' => $output,
                ]);
            }
            return false;
        }
        return false;
    }

    public function add_new_file_item()
    {
        $output = "";
        if (isset($_POST)) {
            $file_id = md5(time() . rand(100, 999));
            $output .= Render::html(WCBEF_VIEWS_DIR . 'bulk_edit/columns_modals/file_item.php', compact('file_id'));
            $this->make_response([
                'success' => true,
                'file_item' => $output,
            ]);
        }
        return false;
    }

    public function sort_by_column()
    {
        if (!empty($_POST['column_name']) && !empty($_POST['sort_type']) && !empty($_POST['filter_data'])) {
            $filter_data = Sanitizer::array($_POST['filter_data']);
            Session::set('wcbef_sort_by', sanitize_text_field($_POST['column_name']));
            Session::set('wcbef_sort_type', sanitize_text_field($_POST['sort_type']));
            $result = $this->product_repository->get_products_list($filter_data, 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $filter_data,
                'products_list' => $result->products_list,
                'pagination' => $result->pagination,
                'products_count' => $result->count,
            ]);
        }
        return false;
    }

    private function save_history($product_ids, array $fields, $new_value, $operation_type)
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

        foreach ($product_ids as $product_id) {
            $product_object = $this->product_repository->get_product(intval($product_id));
            if (!($product_object instanceof \WC_Product)) {
                return false;
            }
            $product_item = $this->product_repository->get_product_fields($product_object);
            if (!empty($fields)) {
                foreach ($fields as $field_type => $field) {
                    $prev_value = '';
                    if (is_numeric($field_type)) {
                        $prev_value = (isset($product_item[$field])) ? serialize($product_item[$field]) : '';
                    } else {
                        $encoded_field = strtolower(urlencode($field));
                        switch ($field_type) {
                            case 'meta_field':
                                $prev_value = (isset($product_item[$field_type][$encoded_field][0])) ? serialize($product_item[$field_type][$encoded_field][0]) : '';
                                break;
                            case 'attribute':
                                $prev_value = (isset($product_item[$field_type][$encoded_field]['options'])) ? serialize($product_item[$field_type][$encoded_field]['options']) : '';
                                break;
                            default:
                                break;
                        }
                    }

                    $this->history_repository->create_history_item([
                        'history_id' => intval($create_history),
                        'product_id' => intval($product_id),
                        'field' => serialize([$field_type => $field]),
                        'prev_value' => $prev_value,
                        'new_value' => (!empty($new_value[$field])) ? serialize($new_value[$field]) : serialize($new_value),
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
