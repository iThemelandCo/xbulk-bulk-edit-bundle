<?php

namespace wccbef\classes\repositories;

use wccbef\classes\helpers\Meta_Fields;

class Column
{
    const SHOW_ID_COLUMN = true;
    const DEFAULT_PROFILE_NAME = 'default';

    private $columns_option_name;
    private $active_columns_option_name;

    public function __construct()
    {
        $this->columns_option_name = "wccbef_column_fields";
        $this->active_columns_option_name = 'wccbef_active_columns';
    }

    public function update(array $data)
    {
        if (!isset($data['key'])) {
            return false;
        }

        $presets = $this->get_presets();
        $presets[$data['key']] = $data;
        return update_option($this->columns_option_name, $presets);
    }

    public function delete($preset_key)
    {
        $presets = $this->get_presets();
        if (is_array($presets) && array_key_exists($preset_key, $presets)) {
            unset($presets[$preset_key]);
        }
        return update_option($this->columns_option_name, $presets);
    }

    public function get_preset($preset_key)
    {
        $presets = $this->get_presets();
        return (isset($presets[$preset_key])) ? $presets[$preset_key] : false;
    }

    public function get_presets()
    {
        return get_option($this->columns_option_name);
    }

    public function set_active_columns(string $profile_name, array $columns, string $option_name = "")
    {
        $option_name = (!empty($option_name)) ? esc_sql($option_name) : $this->active_columns_option_name;
        return update_option($option_name, ['name' => $profile_name, 'fields' => $columns]);
    }

    public function get_active_columns()
    {
        return get_option($this->active_columns_option_name);
    }

    public function delete_active_columns()
    {
        return delete_option($this->active_columns_option_name);
    }

    public function has_column_fields()
    {
        $columns = get_option($this->columns_option_name);
        return !empty($columns['default']['fields']);
    }

    public static function get_columns_title()
    {
        return [
            'coupon_amount' => "Value of the coupon",
            'date_expires' => "The coupon will expire <br> at 00:00:00 of this date",
            'minimum_amount' => "This field allows you to set <br> the minimum spend (subtotal) allowed <br> to use the coupon.",
            'maximum_amount' => "This field allows you to set <br> the maximum spend (subtotal) allowed <br> when using the coupon.",
            'product_ids' => "Products that the coupon will <br> be applied to, or that need to be in <br> the cart in order for the <br> 'Fixed cart discount to be applied.",
            'exclude_product_ids' => "Products that the coupon will not <br> be applied to, or that cannot to be in <br> the cart in order for the <br> 'Fixed cart discount to be applied.",
            'product_categories' => "Product categories that the <br> coupon will be applied to, or that need to <br> be in the cart in order for the <br> 'Fixed cart discount' to be applied.",
            'exclude_product_categories' => "Product categories that the coupon will <br> not be applied to, or that cannot be in the <br> cart in order for the <br> 'Fixed cart discount' to be applied.",
            'customer_email' => "List of allowed billing emails <br> to check against when an order is placed. <br> Separate email addresses with commas.<br> You can also use an asterisk (*) to <br> match parts of an email",
            'usage_limit' => "How many times this coupon <br> can be used before it is void.",
            'usage_limit_per_user' => "How many times this coupon <br> can be used by an individual user. <br> Uses billing email for guests, and user ID <br> for logged in users.",
        ];
    }

    public static function get_static_columns()
    {
        return [
            'post_title' => [
                'field' => 'post_title',
                'title' => esc_html__('Title', WBEBL_NAME)
            ]
        ];
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

    public function set_default_columns()
    {
        $fields['default'] = [
            'name' => 'Default',
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'default',
            'fields' => $this->get_default_columns_default(),
            'checked' => array_keys($this->get_default_columns_default()),
        ];
        return update_option('wccbef_column_fields', $fields);
    }

    public function get_grouped_fields()
    {
        $grouped_fields = [];
        $fields = $this->get_fields();
        if (!empty($fields)) {
            foreach ($fields as $key => $field) {
                if (isset($field['field_type'])) {
                    switch ($field['field_type']) {
                        case 'general':
                            $grouped_fields['General'][$key] = $field;
                            break;
                        case 'usage_limits':
                            $grouped_fields['Usage limits'][$key] = $field;
                            break;
                        case 'usage_restriction':
                            $grouped_fields['Usage restriction'][$key] = $field;
                            break;
                        case 'custom_field':
                            $grouped_fields['Custom Fields'][$key] = $field;
                            break;
                    }
                } else {
                    $grouped_fields['General'][$key] = $field;
                }
            }
        }
        return $grouped_fields;
    }

    public function get_fields()
    {
        $discount_types = wc_get_coupon_types();
        $coupon_statuses = get_post_statuses();

        return apply_filters('wccbef_column_fields', [
            'post_excerpt' => [
                'label' => esc_html__('Description', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general'
            ],
            'post_date' => [
                'label' => esc_html__('Published on', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date_time_picker',
                'field_type' => 'general'
            ],
            'post_modified' => [
                'label' => esc_html__('Modification date', WBEBL_NAME),
                'editable' => false,
                'sortable' => true,
                'content_type' => 'date',
                'field_type' => 'general'
            ],
            'date_expires' => [
                'label' => esc_html__('Coupon expiry date', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'date',
                'field_type' => 'general'
            ],
            'product_ids' => [
                'label' => esc_html__('Products', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'products',
                'field_type' => 'usage_restriction'
            ],
            'exclude_product_ids' => [
                'label' => esc_html__('Exclude products', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'products',
                'field_type' => 'usage_restriction'
            ],
            'product_categories' => [
                'label' => esc_html__('Product categories', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'product_categories',
                'field_type' => 'usage_restriction'
            ],
            'exclude_product_categories' => [
                'label' => esc_html__('Exclude categories', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'product_categories',
                'field_type' => 'usage_restriction'
            ],
            'coupon_amount' => [
                'label' => esc_html__('Coupon amount', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'general'
            ],
            'minimum_amount' => [
                'label' => esc_html__('Minimum spend', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'usage_restriction'
            ],
            'maximum_amount' => [
                'label' => esc_html__('Maximum spend', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'usage_restriction'
            ],
            'usage_limit' => [
                'label' => esc_html__('Usage limit per coupon', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'usage_limits'
            ],
            'usage_limit_per_user' => [
                'label' => esc_html__('Usage limit per user', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'usage_limits'
            ],
            'limit_usage_to_x_items' => [
                'label' => esc_html__('Usage limit to x items', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'usage_limits'
            ],
            'discount_type' => [
                'label' => esc_html__('Discount type', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => $discount_types,
                'field_type' => 'general'
            ],
            'post_status' => [
                'label' => esc_html__('Status', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => $coupon_statuses,
                'field_type' => 'general'
            ],
            'free_shipping' => [
                'label' => esc_html__('Allow free shipping', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'general'
            ],
            'individual_use' => [
                'label' => esc_html__('Individual use only', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'usage_restriction'
            ],
            'exclude_sale_items' => [
                'label' => esc_html__('Exclude sale items', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'usage_restriction'
            ],
            'customer_email' => [
                'label' => esc_html__('Allowed Emails', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'usage_restriction'
            ],
            'usage_count' => [
                'label' => esc_html__('Usage count', WBEBL_NAME),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general'
            ],
            '_used_by' => [
                'label' => esc_html__('Used by', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'used_by',
                'field_type' => 'general'
            ],
            'used_in' => [
                'label' => esc_html__('Used in', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'used_in',
                'field_type' => 'general'
            ],
        ]);
    }

    public function set_default_active_columns()
    {
        return $this->set_active_columns(self::DEFAULT_PROFILE_NAME, self::get_default_columns_default());
    }

    public static function get_default_columns_name()
    {
        return [
            'default'
        ];
    }

    public static function get_default_columns_default()
    {
        $discount_types = wc_get_coupon_types();

        return [
            'discount_type' => [
                'label' => esc_html__('Discount type', WBEBL_NAME),
                'title' => esc_html__('Discount type', WBEBL_NAME),
                'editable' => true,
                'options' => $discount_types,
                'content_type' => 'date_time_picker',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general'
            ],
            'coupon_amount' => [
                'label' => esc_html__('Coupon amount', WBEBL_NAME),
                'title' => esc_html__('Coupon amount', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'general',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'post_excerpt' => [
                'label' => esc_html__('Description', WBEBL_NAME),
                'title' => esc_html__('Description', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'product_ids' => [
                'label' => esc_html__('Products', WBEBL_NAME),
                'title' => esc_html__('Products', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'products',
                'field_type' => 'usage_restriction',
                'background_color' => '#fff',
                'text_color' => '#444',
            ],
            'usage_limit' => [
                'label' => esc_html__('Usage limit per coupon', WBEBL_NAME),
                'title' => esc_html__('Usage limit per coupon', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'usage_limits',
                'text_color' => '#444',
            ],
            'date_expires' => [
                'label' => esc_html__('Coupon expiry date', WBEBL_NAME),
                'title' => esc_html__('Coupon expiry date', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'date',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general'
            ],
        ];
    }
}
