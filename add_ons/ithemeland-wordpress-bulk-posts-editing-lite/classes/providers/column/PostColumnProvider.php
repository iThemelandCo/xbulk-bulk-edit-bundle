<?php

namespace wpbel\classes\providers\column;

use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Setting;

class PostColumnProvider
{
    private static $instance;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    public function get_item_columns($item, $columns)
    {
        return $this->item_columns($item, $columns);
    }

    protected function item_columns($item, $columns)
    {
        if ($item instanceof \WP_Post) {
            $post_repository = new Post();
            $setting_repository = new Setting();
            $sticky_first_columns = $setting_repository->get_sticky_first_columns();
            $post_object = $item;
            $post = $post_repository->get_post_fields($post_object);
            $output = '<tr data-item-id="' . esc_attr($post['id']) . '" data-item-type="' . esc_attr($post['post_type']) . '">';
            if (Column::SHOW_ID_COLUMN === true) {
                $sticky_class = ($sticky_first_columns == 'yes') ? 'wpbel-td-sticky wpbel-td-sticky-id wpbel-gray-bg' : '';
                $output .= '<td data-item-id="' . esc_attr($post['id']) . '" data-item-title="' . esc_attr($post['post_title']) . '" data-col-title="ID" class="' . esc_attr($sticky_class) . '">';
                $output .= '<label class="wpbel-td70">';
                $output .= '<input type="checkbox" class="wpbel-check-item" data-item-type="' . esc_attr($post['post_type']) . '" value="' . esc_attr($post['id']) . '" title="Select Item">';
                $output .= intval($post['id']);
                $output .= '<a href="' . esc_url(get_the_permalink($post['id'])) . '" target="_blank" title="View on site" class="wpbel-item-view-icon wpbel-ml5 wpbel-float-right"><span class="lni lni-eye"></span></a>';
                $output .= '<a href="' . admin_url("post.php?post=" . intval($post['id']) . "&action=edit") . '" target="_blank" class="wpbel-ml5 wpbel-float-right" title="Edit Post"><span class="lni lni-pencil-alt"></span></a>';
                $output .= "</label>";
                $output .= "</td>";
            }
            if (!empty(Column::get_static_columns())) {
                foreach (Column::get_static_columns() as $static_column) {
                    $sticky_class = ($sticky_first_columns == 'yes') ? 'wpbel-td-sticky wpbel-td-sticky-title wpbel-gray-bg' : '';
                    $output .= '<td class="' . esc_attr($sticky_class) . '" data-item-id="' . esc_attr($post['id']) . '" data-item-title="' . esc_attr($post[$static_column['field']]) . '" data-col-title="' . esc_attr($static_column['title']) . '" data-field="' . esc_attr($static_column['field']) . '" data-field-type="" data-content-type="text" data-action="inline-editable">';
                    $output .= '<span data-action="inline-editable" class="wpbel-td160">' . esc_attr($post[$static_column['field']]) . '</span>';
                    $output .= '</td>';
                }
            }
            if (!empty($columns) && is_array($columns)) {
                foreach ($columns as $key => $column) {
                    $includes = [];
                    $key_decoded = $key;
                    $field_type = '';
                    $key = urlencode($key);
                    if (isset($column['field_type'])) {
                        switch ($column['field_type']) {
                            case 'custom_field':
                                $field_type = 'custom_field';
                                $post[$key] = (isset($post['custom_field'][$key])) ? $post['custom_field'][$key][0] : '';
                                break;
                            default:
                                break;
                        }
                    }
                    $background_color = (!empty($column['background_color']) && $column['background_color'] != '#fff' && $column['background_color'] != '#ffffff') ? 'background:' . esc_attr($column['background_color']) . ';' : '';
                    $text_color = (!empty($column['text_color'])) ? 'color:' . esc_attr($column['text_color']) . ';' : '';
                    $output .= '<td data-item-id="' . esc_attr($post['id']) . '" data-item-title="' . esc_attr($post['post_title']) . '" data-col-title="' . esc_attr($column['title']) . '" data-field="' . esc_attr($key_decoded) . '" data-field-type="' . esc_attr($field_type) . '" style="' . esc_attr($background_color) . ' ' . esc_attr($text_color) . '"';
                    if ($column['editable'] === true && !in_array($column['content_type'], ['multi_select', 'multi_select_attribute'])) {
                        $output .= 'data-content-type="' . esc_attr($column['content_type']) . '" data-action="inline-editable"';
                    }
                    $output .= '>';

                    if ($column['editable'] === true) {
                        switch ($column['content_type']) {
                            case 'text':
                                $value = (is_array($post[$key])) ? implode(',', $post[$key]) : $post[$key];
                                $output .= "<span data-action='inline-editable' class='wpbel-td160'>" . esc_html($value) . "</span>";
                                break;
                            case 'textarea':
                                $output .= "<button type='button' data-toggle='modal' data-target='#wpbel-modal-text-editor' class='wpbel-button wpbel-button-white wpbel-load-text-editor wpbel-td160' data-item-id='" . esc_attr($post['id']) . "' data-item-name='" . esc_attr($post['post_title']) . "' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "'>Content</button>";
                                break;
                            case 'image':
                                $image = !empty($post[$key]['small']) ? sprintf('%s', $post[$key]['small']) : '<img src="' . esc_url(WPBEL_IMAGES_URL . "no-image-small.png") . '" width="40" height="40">';
                                $output .= "<span data-toggle='modal' data-target='#wpbel-modal-" . esc_attr($key_decoded) . "-" . esc_attr($post['id']) . "' data-id='wpbel-image-" . esc_attr($post['id']) . "' class='wpbel-image-inline-edit'>{$image}</span>";
                                $includes[] = WPBEL_VIEWS_DIR . 'bulk_edit/columns_modals/thumbnail.php';
                                break;
                            case 'numeric':
                                $output .= "<span data-action='inline-editable' class='wpbel-numeric-content wpbel-td120'>" . esc_html($post[$key]) . "</span><button type='button' data-toggle='modal' class='wpbel-calculator' data-field='" . esc_attr($key_decoded) . "' data-item-id='" . esc_attr($post['id']) . "' data-item-name='" . esc_attr($post['post_title']) . "' data-field-type='" . esc_attr($field_type) . "' data-target='#wpbel-modal-numeric-calculator'></button>";
                                break;
                            case 'numeric_without_calculator':
                                $output .= "<span data-action='inline-editable' class='wpbel-numeric-content wpbel-td120'>" . esc_html($post[$key]) . "</span>";
                                break;
                            case 'checkbox':
                                $checked = ($post[$key]) ? 'checked="checked"' : '';
                                $label = ($post[$key]) ? 'Yes' : 'No';
                                $output .= "<label><input type='checkbox' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "' data-item-id='" . esc_attr($post['id']) . "' value='yes' class='wpbel-dual-mode-checkbox wpbel-inline-edit-action' " . esc_attr($checked) . "><span>" . esc_html($label) . "</span></label>";
                                break;
                            case 'select':
                                $output .= "<select class='wpbel-inline-edit-action' data-field='" . esc_attr($key_decoded) . "' data-item-id='" . esc_attr($post['id']) . "' title='Select " . esc_attr($column['label']) . "'>";
                                if (!empty($column['options'])) {
                                    foreach ($column['options'] as $option_key => $option_value) {
                                        $selected = ($option_key == $post[$key]) ? 'selected' : '';
                                        $output .= "<option value='{$option_key}' $selected>{$option_value}</option>";
                                    }
                                }
                                $output .= '</select>';
                                break;
                            case 'select_post':
                                $output .= "<button type='button' data-toggle='modal' data-target='#wpbel-modal-select-post' class='wpbel-button wpbel-button-white' data-parent-id='" . esc_attr($post['post_parent']) . "' data-item-id='" . esc_attr($post['id']) . "' data-item-name='" . esc_attr($post['post_title']) . "' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "'>Select</button>";
                                $all_posts = $post_repository->get_posts([
                                    'post_type' => $GLOBALS['wpbel_common']['active_post_type']
                                ]);
                                include_once WPBEL_VIEWS_DIR . "bulk_edit/columns_modals/select_post.php";
                                break;
                            case 'date':
                                $date = (!empty($post[$key])) ? date('Y/m/d', strtotime($post[$key])) : '';
                                $clear_button = ($key != 'post_date') ? "<button type='button' class='wpbel-clear-date-btn wpbel-inline-edit-clear-date' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "' data-item-id='" . esc_attr($post['id']) . "' value=''><img src='" . esc_url(WPBEL_IMAGES_URL . 'calendar_clear.svg') . "' alt='Clear' title='Clear Date'></button>" : '';
                                $output .= "<input type='text' class='wpbel-datepicker wpbel-inline-edit-action' data-field='" . esc_attr($key_decoded) . "' data-field-type='" . esc_attr($field_type) . "' data-item-id='" . esc_attr($post['id']) . "' title='Select " . esc_attr($column['label']) . "' value='" . esc_attr($date) . "'>" . sprintf('%s', $clear_button);
                                break;
                            case 'multi_select':
                                $values = get_the_term_list($post['id'], $key_decoded, '<span class="wpbel-category-item">', ', </span><span class="wpbel-category-item">', '</span>');
                                $output .= "<span data-toggle='modal' class='wpbel-is-taxonomy-modal wpbel-post-taxonomy' data-target='#wpbel-modal-taxonomy-" . esc_attr($key_decoded) . "-" . esc_attr($post['id']) . "' data-item-id='" . esc_attr($post['id']) . "'>";
                                $output .= (!empty($values)) ? strip_tags(sprintf('%s', $values), '<span>') : 'No items';
                                $output .= "</span>";
                                $includes[] = WPBEL_VIEWS_DIR . 'bulk_edit/columns_modals/post_taxonomy.php';
                                break;
                            default:
                                $value = (is_array($post[$key])) ? implode(',', $post[$key]) : $post[$key];
                                $output .= $value;
                                break;
                        }
                    } else {
                        if (!empty($post[$key])) {
                            $output .= (is_array($post[$key])) ? esc_html(implode(',', $post[$key])) : esc_html($post[$key]);
                        } else {
                            $output .= ' ';
                        }
                    }

                    $output .= '</td>';
                    if (!empty($includes)) {
                        foreach ($includes as $include) {
                            include $include;
                        }
                    }
                }
            }
            $output .= "</tr>";
            return $output;
        }
    }
}
