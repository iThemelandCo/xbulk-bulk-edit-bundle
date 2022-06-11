<?php

namespace wccbef\classes\controllers;

use wccbef\classes\repositories\Flush_Message;
use wccbef\classes\providers\coupon\CouponProvider;
use wccbef\classes\repositories\Column;
use wccbef\classes\repositories\History;
use wccbef\classes\repositories\Meta_Field;
use wccbef\classes\repositories\Search;
use wccbef\classes\repositories\Setting;
use wccbef\classes\repositories\Tab_Repository;

class Woo_Coupon_Controller
{
    private $meta_field_repository;
    private $column_repository;
    private $history_repository;
    private $search_repository;
    private $setting_repository;
    private $flush_message_repository;
    private $current_settings;
    private $settings;

    public function __construct()
    {
        $this->meta_field_repository = new Meta_Field();
        $this->column_repository = new Column();
        $this->history_repository = new History();
        $this->search_repository = new Search();
        $this->setting_repository = new Setting();
        $this->flush_message_repository = new Flush_Message();
    }

    public function index()
    {
        $this->initial_check();
        $settings = $this->settings;
        $current_settings = $this->current_settings;
        $flush_message = $this->flush_message_repository->get();
        $get_active_columns = $this->column_repository->get_active_columns();
        $last_filter_data = (isset($this->search_repository->get_current_data()['last_filter_data'])) ? $this->search_repository->get_current_data()['last_filter_data'] : null;
        $active_columns = $get_active_columns['fields'];
        $active_columns_key = $get_active_columns['name'];
        $default_columns_name = $this->column_repository::get_default_columns_name();
        $sort_by = $current_settings['sort_by'];
        $sort_type = $current_settings['sort_type'];
        $sticky_first_columns = isset($current_settings['sticky_first_columns']) ? $current_settings['sticky_first_columns'] : 'yes';
        $item_provider = CouponProvider::get_instance();
        $show_id_column = $this->column_repository::SHOW_ID_COLUMN;
        $items_loading = true;
        $users = get_users();
        $filter_profile_use_always = $this->search_repository->get_use_always();
        $histories = $this->history_repository->get_histories();
        $reverted = $this->history_repository->get_latest_reverted();
        $columns = $get_active_columns['fields'];
        $meta_fields_main_types = Meta_Field::get_main_types();
        $meta_fields_sub_types = Meta_Field::get_sub_types();
        $meta_fields = $this->meta_field_repository->get();
        $grouped_fields = $this->column_repository->get_grouped_fields();
        $column_items = $this->column_repository->get_fields();
        $column_manager_presets = $this->column_repository->get_presets();
        $filters_preset = $this->search_repository->get_presets();

        $tab_repository = new Tab_Repository();
        $tabs_title = $tab_repository->get_main_tabs_title();
        $tabs_content = $tab_repository->get_main_tabs_content();
        $bulk_edit_form_tabs_title = $tab_repository->get_bulk_edit_form_tabs_title();
        $bulk_edit_form_tabs_content = $tab_repository->get_bulk_edit_form_tabs_content();
        $filter_form_tabs_title = $tab_repository->get_filter_form_tabs_title();
        $filter_form_tabs_content = $tab_repository->get_filter_form_tabs_content();

        $defaultPresets = $this->column_repository::get_default_columns_name();

        $title = esc_html__('WooCommerce Bulk Coupons Editing', WBEBL_NAME);
        $doc_link = "https://ithemelandco.com/Plugins/Documentations/Pro-Bulk-Editing/xbulk/documentation.pdf";

        echo "<script> var defaultPresets = " . json_encode($defaultPresets) . ";</script>";
        include_once WCCBEF_VIEWS_DIR . "layouts/main.php";
    }

    private function initial_check()
    {
        if (empty($this->setting_repository->get_settings())) {
            $this->setting_repository->set_default_settings();
        }

        if (!$this->column_repository->has_column_fields()) {
            $this->column_repository->set_default_columns();
        }

        if (!$this->search_repository->has_search_options()) {
            $this->search_repository->set_default_item();
        }

        $this->current_settings = $this->setting_repository->get_current_settings();
        $this->settings = $this->setting_repository->get_settings();
        $this->current_settings = $this->setting_repository->update_current_settings([
            'sort_by' => isset($this->settings['default_sort_by']) ? $this->settings['default_sort_by'] : '',
            'sort_type' => isset($this->settings['default_sort']) ? $this->settings['default_sort'] : ''
        ]);

        if (!isset($current_settings['count_per_page'])) {
            $current_settings = $this->setting_repository->update_current_settings([
                'count_per_page' => isset($this->settings['count_per_page']) ? $this->settings['count_per_page'] : 10
            ]);
        }

        if (!isset($current_settings['sticky_first_columns'])) {
            $current_settings = $this->setting_repository->update_current_settings([
                'sticky_first_columns' => isset($this->settings['sticky_first_columns']) ? $this->settings['sticky_first_columns'] : 'yes'
            ]);
        }

        if (!$this->column_repository->get_active_columns()) {
            $this->column_repository->set_default_active_columns();
        }
    }
}
