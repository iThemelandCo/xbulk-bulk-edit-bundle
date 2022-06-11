<?php

namespace wccbef\classes\helpers;

class Operator
{
    public static function edit_text()
    {
        return [
            'text_append' => esc_html__('Append', WBEBL_NAME),
            'text_prepend' => esc_html__('Prepend', WBEBL_NAME),
            'text_new' => esc_html__('New', WBEBL_NAME),
            'text_delete' => esc_html__('Delete', WBEBL_NAME),
            'text_replace' => esc_html__('Replace', WBEBL_NAME),
        ];
    }

    public static function edit_taxonomy()
    {
        return [
            'taxonomy_append' => esc_html__('Append', WBEBL_NAME),
            'taxonomy_replace' => esc_html__('Replace', WBEBL_NAME),
            'taxonomy_delete' => esc_html__('Delete', WBEBL_NAME),
        ];
    }

    public static function edit_number()
    {
        return [
            'number_new' => esc_html__('Set New', WBEBL_NAME),
            'number_clear' => esc_html__('Clear Value', WBEBL_NAME),
            'number_formula' => esc_html__('Formula', WBEBL_NAME),
            'increase_by_value' => esc_html__('Increase by value', WBEBL_NAME),
            'decrease_by_value' => esc_html__('Decrease by value', WBEBL_NAME),
            'increase_by_percent' => esc_html__('Increase by %', WBEBL_NAME),
            'decrease_by_percent' => esc_html__('Decrease by %', WBEBL_NAME),
        ];
    }

    public static function edit_regular_price()
    {
        return [
            'increase_by_value_from_sale' => esc_html__('Increase by value (From sale)', WBEBL_NAME),
            'increase_by_percent_from_sale' => esc_html__('Increase by % (From sale)', WBEBL_NAME),
        ];
    }

    public static function edit_sale_price()
    {
        return [
            'decrease_by_value_from_regular' => esc_html__('Decrease by value (From regular)', WBEBL_NAME),
            'decrease_by_percent_from_regular' => esc_html__('Decrease by % (From regular)', WBEBL_NAME),
        ];
    }

    public static function filter_text()
    {
        return [
            'like' => esc_html__('Like', WBEBL_NAME),
            'exact' => esc_html__('Exact', WBEBL_NAME),
            'not' => esc_html__('Not', WBEBL_NAME),
            'begin' => esc_html__('Begin', WBEBL_NAME),
            'end' => esc_html__('End', WBEBL_NAME),
        ];
    }

    public static function filter_multi_select()
    {
        return [
            'or' => 'OR',
            'and' => 'And',
            'not_in' => 'Not IN',
        ];
    }

    public static function round_items()
    {
        return [
            5 => 5,
            10 => 10,
            19 => 19,
            29 => 29,
            39 => 39,
            49 => 49,
            59 => 59,
            69 => 69,
            79 => 79,
            89 => 89,
            99 => 99
        ];
    }
}
