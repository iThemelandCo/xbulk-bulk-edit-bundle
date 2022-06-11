<?php
/*
Plugin Name: iThemeland Bulk Product Editing Lite For WooCommerce
Plugin URI: https://www.ithemelandco.com/Plugins/Pro-Bulk-Editing/woocommerce-bulk-product-editing
Description: Editing Date in WordPress is very painful. Be professionals with managing data in the reliable and flexible way by WooCommerce Bulk Product Editor.
Author: iThemelandco
Tested up to: WP 5.3
Requires PHP: 5.4
Tags: woocommerce,woocommerce bulk edit,bulk edit,bulk,products bulk editor
Text Domain: woocommerce-bulk-edit-free
WC requires at least: 3.3.1
WC tested up to: 3.8
Version: 1.0.0
Author URI: https://www.ithemelandco.com
*/

defined('ABSPATH') || exit();

require_once __DIR__ . '/vendor/autoload.php';

define('WCBEF_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WCBEF_PLUGIN_MAIN_PAGE', admin_url('admin.php?page=wcbef'));
define('WCBEF_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WCBEF_VIEWS_DIR', trailingslashit(WCBEF_DIR . 'views'));
define('WCBEF_ASSETS_DIR', trailingslashit(WCBEF_DIR . 'assets'));
define('WCBEF_ASSETS_URL', trailingslashit(WCBEF_URL . 'assets'));
define('WCBEF_CSS_URL', trailingslashit(WCBEF_ASSETS_URL . 'css'));
define('WCBEF_IMAGES_URL', trailingslashit(WCBEF_ASSETS_URL . 'images'));
define('WCBEF_JS_URL', trailingslashit(WCBEF_ASSETS_URL . 'js'));
define('WCBEF_VERSION', '1.0.0');
define('WCBEF_UPGRADE_URL', 'https://ithemelandco.com/plugins/xbulk-edit-bundle');
define('WCBEF_UPGRADE_TEXT', 'Download Pro Version');

register_activation_hook(__FILE__, ['\wcbef\classes\bootstrap\WCBEF', 'activate']);
register_deactivation_hook(__FILE__, ['\wcbef\classes\bootstrap\WCBEF', 'deactivate']);
add_action('init', ['\wcbef\classes\bootstrap\WCBEF', 'wcbef_wp_init']);

add_action('plugins_loaded', 'wcbef_init');
function wcbef_init()
{
    if (!class_exists('WooCommerce')) {
        \wcbef\classes\bootstrap\WCBEF::wc_required();
    } else {
        \wcbef\classes\bootstrap\WCBEF::init();
    }
}
