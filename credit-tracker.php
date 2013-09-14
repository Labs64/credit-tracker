<?php
/**
 * @wordpress-plugin
 * Plugin Name: Credit Tracker
 * Plugin URI:  https://github.com/Labs64/credit-tracker
 * Description: Fetch (from image agencies) & process credits for the different WordPress entities.
 * Author:      Labs64
 * Author URI:  http://www.labs64.com
 * Version:     1.0.0
 * Text Domain: credit-tracker
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 3.3
 * Tested up to: 3.6.1
 *
 * @package   Credit_Tacker
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2013 Labs64
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require_once(plugin_dir_path(__FILE__) . 'class-credit-tracker.php');

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook(__FILE__, array('Credit_Tacker', 'activate'));
register_deactivation_hook(__FILE__, array('Credit_Tacker', 'deactivate'));

add_action('plugins_loaded', array('Credit_Tacker', 'get_instance'));
