<?php

namespace wpbel\classes\repositories;

use wpbel\classes\repositories\setting\Setting_Main;
use wpbel\classes\helpers\Post_Helper;

class Setting
{
    private $settings_option_name;
    private $current_settings_option_name;

    private $count_per_page;
    private $default_sort_by;
    private $default_sort;
    private $show_quick_search;
    private $sticky_search_form;
    private $sticky_first_columns;
    private $fetch_data_in_bulk;

    public function __construct(string $post_type = "")
    {
        $post_type = (!empty($post_type)) ? $post_type : $GLOBALS['wpbel_common']['active_post_type'];
        $this->set_option_name($post_type);

        $settings = unserialize(get_option($this->settings_option_name));
        $this->count_per_page = (isset($settings['count_per_page'])) ? $settings['count_per_page'] : 10;
        $this->default_sort_by = (isset($settings['default_sort_by'])) ? $settings['default_sort_by'] : 'id';
        $this->default_sort = (isset($settings['default_sort'])) ? $settings['default_sort'] : "desc";
        $this->show_quick_search = (isset($settings['show_quick_search'])) ? $settings['show_quick_search'] : 'yes';
        $this->sticky_search_form = (isset($settings['sticky_search_form'])) ? $settings['sticky_search_form'] : 'yes';
        $this->sticky_first_columns = (isset($settings['sticky_first_columns'])) ? $settings['sticky_first_columns'] : 'yes';
        $this->fetch_data_in_bulk = (isset($settings['fetch_data_in_bulk'])) ? $settings['fetch_data_in_bulk'] : 'no';
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

    public function get_fetch_data_in_bulk()
    {
        return $this->fetch_data_in_bulk;
    }

    public function update(array $data = [])
    {
        $settings = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $settings[$key] = $value;
            }
        }

        $this->set_current_settings($settings);
        return update_option($this->settings_option_name, serialize($settings));
    }

    public function get_settings()
    {
        return unserialize(get_option($this->settings_option_name));
    }

    public function set_default_settings()
    {
        $this->update([
            'count_per_page' => 10,
            'default_sort_by' => 'id',
            'default_sort' => "desc",
            'show_quick_search' => 'yes',
            'sticky_search_form' => 'yes',
            'sticky_first_columns' => 'yes',
            'fetch_data_in_bulk' => 'no',
        ]);
    }

    public function get_current_settings()
    {
        return unserialize(get_option($this->current_settings_option_name));
    }

    public function update_current_settings(array $current_settings)
    {
        $old_current_settings = $this->get_current_settings();
        if (!empty($current_settings)) {
            foreach ($current_settings as $setting_key => $setting_value) {
                $old_current_settings[$setting_key] = $setting_value;
            }
        }
        update_option($this->current_settings_option_name, serialize($old_current_settings));
        return $this->get_current_settings();
    }

    public function delete_current_settings()
    {
        return delete_option($this->current_settings_option_name);
    }

    private function set_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        $this->settings_option_name = "wpbel_{$post_type}_settings";
        $this->current_settings_option_name = "wpbel_{$post_type}_current_settings";
    }

    public function set_current_settings(array $settings)
    {
        $this->update_current_settings([
            'count_per_page' => $settings['count_per_page'],
            'sticky_first_columns' => $settings['sticky_first_columns']
        ]);
    }
}
