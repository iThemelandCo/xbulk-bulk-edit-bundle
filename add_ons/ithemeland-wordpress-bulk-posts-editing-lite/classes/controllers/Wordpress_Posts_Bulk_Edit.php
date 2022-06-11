<?php

namespace wpbel\classes\controllers;

use wpbel\classes\helpers\Operator;
use wpbel\classes\helpers\Meta_Fields;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Flush_Message;
use wpbel\classes\repositories\History;
use wpbel\classes\repositories\Meta_Field;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Search;
use wpbel\classes\repositories\Setting;

class Wordpress_Posts_Bulk_Edit
{
    public function index()
    {
        $history_repository = new History();
        $search_repository = new Search();
        $column_repository = new Column();
        $setting_repository = new Setting();
        $meta_field_repository = new Meta_Field();
        $flush_message_repository = new Flush_Message();
        $post_repository = new Post();
        $current_settings = $setting_repository->get_current_settings();
        $settings = [
            'count_per_page' => $setting_repository->get_count_per_page(),
            'default_sort_by' => $setting_repository->get_default_sort_by(),
            'default_sort' => $setting_repository->get_default_sort(),
            'show_quick_search' => $setting_repository->get_show_quick_search(),
            'sticky_search_form' => $setting_repository->get_sticky_search_form(),
            'sticky_first_columns' => $setting_repository->get_sticky_first_columns(),
            'fetch_data_in_bulk' => $setting_repository->get_fetch_data_in_bulk(),
        ];

        $current_settings = $setting_repository->update_current_settings([
            'sort_by' => $settings['default_sort_by'],
            'sort_type' => $settings['default_sort']
        ]);

        if (empty($setting_repository->get_settings())) {
            $setting_repository->set_default_settings();
        }

        if (!$column_repository->has_column_fields()) {
            $column_repository->set_default_columns();
        }

        if (!$search_repository->has_search_options()) {
            $search_repository->set_default_item();
        }

        if (!isset($current_settings['count_per_page'])) {
            $current_settings = $setting_repository->update_current_settings([
                'count_per_page' => $settings['count_per_page']
            ]);
        }

        if (!isset($current_settings['sticky_first_columns'])) {
            $current_settings = $setting_repository->update_current_settings([
                'sticky_first_columns' => $settings['sticky_first_columns']
            ]);
        }

        if (!$column_repository->get_active_columns()) {
            $column_repository->set_default_active_columns();
        }

        if ($GLOBALS['wpbel_common']['active_post_type'] == 'custom_post') {
            $custom_post_types = $post_repository->get_custom_post_types();
            $field = "<select id='wpbel-new-item-select-custom-post' class='wpbel-input-md wpbel-w500 wpbel-m0' required>";
            $field .= "<option value=''>Select</option>";
            if (!empty($custom_post_types)) {
                foreach ($custom_post_types as $post_type_key => $post_type_label) {
                    $field .= "<option value='" . esc_attr($post_type_key) . "'>" . esc_html($post_type_label) . "</option>";
                }
            }
            $field .= "</select>";
            $new_item_extra_fields = [
                [
                    'label' => "<label class='wpbel-label-big' for='wpbel-new-item-select-custom-post'> Select Custom Post </label>",
                    'field' => $field
                ]
            ];
        }

        $items_loading = true;
        $active_post_type = $GLOBALS['wpbel_common']['active_post_type'];
        $post_type_name = ucfirst(str_replace('_', ' ', $active_post_type));
        $get_active_columns = $column_repository->get_active_columns();
        $active_columns = $get_active_columns['fields'];
        $active_columns_key = $get_active_columns['name'];
        $columns = $get_active_columns['fields'];
        $column_profile_action_form = "wpbel_load_column_profile";
        $default_columns_name = $column_repository::get_default_columns_name();
        $sort_by = $current_settings['sort_by'];
        $sort_type = $current_settings['sort_type'];
        $sticky_first_columns = $current_settings['sticky_first_columns'];
        $post_statuses = get_post_statuses();
        $show_id_column = $column_repository::SHOW_ID_COLUMN;
        $next_static_columns = $column_repository::get_static_columns();
        $filter_profile_use_always = $search_repository->get_use_always();
        $histories = $history_repository->get_histories();
        $reverted = $history_repository->get_latest_reverted();
        $edit_text_operators = Operator::edit_text();
        $edit_taxonomy_operators = Operator::edit_taxonomy();
        $edit_number_operators = Operator::edit_number();
        $users = get_users();
        $all_posts = $post_repository->get_posts([
            'post_type' => $GLOBALS['wpbel_common']['active_post_type']
        ]);
        $grouped_fields = $column_repository->get_grouped_columns();
        $column_items = $column_repository->get_columns();
        $column_manager_presets = $column_repository->get_presets();
        $filters_preset = $search_repository->get_presets();
        $post_types = $post_repository->get_post_types();
        $all_post_types = $post_repository->get_post_types();
        $meta_fields_main_types = $meta_field_repository::get_main_types();
        $meta_fields_sub_types = $meta_field_repository::get_sub_types();
        $meta_fields = $meta_field_repository->get();
        $flush_message = $flush_message_repository->get();
        $taxonomies = $post_repository->get_taxonomies();
        $except_taxonomies = Meta_Fields::get_except_taxonomies();
        if (!empty($except_taxonomies)) {
            foreach ($except_taxonomies as $except_taxonomy) {
                if (isset($taxonomies[$except_taxonomy])) {
                    unset($taxonomies[$except_taxonomy]);
                }
            }
        }

        $title = esc_html__('Wordpress Bulk Posts Editing Lite', WBEBL_NAME);
        $doc_link = "https://ithemelandco.com/Plugins/Documentations/Pro-Bulk-Editing/xbulk/documentation.pdf";
        $tabs_title = [
            'bulk-edit' => esc_html__('Bulk Edit', WBEBL_NAME),
            'column-manager' => esc_html__('Column Manager', WBEBL_NAME),
            'meta-fields' => esc_html__('Meta Fields', WBEBL_NAME),
            'history' => esc_html__('History', WBEBL_NAME),
            'import-export' => esc_html__('Import/Export', WBEBL_NAME),
            'settings' => esc_html__('Settings', WBEBL_NAME),
        ];
        $tabs_content = [
            'bulk-edit' => WPBEL_VIEWS_DIR . "bulk_edit/main.php",
            'column-manager' => WPBEL_VIEWS_DIR . "column_manager/main.php",
            'meta-fields' => WPBEL_VIEWS_DIR . "meta_field/main.php",
            'history' => WPBEL_VIEWS_DIR . "history/main.php",
            'import-export' => WPBEL_VIEWS_DIR . "import_export/main.php",
            'settings' => WPBEL_VIEWS_DIR . "settings/main.php",
        ];

        include_once WPBEL_VIEWS_DIR . "layouts/main.php";
    }
}
