<?php

namespace wcbef\classes\repositories;

use wcbef\classes\helpers\Session;

class Setting
{
    private $count_per_page;
    private $default_sort_by;
    private $default_sort;
    private $show_quick_search;
    private $sticky_search_form;
    private $sticky_first_columns;
    private $fetch_product_in_bulk;

    public function __construct()
    {
        $settings = unserialize(get_option('wcbef_settings'));
        $this->count_per_page = (isset($settings['count_per_page'])) ? $settings['count_per_page'] : 10;
        $this->default_sort_by = (isset($settings['default_sort_by'])) ? $settings['default_sort_by'] : 'id';
        $this->default_sort = (isset($settings['default_sort'])) ? $settings['default_sort'] : "desc";
        $this->show_quick_search = (isset($settings['show_quick_search'])) ? $settings['show_quick_search'] : 'yes';
        $this->sticky_search_form = (isset($settings['sticky_search_form'])) ? $settings['sticky_search_form'] : 'yes';
        $this->sticky_first_columns = (isset($settings['sticky_first_columns'])) ? $settings['sticky_first_columns'] : 'yes';
        $this->fetch_product_in_bulk = (isset($settings['fetch_product_in_bulk'])) ? $settings['fetch_product_in_bulk'] : 'no';
    }

    public function get_count_per_page()
    {
        return $this->count_per_page;
    }

    public function get_default_sort_by()
    {
        return $this->default_sort_by;
    }

    public function get_default_sort()
    {
        return $this->default_sort;
    }

    public function get_show_quick_search()
    {
        return $this->show_quick_search;
    }

    public function get_sticky_search_form()
    {
        return $this->sticky_search_form;
    }

    public function get_sticky_first_columns()
    {
        return $this->sticky_first_columns;
    }

    public function get_fetch_product_in_bulk()
    {
        return $this->fetch_product_in_bulk;
    }

    public function update(array $data = [])
    {
        $settings = [
            'count_per_page' => (isset($data['count_per_page'])) ? intval($data['count_per_page']) : 10,
            'default_sort_by' => (isset($data['default_sort_by'])) ? esc_sql($data['default_sort_by']) : 'id',
            'default_sort' => (isset($data['default_sort'])) ? esc_sql($data['default_sort']) : 'desc',
            'show_quick_search' => (isset($data['show_quick_search'])) ? esc_sql($data['show_quick_search']) : 'yes',
            'sticky_search_form' => (isset($data['sticky_search_form'])) ? esc_sql($data['sticky_search_form']) : 'yes',
            'sticky_first_columns' => (isset($data['sticky_first_columns'])) ? esc_sql($data['sticky_first_columns']) : 'yes',
            'fetch_product_in_bulk' => (isset($data['fetch_product_in_bulk'])) ? esc_sql($data['fetch_product_in_bulk']) : 'no',
        ];
        Session::set('wcbef_count_per_page', $settings['count_per_page']);
        Session::set('sticky_first_columns', $settings['sticky_first_columns']);
        return update_option('wcbef_settings', serialize($settings));
    }
}
