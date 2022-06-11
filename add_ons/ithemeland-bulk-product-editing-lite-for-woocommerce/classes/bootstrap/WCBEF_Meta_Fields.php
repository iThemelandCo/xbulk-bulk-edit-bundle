<?php


namespace wcbef\classes\bootstrap;


use wcbef\classes\helpers\Meta_Fields;
use wcbef\classes\repositories\Meta_Field;
use wcbef\classes\repositories\Product;

class WCBEF_Meta_Fields
{
    public function init()
    {
        add_filter('wcbef_column_fields', [$this, 'add_meta_fields_to_column_manager']);
        add_filter('wcbef_column_fields', [$this, 'add_attributes_to_column_manager']);
    }

    public function add_meta_fields_to_column_manager($fields)
    {
        $meta_fields = (new Meta_Field())->get();
        if (!empty($meta_fields)) {
            foreach ($meta_fields as $meta_field) {
                $content_type = '';
                switch ($meta_field['main_type']) {
                    case "textinput":
                        if ($meta_field['sub_type'] == 'string') {
                            $content_type = 'text';
                        } else {
                            $content_type = 'numeric';
                        }
                        break;
                    case 'textarea':
                        $content_type = 'textarea';
                        break;
                    case 'checkbox':
                        $content_type = 'checkbox';
                        break;
                    case 'array':
                        $content_type = 'text';
                        break;
                    case 'calendar':
                        $content_type = 'date';
                        break;
                }
                $fields[$meta_field['key']] = [
                    'field_type' => 'meta_field',
                    'label' => $meta_field['title'],
                    'editable' => true,
                    'content_type' => $content_type,
                    'allowed_type' => ['simple', 'variable', 'grouped', 'external']
                ];
            }
        }
        return $fields;
    }

    public function add_attributes_to_column_manager($fields)
    {
        $taxonomies = (new Product())->get_taxonomies();
        if (!empty($taxonomies)) {
            $default_taxonomies = Meta_Fields::get_default_taxonomies();
            if (!empty($default_taxonomies)) {
                $except = ['product_cat', 'product_tag'];
                foreach ($default_taxonomies as $default_taxonomy) {
                    if (!in_array($default_taxonomy, $except) && isset($taxonomies[$default_taxonomy])) {
                        unset($taxonomies[$default_taxonomy]);
                    }
                }
            }
            foreach ($taxonomies as $key => $taxonomy) {
                $fields[$key] = [
                    'label' => $taxonomy['label'],
                    'editable' => true,
                    'content_type' => 'multi_select',
                    'allowed_type' => ['simple', 'variable', 'grouped', 'external']
                ];

                if ($key != 'product_cat' && $key != 'product_tag') {
                    $fields[$key]['field_type'] = 'attribute';
                }
            }
        }
        return $fields;
    }
}