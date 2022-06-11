<?php


namespace wcbef\classes\repositories;


class Search
{
    public function update(array $data)
    {
        if (!isset($data['key'])) {
            return false;
        }

        $presets = $this->get_presets();
        $presets[$data['key']] = $data;
        return update_option('wcbef_filter_profile', serialize($presets));
    }

    public function delete($preset_key)
    {
        $presets = $this->get_presets();
        if (is_array($presets) && array_key_exists($preset_key, $presets)) {
            unset($presets[$preset_key]);
        }
        return update_option('wcbef_filter_profile', serialize($presets));
    }

    public function get_preset($preset_key)
    {
        $presets = $this->get_presets();
        return (isset($presets[esc_sql($preset_key)])) ? $presets[esc_sql($preset_key)] : false;
    }

    public function get_presets()
    {
        return unserialize(get_option('wcbef_filter_profile'));
    }

    public function update_use_always($preset_key)
    {
        return update_option('wcbef_filter_profile_use_always', esc_sql($preset_key));
    }

    public function get_use_always()
    {
        return get_option('wcbef_filter_profile_use_always');
    }

    public function set_default_item()
    {
        $default_item['default'] = [
            'name' => esc_html__('All Products', WBEBL_NAME),
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'default',
            'filter_data' => []
        ];
        $this->update_use_always('default');
        return update_option('wcbef_filter_profile', serialize($default_item));
    }
}
