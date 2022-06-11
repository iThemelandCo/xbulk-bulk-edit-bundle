<?php

namespace wpbel\classes\controllers;

use wpbel\classes\helpers\Sanitizer;
use wpbel\classes\repositories\Flush_Message;
use wpbel\classes\repositories\Column;
use wpbel\classes\repositories\Common;
use wpbel\classes\repositories\Post;
use wpbel\classes\repositories\Search;
use wpbel\classes\repositories\Setting;

class WPBEL_Post
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
        add_action('admin_post_wpbel_switcher', [$this, 'switcher']);
        add_action('admin_post_wpbel_meta_fields', [$this, 'meta_fields']);
        add_action('admin_post_wpbel_settings', [$this, 'settings']);
        add_action('admin_post_wpbel_load_column_profile', [$this, 'load_column_profile']);
        add_action('admin_post_wpbel_export_posts', [$this, 'export_posts']);
    }

    public function switcher()
    {
        if (isset($_POST['post_type']) && $_POST['_wpnonce'] && wp_verify_nonce($_POST['_wpnonce'])) {
            $common_repository = new Common();
            $common_repository->update([
                'active_post_type' => sanitize_text_field($_POST['post_type'])
            ]);
        }
        $this->redirect('bulk-edit');
    }

    public function settings()
    {
        $setting_repository = new Setting();
        $setting_repository->update(Sanitizer::array($_POST));
        $this->redirect('settings', esc_html__('Success !', WBEBL_NAME));
    }

    public function load_column_profile()
    {
        if (isset($_POST['preset_key'])) {
            $preset_key = sanitize_text_field($_POST['preset_key']);
            $checked_columns = Sanitizer::array($_POST["columns_{$preset_key}"]);
            $column_repository = new Column();
            $columns = [];
            $fields = $column_repository->get_columns();
            if (!empty($checked_columns)) {
                foreach ($checked_columns as $column_item) {
                    if (isset($fields[$column_item])) {
                        $checked_column = [
                            'name' => sanitize_text_field($column_item),
                            'label' => sanitize_text_field($fields[$column_item]['label']),
                            'title' => sanitize_text_field($fields[$column_item]['label']),
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

            $column_repository->set_active_columns($preset_key, $columns);
        }
        $this->redirect();
    }

    public function export_posts()
    {
        $file_name = "wpbel-post-export-" . time() . '.csv';
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Pragma: no-cache");
        header("Expires: 0");
        $file = fopen('php://output', 'w');
        fwrite($file, chr(239) . chr(187) . chr(191)); //BOM For UTF-8
        $search_repository = new Search();
        $last_filter_data = isset($search_repository->get_current_data()['last_filter_data']) ? $search_repository->get_current_data()['last_filter_data'] : null;
        $column_repository = new Column();
        $post_repository = new Post();

        if (isset($_POST['posts'])) {
            switch ($_POST['posts']) {
                case 'all':
                    $args = \wpbel\classes\helpers\Post_Helper::set_filter_data_items($last_filter_data, [
                        'posts_per_page' => '-1',
                        'post_type' => [$GLOBALS['wpbel_common']['active_post_type']],
                        'fields' => 'ids',
                    ]);
                    $posts = $post_repository->get_posts($args);
                    $post_ids = $posts->posts;
                    break;
                case 'selected':
                    $post_ids = isset($_POST['item_ids']) ? array_map('intval', $_POST['item_ids']) : [];
                    break;
            }
            switch ($_POST['fields']) {
                case 'all':
                    $columns = $column_repository->get_columns();
                    break;
                case 'visible':
                    $columns = $column_repository->get_active_columns()['fields'];
                    break;
            }

            $header = [];
            if (!empty($post_ids)) {
                $header[] = 'Post ID';
                $header[] = 'Post Title';
                if (!empty($columns)) {
                    foreach ($columns as $field => $column) {
                        if (isset($column['field_type'])) {
                            switch ($column['field_type']) {
                                case 'custom_field':
                                    $header[] = "Meta: {$field}";
                                    break;
                                case 'taxonomy':
                                    $header[] = "Taxonomy: {$field}";
                                    break;
                                default:
                                    break;
                            }
                        } else {
                            $header[] = $column['label'];
                        }
                    }
                }
                fputcsv($file, $header);
                foreach ($post_ids as $post_id) {
                    $output = [];
                    $post_object = $post_repository->get_post(intval($post_id));
                    if (!($post_object instanceof \WP_Post)) {
                        return false;
                    }
                    $post = $post_repository->get_post_fields($post_object);
                    $output[] = $post['id'];
                    $output[] = $post['post_title'];
                    if (!empty($columns)) {
                        foreach ($columns as $field => $column_item) {
                            if (isset($post[$field])) {
                                if (isset($column_item['field_type'])) {
                                    switch ($column_item['field_type']) {
                                        case 'custom_field':
                                            $output[] = (isset($post['custom_field'][$field])) ? $post['custom_field'][$field][0] : '';
                                            break;
                                        case 'taxonomy':
                                            $output[] = implode(', ', wp_get_post_terms($post['id'], $field, ['fields' => 'ids']));
                                            break;
                                        default:
                                            break;
                                    }
                                } else {
                                    switch ($field) {
                                        case "_thumbnail_id":
                                            $image = wp_get_attachment_image_src($post[$field]['id'], 'original');
                                            $value = isset($image[0]) ? $image[0] : '';
                                            break;
                                        default:
                                            $value = (is_array($post[$field])) ? implode(',', $post[$field]) : $post[$field];
                                            break;
                                    }
                                    $output[] = $value;
                                }
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

    private function redirect(string $active_tab = '', string $message = '', string $url = '')
    {
        $hash = '';
        if (!is_null($active_tab)) {
            $hash = $active_tab;
        }

        if (!is_null($message)) {
            $flush_message_repository = new Flush_Message();
            $flush_message_repository->set(['message' => $message, 'hash' => $hash]);
        }

        return wp_redirect(WPBEL_PLUGIN_MAIN_PAGE . '#' . $hash);
    }
}
