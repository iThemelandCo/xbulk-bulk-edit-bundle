<?php

namespace wccbef\classes\repositories;

use wccbef\classes\helpers\Generator;
use wccbef\classes\helpers\Operator;

class Tab_Repository
{
    private $field_titles;
    private $coupon_statuses;
    private $discount_types;
    private $meta_fields;
    private $meta_field_repository;

    public function __construct()
    {
        $column_repository = new Column();
        $this->meta_field_repository = new Meta_Field();

        $this->field_titles = $column_repository->get_columns_title();
        $this->coupon_statuses = get_post_statuses();
        $this->discount_types = wc_get_coupon_types();
        $this->meta_fields = $this->meta_field_repository->get();
    }

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
            'bulk-edit' => WCCBEF_VIEWS_DIR . "bulk_edit/main.php",
            'column-manager' => WCCBEF_VIEWS_DIR . "column_manager/main.php",
            'meta-fields' => WCCBEF_VIEWS_DIR . "meta_field/main.php",
            'history' => WCCBEF_VIEWS_DIR . "history/main.php",
            'import-export' => WCCBEF_VIEWS_DIR . "import_export/main.php",
            'settings' => WCCBEF_VIEWS_DIR . "settings/main.php",
        ];
    }

    public function get_bulk_edit_form_tabs_title()
    {
        return [
            'general' => esc_html__("General", WBEBL_NAME),
            'usage_restriction' => esc_html__("Usage Restriction", WBEBL_NAME),
            'usage_limits' => esc_html__("Usage Limits", WBEBL_NAME),
            'custom_fields' => esc_html__("Custom Fields", WBEBL_NAME),
        ];
    }

    public function get_bulk_edit_form_tabs_content()
    {
        $custom_fields = [];
        $top_alert = [];
        if (!empty($this->meta_fields) && is_array($this->meta_fields)) {
            foreach ($this->meta_fields as $meta_field) {
                $field_id = 'wccbef-bulk-edit-form-coupon-' . $meta_field['key'];
                $custom_fields[$meta_field['key']][] = Generator::label_field(['for' => $field_id], $meta_field['title']);
                if (in_array($meta_field['main_type'], $this->meta_field_repository::get_fields_name_have_operator()) || ($meta_field['main_type'] == $this->meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $this->meta_field_repository::STRING_TYPE)) {
                    $class = ($meta_field['main_type'] == $this->meta_field_repository::CALENDAR) ? 'wccbef-datepicker' : '';
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
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $this->meta_field_repository::NUMBER) {
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
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::CHECKBOX) {
                    $custom_fields[$meta_field['key']][] = Generator::select_field([
                        'id' => $field_id,
                        'data-field' => 'value',
                    ], [
                        'yes' => esc_html_e('Yes', WBEBL_NAME),
                        'no' => esc_html_e('No', WBEBL_NAME),
                    ], true);
                } elseif (in_array($meta_field['main_type'], [$this->meta_field_repository::CALENDAR, $this->meta_field_repository::DATE])) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wccbef-input-md wccbef-datepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::DATE_TIME) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wccbef-input-md wccbef-datetimepicker',
                        'data-field' => 'value',
                        'data-field-type' => 'date',
                        'id' => $field_id,
                        'placeholder' => $meta_field['title'] . ' ...',
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::TIME) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wccbef-input-md wccbef-timepicker',
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
                    'class' => 'wccbef-alert wccbef-alert-warning',
                ]),
                Generator::span_field(esc_html__('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', WBEBL_NAME)),
                Generator::div_field_end()
            ];
        }

        return [
            'general' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'selected wccbef-tab-content-item',
                    'data-content' => 'general'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'post_title' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-title'], esc_html__('Title', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-bulk-edit-form-coupon-title-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wccbef-bulk-edit-form-coupon-title',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Title ...', WBEBL_NAME)
                        ]),
                    ],
                    'post_date' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-date'], esc_html__('Date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-datetimepicker',
                            'type' => 'text',
                            'id' => 'wccbef-bulk-edit-form-coupon-date',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Date ...', WBEBL_NAME)
                        ]),
                    ],
                    'post_excerpt' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-description'], esc_html__('Description', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-bulk-edit-form-coupon-description-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_text()),
                        Generator::textarea_field([
                            'id' => 'wccbef-bulk-edit-form-coupon-description',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Description ...', WBEBL_NAME)
                        ]),
                    ],
                    'post_status' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-status'], esc_html__('Status', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wccbef-input-md',
                            'id' => 'wccbef-bulk-edit-form-coupon-status',
                            'data-field' => 'value',
                            'title' => esc_html__('Select Status ...', WBEBL_NAME)
                        ], $this->coupon_statuses, true),
                    ],
                    'discount_type' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-discount-type'], esc_html__('Discount Type', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wccbef-input-md',
                            'id' => 'wccbef-bulk-edit-form-coupon-discount-type',
                            'data-field' => 'value',
                            'title' => esc_html__('Select Discount Type ...', WBEBL_NAME)
                        ], $this->discount_types, true),
                    ],
                    'coupon_amount' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-amount'], esc_html__('Coupon Amount', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-bulk-edit-form-coupon-amount-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_number()),
                        Generator::input_field([
                            'type' => 'number',
                            'id' => 'wccbef-bulk-edit-form-coupon-amount',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Coupon Amount ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['coupon_amount']) ? $this->field_titles['coupon_amount'] : '')
                    ],
                    'free_shipping' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-free-shipping'], esc_html__('Allow free shipping', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wccbef-input-md',
                            'id' => 'wccbef-bulk-edit-form-coupon-free-shipping',
                            'data-field' => 'value',
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true),
                    ],
                    'date_expires' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-expire-date'], esc_html__('Coupon expiry date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-datepicker',
                            'type' => 'text',
                            'id' => 'wccbef-bulk-edit-form-coupon-expire-date',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Coupon expiry date ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['date_expires']) ? $this->field_titles['date_expires'] : '')
                    ],
                ]
            ],
            'usage_restriction' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wccbef-tab-content-item',
                    'data-content' => 'usage_restriction'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'minimum_amount' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-minimum-amount'], esc_html__('Minimum spend', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-bulk-edit-form-coupon-minimum-amount-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_number()),
                        Generator::input_field([
                            'type' => 'number',
                            'id' => 'wccbef-bulk-edit-form-coupon-minimum-amount',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Minimum spend ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['minimum_amount']) ? $this->field_titles['minimum_amount'] : '')
                    ],
                    'maximum_amount' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-maximum-amount'], esc_html__('Maximum spend', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-bulk-edit-form-coupon-maximum-amount-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_number()),
                        Generator::input_field([
                            'type' => 'number',
                            'id' => 'wccbef-bulk-edit-form-coupon-maximum-amount',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Maximum spend ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['maximum_amount']) ? $this->field_titles['maximum_amount'] : '')
                    ],
                    'individual_use' => [
                        Generator::label_field([], esc_html__('Individual use only', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wccbef-input-md',
                            'disabled' => 'disabled',
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'exclude_sale_items' => [
                        Generator::label_field([], esc_html__('Exclude sale items', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wccbef-input-md',
                            'disabled' => 'disabled',
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'product_ids' => [
                        Generator::label_field([], esc_html__('Include products', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_taxonomy()),
                        Generator::select_field([
                            'class' => 'wccbef-select2-products',
                            'multiple' => 'multiple',
                            'disabled' => 'disabled',
                            'data-placeholder' => esc_html__('Include products ...', WBEBL_NAME)
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'exclude_product_ids' => [
                        Generator::label_field([], esc_html__('Exclude products', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_taxonomy()),
                        Generator::select_field([
                            'class' => 'wccbef-select2-products',
                            'multiple' => 'multiple',
                            'disabled' => 'disabled',
                            'data-placeholder' => esc_html__('Exclude products ...', WBEBL_NAME)
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'product_categories' => [
                        Generator::label_field([], esc_html__('Include categories', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_taxonomy()),
                        Generator::select_field([
                            'class' => 'wccbef-select2-categories',
                            'multiple' => 'multiple',
                            'disabled' => 'disabled',
                            'data-placeholder' => esc_html__('Include categories ...', WBEBL_NAME)
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'exclude_product_categories' => [
                        Generator::label_field([], esc_html__('Exclude categories', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::edit_taxonomy()),
                        Generator::select_field([
                            'class' => 'wccbef-select2-categories',
                            'multiple' => 'multiple',
                            'disabled' => 'disabled',
                            'data-placeholder' => esc_html__('Exclude categories ...', WBEBL_NAME)
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'customer_email' => [
                        Generator::label_field([], esc_html__('Allowed Emails', WBEBL_NAME)),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Allowed Emails ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                ]
            ],
            'usage_limits' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wccbef-tab-content-item',
                    'data-content' => 'usage_limits'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'usage_limit' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-usage-limit'], esc_html__('Usage limit per coupon', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-bulk-edit-form-coupon-usage-limit-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_number()),
                        Generator::input_field([
                            'type' => 'number',
                            'id' => 'wccbef-bulk-edit-form-coupon-usage-limit',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Usage limit per coupon ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['usage_limit']) ? $this->field_titles['usage_limit'] : '')
                    ],
                    'limit_usage_to_x_items' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-limit-usage-to-x-items'], esc_html__('Limit usage to x items', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-bulk-edit-form-coupon-limit-usage-to-x-items-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_number()),
                        Generator::input_field([
                            'type' => 'number',
                            'id' => 'wccbef-bulk-edit-form-coupon-limit-usage-to-x-items',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Limit usage to x items ...', WBEBL_NAME)
                        ]),
                    ],
                    'usage_limit_per_user' => [
                        Generator::label_field(['for' => 'wccbef-bulk-edit-form-coupon-usage-limit-per-user'], esc_html__('Usage limit per user', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-bulk-edit-form-coupon-usage-limit-per-user-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::edit_number()),
                        Generator::input_field([
                            'type' => 'number',
                            'id' => 'wccbef-bulk-edit-form-coupon-usage-limit-per-user',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Usage limit per user ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['usage_limit_per_user']) ? $this->field_titles['usage_limit_per_user'] : '')
                    ],
                ]
            ],
            'custom_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wccbef-tab-content-item',
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
            'filter_usage_restriction' => esc_html__("Usage Restriction", WBEBL_NAME),
            'filter_usage_limits' => esc_html__("Usage Limits", WBEBL_NAME),
            'filter_custom_fields' => esc_html__("Custom Fields", WBEBL_NAME),
        ];
    }

    public function get_filter_form_tabs_content()
    {
        $custom_fields = [];
        $top_alert = [];

        if (!empty($this->meta_fields) && is_array($this->meta_fields)) {
            foreach ($this->meta_fields as $meta_field) {
                $field_id = 'wccbef-bulk-edit-form-coupon-' . $meta_field['key'];
                $custom_fields[$meta_field['key']][] = Generator::label_field(['for' => $field_id], $meta_field['title']);
                if (in_array($meta_field['main_type'], $this->meta_field_repository::get_fields_name_have_operator()) || ($meta_field['main_type'] == $this->meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $this->meta_field_repository::STRING_TYPE)) {
                    $class = ($meta_field['main_type'] == $this->meta_field_repository::CALENDAR) ? 'wccbef-datepicker' : '';
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
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::TEXTINPUT && $meta_field['sub_type'] == $this->meta_field_repository::NUMBER) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'number',
                        'class' => 'wccbef-input-md',
                        'data-field' => 'from',
                        'data-field-type' => 'number',
                        'id' => $field_id . '-from',
                        'placeholder' => $meta_field['title'] . ' From ...',
                    ]);
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'number',
                        'class' => 'wccbef-input-md',
                        'data-field' => 'to',
                        'data-field-type' => 'number',
                        'id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' To ...',
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::CHECKBOX) {
                    $custom_fields[$meta_field['key']][] = Generator::select_field([
                        'id' => $field_id,
                        'data-field' => 'value',
                    ], [
                        'yes' => esc_html_e('Yes', WBEBL_NAME),
                        'no' => esc_html_e('No', WBEBL_NAME),
                    ], true);
                } elseif (in_array($meta_field['main_type'], [$this->meta_field_repository::CALENDAR, $this->meta_field_repository::DATE])) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wccbef-input-md wccbef-datepicker',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' From ...',
                    ]);
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wccbef-input-md wccbef-datepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' To ...',
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::DATE_TIME) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wccbef-input-md wccbef-datetimepicker',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' From ...',
                    ]);
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wccbef-input-md wccbef-datetimepicker',
                        'data-field' => 'to',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' To ...',
                    ]);
                } elseif ($meta_field['main_type'] == $this->meta_field_repository::TIME) {
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wccbef-input-md wccbef-timepicker',
                        'data-field' => 'from',
                        'data-field-type' => 'date',
                        'id' => $field_id . '-from',
                        'data-to-id' => $field_id . '-to',
                        'placeholder' => $meta_field['title'] . ' From ...',
                    ]);
                    $custom_fields[$meta_field['key']][] = Generator::input_field([
                        'type' => 'text',
                        'class' => 'wccbef-input-md wccbef-timepicker',
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
                    'class' => 'wccbef-alert wccbef-alert-warning',
                ]),
                Generator::span_field(esc_html__('There is not any added Meta Fields, You can add new Meta Fields trough "Meta Fields" tab.', WBEBL_NAME)),
                Generator::div_field_end()
            ];
        }

        return [
            'filter_general' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'selected wccbef-tab-content-item',
                    'data-content' => 'filter_general'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'coupon_ids' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-ids'], esc_html__('Coupon ID(s)', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-filter-form-coupon-ids-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], [
                            'exact' => esc_html__('Exact', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wccbef-filter-form-coupon-ids',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('for example: 1,2,3 or 1-10 or 1,2,3|10-20', WBEBL_NAME)
                        ]),
                    ],
                    'post_title' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-title'], esc_html__('Title', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-filter-form-coupon-title-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'id' => 'wccbef-filter-form-coupon-title',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Title ...', WBEBL_NAME)
                        ]),
                    ],
                    'post_excerpt' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-description'], esc_html__('Description', WBEBL_NAME)),
                        Generator::select_field([
                            'id' => 'wccbef-filter-form-coupon-description-operator',
                            'data-field' => 'operator',
                            'title' => esc_html__('Select Operator', WBEBL_NAME)
                        ], Operator::filter_text()),
                        Generator::textarea_field([
                            'id' => 'wccbef-filter-form-coupon-description',
                            'data-field' => 'value',
                            'placeholder' => esc_html__('Description ...', WBEBL_NAME)
                        ]),
                    ],
                    'post_date' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-date-from'], esc_html__('Date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-datetimepicker wccbef-input-ft wccbef-date-from',
                            'data-to-id' => 'wccbef-filter-form-coupon-date-to',
                            'type' => 'text',
                            'id' => 'wccbef-filter-form-coupon-date-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Date From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wccbef-datetimepicker wccbef-input-ft',
                            'type' => 'text',
                            'id' => 'wccbef-filter-form-coupon-date-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Date To ...', WBEBL_NAME)
                        ]),
                    ],
                    'post_modified' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-modified-date-from'], esc_html__('Modified Date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-datetimepicker wccbef-input-ft wccbef-date-from',
                            'data-to-id' => 'wccbef-filter-form-coupon-modified-date-to',
                            'type' => 'text',
                            'id' => 'wccbef-filter-form-coupon-modified-date-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Modified Date From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wccbef-datetimepicker wccbef-input-ft',
                            'type' => 'text',
                            'id' => 'wccbef-filter-form-coupon-modified-date-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Modified Date To ...', WBEBL_NAME)
                        ]),
                    ],
                    'post_status' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-status'], esc_html__('Status', WBEBL_NAME)),
                        Generator::select_field([
                            'multiple' => 'true',
                            'class' => 'wccbef-input-md wccbef-select2',
                            'id' => 'wccbef-filter-form-coupon-status',
                            'data-field' => 'value',
                            'title' => esc_html__('Select Status ...', WBEBL_NAME)
                        ], $this->coupon_statuses, false)
                    ],
                    'discount_type' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-discount-type'], esc_html__('Discount type', WBEBL_NAME)),
                        Generator::select_field([
                            'multiple' => 'true',
                            'class' => 'wccbef-input-md wccbef-select2',
                            'id' => 'wccbef-filter-form-coupon-discount-type',
                            'data-field' => 'value',
                            'title' => esc_html__('Discount type ...', WBEBL_NAME)
                        ], $this->discount_types, false)
                    ],
                    'coupon_amount' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-amount-from'], esc_html__('Coupon Amount', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'data-to-id' => 'wccbef-filter-form-coupon-amount-to',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-amount-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Amount From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-amount-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Amount To ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['coupon_amount']) ? $this->field_titles['coupon_amount'] : '')
                    ],
                    'free_shipping' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-free-shipping'], esc_html__('Allow free shipping', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wccbef-input-md',
                            'id' => 'wccbef-filter-form-coupon-free-shipping',
                            'data-field' => 'value',
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true),
                    ],
                    'date_expires' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-expiry-date-from'], esc_html__('Expiry date', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-datepicker wccbef-input-ft wccbef-date-from',
                            'data-to-id' => 'wccbef-filter-form-coupon-expiry-date-to',
                            'type' => 'text',
                            'id' => 'wccbef-filter-form-coupon-expiry-date-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Date From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wccbef-datepicker wccbef-input-ft',
                            'type' => 'text',
                            'id' => 'wccbef-filter-form-coupon-expiry-date-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Date To ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['date_expires']) ? $this->field_titles['date_expires'] : '')
                    ],
                ]
            ],
            'filter_usage_restriction' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wccbef-tab-content-item',
                    'data-content' => 'filter_usage_restriction'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'minimum_amount' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-minimum-amount'], esc_html__('Minimum spend', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'data-to-id' => 'wccbef-filter-form-coupon-minimum-amount-to',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-minimum-amount-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Amount From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-minimum-amount-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Amount To ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['minimum_amount']) ? $this->field_titles['minimum_amount'] : '')
                    ],
                    'maximum_amount' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-maximum-amount'], esc_html__('Maximum spend', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'data-to-id' => 'wccbef-filter-form-coupon-maximum-amount-to',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-maximum-amount-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('Amount From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-maximum-amount-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('Amount To ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['maximum_amount']) ? $this->field_titles['maximum_amount'] : '')
                    ],
                    'individual_use' => [
                        Generator::label_field([], esc_html__('Individual use only', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wccbef-input-md',
                            'disabled' => 'disabled',
                        ], [], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'exclude_sale_items' => [
                        Generator::label_field([], esc_html__('Exclude sale items', WBEBL_NAME)),
                        Generator::select_field([
                            'class' => 'wccbef-input-md',
                            'disabled' => 'disabled',
                        ], [
                            'yes' => esc_html__('Yes', WBEBL_NAME),
                            'no' => esc_html__('No', WBEBL_NAME),
                        ], true),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'product_ids' => [
                        Generator::label_field([], esc_html__('Include products', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'class' => 'wccbef-select2-products',
                            'multiple' => 'multiple',
                            'disabled' => 'disabled',
                            'data-placeholder' => esc_html__('Include products ...', WBEBL_NAME)
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'exclude_product_ids' => [
                        Generator::label_field([], esc_html__('Exclude products', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'class' => 'wccbef-select2-products',
                            'multiple' => 'multiple',
                            'disabled' => 'disabled',
                            'data-placeholder' => esc_html__('Exclude products ...', WBEBL_NAME)
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'product_categories' => [
                        Generator::label_field([], esc_html__('Include categories', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'class' => 'wccbef-select2-categories',
                            'multiple' => 'multiple',
                            'disabled' => 'disabled',
                            'data-placeholder' => esc_html__('Include categories ...', WBEBL_NAME)
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'exclude_product_categories' => [
                        Generator::label_field([], esc_html__('Exclude categories', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_multi_select()),
                        Generator::select_field([
                            'class' => 'wccbef-select2-categories',
                            'multiple' => 'multiple',
                            'disabled' => 'disabled',
                            'data-placeholder' => esc_html__('Exclude categories ...', WBEBL_NAME)
                        ], []),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                    'customer_email' => [
                        Generator::label_field([], esc_html__('Allowed Emails', WBEBL_NAME)),
                        Generator::select_field([
                            'disabled' => 'disabled',
                        ], Operator::filter_text()),
                        Generator::input_field([
                            'type' => 'text',
                            'disabled' => 'disabled',
                            'placeholder' => esc_html__('Allowed Emails ...', WBEBL_NAME)
                        ]),
                        Generator::span_field(esc_html__("Upgrade to pro version", WBEBL_NAME), [
                            'class' => 'wccbef-short-description'
                        ])
                    ],
                ]
            ],
            'filter_usage_limits' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wccbef-tab-content-item',
                    'data-content' => 'filter_usage_limits'
                ]),
                'wrapper_end' => Generator::div_field_end(),
                'fields' => [
                    'usage_limit' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-usage-limit-from'], esc_html__('Usage limit per coupon', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'data-to-id' => 'wccbef-filter-form-coupon-usage-limit-to',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-usage-limit-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-usage-limit-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('To ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['usage_limit']) ? $this->field_titles['usage_limit'] : '')
                    ],
                    'limit_usage_to_x_items' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-limit-usage-to-x-items-from'], esc_html__('Limit usage to x items', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'data-to-id' => 'wccbef-filter-form-coupon-limit-usage-to-x-items-to',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-limit-usage-to-x-items-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-limit-usage-to-x-items-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('To ...', WBEBL_NAME)
                        ]),
                    ],
                    'usage_limit_per_user' => [
                        Generator::label_field(['for' => 'wccbef-filter-form-coupon-usage-limit-per-user-from'], esc_html__('Usage limit per user', WBEBL_NAME)),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'data-to-id' => 'wccbef-filter-form-coupon-usage-limit-per-user-to',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-usage-limit-per-user-from',
                            'data-field' => 'from',
                            'placeholder' => esc_html__('From ...', WBEBL_NAME)
                        ]),
                        Generator::input_field([
                            'class' => 'wccbef-input-ft',
                            'type' => 'number',
                            'id' => 'wccbef-filter-form-coupon-usage-limit-per-user-to',
                            'data-field' => 'to',
                            'placeholder' => esc_html__('To ...', WBEBL_NAME)
                        ]),
                        Generator::help_icon(isset($this->field_titles['usage_limit_per_user']) ? $this->field_titles['usage_limit_per_user'] : '')
                    ],
                ]
            ],
            'filter_custom_fields' => [
                'wrapper_start' => Generator::div_field_start([
                    'class' => 'wccbef-tab-content-item',
                    'data-content' => 'filter_custom_fields'
                ]),
                'fields_top' => $top_alert,
                'wrapper_end' => Generator::div_field_end(),
                'fields' => $custom_fields
            ],
        ];
    }
}
