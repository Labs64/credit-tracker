<?php
/**
 * Plugin options page.
 *
 * @package   Credit_Tracker
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2013 Labs64
 */

function credit_tracker_options_page()
{
    if (!current_user_can('manage_options')) {
        ?>
        <div class="error"><p><strong><?php _e('You do not have sufficient permissions to edit this site.'); ?></strong>
            </p></div>
        <?php wp_die();
    }
    ?>

    <div class="wrap">

    <a href="http://www.labs64.com" target="_blank" class="labs64-icon icon32"></a>

    <h2><?php _e('Credit Tracker by Labs64', 'credit-tracker'); ?></h2>

    <p><?php _e('Adds custom fields to media library to allow users to store image credit information such as author, publisher, ident number, license etc.', 'credit-tracker'); ?></p>

    <h3><?php _e('Feedback', 'credit-tracker'); ?></h3>

    <p><?php _e('Did you find a bug? Have an idea for a plugin? Please help us improve this plugin', 'credit-tracker'); ?>
        :</p>
    <ul>
        <li>
            <a href="https://github.com/Labs64/credit-tracker/issues"
               target="_blank"><?php _e('Report a bug, or suggest an improvement', 'credit-tracker'); ?></a>
        </li>
        <li><a href="http://www.facebook.com/labs64" target="_blank"><?php _e('Like us on Facebook'); ?></a></li>
        <li><a href="http://www.labs64.com/blog" target="_blank"><?php _e('Read Labs64 Blog'); ?></a></li>
    </ul>

    </div><?php
}
