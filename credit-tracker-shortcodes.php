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


require_once(plugin_dir_path(__FILE__) . 'credit-tracker-functions.php');

// add shortcodes
add_shortcode('credit_tracker_table', 'credit_tracker_table_shortcode');


function credit_tracker_table_shortcode($atts)
{
    extract(shortcode_atts(
            array(
                'id' => '',
                'size' => 'thumbnail',
                'style' => 'default',
            ), $atts)
    );

    if (is_numeric($size)) {
        $size = array($size, $size);
    }

    $request = array(
        'size' => $size,
        'include' => $id
    );
    $images = get_images($request);

    $ret = '<table class="credit-tracker-' . $style . '"><thead>';
    $ret .= '<th class="credit-tracker-column">' . '&nbsp;' . '</th>';
    $ret .= '<th class="credit-tracker-column">' . __('Ident-Nr.', SLUG) . '</th>';
    $ret .= '<th class="credit-tracker-column">' . __('Author', SLUG) . '</th>';
    $ret .= '<th class="credit-tracker-column">' . __('Publisher', SLUG) . '</th>';
    $ret .= '<th class="credit-tracker-column">' . __('Copyright', SLUG) . '</th>';
    $ret .= '<th class="credit-tracker-column">' . __('License', SLUG) . '</th>';
    $ret .= '</thead><tbody>';

    if (empty($images)) {
        $ret .= '<tr class="credit-tracker-row"><td colspan="6" class="credit-tracker-column-empty">' . __('No images found', SLUG) . '</td></tr>';
    }

    foreach ($images as $image) {
        if (!empty($image['author']) && !empty($image['publisher'])) {
            $ret .= '<tr class="credit-tracker-row">';
            $ret .= '<td class="credit-tracker-column">' . '<img width="' . $image['width'] . '" height="' . $image['height'] . '" src="' . $image['url'] . '" class="attachment-thumbnail" alt="' . $image['alt'] . '">' . '</td>';
            $ret .= '<td class="credit-tracker-column">' . $image['ident_nr'] . '</td>';
            $ret .= '<td class="credit-tracker-column">' . $image['author'] . '</td>';
            $ret .= '<td class="credit-tracker-column">' . $image['publisher'] . '</td>';
            $ret .= '<td class="credit-tracker-column">' . '&copy;&nbsp;' . $image['author'] . ' - ' . $image['publisher'] . '</td>';
            $ret .= '<td class="credit-tracker-column">' . $image['license'] . '</td>';
            $ret .= '</tr>';
        }
    }

    $ret .= '</tbody></table>';
    return $ret;

}

?>
