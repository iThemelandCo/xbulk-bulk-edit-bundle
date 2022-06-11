<?php

namespace wobef\classes\helpers;

class Generator
{
    public static function div_field_start($attributes = [])
    {
        $output = "<div";
        $output .= self::get_field_attributes($attributes);
        $output .= ">";
        return sprintf('%s', $output);
    }

    public static function div_field_end()
    {
        return "</div>";
    }

    public static function label_field($attributes, $label_text)
    {
        $output = "<label";
        $output .= self::get_field_attributes($attributes);
        $output .= ">";
        if (!empty($label_text)) {
            $output .= $label_text;
        }
        $output .= "</label>";
        return sprintf('%s', $output);
    }

    public static function select_field($attributes, $options, $first_select_option = false)
    {
        $output = "<select";
        $output .= self::get_field_attributes($attributes);
        $output .= ">";
        if ($first_select_option) {
            $output .= "<option value=''>" . esc_html__('Select', WBEBL_NAME) . "</option>";
        }
        if (!empty($options) && is_array($options)) {
            foreach ($options as $key => $value) {
                $output .= '<option value="' . esc_attr($key) . '">' . esc_html($value) . '</option>';
            }
        }
        $output .= "</select>";

        return sprintf('%s', $output);
    }

    public static function textarea_field($attributes, $value = "")
    {
        $output = "<textarea";
        $output .= self::get_field_attributes($attributes);
        $output .= ">";
        if (!empty($value)) {
            $output .= $value;
        }
        $output .= "</textarea>";
        return sprintf('%s', $output);
    }

    public static function input_field($attributes)
    {
        $output = "<input";
        $output .= self::get_field_attributes($attributes);
        $output .= ">";
        return sprintf('%s', $output);
    }

    public static function span_field($text, $attributes = [])
    {
        $output = "<span";
        $output .= self::get_field_attributes($attributes);
        $output .= ">";
        $output .= esc_html($text);
        $output .= "</span>";
        return sprintf('%s', $output);
    }

    private static function get_field_attributes($attributes = [])
    {
        $output = "";
        if (!empty($attributes) && is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $output .= " " . esc_attr($key) . '="' . esc_html($value) . '"';
            }
        }
        return $output;
    }
}
