<?php
/*
Plugin Name: iThemeland WooCommerce Bulk Coupons Editing Lite
Plugin URI: https://ithemelandco.com/plugins/woocommerce-bulk-coupons-editing
Description: Editing Date in WordPress is very painful. Be professionals with managing data in the reliable and flexible way by WooCommerce Bulk Coupon Editor.
Author: iThemelandco
Tested up to: WP 5.3
Requires PHP: 5.4
Tags: woocommerce,woocommerce bulk edit,bulk edit,bulk,coupons bulk editor
Text Domain: ithemeland-woocommerce-bulk-coupons-editing-lite
Domain Path: /languages
WC requires at least: 3.3.1
WC tested up to: 3.8
Version: 1.0.1
Author URI: https://www.ithemelandco.com
*/

defined('ABSPATH') || exit();

require_once __DIR__ . '/vendor/autoload.php';

define('WCCBEF_LABEL', 'Ithemeland Woocommerce Bulk Coupons Editing Lite');
define('WCCBEF_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WCCBEF_PLUGIN_MAIN_PAGE', admin_url('admin.php?page=wccbef'));
define('WCCBEF_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WCCBEF_LIB_DIR', trailingslashit(WCCBEF_DIR . 'classes/lib'));
define('WCCBEF_VIEWS_DIR', trailingslashit(WCCBEF_DIR . 'views'));
define('WCCBEF_ASSETS_DIR', trailingslashit(WCCBEF_DIR . 'assets'));
define('WCCBEF_ASSETS_URL', trailingslashit(WCCBEF_URL . 'assets'));
define('WCCBEF_CSS_URL', trailingslashit(WCCBEF_ASSETS_URL . 'css'));
define('WCCBEF_IMAGES_URL', trailingslashit(WCCBEF_ASSETS_URL . 'images'));
define('WCCBEF_JS_URL', trailingslashit(WCCBEF_ASSETS_URL . 'js'));
define('WCCBEF_VERSION', '1.0.1');
define('WCCBEF_UPGRADE_URL', 'https://ithemelandco.com/plugins/xbulk-edit-bundle');
define('WCCBEF_UPGRADE_TEXT', 'Download Pro Version');

register_activation_hook(__FILE__, ['wccbef\classes\bootstrap\WCCBEF', 'activate']);
register_deactivation_hook(__FILE__, ['wccbef\classes\bootstrap\WCCBEF', 'deactivate']);

add_action('init', ['wccbef\classes\bootstrap\WCCBEF', 'wccbef_wp_init']);

add_action('plugins_loaded', 'wccbef_init');
function wccbef_init()
{
    if (!class_exists('WooCommerce')) {
        wccbef\classes\bootstrap\WCCBEF::wccbef_woocommerce_required();
    } else {
        \wccbef\classes\bootstrap\WCCBEF::init();
    }
}
