<?php

namespace wobef\classes\repositories;

use wobef\classes\helpers\Meta_Fields;

class Column
{
    const SHOW_ID_COLUMN = true;
    const DEFAULT_PROFILE_NAME = 'default';

    private $columns_option_name;
    private $active_columns_option_name;

    public function __construct()
    {
        $this->columns_option_name = "wobef_column_fields";
        $this->active_columns_option_name = 'wobef_active_columns';
    }

    public static function get_columns_title()
    {
        return [];
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
        $fields['billing'] = [
            'name' => 'Billing Fields',
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'billing',
            'fields' => $this->get_default_columns_billing(),
            'checked' => array_keys($this->get_default_columns_billing()),
        ];
        $fields['shipping'] = [
            'name' => 'Shipping Fields',
            'date_modified' => date('Y-m-d H:i:s', time()),
            'key' => 'shipping',
            'fields' => $this->get_default_columns_shipping(),
            'checked' => array_keys($this->get_default_columns_shipping()),
        ];
        return update_option('wobef_column_fields', $fields);
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
                        case 'billing':
                            $grouped_fields['Billing'][$key] = $field;
                            break;
                        case 'shipping':
                            $grouped_fields['Shipping'][$key] = $field;
                            break;
                        case 'pricing':
                            $grouped_fields['Pricing'][$key] = $field;
                            break;
                        case 'other_field':
                            $grouped_fields['Other Fields'][$key] = $field;
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
        $order_repository = new Order();
        $order_statuses = $order_repository->get_order_statuses();
        $currencies = $order_repository->get_currencies();
        $payment_methods = $order_repository->get_payment_methods();
        $countries = $order_repository->get_shipping_countries();
        $payment_methods['other'] = esc_html__('Other', WBEBL_NAME);

        return apply_filters('wobef_column_fields', [
            'post_date' => [
                'label' => esc_html__('Date', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'date_time_picker',
                'field_type' => 'general'
            ],
            'customer_note' => [
                'label' => esc_html__('Customer Note', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general'
            ],
            'order_notes' => [
                'label' => esc_html__('Order Notes', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'order_notes',
                'field_type' => 'general'
            ],
            'post_status' => [
                'label' => esc_html__('Status', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => $order_statuses,
                'field_type' => 'general'
            ],
            'post_modified' => [
                'label' => esc_html__('Modification Date', WBEBL_NAME),
                'editable' => false,
                'sortable' => true,
                'content_type' => 'date',
                'field_type' => 'general'
            ],
            'order_items' => [
                'label' => esc_html__('Order Items', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'order_items',
                'field_type' => 'general'
            ],
            'order_items_no' => [
                'label' => esc_html__('Order Items No.', WBEBL_NAME),
                'editable' => false,
                'sortable' => true,
                'content_type' => 'text',
                'field_type' => 'general'
            ],
            'coupon_used' => [
                'label' => esc_html__('Coupon Used', WBEBL_NAME),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'other_field'
            ],
            '_order_stock_reduced' => [
                'label' => esc_html__('Order Stock Reduce', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field'
            ],
            '_recorded_sales' => [
                'label' => esc_html__('Recorded Sales', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field'
            ],
            '_customer_ip_address' => [
                'label' => esc_html__('Customer IP Address', WBEBL_NAME),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general'
            ],
            '_customer_user' => [
                'label' => esc_html__('Customer User', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'customer',
                'field_type' => 'general'
            ],
            '_customer_user_agent' => [
                'label' => esc_html__('Customer User Agent', WBEBL_NAME),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general'
            ],
            '_paid_date' => [
                'label' => esc_html__('Paid Date', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'date_time_picker',
                'field_type' => 'general'
            ],
            '_completed_date' => [
                'label' => esc_html__('Completed Date', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'date',
                'field_type' => 'general'
            ],
            '_order_total' => [
                'label' => esc_html__('Order Total', WBEBL_NAME),
                'editable' => true,
                'sortable' => true,
                'content_type' => 'numeric',
                'field_type' => 'pricing'
            ],
            '_order_sub_total' => [
                'label' => esc_html__('Order SubTotal', WBEBL_NAME),
                'editable' => false,
                'sortable' => true,
                'content_type' => 'numeric',
                'field_type' => 'pricing'
            ],
            '_cart_discount' => [
                'label' => esc_html__('Cart Discount', WBEBL_NAME),
                'editable' => false,
                'sortable' => true,
                'content_type' => 'numeric',
                'field_type' => 'pricing'
            ],
            '_cart_discount_tax' => [
                'label' => esc_html__('Cart Discount Tax', WBEBL_NAME),
                'editable' => false,
                'sortable' => true,
                'content_type' => 'numeric',
                'field_type' => 'pricing'
            ],
            'order_details' => [
                'label' => esc_html__('Order Details', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'order_details',
                'field_type' => 'general'
            ],
            'all_billing' => [
                'label' => esc_html__('All Billing', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'all_billing',
                'field_type' => 'billing'
            ],
            'all_shipping' => [
                'label' => esc_html__('All Shipping', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'all_shipping',
                'field_type' => 'shipping'
            ],
            '_created_via' => [
                'label' => esc_html__('Create Via', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => [
                    'checkout' => esc_html__('Checkout', WBEBL_NAME),
                    'admin' => esc_html__('Admin', WBEBL_NAME),
                ],
                'field_type' => 'other_field'
            ],
            '_order_currency' => [
                'label' => esc_html__('Order Currency', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => (!empty($currencies) && is_array($currencies)) ? $currencies : [],
                'field_type' => 'pricing'
            ],
            '_payment_method' => [
                'label' => esc_html__('Payment Method', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => $payment_methods,
                'field_type' => 'other_field'
            ],
            '_payment_method_title' => [
                'label' => esc_html__('Payment Method Title', WBEBL_NAME),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'other_field'
            ],
            '_order_version' => [
                'label' => esc_html__('Order Version', WBEBL_NAME),
                'editable' => false,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'general'
            ],
            '_prices_include_tax' => [
                'label' => esc_html__('Prices Include Tax', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field'
            ],
            '_order_tax' => [
                'label' => esc_html__('Order Tax', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'numeric',
                'field_type' => 'pricing'
            ],
            '_order_shipping' => [
                'label' => esc_html__('Order Shipping', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field'
            ],
            '_order_shipping_tax' => [
                'label' => esc_html__('Order Shipping Tax', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'checkbox_dual_mode',
                'field_type' => 'other_field'
            ],
            '_billing_first_name' => [
                'label' => esc_html__('Billing First Name', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing'
            ],
            '_billing_last_name' => [
                'label' => esc_html__('Billing Last Name', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing'
            ],
            '_billing_address_1' => [
                'label' => esc_html__('Billing Address 1', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'billing'
            ],
            '_billing_address_2' => [
                'label' => esc_html__('Billing Address 2', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'billing'
            ],
            '_billing_city' => [
                'label' => esc_html__('Billing City', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing'
            ],
            '_billing_city' => [
                'label' => esc_html__('Billing City', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing'
            ],
            '_billing_company' => [
                'label' => esc_html__('Billing Company', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing'
            ],
            '_billing_country' => [
                'label' => esc_html__('Billing Country', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => $countries,
                'field_type' => 'billing'
            ],
            '_billing_email' => [
                'label' => esc_html__('Billing Email', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing'
            ],
            '_billing_phone' => [
                'label' => esc_html__('Billing Phone', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing'
            ],
            '_billing_address_index' => [
                'label' => esc_html__('Billing Address Index', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'billing'
            ],
            '_billing_postcode' => [
                'label' => esc_html__('Billing Postcode', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'billing'
            ],
            '_billing_state' => [
                'label' => esc_html__('Billing State', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'field_type' => 'billing'
            ],
            '_shipping_first_name' => [
                'label' => esc_html__('Shipping First Name', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping'
            ],
            '_shipping_last_name' => [
                'label' => esc_html__('Shipping Last Name', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping'
            ],
            '_shipping_address_1' => [
                'label' => esc_html__('Shipping Address 1', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'shipping'
            ],
            '_shipping_address_2' => [
                'label' => esc_html__('Shipping Address 2', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'shipping'
            ],
            '_shipping_city' => [
                'label' => esc_html__('Shipping City', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping'
            ],
            '_shipping_company' => [
                'label' => esc_html__('Shipping Company', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping'
            ],
            '_shipping_country' => [
                'label' => esc_html__('Shipping Country', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'options' => $countries,
                'field_type' => 'shipping'
            ],
            '_shipping_address_index' => [
                'label' => esc_html__('Shipping Address Index', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'address',
                'field_type' => 'shipping'
            ],
            '_shipping_postcode' => [
                'label' => esc_html__('Shipping Postcode', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'text',
                'field_type' => 'shipping'
            ],
            '_shipping_state' => [
                'label' => esc_html__('Shipping State', WBEBL_NAME),
                'editable' => true,
                'sortable' => false,
                'content_type' => 'select',
                'field_type' => 'shipping'
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
            'default',
            'billing',
            'shipping'
        ];
    }

    public static function get_default_columns_default()
    {
        $order_repository = new Order();
        $order_statuses = $order_repository->get_order_statuses();

        return [
            'post_status' => [
                'label' => esc_html__('Status', WBEBL_NAME),
                'title' => esc_html__('Status', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => $order_statuses,
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'general'
            ],
            '_billing_first_name' => [
                'label' => esc_html__('Billing First Name', WBEBL_NAME),
                'title' => esc_html__('Billing First Name', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_phone' => [
                'label' => esc_html__('Billing Phone', WBEBL_NAME),
                'title' => esc_html__('Billing Phone', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_shipping_first_name' => [
                'label' => esc_html__('Shipping First Name', WBEBL_NAME),
                'title' => esc_html__('Shipping First Name', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_last_name' => [
                'label' => esc_html__('Shipping Last Name', WBEBL_NAME),
                'title' => esc_html__('Shipping Last Name', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
        ];
    }

    public static function get_default_columns_billing()
    {
        $order_repository = new Order();
        $countries = $order_repository->get_shipping_countries();

        return [
            '_billing_first_name' => [
                'label' => esc_html__('Billing First Name', WBEBL_NAME),
                'title' => esc_html__('Billing First Name', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_last_name' => [
                'label' => esc_html__('Billing Last Name', WBEBL_NAME),
                'title' => esc_html__('Billing Last Name', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_email' => [
                'label' => esc_html__('Billing Email', WBEBL_NAME),
                'title' => esc_html__('Billing Email', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_phone' => [
                'label' => esc_html__('Billing Phone', WBEBL_NAME),
                'title' => esc_html__('Billing Phone', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_city' => [
                'label' => esc_html__('Billing City', WBEBL_NAME),
                'title' => esc_html__('Billing City', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_country' => [
                'label' => esc_html__('Billing Country', WBEBL_NAME),
                'title' => esc_html__('Billing Country', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => $countries,
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_state' => [
                'label' => esc_html__('Billing State', WBEBL_NAME),
                'title' => esc_html__('Billing State', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_address_1' => [
                'label' => esc_html__('Billing Address 1', WBEBL_NAME),
                'title' => esc_html__('Billing Address 1', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_address_2' => [
                'label' => esc_html__('Billing Address 2', WBEBL_NAME),
                'title' => esc_html__('Billing Address 2', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_address_index' => [
                'label' => esc_html__('Billing Address Index', WBEBL_NAME),
                'title' => esc_html__('Billing Address Index', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_postcode' => [
                'label' => esc_html__('Billing Postcode', WBEBL_NAME),
                'title' => esc_html__('Billing Postcode', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            '_billing_company' => [
                'label' => esc_html__('Billing Company', WBEBL_NAME),
                'title' => esc_html__('Billing Company', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
            'all_billing' => [
                'label' => esc_html__('All Billing', WBEBL_NAME),
                'title' => esc_html__('All Billing', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'all_billing',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'billing'
            ],
        ];
    }

    public static function get_default_columns_shipping()
    {
        $order_repository = new Order();
        $countries = $order_repository->get_shipping_countries();
        return [
            '_shipping_first_name' => [
                'label' => esc_html__('Shipping First Name', WBEBL_NAME),
                'title' => esc_html__('Shipping First Name', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_last_name' => [
                'label' => esc_html__('Shipping Last Name', WBEBL_NAME),
                'title' => esc_html__('Shipping Last Name', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_city' => [
                'label' => esc_html__('Shipping City', WBEBL_NAME),
                'title' => esc_html__('Shipping City', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_country' => [
                'label' => esc_html__('Shipping Country', WBEBL_NAME),
                'title' => esc_html__('Shipping Country', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'options' => $countries,
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_state' => [
                'label' => esc_html__('Shipping State', WBEBL_NAME),
                'title' => esc_html__('Shipping State', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'select',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_address_1' => [
                'label' => esc_html__('Shipping Address 1', WBEBL_NAME),
                'title' => esc_html__('Shipping Address 1', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_address_2' => [
                'label' => esc_html__('Shipping Address 2', WBEBL_NAME),
                'title' => esc_html__('Shipping Address 2', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_address_index' => [
                'label' => esc_html__('Shipping Address Index', WBEBL_NAME),
                'title' => esc_html__('Shipping Address Index', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'address',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_postcode' => [
                'label' => esc_html__('Shipping Postcode', WBEBL_NAME),
                'title' => esc_html__('Shipping Postcode', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            '_shipping_company' => [
                'label' => esc_html__('Shipping Company', WBEBL_NAME),
                'title' => esc_html__('Shipping Company', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'text',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
            'all_shipping' => [
                'label' => esc_html__('All Shipping', WBEBL_NAME),
                'title' => esc_html__('All Shipping', WBEBL_NAME),
                'editable' => true,
                'content_type' => 'all_shipping',
                'background_color' => '#fff',
                'text_color' => '#444',
                'field_type' => 'shipping'
            ],
        ];
    }
}
