<?php

namespace wcbef\classes\bootstrap;

use wcbef\classes\controllers\WCBEF_Ajax;
use wcbef\classes\controllers\WCBEF_Post;
use wcbef\classes\controllers\Woocommerce_Bulk_Edit_Free;

class WCBEF
{
    private static $instance = null;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);
        WCBEF_Ajax::register_callback();
        WCBEF_Post::register_callback();
        (new WCBEF_Meta_Fields())->init();
        (new WCBEF_Custom_Queries())->init();
    }

    public static function wc_required()
    {
        include WCBEF_VIEWS_DIR . 'alerts/wc_required.php';
    }

    public static function wcbef_wp_init()
    {
        // Session start
        if (!session_id()) {
            session_start();
        }
    }

    public function add_menu()
    {
        add_submenu_page('wbebl', esc_html__('Woo Products', WBEBL_NAME), esc_html__('Woo Products', WBEBL_NAME), 'manage_woocommerce', 'wcbef', [new Woocommerce_Bulk_Edit_Free(), 'index'], 1);
    }

    public function load_assets($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wcbef') {
            // Styles
            wp_enqueue_style('wcbef-reset', WCBEF_CSS_URL . 'reset.css');
            wp_enqueue_style('wcbef-LineIcons', WCBEF_CSS_URL . 'LineIcons.min.css');
            wp_enqueue_style('wcbef-datepicker', WCBEF_CSS_URL . 'bootstrap-material-datetimepicker.css');
            wp_enqueue_style('wcbef-select2', WCBEF_CSS_URL . 'select2.min.css');
            wp_enqueue_style('wcbef-sweetalert', WCBEF_CSS_URL . 'sweetalert.css');
            wp_enqueue_style('wcbef-jquery_ui', WCBEF_CSS_URL . 'jquery-ui.min.css');
            wp_enqueue_style('wcbef-tipsy', WCBEF_CSS_URL . 'jquery.tipsy.css');
            wp_enqueue_style('wcbef-scrollbar', WCBEF_CSS_URL . 'jquery.scrollbar.css');
            wp_enqueue_style('wcbef-main', WCBEF_CSS_URL . 'style.css');
            wp_enqueue_style('wp-color-picker');

            // Scripts
            wp_enqueue_script('wcbef-functions', WCBEF_JS_URL . 'functions.js', ['jquery'], '1.0.1');
            wp_enqueue_script('wcbef-select2', WCBEF_JS_URL . 'select2.min.js', ['jquery']);
            wp_enqueue_script('wcbef-moment', WCBEF_JS_URL . 'moment-with-locales.min.js', ['jquery']);
            wp_enqueue_script('wcbef-tipsy', WCBEF_JS_URL . 'jquery.tipsy.js', ['jquery']);
            wp_enqueue_script('wcbef-scrollbar', WCBEF_JS_URL . 'jquery.scrollbar.min.js', ['jquery']);
            wp_enqueue_script('wcbef-bootstrap_datepicker', WCBEF_JS_URL . 'bootstrap-material-datetimepicker.js', ['jquery']);
            wp_enqueue_script('wcbef-sweetalert', WCBEF_JS_URL . 'sweetalert.min.js', ['jquery']);
            wp_enqueue_script('wcbef-main', WCBEF_JS_URL . 'main.js', ['jquery', 'jquery-ui-sortable', 'wp-color-picker', 'wcbef-functions'], '6.0');
            wp_localize_script('wcbef-main', 'WCBEF_DATA', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'wp_nonce' => wp_create_nonce(),
            ]);
            wp_enqueue_media();
            wp_enqueue_editor();
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-datepicker');
        }
    }

    public static function activate()
    {
        // 
    }

    public static function deactivate()
    {
        unset($_SESSION['wcbef_active_columns']);
        unset($_SESSION['wcbef_active_columns_key']);
    }
}
