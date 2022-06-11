<?php

namespace wcbef\classes\repositories;

use wcbef\classes\helpers\Columns;
use wcbef\classes\helpers\Meta_Fields;

class Column
{
    public function update(array $data)
    {
        if (!isset($data['key'])) {
            return false;
        }

        $presets = $this->get_presets();
        $presets[$data['key']] = $data;
        return update_option('wcbef_column_fields', serialize($presets));
    }

    public function delete($preset_key)
    {
        $presets = $this->get_presets();
        if (is_array($presets) && array_key_exists($preset_key, $presets)) {
            unset($presets[$preset_key]);
        }
        return update_option('wcbef_column_fields', serialize($presets));
    }

    public function get_preset($preset_key)
    {
        $presets = $this->get_presets();
        return (isset($presets[$preset_key])) ? $presets[$preset_key] : false;
    }

    public function get_presets()
    {
        return unserialize(get_option('wcbef_column_fields'));
    }

    public function set_default_fields()
    {
        $fields['default1'] = [
            'name' => 'Default 1',
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'default1',
            'fields' => Columns::get_default_columns1(),
            'checked' => array_keys(Columns::get_default_columns1()),
        ];
        $fields['default2'] = [
            'name' => 'Default 2',
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'default2',
            'fields' => Columns::get_default_columns2(),
            'checked' => array_keys(Columns::get_default_columns2()),
        ];
        $fields['default3'] = [
            'name' => 'Default 3',
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'default3',
            'fields' => Columns::get_default_columns3(),
            'checked' => array_keys(Columns::get_default_columns3()),
        ];
        return update_option('wcbef_column_fields', serialize($fields));
    }

    public function get_fields()
    {
        $tax_classes = [];
        foreach (\WC_Tax::get_tax_classes() as $tax_class) {
            $name = '';
            if ($tax_class != 'Standard') {
                $name = str_replace(' ', '-', strtolower($tax_class));
            }
            $tax_classes[$name] = $tax_class;
        }
        $shipping_items = wc()->shipping()->get_shipping_classes();
        $shipping_classes = [
            -1 => 'No Shipping Class',
        ];
        if (!empty($shipping_items)) {
            foreach ($shipping_items as $shipping_class) {
                $shipping_classes[$shipping_class->term_id] = $shipping_class->name;
            }
        }

        $users = get_users();
        $authors = [];
        if (!empty($users)) {
            foreach ($users as $user_item) {
                $authors[$user_item->ID] = $user_item->user_login;
            }
        }

        return apply_filters('wcbef_column_fields', [
            'post_parent' => [
                'label' => esc_html__('Parent', WBEBL_NAME),
                'editable' => false,
                'content_type' => 'numeric_without_calculator',
                'allowed_type' => ['variation']
            ],
            '_thumbnail_id' => [
                'label' => esc_html__('Thumbnail', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'image',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'gallery' => [
                'label' => esc_html__('Gallery', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'gallery',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'post_slug' => [
                'label' => esc_html__('Slug', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'post_content' => [
                'label' => esc_html__('Description', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'post_excerpt' => [
                'label' => esc_html__('Short Description', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'post_status' => [
                'label' => esc_html__('Status', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => get_post_statuses(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'product_type' => [
                'label' => esc_html__('Product Type', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_types(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'product_url' => [
                'label' => esc_html__('Product URL', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'grouped', 'external']
            ],
            'button_text' => [
                'label' => esc_html__('Button Text', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'grouped', 'external']
            ],
            'catalog_visibility' => [
                'label' => esc_html__('Catalog Visibility', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_visibility_options(),
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'featured' => [
                'label' => esc_html__('Featured', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'checkbox',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'regular_price' => [
                'label' => esc_html__('Regular price', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'regular_price',
                'allowed_type' => ['simple', 'variation', 'external']
            ],
            'sale_price' => [
                'label' => esc_html__('Sale price', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'sale_price',
                'allowed_type' => ['simple', 'variation', 'external']
            ],
            'date_on_sale_from' => [
                'label' => esc_html__('Sale time from', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external']
            ],
            'date_on_sale_to' => [
                'label' => esc_html__('Sale time to', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variation', 'external']
            ],
            '_children' => [
                'label' => esc_html__('Grouped Products', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['grouped']
            ],
            'downloadable' => [
                'label' => esc_html__('Downloadable', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'checkbox',
                'allowed_type' => ['simple', 'variation', 'external', 'variation']
            ],
            'downloadable_files' => [
                'label' => esc_html__('Downloadable Files', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select_files',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation']
            ],
            'download_limit' => [
                'label' => esc_html__('Download limit', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation']
            ],
            'download_expiry' => [
                'label' => esc_html__('Download expiry', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation']
            ],
            'tax_status' => [
                'label' => esc_html__('Tax status', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => [
                    'taxable' => 'Taxable',
                    'shipping' => 'Shipping Only',
                    'none' => 'None'
                ],
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'tax_class' => [
                'label' => esc_html__('Tax class', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => $tax_classes,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'sku' => [
                'label' => esc_html__('SKU', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'text',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'manage_stock' => [
                'label' => esc_html__('Manage stock', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'checkbox',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'stock_quantity' => [
                'label' => esc_html__('Stock quantity', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'stock_status' => [
                'label' => esc_html__('Stock status', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_stock_status_options(),
                'allowed_type' => ['simple', 'grouped', 'external', 'variation']
            ],
            'backorders' => [
                'label' => esc_html__('Allow backorders', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => wc_get_product_backorder_options(),
                'allowed_type' => ['simple', 'grouped', 'external', 'variation']
            ],
            'sold_individually' => [
                'label' => esc_html__('Sold individually', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'checkbox',
                'allowed_type' => ['simple', 'variable', 'external', 'grouped']
            ],
            'weight' => [
                'label' => esc_html__('Weight', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'external', 'grouped', 'variation']
            ],
            'length' => [
                'label' => esc_html__('Length', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'width' => [
                'label' => esc_html__('Width', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'height' => [
                'label' => esc_html__('Height', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'shipping_class' => [
                'label' => esc_html__('Shipping class', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => $shipping_classes,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'upsell_ids' => [
                'label' => esc_html__('Upsells', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'cross_sell_ids' => [
                'label' => esc_html__('Cross-sells', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select_products',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'purchase_note' => [
                'label' => esc_html__('Purchase note', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'textarea',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'menu_order' => [
                'label' => esc_html__('Menu order', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'reviews_allowed' => [
                'label' => esc_html__('Reviews allowed', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'checkbox',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'virtual' => [
                'label' => esc_html__('Virtual', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'checkbox',
                'allowed_type' => ['simple', 'grouped', 'external', 'variation']
            ],
            'post_author' => [
                'label' => esc_html__('Author', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => $authors,
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'total_sales' => [
                'label' => esc_html__('Total sales', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'review_count' => [
                'label' => esc_html__('Review count', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external', 'variation']
            ],
            'average_rating' => [
                'label' => esc_html__('Average rating', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ],
            'post_date' => [
                'label' => esc_html__('Date Published', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date',
                'allowed_type' => ['simple', 'variable', 'grouped', 'external']
            ]
        ]);
    }

    public function update_meta_field_items()
    {
        $presets = $this->get_presets();
        $meta_fields = (new Meta_Field())->get();
        if (!empty($presets)) {
            foreach ($presets as $preset) {
                if (!empty($preset['fields'])) {
                    foreach ($preset['fields'] as $field) {
                        if (isset($field['field_type'])) {
                            if (isset($meta_fields[$field['name']])) {
                                $preset['fields'][$field['name']]['content_type'] = Meta_Fields::get_meta_field_type($meta_fields[$field['name']]['main_type'], $meta_fields[$field['name']]['sub_type']);
                                $this->update($preset);
                            }
                        }
                    }
                }
            }
        }
    }
}
