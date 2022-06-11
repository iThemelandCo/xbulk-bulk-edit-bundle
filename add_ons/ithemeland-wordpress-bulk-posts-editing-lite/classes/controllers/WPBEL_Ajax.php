<?php

namespace wpbel\classes\controllers;

use wpbel\classes\helpers\Render;
use wpbel\classes\helpers\Filter_Helper;
use wpbel\classes\helpers\Meta_Fields;
use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\helpers\Taxonomy_Helper;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\History;
use wpbel\classes\repositories\Meta_Field;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Search;
use wpbel\classes\repositories\Setting;

class WPBEL_Ajax
{
    private static $instance;
    private $post_repository;
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
        $this->post_repository = new Post();
        $this->history_repository = new History();
        add_action('wp_ajax_wpbel_get_default_filter_profile_posts', [$this, 'get_default_filter_profile_posts']);
        add_action('wp_ajax_wpbel_create_new_post', [$this, 'create_new_post']);
        add_action('wp_ajax_wpbel_posts_filter', [$this, 'posts_filter']);
        add_action('wp_ajax_wpbel_duplicate_post', [$this, 'duplicate_post']);
        add_action('wp_ajax_wpbel_delete_posts', [$this, 'delete_post']);
        add_action('wp_ajax_wpbel_filter_profile_change_use_always', [$this, 'filter_profile_change_use_always']);
        add_action('wp_ajax_wpbel_change_count_per_page', [$this, 'change_count_per_page']);
        add_action('wp_ajax_wpbel_inline_edit', [$this, 'inline_edit']);
        add_action('wp_ajax_wpbel_get_text_editor_content', [$this, 'get_text_editor_content']);
        add_action('wp_ajax_wpbel_history_filter', [$this, 'history_filter']);
        add_action('wp_ajax_wpbel_add_meta_keys_by_post_id', [$this, 'add_meta_keys_by_post_id']);
        add_action('wp_ajax_wpbel_update_post_taxonomy', [$this, 'update_post_taxonomy']);
        add_action('wp_ajax_wpbel_add_post_taxonomy', [$this, 'add_post_taxonomy']);
        add_action('wp_ajax_wpbel_posts_bulk_edit', [$this, 'posts_bulk_edit']);
        add_action('wp_ajax_wpbel_load_filter_profile', [$this, 'load_filter_profile']);
        add_action('wp_ajax_wpbel_save_filter_preset', [$this, 'save_filter_preset']);
        add_action('wp_ajax_wpbel_save_column_profile', [$this, 'save_column_profile']);
        add_action('wp_ajax_wpbel_sort_by_column', [$this, 'sort_by_column']);
        add_action('wp_ajax_wpbel_delete_filter_profile', [$this, 'delete_filter_profile']);
        add_action('wp_ajax_wpbel_get_taxonomy_parent_select_box', [$this, 'get_taxonomy_parent_select_box']);
    }

    public function inline_edit()
    {
        if (isset($_POST)) {
            $result = false;
            if (!is_array($_POST['posts_ids'])) {
                return false;
            }
            $posts_ids = array_map('intval', $_POST['posts_ids']);
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

            $this->save_history($posts_ids, $field_for_history, sanitize_text_field($_POST['value']), $this->history_repository::INLINE_OPERATION);

            $result = $this->post_repository->update($posts_ids, [
                'field_type' => $field_type,
                'field' => $field,
                'value' => sanitize_text_field($_POST['value']),
                'operator' => $operator,
            ]);
            if ($result) {
                $histories = $this->history_repository->get_histories();
                $reverted = $this->history_repository->get_latest_reverted();
                $histories_rendered = Render::html(WPBEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
                $this->make_response([
                    'success' => true,
                    'message' => esc_html__('Success !', WBEBL_NAME),
                    'history_items' => $histories_rendered,
                    'reverted' => !empty($reverted),
                    'edited_ids' => $posts_ids,
                ]);
            }
        }

        return false;
    }

    public function posts_bulk_edit()
    {
        if (!empty($_POST['new_data']) && is_array($_POST['new_data'])) {
            if (!empty($_POST['post_ids'])) {
                $post_ids = array_map('intval', $_POST['post_ids']);
            } elseif (!empty($_POST['filter_data'])) {
                $args = \wpbel\classes\helpers\Post_Helper::set_filter_data_items($_POST['filter_data'], [
                    'posts_per_page' => '-1',
                    'fields' => 'ids',
                    'post_type' => [$GLOBALS['wpbel_common']['active_post_type']],
                ]);
                $post_ids = ($this->post_repository->get_posts($args))->posts;
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
                                    $new_value[sanitize_text_field($item['field'])] = sanitize_text_field($item['value']);
                                    $fields['taxonomy'][] = sanitize_text_field($item['field']);
                                    break;
                                case 'custom_field':
                                    $new_value[sanitize_text_field($item['field'])] = sanitize_text_field($item['value']);
                                    $fields['meta_field'][] = sanitize_text_field($item['field']);
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

            $this->save_history($post_ids, $fields, $new_value, History::BULK_OPERATION);

            foreach ($_POST['new_data'] as $field => $data_item) {
                if (in_array($field, ['taxonomy', 'custom_field']) && !empty($data_item)) {
                    foreach ($data_item as $item) {
                        if (!empty($item['value'])) {
                            $this->post_repository->update($post_ids, [
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
                        $this->post_repository->update($post_ids, [
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
            $histories_rendered = Render::html(WPBEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'post_ids' => $post_ids,
                'history_items' => $histories_rendered,
                'reverted' => !empty($reverted),
            ]);
        }
        return false;
    }

    private function save_history($post_ids, array $fields, $new_value, $operation_type)
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

        foreach ($post_ids as $post_id) {
            $post_object = $this->post_repository->get_post(intval($post_id));
            if (!($post_object instanceof \WP_Post)) {
                return false;
            }
            $post_item = $this->post_repository->get_post_fields($post_object);
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
                                    $prev_val['custom_field'][$encoded_field] = (isset($post_item[$field_type][$encoded_field][0])) ? $post_item[$field_type][$encoded_field][0] : '';
                                    break;
                                case 'taxonomy':
                                    $new_val['taxonomy'][$encoded_field] = $new_value[$encoded_field];
                                    $prev_val['taxonomy'][$encoded_field] = ($encoded_field == 'post_tag') ? wp_get_post_terms($post_item['id'], $encoded_field, ['fields' => 'names']) : wp_get_post_terms($post_item['id'], $encoded_field, ['fields' => 'ids']);
                                    break;
                                default:
                                    break;
                            }
                        }
                        $new_val = serialize($new_val);
                        $prev_val = serialize($prev_val);
                    } else {
                        $prev_val = (isset($post_item[$field])) ? serialize($post_item[$field]) : '';
                        if ($field == '_thumbnail_id') {
                            $new_val = serialize([
                                'id' => intval($new_value),
                                'small' => wp_get_attachment_image_src(intval($new_value), [40, 40]),
                                'big' => wp_get_attachment_image_src(intval($new_value), [600, 600]),
                            ]);
                        } else {
                            $new_val = (!empty($new_value[$field])) ? serialize($new_value[$field]) : serialize($new_value);
                        }
                    }

                    $this->history_repository->create_history_item([
                        'history_id' => intval($create_history),
                        'historiable_id' => intval($post_id),
                        'field' => serialize([$field_type => $field]),
                        'prev_value' => $prev_val,
                        'new_value' => $new_val,
                    ]);
                }
            }
        }
        return true;
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
            $histories_rendered = Render::html(WPBEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'history_items' => $histories_rendered,
            ]);
        }
        return false;
    }

    public function get_text_editor_content()
    {
        if (isset($_POST['post_id']) && isset($_POST['field'])) {
            $field = sanitize_text_field($_POST['field']);
            $field_type = sanitize_text_field($_POST['field_type']);
            $post_object = $this->post_repository->get_post(intval(sanitize_text_field($_POST['post_id'])));
            if (!($post_object instanceof \WP_Post)) {
                return false;
            }
            $post = $this->post_repository->get_post_fields($post_object);
            if ($field_type == 'custom_field') {
                $value = (isset($post['custom_field'][$field])) ? $post['custom_field'][$field][0] : '';
            } else {
                $value = $post[$field];
            }
            $this->make_response([
                'success' => true,
                'content' => $value,
            ]);
        }
        return false;
    }

    public function get_default_filter_profile_posts()
    {
        $filter_data = Filter_Helper::get_active_filter_data();
        $result = $this->post_repository->get_posts_list([$GLOBALS['wpbel_common']['active_post_type']], $filter_data, 1);
        $this->make_response([
            'success' => true,
            'filter_data' => $filter_data,
            'posts_list' => $result->posts_list,
            'pagination' => $result->pagination,
            'posts_count' => $result->count,
        ]);
    }

    public function create_new_post()
    {
        if (isset($_POST) && !empty($_POST['count'])) {
            if ($GLOBALS['wpbel_common']['active_post_type'] == 'custom_post' && empty($_POST['post_type'])) {
                return false;
            }
            $post_type = (!empty($_POST['post_type'])) ? sanitize_text_field($_POST['post_type']) : $GLOBALS['wpbel_common']['active_post_type'];
            $posts = [];
            for ($i = 1; $i <= intval(sanitize_text_field($_POST['count'])); $i++) {
                $posts[] = $this->post_repository->create($post_type);
            }
            $this->make_response([
                'success' => true,
                'post_ids' => $posts,
            ]);
        }
    }

    public function posts_filter()
    {
        if (isset($_POST['filter_data'])) {
            $data = Sanitizer::array($_POST['filter_data']);
            $search_repository = new Search();
            $search_repository->update_current_data([
                'last_filter_data' => $data
            ]);
            $current_page = !empty($_POST['current_page']) ? intval(sanitize_text_field($_POST['current_page'])) : 1;
            $filter_result = $this->post_repository->get_posts_list([$GLOBALS['wpbel_common']['active_post_type']], $data, $current_page);
            $this->make_response([
                'success' => true,
                'posts_list' => $filter_result->posts_list,
                'pagination' => $filter_result->pagination,
                'posts_count' => $filter_result->count,
            ]);
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
            $search_repository->update_current_data([
                'last_filter_data' => $preset['filter_data']
            ]);
            $result = $this->post_repository->get_posts_list([$GLOBALS['wpbel_common']['active_post_type']], $preset['filter_data'], 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $preset['filter_data'],
                'posts_list' => $result->posts_list,
                'pagination' => $result->pagination,
                'posts_count' => $result->count,
            ]);
        }
        return false;
    }

    public function save_filter_preset()
    {
        if (!empty($_POST['preset_name'])) {
            $filter_item['name'] = sanitize_text_field($_POST['preset_name']);
            $filter_item['date_modified'] = date('Y-m-d H:i:s');
            $filter_item['key'] = 'preset-' . rand(1000000, 9999999);
            $filter_item['filter_data'] = Sanitizer::array($_POST['filter_data']);
            $save_result = (new Search())->update($filter_item);
            if (!$save_result) {
                return false;
            }
            $new_item = Render::html(WPBEL_VIEWS_DIR . 'modals/filter_profile_item.php', compact('filter_item'));
            $this->make_response([
                'success' => $save_result,
                'new_item' => $new_item,
            ]);
        }
        return false;
    }

    public function delete_filter_profile()
    {
        if (isset($_POST['preset_key'])) {
            $search_repository = new Search();
            $use_always = $search_repository->get_use_always();
            if ($use_always == $_POST['preset_key']) {
                $search_repository->update_use_always('default');
            }
            $delete_result = $search_repository->delete(sanitize_text_field($_POST['preset_key']));
            if (!$delete_result) {
                return false;
            }

            $this->make_response([
                'success' => true
            ]);
        }
        return false;
    }

    public function get_taxonomy_parent_select_box()
    {
        if (isset($_POST['taxonomy'])) {
            $taxonomies = get_terms(['taxonomy' => sanitize_text_field($_POST['taxonomy']), 'hide_empty' => false]);
            $options = '<option value="-1">None</option>';
            if (!empty($taxonomies)) {
                foreach ($taxonomies as $taxonomy) {
                    $term_id = intval($taxonomy->term_id);
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

    public function duplicate_post()
    {
        if (isset($_POST['post_ids']) && !empty($_POST['post_ids']) && !empty($_POST['duplicate_number'])) {
            foreach ($_POST['post_ids'] as $post_id) {
                $post = $this->post_repository->get_post(intval(sanitize_text_field($post_id)));
                if (!($post instanceof \WP_Post)) {
                    return false;
                }
                for ($i = 1; $i <= intval(sanitize_text_field($_POST['duplicate_number'])); $i++) {
                    $this->post_repository->duplicate($post);
                }
            }

            $this->make_response([
                'success' => esc_html__('Success !', WBEBL_NAME),
            ]);
        }
        return false;
    }

    public function delete_post()
    {
        if (isset($_POST['post_ids']) && is_array($_POST['post_ids']) && !empty($_POST['delete_type'])) {
            $posts_ids = array_map('intval', $_POST['post_ids']);
            foreach ($posts_ids as $post_id) {
                if ($_POST['delete_type'] == 'trash') {
                    $this->save_history_for_delete($post_id);
                }
                $this->post_repository->delete($post_id, sanitize_text_field($_POST['delete_type']));
            }

            $histories = $this->history_repository->get_histories();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(WPBEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', WBEBL_NAME),
                'history_items' => $histories_rendered,
                'reverted' => !empty($reverted),
                'edited_ids' => $posts_ids,
            ]);
        }
        return false;
    }

    private function save_history_for_delete(int $post_id)
    {
        $create_history = $this->history_repository->create_history([
            'user_id' => intval(get_current_user_id()),
            'fields' => serialize(['post_delete']),
            'operation_type' => 'bulk',
            'operation_date' => date('Y-m-d H:i:s'),
        ]);
        if (!$create_history) {
            return false;
        }
        $this->history_repository->create_history_item([
            'history_id' => intval($create_history),
            'historiable_id' => intval($post_id),
            'field' => serialize(['post_delete']),
            'prev_value' => null,
            'new_value' => null,
        ]);
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

    public function change_count_per_page()
    {
        if (isset($_POST['count_per_page'])) {
            $setting_repository = new Setting();
            $setting_repository->update_current_settings([
                'count_per_page' => intval(sanitize_text_field($_POST['count_per_page']))
            ]);
            $this->make_response([
                'success' => true,
            ]);
        }
        return false;
    }

    public function add_meta_keys_by_post_id()
    {
        if (isset($_POST)) {
            $post_id = intval(sanitize_text_field($_POST['post_id']));
            $post = $this->post_repository->get_post($post_id);
            if (!($post instanceof \WP_Post)) {
                die();
            }
            $meta_keys = Meta_Fields::remove_default_meta_keys(array_keys(get_post_meta($post->ID)));
            $output = "";
            if (!empty($meta_keys)) {
                foreach ($meta_keys as $meta_key) {
                    $meta_field['key'] = $meta_key;
                    $meta_fields_main_types = Meta_Field::get_main_types();
                    $meta_fields_sub_types = Meta_Field::get_sub_types();
                    $output .= Render::html(WPBEL_VIEWS_DIR . "meta_field/meta_field_item.php", compact('meta_field', 'meta_fields_main_types', 'meta_fields_sub_types'));
                }
            }

            $this->make_response($output);
        }
        return false;
    }

    public function update_post_taxonomy()
    {
        if (isset($_POST['post_ids']) && is_array($_POST['post_ids'])) {
            $post_ids = array_map('intval', $_POST['post_ids']);
            $field = sanitize_text_field($_POST['field']);
            $values = Sanitizer::array($_POST['values']);
            $this->save_history($post_ids, ['taxonomy' => $field], $values, History::INLINE_OPERATION);

            $result = $this->post_repository->update($post_ids, [
                'field_type' => 'taxonomy',
                'field' => $field,
                'value' => $values,
                'operator' => 'taxonomy_replace',
            ]);
            if (!$result) {
                return false;
            }

            $histories = $this->history_repository->get_histories();
            $reverted = $this->history_repository->get_latest_reverted();
            $histories_rendered = Render::html(WPBEL_VIEWS_DIR . 'history/history_items.php', compact('histories'));
            $this->make_response([
                'success' => true,
                'message' => esc_html__('Success !', WBEBL_NAME),
                'history_items' => $histories_rendered,
                'reverted' => !empty($reverted),
                'edited_ids' => $post_ids,
            ]);
        }
        return false;
    }

    public function add_post_taxonomy()
    {
        if (!empty($_POST['taxonomy_info']) && !empty($_POST['taxonomy_name']) && !empty($_POST['taxonomy_info']['name'])) {
            $result = wp_insert_category([
                'taxonomy' => sanitize_text_field($_POST['taxonomy_name']),
                'cat_name' => sanitize_text_field($_POST['taxonomy_info']['name']),
                'category_nicename' => sanitize_text_field($_POST['taxonomy_info']['slug']),
                'category_description' => sanitize_text_field($_POST['taxonomy_info']['description']),
                'category_parent' => intval(sanitize_text_field($_POST['taxonomy_info']['parent'])),
            ]);
            $checked = ($_POST['taxonomy_name'] == 'post_tag') ? wp_get_post_terms(intval(sanitize_text_field($_POST['taxonomy_info']['post_id'])), sanitize_text_field($_POST['taxonomy_name']), ['fields' => 'names']) : wp_get_post_terms(intval(sanitize_text_field($_POST['taxonomy_info']['post_id'])), sanitize_text_field($_POST['taxonomy_name']), ['fields' => 'ids']);
            if (!empty($result)) {
                $taxonomy_items = Taxonomy_Helper::get_post_taxonomy_list(sanitize_text_field($_POST['taxonomy_name']), $checked);
                $this->make_response([
                    'success' => true,
                    'post_id' => intval(sanitize_text_field($_POST['taxonomy_info']['post_id'])),
                    'taxonomy_items' => $taxonomy_items,
                ]);
            }
        }
    }

    public function save_column_profile()
    {
        if (isset($_POST['preset_key']) && isset($_POST['type'])) {
            $column_repository = new Column();
            $fields = $column_repository->get_columns();
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

            $preset['fields'] = [];

            foreach ($_POST['items'] as $item) {
                if (isset($fields[$item])) {
                    $preset['fields'][$item] = [
                        'name' => sanitize_text_field($item),
                        'label' => sanitize_text_field($fields[$item]['label']),
                        'title' => sanitize_text_field($fields[$item]['label']),
                        'editable' => $fields[$item]['editable'],
                        'content_type' => $fields[$item]['content_type'],
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
            $column_repository->set_active_columns($preset['key'], $preset['fields']);
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
                'sort_type' => sanitize_text_field($_POST['sort_type']),

            ]);
            $result = $this->post_repository->get_posts_list([$GLOBALS['wpbel_common']['active_post_type']], $filter_data, 1);
            $this->make_response([
                'success' => true,
                'filter_data' => $filter_data,
                'posts_list' => $result->posts_list,
                'pagination' => $result->pagination,
                'posts_count' => $result->count,
            ]);
        }
        return false;
    }

    private function make_response($data)
    {
        echo (is_array($data)) ? json_encode($data) : sprintf('%s', $data);
        die();
    }
}
