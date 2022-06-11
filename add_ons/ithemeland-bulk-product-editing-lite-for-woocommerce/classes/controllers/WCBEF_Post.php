<?php

namespace wcbef\classes\controllers;

use wcbef\classes\helpers\Sanitizer;
use wcbef\classes\helpers\Session;
use wcbef\classes\repositories\Column;
use wcbef\classes\repositories\Product;
use wcbef\classes\repositories\Setting;

class WCBEF_Post
{
    private static $instance;

    public static function register_callback()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('admin_post_wcbef_load_column_profile', [$this, 'load_column_profile']);
        add_action('admin_post_wcbef_settings', [$this, 'settings']);
        add_action('admin_post_wcbef_export_products', [$this, 'export_products']);
    }

    public function load_column_profile()
    {
        if (isset($_POST['preset_key'])) {
            $preset_key = sanitize_text_field($_POST['preset_key']);
            $checked_columns = Sanitizer::array($_POST["columns_{$preset_key}"]);
            $column_repository = new Column();
            $columns = [];
            $fields = $column_repository->get_fields();
            if (!empty($checked_columns)) {
                foreach ($checked_columns as $column_item) {
                    if (isset($fields[$column_item])) {
                        $checked_column = [
                            'name' => esc_sql($column_item),
                            'label' => esc_sql($fields[$column_item]['label']),
                            'title' => esc_sql($fields[$column_item]['label']),
                            'editable' => $fields[$column_item]['editable'],
                            'content_type' => $fields[$column_item]['content_type'],
                            'allowed_type' => $fields[$column_item]['allowed_type'],
                            'background_color' => '#fff',
                            'text_color' => '#444',
                        ];
                        if (isset($fields[$column_item]['sortable'])) {
                            $checked_column['sortable'] = ($fields[$column_item]['sortable']);
                        }
                        if (isset($fields[$column_item]['options'])) {
                            $checked_column['options'] = $fields[$column_item]['options'];
                        }
                        if (isset($fields[$column_item]['field_type'])) {
                            $checked_column['field_type'] = $fields[$column_item]['field_type'];
                        }
                        $columns[$column_item] = $checked_column;
                    }
                }
            }

            Session::set('wcbef_active_columns_key', $preset_key);
            Session::set('wcbef_active_columns', $columns);
        }
        $this->redirect();
    }

    public function settings()
    {
        $setting_repository = new Setting();
        $setting_repository->update(Sanitizer::array($_POST));
        $this->redirect('settings', esc_html__('Success !', WBEBL_NAME));
    }

    public function export_products()
    {
        $file_name = "wcbef-product-export-" . time() . '.csv';
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Pragma: no-cache");
        header("Expires: 0");
        $file = fopen('php://output', 'w');
        fwrite($file, chr(239) . chr(187) . chr(191)); //BOM For UTF-8
        $column_repository = new Column();
        $product_repository = new Product();
        if (defined('WC_ABSPATH')) {
            include_once WC_ABSPATH . 'includes/export/class-wc-product-csv-exporter.php';
            $exporter = new \WC_Product_CSV_Exporter();
        }
        if (isset($_POST['products'])) {
            switch ($_POST['products']) {
                case 'all':
                    $args = \wcbef\classes\helpers\Product::set_filter_data_items(Session::get('last_filter_data'), [
                        'posts_per_page' => '-1',
                        'post_type' => ['product', 'product_variation'],
                        'fields' => 'ids',
                    ]);
                    $products = $product_repository->get_products($args);
                    $product_ids = $products->posts;
                    break;
                case 'selected':
                    $product_ids = isset($_POST['product_ids']) ? Sanitizer::array($_POST['product_ids']) : [];
                    break;
            }
            switch ($_POST['fields']) {
                case 'all':
                    $columns = $column_repository->get_fields();
                    break;
                case 'visible':
                    $columns = Session::get('wcbef_active_columns');
                    break;
            }

            $header = [];
            if (!empty($product_ids)) {
                $header[] = 'Product ID';
                $header[] = 'Product Title';
                $attribute_counter = 1;
                $download_counter = 1;
                if (!empty($columns)) {
                    foreach ($columns as $field => $column) {
                        if (isset($column['field_type'])) {
                            switch ($column['field_type']) {
                                case 'meta_field':
                                    $header[] = "Meta: {$field}";
                                    break;
                                case 'attribute':
                                    $header[] = "Attribute {$attribute_counter} name";
                                    $header[] = "Attribute {$attribute_counter} value(s)";
                                    $header[] = "Attribute {$attribute_counter} visible";
                                    $header[] = "Attribute {$attribute_counter} global";
                                    $attribute_counter++;
                                    break;
                                default:
                                    break;
                            }
                        } elseif ($field == 'downloadable_files') {
                            $header[] = "downloads:name" . $download_counter;
                            $header[] = "downloads:url" . $download_counter;
                            $download_counter++;
                        } else {
                            $header[] = $column['label'];
                        }
                    }
                }
                fputcsv($file, $header);
                foreach ($product_ids as $product_id) {
                    $output = [];
                    $product_object = $product_repository->get_product(intval($product_id));
                    if (!($product_object instanceof \WC_Product)) {
                        return false;
                    }
                    $product = $product_repository->get_product_fields($product_object);
                    $output[] = $product['id'];
                    $output[] = $product['post_title'];
                    if (!empty($columns)) {
                        foreach ($columns as $field => $column_item) {
                            $field_encoded = strtolower(urlencode($field));
                            if (isset($column_item['field_type'])) {
                                switch ($column_item['field_type']) {
                                    case 'meta_field':
                                        $output[] = (isset($product['meta_field'][$field_encoded])) ? $product['meta_field'][$field_encoded][0] : '';
                                        break;
                                    case 'attribute':
                                        if (!empty($exporter)) {
                                            $value = (isset($product['attribute'][$field_encoded]['options'])) ? $product['attribute'][$field_encoded]['options'] : '';
                                            $output[] = $field;
                                            $output[] = $exporter->format_term_ids($value, $field);
                                            $output[] = 1;
                                            $output[] = 1;
                                        } else {
                                            $output[] = '';
                                        }
                                        break;
                                    default:
                                        break;
                                }
                            } elseif ($field == 'downloadable_files') {
                                if (!empty($product[$field]) && is_array($product[$field])) {
                                    foreach ($product[$field] as $download) {
                                        if ($download instanceof \WC_Product_Download) {
                                            $output[] = $download->get_name();
                                            $output[] = $download->get_file();
                                        } else {
                                            $output[] = "";
                                            $output[] = "";
                                        }
                                    }
                                } else {
                                    $output[] = "";
                                    $output[] = "";
                                }
                            } else {
                                switch ($field) {
                                    case "_thumbnail_id":
                                        $image = wp_get_attachment_image_src($product[$field]['id'], 'original');
                                        $value = isset($image[0]) ? $image[0] : '';
                                        break;
                                    case "product_cat":
                                        $value = $exporter->format_term_ids($product[$field], $field);
                                        break;
                                    default:
                                        $value = (is_array($product[$field])) ? implode(',', $product[$field]) : $product[$field];
                                        break;
                                }
                                $output[] = $value;
                            }
                        }
                    }
                    fputcsv($file, $output);
                }
            }
            die();
        }
        return false;
    }

    private function redirect($active_tab = null, $message = null)
    {
        if (!is_null($active_tab)) {
            Session::set('wcbef-tab', $active_tab);
        }
        if (!is_null($message)) {
            Session::set('flush-message', $message);
        }

        return wp_redirect(WCBEF_PLUGIN_MAIN_PAGE);
    }
}
