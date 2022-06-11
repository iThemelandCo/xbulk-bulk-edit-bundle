<?php

namespace wcbef\classes\controllers;

use wcbef\classes\helpers\Columns;
use wcbef\classes\helpers\Meta_Fields;
use wcbef\classes\helpers\Operator;
use wcbef\classes\helpers\Session;
use wcbef\classes\helpers\Taxonomy;
use wcbef\classes\repositories\Column;
use wcbef\classes\repositories\History;
use wcbef\classes\repositories\Meta_Field;
use wcbef\classes\repositories\Product;
use wcbef\classes\repositories\Search;
use wcbef\classes\repositories\Setting;

class Woocommerce_Bulk_Edit_Free
{
    private $product_repository;
    private $meta_field_repository;
    private $column_repository;
    private $history_repository;
    private $search_repository;
    private $setting_repository;

    public function __construct()
    {
        $this->product_repository = new Product();
        $this->meta_field_repository = new Meta_Field();
        $this->column_repository = new Column();
        $this->history_repository = new History();
        $this->search_repository = new Search();
        $this->setting_repository = new Setting();
    }

    public function index()
    {
        $settings = [
            'count_per_page' => $this->setting_repository->get_count_per_page(),
            'default_sort_by' => $this->setting_repository->get_default_sort_by(),
            'default_sort' => $this->setting_repository->get_default_sort(),
            'show_quick_search' => $this->setting_repository->get_show_quick_search(),
            'sticky_search_form' => $this->setting_repository->get_sticky_search_form(),
            'sticky_first_columns' => $this->setting_repository->get_sticky_first_columns(),
            'fetch_product_in_bulk' => $this->setting_repository->get_fetch_product_in_bulk(),
        ];

        Session::set('wcbef_sort_by', $settings['default_sort_by']);
        Session::set('wcbef_sort_type', $settings['default_sort']);

        if (!Session::has('wcbef_count_per_page')) {
            Session::set('wcbef_count_per_page', $settings['count_per_page']);
        }
        if (!Session::has('sticky_first_columns')) {
            Session::set('sticky_first_columns', $settings['sticky_first_columns']);
        }
        if (!Session::has('wcbef_active_columns')) {
            Session::set('wcbef_active_columns', Columns::get_default_columns1());
        }
        if (!Session::has('wcbef_active_columns_key')) {
            Session::set('wcbef_active_columns_key', 'default1');
        }

        $products_loading = true;
        $all_products = $this->product_repository->get_products([
            'posts_per_page' => '-1',
            'post_type' => ['product'],
        ]);
        $current_tab = Session::has('wcbef-tab') ? Session::get_flush('wcbef-tab') : 'bulk-edit';
        $filter_profile_use_always = $this->search_repository->get_use_always();
        $histories = $this->history_repository->get_histories();
        $reverted = $this->history_repository->get_latest_reverted();
        $columns = Session::get('wcbef_active_columns');
        $edit_text_operators = Operator::edit_text();
        $edit_taxonomy_operators = Operator::edit_taxonomy();
        $edit_number_operators = Operator::edit_number();
        $edit_regular_price_operators = Operator::edit_regular_price();
        $edit_sale_price_operators = Operator::edit_sale_price();
        $product_types = wc_get_product_types();
        $product_statuses = get_post_statuses();
        $users = get_users();
        $categories_custom_style = Taxonomy::wcbef_product_taxonomy_list();
        $meta_fields_main_types = Meta_Field::get_main_types();
        $meta_fields_sub_types = Meta_Field::get_sub_types();
        $column_fields = $this->column_repository->get_fields();
        $column_manager_presets = $this->column_repository->get_presets();
        $categories = get_categories(['taxonomy' => 'product_cat', 'hide_empty' => false]);
        $tags = get_tags(['taxonomy' => 'product_tag']);
        $shipping_classes = wc()->shipping()->get_shipping_classes();
        $filters_preset = $this->search_repository->get_presets();
        $attributes = $this->product_repository->get_attributes();
        $taxonomies = $this->product_repository->get_taxonomies();
        $default_taxonomies = Meta_Fields::get_default_taxonomies();
        if (!empty($default_taxonomies)) {
            foreach ($default_taxonomies as $default_taxonomy) {
                if (isset($taxonomies[$default_taxonomy])) {
                    unset($taxonomies[$default_taxonomy]);
                }
            }
        }

        include_once WCBEF_VIEWS_DIR . "main.php";
    }
}
