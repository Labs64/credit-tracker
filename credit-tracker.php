<?php
/**
 * @wordpress-plugin
 * Plugin Name: Credit Tracker
 * Plugin URI:  https://github.com/Labs64/credit-tracker
 * Description: A simple way to show credits for the images used on your website.
 * Author:      Labs64
 * Author URI:  https://www.labs64.com
 * Version:     1.1.16
 * Text Domain: credit-tracker
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 4.0
 * Tested up to: 5.0.3
 *
 * @package   Credit_Tracker
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      https://www.labs64.com
 * @copyright 2013 Labs64
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


/**
 * Plugin version, used for cache-busting of style and script file references.
 */
define('CREDITTRACKER_VERSION', '1.1.16');

/**
 * Unique identifier for your plugin.
 *
 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
 * match the Text Domain file header in the main plugin file.
 */
define('CREDITTRACKER_SLUG', 'credit-tracker');

// main
require_once(plugin_dir_path(__FILE__) . 'credit-tracker-class.php');
require_once(plugin_dir_path(__FILE__) . 'credit-tracker-shortcodes.php');
require_once(plugin_dir_path(__FILE__) . 'credit-tracker-widgets.php');
require_once(plugin_dir_path(__FILE__) . 'credit-tracker-functions.php');
require_once(plugin_dir_path(__FILE__) . 'options.php');
// util
require_once(plugin_dir_path(__FILE__) . '/php/netlicensing/netlicensing.php');
require_once(plugin_dir_path(__FILE__) . '/php/curl/curl.php');
require_once(plugin_dir_path(__FILE__) . '/php/curl/curl_response.php');
// parser
require_once(plugin_dir_path(__FILE__) . '/php/parser/parser.php');
require_once(plugin_dir_path(__FILE__) . '/php/parser/fotolia.php');
require_once(plugin_dir_path(__FILE__) . '/php/parser/istockphoto.php');
require_once(plugin_dir_path(__FILE__) . '/php/parser/pixelio.php');
require_once(plugin_dir_path(__FILE__) . '/php/parser/flickr.php');
require_once(plugin_dir_path(__FILE__) . '/php/parser/freeimages.php');
require_once(plugin_dir_path(__FILE__) . '/php/parser/shutterstock.php');
require_once(plugin_dir_path(__FILE__) . '/php/parser/unsplash.php');
require_once(plugin_dir_path(__FILE__) . '/php/parser/depositphotos.php');

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook(__FILE__, array('Credit_Tracker', 'activate'));
register_deactivation_hook(__FILE__, array('Credit_Tracker', 'deactivate'));

add_action('plugins_loaded', array('Credit_Tracker', 'get_instance'));
