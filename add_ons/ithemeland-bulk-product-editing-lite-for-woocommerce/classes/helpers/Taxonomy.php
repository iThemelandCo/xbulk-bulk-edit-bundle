<?php

namespace wcbef\classes\helpers;

use wcbef\classes\lib\WcbeProductsTaxonomyWalker;

class Taxonomy
{
    public static function wcbef_product_taxonomy_list($taxonomy = 'product_cat', array $checked = [], $args = [])
    {
        $defaults = array(
            'taxonomy' => esc_sql($taxonomy),
            'hide_empty' => false,
            'show_option_none' => '',
            'echo' => 0,
            'depth' => 0,
            'wrap_class' => 'wcbef-products-category-list',
            'level_class' => '',
            'parent_title_format' => '%s',
        );
        $args = wp_parse_args($args, $defaults);
        if (!taxonomy_exists($args['taxonomy'])) {
            return false;
        }
        $categories = get_categories($args);
        $output = "<ul class='" . esc_attr($args['wrap_class']) . "'>";
        if (empty($categories)) {
            if (!empty($args['show_option_none'])) {
                $output .= "<li>" . esc_html($args['show_option_none']) . "</li>";
            }
        } else {
            $walker = new WcbeProductsTaxonomyWalker($checked);
            $output .= $walker->walk($categories, $args['depth'], $args);
        }
        $output .= "</ul>";
        return $output;
    }
}
