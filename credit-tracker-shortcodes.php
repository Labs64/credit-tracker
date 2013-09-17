<?php
/**
 * Plugin shortcodes.
 *
 * @package   Credit_Tracker
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2013 Labs64
 */


// add shortcodes
add_shortcode('credit_tracker_list', 'credit_tracker_list_shortcode');


function credit_tracker_list_shortcode($atts)
{
    extract(shortcode_atts(
            array(
                'id' => '',
                'foo' => 'bar',
            ), $atts)
    );

    return "<a href='#' id='credit-tracker-list'>{$id}</a>";
}

?>
