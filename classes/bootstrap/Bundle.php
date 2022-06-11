<?php

namespace wbebl\classes\bootstrap;

use wbebl\classes\controllers\Active_Plugin_Controller;
use wbebl\classes\controllers\Dashboard_Controller;
use wbebl\classes\helpers\Lang_Helper;
use wbebl\classes\repositories\option\Option;
use wbebl\classes\requests\Post_Handler;

class Bundle
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
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);

        WBEBL_Buttons_List::init();
        WBEBL_Top_Banners::register();
        Post_Handler::register_callback();
        (new Option())->update_options('wbebl');
        Add_Ons::init();
    }

    public static function deactivate_plugins()
    {
        if (!function_exists('deactivate_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $active_plugins = [];
        $plugins = [
            Add_Ons::WOO_COUPONS,
            Add_Ons::WOO_ORDERS,
            Add_Ons::WOO_PRODUCTS,
            Add_Ons::WP_POSTS,
        ];

        foreach ($plugins as $plugin) {
            if (is_plugin_active($plugin)) {
                $active_plugins[] = $plugin;
            }
        }

        if (!empty($active_plugins) && count($active_plugins)) {
            $page_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            deactivate_plugins($active_plugins, true);
            wp_redirect($page_url);
            die();
        }

        return true;
    }

    public function add_menu()
    {
        add_menu_page(esc_html__('iThemeland bulk bundle core lite', WBEBL_NAME), esc_html__('X-Bulk Lite', WBEBL_NAME), 'manage_options', 'wbebl', [new Dashboard_Controller(), 'index'], WBEBL_IMAGES_URL . 'wbebl_icon.svg', 2);
        add_submenu_page('wbebl', esc_html__('Dashboard', WBEBL_NAME), esc_html__('Dashboard', WBEBL_NAME), 'manage_options', 'wbebl');
        add_submenu_page('wbebl', esc_html__('Active Plugin', WBEBL_NAME), esc_html__('Active Plugin', WBEBL_NAME), 'manage_options', 'wbebl-active-plugin', [new Active_Plugin_Controller(), 'index'], 25);
    }

    public function load_assets($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wbebl') {
            // Styles
            wp_enqueue_style('wbebl-reset', WBEBL_CSS_URL . 'reset.css', [], WBEBL_VERSION);
            wp_enqueue_style('wbebl-main', WBEBL_CSS_URL . 'style.css', [], WBEBL_VERSION);
            wp_enqueue_style('wbebl-wbebl', WBEBL_CSS_URL . 'wbebl.css', [], WBEBL_VERSION);
            wp_enqueue_style('wbebl-sweetalert', WBEBL_CSS_URL . 'sweetalert.css', [], WBEBL_VERSION);
            wp_enqueue_style('wbebl-LineIcons', WBEBL_CSS_URL . 'LineIcons.min.css', [], WBEBL_VERSION);
            wp_enqueue_style('wbebl-tipsy', WBEBL_CSS_URL . 'jquery.tipsy.css', [], WBEBL_VERSION);

            // Scripts
            wp_enqueue_script('wbebl-functions', WBEBL_JS_URL . 'functions.js', ['jquery'], WBEBL_VERSION);
            wp_enqueue_script('wbebl-tipsy', WBEBL_JS_URL . 'jquery.tipsy.js', ['jquery'], WBEBL_VERSION);
            wp_enqueue_script('wbebl-sweetalert', WBEBL_JS_URL . 'sweetalert.min.js', ['jquery'], WBEBL_VERSION);
            wp_enqueue_script('wbebl-main', WBEBL_JS_URL . 'main.js', ['jquery'], WBEBL_VERSION);
            wp_localize_script('wbebl-main', 'wbeblTranslate', Lang_Helper::get_js_strings());
        }

        if (!empty($_GET['page']) && $_GET['page'] == 'wbebl-active-plugin') {
            wp_enqueue_style('wbebl-reset', WBEBL_CSS_URL . 'reset.css', [], WBEBL_VERSION);
            wp_enqueue_style('wbebl-main', WBEBL_CSS_URL . 'style.css', [], WBEBL_VERSION);

            wp_enqueue_script('wbebl-activation', WBEBL_JS_URL . 'activation.js', ['jquery'], WBEBL_VERSION);
        }
    }

    public static function activate()
    {
        self::woo_products_create_tables();

        self::woo_coupons_create_tables();

        self::woo_orders_create_tables();

        self::wp_posts_create_tables();
    }

    public static function deactivate()
    {
        // clear options
        $option_repository = new Option();
        $option_repository->delete_options_with_like_name('wbebl');
    }

    public static function wp_init()
    {
        $version = get_option('wbebl_version');
        if (empty($version) || $version != WBEBL_VERSION) {
            update_option('wbebl_version', WBEBL_VERSION);
        }

        // load textdomain
        load_plugin_textdomain(WBEBL_NAME, false, WBEBL_LANGUAGES_DIR);
    }

    public static function wp_loaded()
    {
        // woo products
        // set default settings
        if (!get_option('wcbef_settings') && class_exists('\wcbef\classes\repositories\Setting')) {
            (new \wcbef\classes\repositories\Setting())->update();
        }

        // set default column profile
        if (!(get_option('wcbef_column_fields')) && class_exists('\wcbef\classes\repositories\Column')) {
            (new \wcbef\classes\repositories\Column())->set_default_fields();
        }

        // set default filter profile
        if (!(get_option('wcbef_filter_profile')) && class_exists('\wcbef\classes\repositories\Search')) {
            (new \wcbef\classes\repositories\Search())->set_default_item();
        }

        // WP posts
        // set default settings
        if (class_exists('\wpbel\classes\repositories\Setting')) {
            (new \wpbel\classes\repositories\Setting('post'))->set_default_settings();
        }

        // set default column profile
        if (class_exists('\wpbel\classes\repositories\Column')) {
            (new \wpbel\classes\repositories\Column('post'))->set_default_columns();
        }

        // set default filter profile
        if (class_exists('\wpbel\classes\repositories\Search')) {
            (new \wpbel\classes\repositories\Search('post'))->set_default_item();
        }
    }

    private static function woo_products_create_tables()
    {
        global $wpdb;
        $history_table_name = $wpdb->prefix . 'wcbef_history';
        $history_items_table_name = $wpdb->prefix . 'wcbef_history_items';
        $query = '';
        $history_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_table_name));
        if (!$wpdb->get_var($history_table) == $history_table_name) {
            $query .= "CREATE TABLE {$history_table_name} (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  fields text NOT NULL,
                  operation_type varchar(32) NOT NULL,
                  operation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  reverted tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (id),
                  INDEX (user_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        }

        $history_items_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_items_table_name));
        if (!$wpdb->get_var($history_items_table) == $history_items_table_name) {
            $query .= "CREATE TABLE {$history_items_table_name} (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      history_id int(11) NOT NULL,
                      product_id int(11) NOT NULL,
                      field varchar(255) NOT NULL,
                      prev_value longtext,
                      new_value longtext,
                      PRIMARY KEY (id),
                      INDEX (history_id),
                      INDEX (product_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        }

        if (!empty($query)) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            dbDelta($query);
        }
    }

    private static function woo_coupons_create_tables()
    {
        global $wpdb;
        $history_table_name = esc_sql($wpdb->prefix . 'wccbef_history');
        $history_items_table_name = esc_sql($wpdb->prefix . 'wccbef_history_items');
        $query = '';
        $history_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_table_name));
        if (!$wpdb->get_var($history_table) == $history_table_name) {
            $query .= "CREATE TABLE {$history_table_name} (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  fields text NOT NULL,
                  operation_type varchar(32) NOT NULL,
                  operation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  reverted tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (id),
                  INDEX (user_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        }

        $history_items_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_items_table_name));
        if (!$wpdb->get_var($history_items_table) == $history_items_table_name) {
            $query .= "CREATE TABLE {$history_items_table_name} (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      history_id int(11) NOT NULL,
                      historiable_id int(11) NOT NULL,
                      field varchar(255) NOT NULL,
                      prev_value longtext,
                      new_value longtext,
                      PRIMARY KEY (id),
                      INDEX (history_id),
                      INDEX (historiable_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

            $query .= "ALTER TABLE {$history_items_table_name} ADD CONSTRAINT wccbef_history_items_history_id_relation FOREIGN KEY (history_id) REFERENCES {$history_table_name} (id) ON DELETE CASCADE ON UPDATE CASCADE;";
        }

        if (!empty($query)) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            dbDelta($query);
        }
    }

    private static function woo_orders_create_tables()
    {
        global $wpdb;
        $history_table_name = esc_sql($wpdb->prefix . 'wobef_history');
        $history_items_table_name = esc_sql($wpdb->prefix . 'wobef_history_items');
        $query = '';
        $history_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_table_name));
        if (!$wpdb->get_var($history_table) == $history_table_name) {
            $query .= "CREATE TABLE {$history_table_name} (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  fields text NOT NULL,
                  operation_type varchar(32) NOT NULL,
                  operation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  reverted tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (id),
                  INDEX (user_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        }

        $history_items_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_items_table_name));
        if (!$wpdb->get_var($history_items_table) == $history_items_table_name) {
            $query .= "CREATE TABLE {$history_items_table_name} (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      history_id int(11) NOT NULL,
                      historiable_id int(11) NOT NULL,
                      field varchar(255) NOT NULL,
                      prev_value longtext,
                      new_value longtext,
                      PRIMARY KEY (id),
                      INDEX (history_id),
                      INDEX (historiable_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

            $query .= "ALTER TABLE {$history_items_table_name} ADD CONSTRAINT wobef_history_items_history_id_relation FOREIGN KEY (history_id) REFERENCES {$history_table_name} (id) ON DELETE CASCADE ON UPDATE CASCADE;";
        }

        if (!empty($query)) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            dbDelta($query);
        }
    }

    private static function wp_posts_create_tables()
    {
        global $wpdb;
        $history_table_name = esc_sql($wpdb->prefix . 'wpbel_history');
        $history_items_table_name = esc_sql($wpdb->prefix . 'wpbel_history_items');
        $query = '';
        $history_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_table_name));
        if (!$wpdb->get_var($history_table) == $history_table_name) {
            $query .= "CREATE TABLE {$history_table_name} (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  fields text NOT NULL,
                  operation_type varchar(32) NOT NULL,
                  operation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  reverted tinyint(1) NOT NULL DEFAULT '0',
                  sub_system varchar(64) NOT NULL,
                  PRIMARY KEY (id),
                  INDEX (user_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        }

        $history_items_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_items_table_name));
        if (!$wpdb->get_var($history_items_table) == $history_items_table_name) {
            $query .= "CREATE TABLE {$history_items_table_name} (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      history_id int(11) NOT NULL,
                      historiable_id int(11) NOT NULL,
                      field varchar(255) NOT NULL,
                      prev_value longtext,
                      new_value longtext,
                      PRIMARY KEY (id),
                      INDEX (history_id),
                      INDEX (historiable_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

            $query .= "ALTER TABLE {$history_items_table_name} ADD CONSTRAINT wpbel_history_items_history_id_relation FOREIGN KEY (history_id) REFERENCES {$history_table_name} (id) ON DELETE CASCADE ON UPDATE CASCADE;";
        }

        if (!empty($query)) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            dbDelta($query);
        }
    }
}
