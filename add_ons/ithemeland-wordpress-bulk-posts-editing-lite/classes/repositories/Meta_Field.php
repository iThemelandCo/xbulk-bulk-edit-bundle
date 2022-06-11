<?php

namespace wpbel\classes\repositories;

use wpbel\classes\helpers\Post_Helper;

class Meta_Field
{
    const TEXTINPUT = "textinput";
    const TEXTAREA = "textarea";
    const CHECKBOX = "checkbox";
    const ARRAY_TYPE = "array";
    const CALENDAR = "calendar";

    const STRING_TYPE = "string";
    const NUMBER = "number";

    private $meta_fields_option_name;

    public function __construct(string $post_type = "")
    {
        $post_type = (!empty($post_type)) ? $post_type : $GLOBALS['wpbel_common']['active_post_type'];
        $this->set_option_name($post_type);
    }

    public static function get_fields_name_have_operator()
    {
        return [
            self::TEXTAREA,
            self::ARRAY_TYPE,
        ];
    }

    public static function get_main_types()
    {
        return [
            self::TEXTINPUT => esc_html__('TextInput', WBEBL_NAME),
            self::TEXTAREA => esc_html__('TextArea', WBEBL_NAME),
            self::CHECKBOX => esc_html__('Checkbox', WBEBL_NAME),
            self::ARRAY_TYPE => esc_html__('Array', WBEBL_NAME),
            self::CALENDAR => esc_html__('Calendar', WBEBL_NAME)
        ];
    }

    public static function get_sub_types()
    {
        return [
            self::STRING_TYPE => esc_html__('String', WBEBL_NAME),
            self::NUMBER => esc_html__('Number', WBEBL_NAME),
        ];
    }

    public function update(array $meta_fields)
    {
        return update_option($this->meta_fields_option_name, serialize($meta_fields));
    }

    public function get()
    {
        $meta_fields = get_option($this->meta_fields_option_name);
        return !empty($meta_fields) ? unserialize($meta_fields) : [];
    }

    private function set_option_name(string $post_type)
    {
        $post_type = Post_Helper::get_post_type_name($post_type);
        $this->meta_fields_option_name = "wpbel_{$post_type}_meta_fields";
    }
}
