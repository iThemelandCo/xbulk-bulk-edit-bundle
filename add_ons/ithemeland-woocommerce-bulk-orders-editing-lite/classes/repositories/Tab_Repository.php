<?php

namespace wobef\classes\repositories;

use wobef\classes\helpers\Generator;
use wobef\classes\helpers\Operator;

class Tab_Repository
{
    public function get_main_tabs_title()
    {
        return [
            'bulk-edit' => esc_html__('Bulk Edit', WBEBL_NAME),
            'column-manager' => esc_html__('Column Manager', WBEBL_NAME),
            'meta-fields' => esc_html__('Meta Fields', WBEBL_NAME),
            'history' => esc_html__('History', WBEBL_NAME),
            'import-export' => esc_html__('Import/Export', WBEBL_NAME),
            'settings' => esc_html__('Settings', WBEBL_NAME),
        ];
    }

    public function get_main_tabs_content()
    {
        return [
            'bulk-edit' => WOBEF_VIEWS_DIR . "bulk_edit/main.php",
            'column-manager' => WOBEF_VIEWS_DIR . "column_manager/main.php",
            'meta-fields' => WOBEF_VIEWS_DIR . "meta_field/main.php",
            'history' => WOBEF_VIEWS_DIR . "history/main.php",
            'import-export' => WOBEF_VIEWS_DIR . "import_export/main.php",
            'settings' => WOBEF_VIEWS_DIR . "settings/main.php",
        ];
    }

    public function get_bulk_edit_form_tabs_title()
    {
        return [
            'general' => esc_html__("General", WBEBL_NAME),
            'billing' => esc_html__("Billing", WBEBL_NAME),
            'shipping' => esc_html__("Shipping", WBEBL_NAME),
            'pricing' => esc_html__("Pricing", WBEBL_NAME),
            'other_fields' => esc_html__("Other Fields", WBEBL_NAME),
            'custom_fields' => esc_html__("Custom Fields", WBEBL_NAME),
        ];
    }

    public function get_bulk_edit_form_tabs_content()
    {
        $order_repository = new Order();
        $order_statuses = $order_repository->get_order_statuses();
        $payment_methods = $order_repository->get_payment_methods();
        $payment_methods['other'] = esc_html__('Other', WBEBL_NAME);
        $meta_field_repository = new Meta_Field();
        $meta_fields = $meta_field_repository->get();
        $custom_fields = [];
        $top_alert = [];
        if (!empty($meta_fields) && is_array($meta_fields)) {
            foreach ($meta_fields as $meta_field) {
                $field_id = 'wobef-bulk-edit-form-order-' . $meta_field['key'];
                $custom_fields[$meta_field['key']][] = Generator::label_field(['for' => $field_id], $meta_field['title']);
                if (in_array($meta_field['main_type'], $meta_field_repository::get_fields_name_have_operator()) || ($meta_field['main_type'] == $meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $meta_field_repository::STRING_TYPE)) {
                    $class = ($meta_field['main_type'] == $meta_field_repository::CALENDAR) ? 'wobef-datepicker' : '';
                    $custom_fields[$meta_field['key']][] = Generator::select_field([
                        'data-field' => 'operator',
                        'id' => $field_id . '-operator'
                    ], Operator::edit_text());
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'data-field' => 'value',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                        'class' => $class
                    ]);
                } elseif ($meta_field['main_type'] == $meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $meta_field_repository::NUMBER) {
                    $custom_fields[$meta_field['key']][] = Generator::select_field([
                        'data-field' => 'operator',
                        'for' => $field_id
                    ], Operator::edit_number());
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'number',
                        'data-field' => 'value',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                } elseif ($meta_field['main_type'] == $meta_field_repository::CHECKBOX) {
                    $custom_fields[$meta_field['key']][] = Generator::select_field([
                        'id' => $field_id,
                        'data-field' => 'value',
                    ], [
                        'yes' => esc_html_e('Yes', WBEBL_NAME),
                        'no' => esc_html_e('No', WBEBL_NAME),
                    ], true);
                } elseif (in_array($meta_field['main_type'], [$meta_field_repository::CALENDAR, $meta_field_repository::DATE])) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobef-input-md wobef-datepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                } elseif ($meta_field['main_type'] == $meta_field_repository::DATE_TIME) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobef-input-md wobef-datetimepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                } elseif ($meta_field['main_type'] == $meta_field_repository::TIME) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobef-input-md wobef-timepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                }
            }
        } else {
            $top_alert = [
                Generator::div_field_start([
                    'class' => 'wobef-alert wobef-alert-warning',
                ]),
                Generator::span_field(esc_html__('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', WBEBL_NAME)),
                Generator::div_field_end()
            ];
        }

        return [
            'general' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'selected wobef-tab-content-item',
                    'data-content' => 'general'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'post_date' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-created-date'], esc_html__('Date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wobef-datetimepicker',
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-created-date',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Date ...', WBEBL_NAME)
                        ]),
                    ],
                    '_paid_date' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-paid-date'], esc_html__('Paid Date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wobef-datetimepicker',
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-paid-date',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Paid Date ...', WBEBL_NAME)
                        ]),
                    ],
                    'post_status' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-status'], esc_html__('Status', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-bulk-edit-form-order-status',
                            'data-field' => 'value',
                            'title' => esc_html__('Select Status ...', WBEBL_NAME)
                        ], $order_statuses, true),
                    ],
                ]
            ],
            'billing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'billing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_billing_first_name' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-billing-first-name'], esc_html__('Billing First Name', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-bulk-edit-form-order-billing-first-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-billing-first-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing First Name ...', WBEBL_NAME)
                        ]),
                    ],
                    '_billing_last_name' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-billing-last-name'], esc_html__('Billing Last Name', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-bulk-edit-form-order-billing-last-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-billing-last-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Last Name ...', WBEBL_NAME)
                        ]),
                    ],
                    '_billing_address_1' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-billing-address-1'], esc_html__('Billing Address 1', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-bulk-edit-form-order-billing-address-1-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-billing-address-1',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Address 1 ...', WBEBL_NAME)
                        ]),
                    ],
                    '_billing_address_2' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-billing-address-2'], esc_html__('Billing Address 2', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-bulk-edit-form-order-billing-address-2-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-billing-address-2',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Address 2 ...', WBEBL_NAME)
                        ]),
                    ],
                    '_billing_city' => [
                        Generator::label_field([], esc_html__('Billing City', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Billing City ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_billing_company' => [
                        Generator::label_field([], esc_html__('Billing Company', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Billing Company ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_billing_country' => [
                        Generator::label_field([], esc_html__('Billing Country', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_billing_state' => [
                        Generator::label_field([], esc_html__('Billing State', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_billing_email' => [
                        Generator::label_field([], esc_html__('Billing Email', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Billing Email ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_billing_phone' => [
                        Generator::label_field([], esc_html__('Billing Phone', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Billing Phone ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_billing_postcode' => [
                        Generator::label_field([], esc_html__('Billing Postcode', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Billing Postcode ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                ]
            ],
            'shipping' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'shipping'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_shipping_first_name' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-shipping-first-name'], esc_html__('Shipping First Name', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-bulk-edit-form-order-shipping-first-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-shipping-first-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping First Name ...', WBEBL_NAME)
                        ]),
                    ],
                    '_shipping_last_name' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-shipping-last-name'], esc_html__('Shipping Last Name', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-bulk-edit-form-order-shipping-last-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-shipping-last-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Last Name ...', WBEBL_NAME)
                        ]),
                    ],
                    '_shipping_address_1' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-shipping-address-1'], esc_html__('Shipping Address 1', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-bulk-edit-form-order-shipping-address-1-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-shipping-address-1',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Address 1 ...', WBEBL_NAME)
                        ]),
                    ],
                    '_shipping_address_2' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-shipping-address-2'], esc_html__('Shipping Address 2', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-bulk-edit-form-order-shipping-address-2-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-bulk-edit-form-order-shipping-address-2',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Address 2 ...', WBEBL_NAME)
                        ]),
                    ],
                    '_shipping_city' => [
                        Generator::label_field([], esc_html__('Shipping City', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Shipping City ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_shipping_company' => [
                        Generator::label_field([], esc_html__('Shipping Company', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Shipping Company ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_shipping_country' => [
                        Generator::label_field([], esc_html__('Shipping Country', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md wobef-order-country',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Shipping Country ...', WBEBL_NAME)
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_shipping_state' => [
                        Generator::label_field([], esc_html__('Shipping State', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_shipping_postcode' => [
                        Generator::label_field([], esc_html__('Shipping Postcode', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Shipping Postcode ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                ]
            ],
            'pricing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'pricing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_order_currency' => [
                        Generator::label_field([], esc_html__('Order Currency', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Order Currency ...', WBEBL_NAME)
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_order_discount' => [
                        Generator::label_field([], esc_html__('Cart Discount', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_number()),
                        Generator::input_field([
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Cart Discount ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_order_discount_tax' => [
                        Generator::label_field([], esc_html__('Cart Discount Tax', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_number()),
                        Generator::input_field([
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Cart Discount Tax ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_order_total' => [
                        Generator::label_field([], esc_html__('Order Total', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_number()),
                        Generator::input_field([
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Order Total ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                ]
            ],
            'other_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'other_fields'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_created_via' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-create-via'], esc_html__('Create Via', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-bulk-edit-form-order-create-via',
                            'data-field' => 'value',
                            'title' => esc_html__('Create Via ...', WBEBL_NAME)
                        ], [
                            'checkout' => esc_html__('Checkout', WBEBL_NAME),
                            'admin' => esc_html__('Admin', WBEBL_NAME),
                        ], true),
                    ],
                    '_payment_method' => [
                        Generator::label_field(['for' => 'wobef-bulk-edit-form-order-payment-method'], esc_html__('Payment Method', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-bulk-edit-form-order-payment-method',
                            'data-field' => 'value',
                            'title' => esc_html__('Payment Method ...', WBEBL_NAME)
                        ], $payment_methods, true),
                    ],
                    '_shipping_tax' => [
                        Generator::label_field([], esc_html__('Shipping Tax', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Shipping Tax ...', WBEBL_NAME)
                        ], [
                            '1' => esc_html__('Yes', WBEBL_NAME),
                            '0' => esc_html__('No', WBEBL_NAME),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_order_shipping' => [
                        Generator::label_field([], esc_html__('Order Shipping', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Order Shipping ...', WBEBL_NAME)
                        ], [
                            '1' => esc_html__('Yes', WBEBL_NAME),
                            '0' => esc_html__('No', WBEBL_NAME),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_recorded-coupon_usage_counts' => [
                        Generator::label_field([], esc_html__('Coupon Usage Counts', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Recorder Coupon Usage Counts ...', WBEBL_NAME)
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_order_stock_reduced' => [
                        Generator::label_field([], esc_html__('Order Stock Reduced', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_prices_include_tax' => [
                        Generator::label_field([], esc_html__('Prices Index Tax', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Prices Index Tax ...', WBEBL_NAME)
                        ], [
                            '1' => esc_html__('Yes', WBEBL_NAME),
                            '0' => esc_html__('No', WBEBL_NAME),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_recorded_sales' => [
                        Generator::label_field([], esc_html__('Recorded Sales', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Recorded Sales ...', WBEBL_NAME)
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                ]
            ],
            'custom_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'custom_fields'
                ]),
                'fields_top' => $top_alert,
                'wrapper_end' => Generator::div_field_end(),
                'fields' => $custom_fields
            ],
        ];
    }

    public function get_filter_form_tabs_title()
    {
        return [
            'filter_general' => esc_html__("General", WBEBL_NAME),
            'filter_billing' => esc_html__("Billing", WBEBL_NAME),
            'filter_shipping' => esc_html__("Shipping", WBEBL_NAME),
            'filter_pricing' => esc_html__("Pricing", WBEBL_NAME),
            'filter_items' => esc_html__("Items", WBEBL_NAME),
            'filter_other_fields' => esc_html__("Other Fields", WBEBL_NAME),
            'filter_custom_fields' => esc_html__("Custom Fields", WBEBL_NAME),
        ];
    }

    public function get_filter_form_tabs_content()
    {
        $order_repository = new Order();
        $order_statuses = $order_repository->get_order_statuses();
        $shipping_countries = $order_repository->get_shipping_countries();
        $payment_methods = $order_repository->get_payment_methods();
        $payment_methods['other'] = esc_html__('Other', 'woocommerce');
        $meta_field_repository = new Meta_Field();
        $meta_fields = $meta_field_repository->get();
        $custom_fields = [];
        $top_alert = [];

        if (!empty($meta_fields) && is_array($meta_fields)) {
            foreach ($meta_fields as $meta_field) {
                $field_id = 'wobef-bulk-edit-form-order-' . $meta_field['key'];
                $custom_fields[$meta_field['key']][] = Generator::label_field(['for' => $field_id], $meta_field['title']);
                if (in_array($meta_field['main_type'], $meta_field_repository::get_fields_name_have_operator()) || ($meta_field['main_type'] == $meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $meta_field_repository::STRING_TYPE)) {
                    $class = ($meta_field['main_type'] == $meta_field_repository::CALENDAR) ? 'wobef-datepicker' : '';
                    $custom_fields[$meta_field['key']][] = Generator::select_field([
                        'data-field' => 'operator',
                        'id' => $field_id . '-operator'
                    ], Operator::filter_text());
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'data-field' => 'value',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                        'class' => $class
                    ]);
                } elseif ($meta_field['main_type'] == $meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $meta_field_repository::NUMBER) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'number',
                        'class' => 'wobef-input-md',
                        'data-field' => 'from',
                        'data-field-type' => 'number',
                        'id' => $field_id . '-from',
                        'placeholder' => $meta_field['title'] . ' From ...',
                    ]);
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'number',
                        'class' => 'wobef-input-md',
                        'data-field' => 'to',
                        'data-field-type' => 'number',
                        'id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' To ...',
                    ]);
                } elseif ($meta_field['main_type'] == $meta_field_repository::CHECKBOX) {
                    $custom_fields[$meta_field['key']][] = Generator::select_field([
                        'id' => $field_id,
                        'data-field' => 'value',
                    ], [
                        'yes' => esc_html_e('Yes', WBEBL_NAME),
                        'no' => esc_html_e('No', WBEBL_NAME),
                    ], true);
                } elseif (in_array($meta_field['main_type'], [$meta_field_repository::CALENDAR, $meta_field_repository::DATE])) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobef-input-md wobef-datepicker',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' From ...',
                    ]);
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobef-input-md wobef-datepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' To ...',
                    ]);
                } elseif ($meta_field['main_type'] == $meta_field_repository::DATE_TIME) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobef-input-md wobef-datetimepicker',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' From ...',
                    ]);
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobef-input-md wobef-datetimepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' To ...',
                    ]);
                } elseif ($meta_field['main_type'] == $meta_field_repository::TIME) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobef-input-md wobef-timepicker',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' From ...',
                    ]);
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wobef-input-md wobef-timepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' To ...',
                    ]);
                }
            }
        } else {
            $top_alert = [
                Generator::div_field_start([
                    'class' => 'wobef-alert wobef-alert-warning',
                ]),
                Generator::span_field(esc_html__('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', WBEBL_NAME)),
                Generator::div_field_end()
            ];
        }

        return [
            'filter_general' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'selected wobef-tab-content-item',
                    'data-content' => 'filter_general'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'order_ids' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-ids'], esc_html__('Order ID(s)', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-ids-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], [
                            'exact' => esc_html__('Exact', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-ids',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('for example: 1,2,3 or 1-10 or 1,2,3|10-20', WBEBL_NAME)
                        ]),
                    ],
                    'post_date' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-created-date-from'], esc_html__('Date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wobef-datetimepicker wobef-input-ft wobef-date-from',
                            'data-to-id' => 'wobef-filter-form-order-created-date-to',
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-created-date-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Date From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wobef-datetimepicker wobef-input-ft',
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-created-date-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Date To ...', WBEBL_NAME)
                        ]),
                    ],
                    'post_modified' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-modified-date-from'], esc_html__('Modified Date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wobef-datetimepicker wobef-input-ft wobef-date-from',
                            'data-to-id' => 'wobef-filter-form-order-modified-date-to',
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-modified-date-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Modified Date From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wobef-datetimepicker wobef-input-ft',
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-modified-date-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Modified Date To ...', WBEBL_NAME)
                        ]),
                    ],
                    '_paid_date' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-paid-date-from'], esc_html__('Paid Date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wobef-datetimepicker wobef-input-ft wobef-date-from',
                            'data-to-id' => 'wobef-filter-form-order-paid-date-to',
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-paid-date-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Paid Date From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wobef-datetimepicker wobef-input-ft',
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-paid-date-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Paid Date To ...', WBEBL_NAME)
                        ]),
                    ],
                    '_customer_ip_address' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-customer-ip-address'], esc_html__('Customer IP Address', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-customer-ip-address-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME),
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-customer-ip-address',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Customer IP Address ...', WBEBL_NAME)
                        ])
                    ],
                    'post_status' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-status'], esc_html__('Status', WBEBL_NAME)),
                        Generator::select_field([
                            'multiple' => 'true',
                            'class' => 'wobef-input-md wobef-select2',
                            'id' => 'wobef-filter-form-order-status',
                            'data-field' => 'value',
                            'title' => esc_html__('Select Status ...', WBEBL_NAME)
                        ], $order_statuses, false)
                    ],
                ]
            ],
            'filter_billing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'filter_billing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_billing_first_name' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-first-name'], esc_html__('Billing First Name', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-billing-first-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-billing-first-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing First Name ...', WBEBL_NAME)
                        ])
                    ],
                    '_billing_last_name' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-last-name'], esc_html__('Billing Last Name', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-billing-last-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-billing-last-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Last Name ...', WBEBL_NAME)
                        ])
                    ],
                    '_billing_address_1' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-address-1'], esc_html__('Billing Address 1', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-billing-address-1-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-billing-address-1',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Address 1 ...', WBEBL_NAME)
                        ])
                    ],
                    '_billing_address_2' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-address-2'], esc_html__('Billing Address 2', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-billing-address-2-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-billing-address-2',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Address 2 ...', WBEBL_NAME)
                        ])
                    ],
                    '_billing_city' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-city'], esc_html__('Billing City', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-billing-city-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-billing-city',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing City ...', WBEBL_NAME)
                        ])
                    ],
                    '_billing_company' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-company'], esc_html__('Billing Company', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-billing-company-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-billing-company',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Company ...', WBEBL_NAME)
                        ])
                    ],
                    '_billing_country' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-country'], esc_html__('Billing Country', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md wobef-order-country',
                            'data-state-target' => '.wobef-filter-form-order-billing-state',
                            'id' => 'wobef-filter-form-order-billing-country',
                            'data-field' => 'value',
                            'title' => esc_html__('Billing Country ...', WBEBL_NAME)
                        ], $shipping_countries, true)
                    ],
                    '_billing_state' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-state'], esc_html__('Billing State', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md wobef-filter-form-order-billing-state',
                            'data-field' => 'value',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Billing State ...', WBEBL_NAME)
                        ], [], true),
                        Generator::input_field([
                            'class' => 'wobef-input-md wobef-filter-form-order-billing-state wobef-h43',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('State ...', WBEBL_NAME),
                            'title' => esc_html__('Billing State ...', WBEBL_NAME)
                        ])
                    ],
                    '_billing_email' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-email'], esc_html__('Billing Email', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-billing-email-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-billing-email',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Email ...', WBEBL_NAME)
                        ])
                    ],
                    '_billing_phone' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-phone'], esc_html__('Billing Phone', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-billing-phone-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-billing-phone',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Phone ...', WBEBL_NAME)
                        ])
                    ],
                    '_billing_postcode' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-billing-postcode'], esc_html__('Billing Postcode', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-billing-postcode-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-billing-postcode',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Billing Postcode ...', WBEBL_NAME)
                        ])
                    ],
                ]
            ],
            'filter_shipping' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'filter_shipping'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_shipping_first_name' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-first-name'], esc_html__('Shipping First Name', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-shipping-first-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-shipping-first-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping First Name ...', WBEBL_NAME)
                        ])
                    ],
                    '_shipping_last_name' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-last-name'], esc_html__('Shipping Last Name', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-shipping-last-name-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-shipping-last-name',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Last Name ...', WBEBL_NAME)
                        ])
                    ],
                    '_shipping_address_1' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-address-1'], esc_html__('Shipping Address 1', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-shipping-address-1-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-shipping-address-1',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Address 1 ...', WBEBL_NAME)
                        ])
                    ],
                    '_shipping_address_2' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-address-2'], esc_html__('Shipping Address 2', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-shipping-address-2-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-shipping-address-2',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Address 2 ...', WBEBL_NAME)
                        ])
                    ],
                    '_shipping_city' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-city'], esc_html__('Shipping City', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-shipping-city-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-shipping-city',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping City ...', WBEBL_NAME)
                        ])
                    ],
                    '_shipping_company' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-company'], esc_html__('Shipping Company', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-shipping-company-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-shipping-company',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Company ...', WBEBL_NAME)
                        ])
                    ],
                    '_shipping_country' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-country'], esc_html__('Shipping Country', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md wobef-order-country',
                            'data-state-target' => '.wobef-filter-form-order-shipping-state',
                            'id' => 'wobef-filter-form-order-shipping-country',
                            'data-field' => 'value',
                            'title' => esc_html__('Shipping Country ...', WBEBL_NAME)
                        ], $shipping_countries, true)
                    ],
                    '_shipping_state' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-state'], esc_html__('Shipping State', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md wobef-filter-form-order-shipping-state',
                            'data-field' => 'value',
                            'disabled' => 'disabled',
                            'title' => esc_html__('Shipping State ...', WBEBL_NAME)
                        ], [], true),
                        Generator::input_field([
                            'class' => 'wobef-input-md wobef-filter-form-order-shipping-state wobef-h43',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('State ...', WBEBL_NAME),
                            'title' => esc_html__('Shipping State ...', WBEBL_NAME)
                        ])
                    ],
                    '_shipping_postcode' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-postcode'], esc_html__('Shipping Postcode', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-shipping-postcode-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wobef-filter-form-order-shipping-postcode',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Shipping Postcode ...', WBEBL_NAME)
                        ])
                    ],
                ]
            ],
            'filter_pricing' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'filter_pricing'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_order_currency' => [
                        Generator::label_field([], esc_html__('Order Currency', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'disabled' => 'disabled',
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_order_discount' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-discount-from'], esc_html__('Cart Discount', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wobef-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'id' => 'wobef-filter-form-order-discount-from',
                            'placeholder' => esc_html__('Cart Discount From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wobef-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'id' => 'wobef-filter-form-order-discount-to',
                            'placeholder' => esc_html__('Cart Discount To ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_order_discount_tax' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-discount-tax-from'], esc_html__('Cart Discount Tax', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wobef-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'id' => 'wobef-filter-form-order-discount-tax-from',
                            'placeholder' => esc_html__('Cart Discount Tax From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wobef-input-ft',
                            'type' => 'number',
                            'disabled' => 'disabled',
                            'id' => 'wobef-filter-form-order-discount-tax-to',
                            'placeholder' => esc_html__('Cart Discount Tax To ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    '_order_total' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-total-from'], esc_html__('Order Total', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wobef-input-ft',
                            'type' => 'number',
                            'id' => 'wobef-filter-form-order-total-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Order Total From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wobef-input-ft',
                            'type' => 'number',
                            'id' => 'wobef-filter-form-order-total-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Order Total To ...', WBEBL_NAME)
                        ]),
                    ],
                ]
            ],
            'filter_items' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'filter_items'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'products' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-products'], esc_html__('Products', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-products-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'id' => 'wobef-filter-form-order-products',
                            'data-field' => 'value',
                            'multiple' => 'true',
                            'class' => 'wobef-select2-products',
                            'data-placeholder' => esc_html__('Select Product', WBEBL_NAME) . ' ...',
                        ], []),
                    ],
                    'categories' => [
                        Generator::label_field([], esc_html__('Categories', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'disabled' => 'disabled',
                            'class' => 'wobef-select2-categories',
                            'data-placeholder' => esc_html__('Select Category', WBEBL_NAME) . ' ...',
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    'tags' => [
                        Generator::label_field([], esc_html__('Tags', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'disabled' => 'disabled',
                            'class' => 'wobef-select2-tags',
                            'data-placeholder' => esc_html__('Select Tag', WBEBL_NAME) . ' ...',
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                    'taxonomies' => [
                        Generator::label_field([], esc_html__('Taxonomies', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'disabled' => 'disabled',
                            'class' => 'wobef-select2-taxonomies',
                            'data-placeholder' => esc_html__('Select Taxonomy', WBEBL_NAME) . ' ...',
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wobef-short-description'
                        ])
                    ],
                ]
            ],
            'filter_other_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'filter_other_fields'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    '_created_via' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-create-via'], esc_html__('Create Via', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-filter-form-order-create-via',
                            'data-field' => 'value',
                            'title' => esc_html__('Create Via ...', WBEBL_NAME)
                        ], [
                            'checkout' => esc_html__('Checkout', WBEBL_NAME),
                            'admin' => esc_html__('Admin', WBEBL_NAME),
                        ], true)
                    ],
                    '_payment_method' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-payment-method'], esc_html__('Payment Method', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-filter-form-order-payment-method',
                            'data-field' => 'value',
                            'title' => esc_html__('Payment Method ...', WBEBL_NAME)
                        ], $payment_methods, true)
                    ],
                    '_order_shipping_tax' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping-tax'], esc_html__('Shipping Tax', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-filter-form-order-shipping-tax',
                            'data-field' => 'value',
                            'title' => esc_html__('Shipping Tax ...', WBEBL_NAME)
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true)
                    ],
                    '_order_shipping' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-shipping'], esc_html__('Order Shipping', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-filter-form-order-shipping',
                            'data-field' => 'value',
                            'title' => esc_html__('Order Shipping ...', WBEBL_NAME)
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true)
                    ],
                    '_recorded_coupon_usage_counts' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-recorder-coupon-usage-counts'], esc_html__('Coupon Usage Counts', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-filter-form-order-recorder-coupon-usage-counts',
                            'data-field' => 'value',
                            'title' => esc_html__('Recorder Coupon Usage Counts ...', WBEBL_NAME)
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true)
                    ],
                    '_order_stock_reduced' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-stock-reduced'], esc_html__('Order Stock Reduced', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-filter-form-order-stock-reduced',
                            'data-field' => 'value',
                            'title' => esc_html__('Order Stock Reduced ...', WBEBL_NAME)
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true)
                    ],
                    '_prices_include_tax' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-prices-index-tax'], esc_html__('Prices Index Tax', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-filter-form-order-prices-index-tax',
                            'data-field' => 'value',
                            'title' => esc_html__('Prices Index Tax ...', WBEBL_NAME)
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true)
                    ],
                    '_recorded_sales' => [
                        Generator::label_field(['for' => 'wobef-filter-form-order-recorded-sales'], esc_html__('Recorded Sales', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wobef-input-md',
                            'id' => 'wobef-filter-form-order-recorded-sales',
                            'data-field' => 'value',
                            'title' => esc_html__('Recorded Sales ...', WBEBL_NAME)
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true)
                    ],
                ]
            ],
            'filter_custom_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wobef-tab-content-item',
                    'data-content' => 'filter_custom_fields'
                ]),
                'fields_top' => $top_alert,
                'wrapper_end' => Generator::div_field_end(),
                'fields' => $custom_fields
            ],
        ];
    }
}
