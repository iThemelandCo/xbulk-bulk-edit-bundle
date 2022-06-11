<?php
/*
Plugin Name: iThemeland Wordpress Bulk Posts Editing Lite
Plugin URI: https://www.ithemelandco.com/Plugins/Pro-Bulk-Editing/wordpress-posts-bulk-editing-lite
Description: Editing Date in WordPress is very painful. Be professionals with managing data in the reliable and flexible way by WooCommerce Bulk Post Editor.
Author: iThemelandco
Tested up to: WP 5.3
Requires PHP: 5.4
Tags: wordpress bulk edit,bulk edit,bulk,posts bulk editor
Text Domain: ithemeland-wordpress-bulk-posts-editing-lite
Domain Path: /languages
Version: 1.0.0
Author URI: https://www.ithemelandco.com
*/

defined('ABSPATH') || exit();

require_once __DIR__ . '/vendor/autoload.php';

define('WPBEL_LABEL', 'Ithemeland Wordpress Bulk Posts Editing Lite');
define('WPBEL_CORE_PLUGIN', 'ithemeland-bulk-bundle-core/ithemeland-bulk-bundle-core.php');
define('WPBEL_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WPBEL_PLUGIN_MAIN_PAGE', admin_url('admin.php?page=wpbel'));
define('WPBEL_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('WPBEL_LIB_DIR', trailingslashit(WPBEL_DIR . 'classes/lib'));
define('WPBEL_VIEWS_DIR', trailingslashit(WPBEL_DIR . 'views'));
define('WPBEL_ASSETS_DIR', trailingslashit(WPBEL_DIR . 'assets'));
define('WPBEL_ASSETS_URL', trailingslashit(WPBEL_URL . 'assets'));
define('WPBEL_CSS_URL', trailingslashit(WPBEL_ASSETS_URL . 'css'));
define('WPBEL_IMAGES_URL', trailingslashit(WPBEL_ASSETS_URL . 'images'));
define('WPBEL_JS_URL', trailingslashit(WPBEL_ASSETS_URL . 'js'));
define('WPBEL_VERSION', '1.0.0');
define('WPBEL_UPGRADE_URL', 'https://ithemelandco.com/plugins/xbulk-edit-bundle');
define('WPBEL_UPGRADE_TEXT', 'Download Pro Version');

register_activation_hook(__FILE__, ['wpbel\classes\bootstrap\WPBEL', 'activate']);
register_deactivation_hook(__FILE__, ['wpbel\classes\bootstrap\WPBEL', 'deactivate']);
add_action('init', ['wpbel\classes\bootstrap\WPBEL', 'wpbel_wp_init']);


add_action('plugins_loaded', 'wpbel_init');
function wpbel_init()
{
    \wpbel\classes\bootstrap\WPBEL::init();
}
