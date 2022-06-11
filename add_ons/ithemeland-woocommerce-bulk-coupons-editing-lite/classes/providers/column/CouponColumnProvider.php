<?php

namespace wccbef\classes\providers\column;

use wccbef\classes\repositories\Column;
use wccbef\classes\repositories\Coupon;
use wccbef\classes\repositories\Setting;

class CouponColumnProvider
{
    private static $instance;
    private $sticky_first_columns;
    private $coupon_repository;
    private $coupon;
    private $coupon_object;
    private $column_key;
    private $decoded_column_key;
    private $column_data;
    private $field_type;
    private $settings;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->coupon_repository = new Coupon();
        $setting_repository = new Setting();
        $this->settings = $setting_repository->get_settings();
        $this->sticky_first_columns = isset($this->settings['sticky_first_columns']) ? $this->settings['sticky_first_columns'] : 'yes';

        $this->field_type = "";

        $this->fields_method = $this->get_fields_method();
    }

    public function get_item_columns($item, $columns)
    {
        if ($item instanceof \WC_Coupon) {
            $this->coupon_object = $item;
            $this->coupon = $this->coupon_repository->coupon_to_array($item);
            $output = '<tr data-item-id="' . esc_attr($this->coupon['id']) . '">';
            $output .= $this->get_static_columns();
            if (!empty($columns) && is_array($columns)) {
                foreach ($columns as $column_key => $column_data) {
                    $this->column_key = $column_key;
                    $this->column_data = $column_data;
                    $this->decoded_column_key = urlencode($this->column_key);
                    $field_data = $this->get_field();
                    $output .= (!empty($field_data['field'])) ? $field_data['field'] : '';
                    if (isset($field_data['includes']) && is_array($field_data['includes'])) {
                        foreach ($field_data['includes'] as $include) {
                            if (file_exists($include)) {
                                include $include;
                            }
                        }
                    }
                }
            }
            $output .= "</tr>";
            return $output;
        }
    }

    private function get_field()
    {
        $output['field'] = '';
        $output['includes'] = [];
        $this->field_type = '';

        $this->set_coupon_field();
        $color = $this->get_column_colors_style();

        $editable = ($this->column_data['editable']) ? 'yes' : 'no';
        $output['field'] .= '<td data-item-id="' . esc_attr($this->coupon['id']) . '" data-editable="' . $editable . '" data-item-title="#' . esc_attr($this->coupon['id']) . '" data-col-title="' . esc_attr($this->column_data['title']) . '" data-field="' . esc_attr($this->column_key) . '" data-field-type="' . esc_attr($this->field_type) . '" style="' . esc_attr($color['background']) . ' ' . esc_attr($color['text']) . '"';
        if ($this->column_data['editable'] === true && !in_array($this->column_data['content_type'], ['multi_select', 'multi_select_attribute'])) {
            $output['field'] .= 'data-content-type="' . esc_attr($this->column_data['content_type']) . '" data-action="inline-editable"';
        }
        $output['field'] .= '>';

        if ($this->column_data['editable'] === true) {
            $generated = $this->generate_field();
            if (is_array($generated) && isset($generated['field']) && isset($generated['includes'])) {
                $output['field'] .= $generated['field'];
                $output['includes'][] = $generated['includes'];
            } else {
                $output['field'] .= $generated;
            }
        } else {
            if (isset($this->coupon[$this->decoded_column_key])) {
                $output['field'] .= (is_array($this->coupon[$this->decoded_column_key])) ? sprintf('%s', implode(',', $this->coupon[$this->decoded_column_key])) : sprintf('%s', $this->coupon[$this->decoded_column_key]);
            } else {
                $output['field'] .= ' ';
            }
        }

        $output['field'] .= '</td>';
        return $output;
    }

    private function get_id_column()
    {
        $output = '';
        if (Column::SHOW_ID_COLUMN === true) {
            $sticky_class = ($this->sticky_first_columns == 'yes') ? 'wccbef-td-sticky wccbef-td-sticky-id wccbef-gray-bg' : '';
            $output .= '<td data-item-id="' . esc_attr($this->coupon['id']) . '" data-item-title="#' . esc_attr($this->coupon['id']) . '" data-col-title="ID" class="' . esc_attr($sticky_class) . '">';
            $output .= '<label class="wccbef-td70">';
            $output .= '<input type="checkbox" class="wccbef-check-item" value="' . esc_attr($this->coupon['id']) . '" title="Select Coupon">';
            $output .= esc_html($this->coupon['id']);
            $output .= '<a href="' . admin_url("post.php?post=" . intval($this->coupon['id']) . "&action=edit") . '" target="_blank" class="wccbef-ml5 wccbef-float-right" title="Edit Coupon"><span class="lni lni-pencil-alt"></span></a>';
            $output .= "</label>";
            $output .= "</td>";
        }
        return $output;
    }

    private function get_static_columns()
    {
        $output = '';
        $output .= $this->get_id_column();
        if (!empty(Column::get_static_columns())) {
            foreach (Column::get_static_columns() as $static_column) {
                $sticky_class = ($this->sticky_first_columns == 'yes') ? 'wccbef-td-sticky wccbef-td-sticky-title wccbef-gray-bg' : '';
                $output .= '<td class="' . esc_attr($sticky_class) . '" data-item-id="' . esc_attr($this->coupon['id']) . '" data-item-title="' . esc_attr($this->coupon[$static_column['field']]) . '" data-col-title="' . esc_attr($static_column['title']) . '" data-field="' . esc_attr($static_column['field']) . '" data-field-type="" data-content-type="text" data-action="inline-editable">';
                $output .= '<span data-action="inline-editable" class="wccbef-td160">' . esc_html($this->coupon[$static_column['field']]) . '</span>';
                $output .= '</td>';
            }
        }
        return $output;
    }

    private function set_coupon_field()
    {
        if (isset($this->column_data['field_type'])) {
            switch ($this->column_data['field_type']) {
                case 'custom_field':
                    $this->field_type = 'custom_field';
                    $this->coupon[$this->decoded_column_key] = (isset($this->coupon['custom_field'][$this->decoded_column_key])) ? $this->coupon['custom_field'][$this->decoded_column_key][0] : '';
                    break;
                default:
                    break;
            }
        }
    }

    private function get_column_colors_style()
    {
        $color['background'] = (!empty($this->column_data['background_color']) && $this->column_data['background_color'] != '#fff' && $this->column_data['background_color'] != '#ffffff') ? 'background:' . esc_attr($this->column_data['background_color']) . ';' : '';
        $color['text'] = (!empty($this->column_data['text_color'])) ? 'color:' . esc_attr($this->column_data['text_color']) . ';' : '';
        return $color;
    }

    private function generate_field()
    {
        if (isset($this->fields_method[$this->column_data['content_type']]) && method_exists($this, $this->fields_method[$this->column_data['content_type']])) {
            return $this->{$this->fields_method[$this->column_data['content_type']]}();
        } else {
            return (is_array($this->coupon[$this->decoded_column_key])) ? implode(',', $this->coupon[$this->decoded_column_key]) : $this->coupon[$this->decoded_column_key];
        }
    }

    private function get_fields_method()
    {
        return [
            'text' => 'text_field',
            'email' => 'text_field',
            'textarea' => 'textarea_field',
            'image' => 'image_field',
            'numeric' => 'numeric_with_calculator_field',
            'numeric_without_calculator' => 'numeric_field',
            'checkbox_dual_mode' => 'checkbox_dual_model_field',
            'checkbox' => 'checkbox_field',
            'radio' => 'radio_field',
            'file' => 'file_field',
            'select' => 'select_field',
            'date' => 'date_field',
            'date_picker' => 'date_field',
            'date_time_picker' => 'datetime_field',
            'time_picker' => 'time_field',
            'used_by' => 'used_by_field',
            'used_in' => 'used_in_field',
            'products' => 'products_field',
            'product_categories' => 'product_categories_field',
        ];
    }

    private function text_field()
    {
        $value = (is_array($this->coupon[$this->decoded_column_key])) ? implode(',', $this->coupon[$this->decoded_column_key]) : $this->coupon[$this->decoded_column_key];
        $output = "<span data-action='inline-editable' class='wccbef-td160'>" . sprintf('%s', $value) . "</span>";
        return $output;
    }

    private function textarea_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wccbef-modal-text-editor' class='wccbef-button wccbef-button-white wccbef-load-text-editor wccbef-td160' data-item-id='" . esc_attr($this->coupon['id']) . "' data-item-name='" . esc_attr($this->coupon['post_title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>Content</button>";
    }

    private function image_field()
    {
        if (isset($this->coupon[$this->decoded_column_key]['small'])) {
            $image_id = intval($this->coupon[$this->decoded_column_key]['id']);
            $image = sprintf('%s', $this->coupon[$this->decoded_column_key]['small']);
            $full_size = wp_get_attachment_image_src($image_id, 'full');
        }
        if (isset($this->coupon[$this->decoded_column_key]) && is_numeric($this->coupon[$this->decoded_column_key])) {
            $image_id = intval($this->coupon[$this->decoded_column_key]);
            $image_url = wp_get_attachment_image_src($image_id, [40, 40]);
            $full_size = wp_get_attachment_image_src($image_id, 'full');
            $image = (!empty($image_url[0])) ? "<img src='" . esc_url($image_url[0]) . "' alt='' width='40' height='40' />" : null;
        }
        $image = (!empty($image)) ? $image : esc_html__('No Image', WBEBL_NAME);
        $full_size = (!empty($full_size[0])) ? $full_size[0] : esc_url(wp_upload_dir()['baseurl'] . "/woocommerce-placeholder.png");
        $image_id = (!empty($image_id)) ? $image_id : 0;

        return "<span data-toggle='modal' data-target='#wccbef-modal-image' data-id='wccbef-" . esc_attr($this->column_key) . "-" . esc_attr($this->coupon['id']) . "' class='wccbef-image-inline-edit' data-full-image-src='" . esc_url($full_size) . "' data-image-id='" . esc_attr($image_id) . "'>" . $image . "</span>";
    }

    private function numeric_with_calculator_field()
    {
        return "<span data-action='inline-editable' class='wccbef-numeric-content wccbef-td120'>" . esc_attr($this->coupon[$this->decoded_column_key]) . "</span><button type='button' data-toggle='modal' class='wccbef-calculator' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->coupon['id']) . "' data-item-name='" . esc_attr($this->coupon['post_title']) . "' data-field-type='" . esc_attr($this->field_type) . "' data-target='#wccbef-modal-numeric-calculator'></button>";
    }

    private function numeric_field()
    {
        return "<span data-action='inline-editable' class='wccbef-numeric-content wccbef-td120'>" . esc_html($this->coupon[$this->decoded_column_key]) . "</span>";
    }

    private function checkbox_dual_model_field()
    {
        $checked = ($this->coupon[$this->decoded_column_key] == 'yes' || $this->coupon[$this->decoded_column_key] == 1) ? 'checked="checked"' : "";
        return "<label><input type='checkbox' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->coupon['id']) . "' value='yes' class='wccbef-dual-mode-checkbox wccbef-inline-edit-action' " . esc_attr($checked) . "><span>" . esc_html__('Yes', WBEBL_NAME) . "</span></label>";
    }

    private function file_field()
    {
        $file_id = (isset($this->coupon[$this->decoded_column_key])) ? intval($this->coupon[$this->decoded_column_key]) : null;
        $file_url = wp_get_attachment_url($file_id);
        $file_url = !empty($file_url) ? esc_url($file_url) : '';
        return "<button type='button' data-toggle='modal' data-target='#wccbef-modal-file' class='wccbef-button wccbef-button-white' data-item-id='" . esc_attr($this->coupon['id']) . "' data-item-name='" . esc_attr($this->coupon['post_title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-file-id='" . $file_id . "' data-file-url='" . $file_url . "'>Select File</button>";
    }

    private function select_field()
    {
        $output = "";
        if (!empty($this->column_data['options'])) {
            $output .= "<select class='wccbef-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-item-id='" . esc_attr($this->coupon['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' data-field-type='" . esc_attr($this->field_type) . "'>";
            foreach ($this->column_data['options'] as $option_key => $option_value) {
                $selected = ($option_key == $this->coupon[$this->decoded_column_key]) ? 'selected' : '';
                $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
            }
            $output .= '</select>';
        }

        return $output;
    }

    private function date_field()
    {
        $date = (!empty($this->coupon[$this->decoded_column_key])) ? date('Y/m/d', strtotime($this->coupon[$this->decoded_column_key])) : '';
        $clear_button = ($this->decoded_column_key != 'post_date') ? "<button type='button' class='wccbef-clear-date-btn wccbef-inline-edit-clear-date' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->coupon['id']) . "' value=''><img src='" . esc_url(WCCBEF_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>" : '';
        return "<input type='text' class='wccbef-datepicker wccbef-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->coupon['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($date) . "'>" . sprintf('%s', $clear_button);
    }

    private function datetime_field()
    {
        $date = (!empty($this->coupon[$this->decoded_column_key])) ? date('Y/m/d H:i', strtotime($this->coupon[$this->decoded_column_key])) : '';
        $clear_button = ($this->decoded_column_key != 'post_date') ? "<button type='button' class='wccbef-clear-date-btn wccbef-inline-edit-clear-date' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->coupon['id']) . "' value=''><img src='" . esc_url(WCCBEF_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>" : '';
        return "<input type='text' class='wccbef-datetimepicker wccbef-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->coupon['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($date) . "'>" . sprintf('%s', $clear_button);
    }

    private function time_field()
    {
        $date = (!empty($this->coupon[$this->decoded_column_key])) ? date('H:i', strtotime($this->coupon[$this->decoded_column_key])) : '';
        $clear_button = "<button type='button' class='wccbef-clear-date-btn wccbef-inline-edit-clear-date' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->coupon['id']) . "' value=''><img src='" . esc_url(WCCBEF_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>";
        return "<input type='text' class='wccbef-timepicker wccbef-inline-edit-action' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "' data-item-id='" . esc_attr($this->coupon['id']) . "' title='Select " . esc_attr($this->column_data['label']) . "' value='" . esc_attr($date) . "'>" . sprintf('%s', $clear_button);
    }

    private function products_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wccbef-modal-products' class='wccbef-button wccbef-button-white wccbef-td160 wccbef-coupon-products-button' data-item-id='" . esc_attr($this->coupon['id']) . "' data-item-name='" . esc_attr($this->coupon['post_title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . esc_html__("Products", WBEBL_NAME) . "</button>";
    }

    private function product_categories_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wccbef-modal-categories' class='wccbef-button wccbef-button-white wccbef-td160 wccbef-coupon-categories-button' data-item-id='" . esc_attr($this->coupon['id']) . "' data-item-name='" . esc_attr($this->coupon['post_title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . esc_html__("Categories", WBEBL_NAME) . "</button>";
    }

    private function used_by_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wccbef-modal-used-by' class='wccbef-button wccbef-button-white wccbef-td160 wccbef-coupon-used-by-button' data-item-id='" . esc_attr($this->coupon['id']) . "' data-item-name='" . esc_attr($this->coupon['post_title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . esc_html__("Used by", WBEBL_NAME) . "</button>";
    }

    private function used_in_field()
    {
        return "<button type='button' data-toggle='modal' data-target='#wccbef-modal-used-in' class='wccbef-button wccbef-button-white wccbef-td160 wccbef-coupon-used-in-button' data-item-id='" . esc_attr($this->coupon['id']) . "' data-item-name='" . esc_attr($this->coupon['post_title']) . "' data-field='" . esc_attr($this->column_key) . "' data-field-type='" . esc_attr($this->field_type) . "'>" . esc_html__("Used in", WBEBL_NAME) . "</button>";
    }
}
