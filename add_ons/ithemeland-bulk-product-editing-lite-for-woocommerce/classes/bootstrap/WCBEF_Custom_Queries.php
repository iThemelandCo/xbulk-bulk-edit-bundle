<?php

namespace wcbef\classes\bootstrap;

use wcbef\classes\repositories\Product;

class WCBEF_Custom_Queries
{
    public function init()
    {
        add_filter('posts_where', [$this, 'general_column_filter'], 10, 2);
        add_filter('posts_where', [$this, 'meta_filter'], 10, 2);
    }

    public function general_column_filter($where, $wp_query)
    {
        global $wpdb;
        if ($search_term = $wp_query->get('wcbef_general_column_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $field = esc_sql($item['field']);
                    $value = trim(esc_sql($item['value']));
                    switch ($item['operator']) {
                        case 'like':
                            $custom_where = "(posts.{$field} LIKE '%{$value}%')";
                            break;
                        case 'exact':
                            $custom_where = "(posts.{$field} = '{$value}')";
                            break;
                        case 'not':
                            $custom_where = "(posts.{$field} != '{$value}')";
                            break;
                        case 'begin':
                            $custom_where = "(posts.{$field} LIKE '{$value}%')";
                            break;
                        case 'end':
                            $custom_where = "(posts.{$field} LIKE '%{$value}')";
                            break;
                        case 'in':
                            $custom_where = "(posts.{$field} IN ({$value}))";
                            break;
                        case 'not_in':
                            $custom_where = "(posts.{$field} NOT IN ({$value}))";
                            break;
                        case 'between':
                            $value = (is_numeric($value[1])) ? "{$value[0]} AND {$value[1]}" : "'{$value[0]}' AND '{$value[1]}'";
                            $custom_where = "(posts.{$field} BETWEEN {$value})";
                            break;
                        case '>':
                            $custom_where = "(posts.{$field} > {$value})";
                            break;
                        case '<':
                            $custom_where = "(posts.{$field} < {$value})";
                            break;
                        case '>_with_quotation':
                            $custom_where = "(posts.{$field} > '{$value}')";
                            break;
                        case '<_with_quotation':
                            $custom_where = "(posts.{$field} < '{$value}')";
                            break;
                    }

                    $product_repository = new Product();
                    if (isset($item['parent_only']) && $item['parent_only'] === true) {
                        $products_ids = $product_repository->get_product_ids_by_custom_query('', $custom_where, 'product');
                    } else {
                        $products_ids = $product_repository->get_product_ids_by_custom_query('', $custom_where);
                    }

                    $ids = (!empty($products_ids)) ? $products_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }

        return $where;
    }

    public function meta_filter($where, $wp_query)
    {
        global $wpdb;
        $product_repository = new Product();
        $join = "LEFT JOIN $wpdb->postmeta AS postmeta ON (posts.ID = postmeta.post_id)";
        if ($search_term = $wp_query->get('wcbef_meta_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $key = esc_sql($item['key']);
                    $value = esc_sql($item['value']);
                    switch ($item['operator']) {
                        case 'like':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$value}%')";
                            break;
                        case 'exact':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value = '{$value}')";
                            break;
                        case 'not':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value != '{$value}')";
                            break;
                        case 'begin':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '{$value}%')";
                            break;
                        case 'end':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$value}')";
                            break;
                        case 'in':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value IN ({$value}))";
                            break;
                        case 'between':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value BETWEEN {$value[0]} AND {$value[1]})";
                            break;
                        case 'between_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value BETWEEN '{$value[0]}' AND '{$value[1]}')";
                            break;
                        case '<=':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value <= {$value})";
                            break;
                        case '>=':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value >= {$value})";
                            break;
                        case '<=_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value <= '{$value}')";
                            break;
                        case '>=_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value >= '{$value}')";
                            break;
                    }
                    $products_ids = $product_repository->get_product_ids_by_custom_query($join, $custom_where);
                    $ids = (!empty($products_ids)) ? $products_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }
        return $where;
    }
}
