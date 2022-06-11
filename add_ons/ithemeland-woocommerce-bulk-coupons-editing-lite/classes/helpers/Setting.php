<?php


namespace wccbef\classes\helpers;


class Setting
{
    public static function get_count_per_page_items()
    {
        return [
            '10',
            '25',
            '50',
            '75',
            '100',
        ];
    }

    public static function get_arg_coupon_by($default_sort, $args)
    {
        switch ($default_sort) {
            case 'ID':
            case 'id':
                $args['orderby'] = 'ID';
                break;
            case 'post_title':
                $args['orderby'] = 'post_title';
                break;
            case 'post_date':
                $args['orderby'] = 'post_date';
                break;
            case 'post_modified':
                $args['orderby'] = 'post_modified';
                break;
        }

        return $args;
    }
}
