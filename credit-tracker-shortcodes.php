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
    $ret .= '<th>' . '&nbsp;' . '</th>';
    $ret .= '<th>' . __('Ident-Nr.', CT_SLUG) . '</th>';
    $ret .= '<th>' . __('Author', CT_SLUG) . '</th>';
    $ret .= '<th>' . __('Publisher', CT_SLUG) . '</th>';
    $ret .= '<th>' . __('Copyright', CT_SLUG) . '</th>';
    $ret .= '<th>' . __('License', CT_SLUG) . '</th>';
    $ret .= '</thead><tbody>';

    if (empty($images)) {
        $ret .= '<tr class="credit-tracker-row"><td colspan="6" class="credit-tracker-column-empty">' . __('No images found', CT_SLUG) . '</td></tr>';
    }

    $ct_copyright_format = get_single_option('ct_copyright_format');

    foreach ($images as $image) {
        if (!empty($image['author']) && !empty($image['publisher'])) {
            $ret .= '<tr>';
            $ret .= '<td>' . '<img width="' . $image['width'] . '" height="' . $image['height'] . '" src="' . $image['url'] . '" class="attachment-thumbnail" alt="' . $image['alt'] . '">' . '</td>';
            $ret .= '<td>' . $image['ident_nr'] . '</td>';
            $ret .= '<td>' . $image['author'] . '</td>';
            $ret .= '<td>' . $image['publisher'] . '</td>';
            $ret .= '<td>' . process_item_copyright($image, $ct_copyright_format) . '</td>';
            $ret .= '<td>' . $image['license'] . '</td>';
            $ret .= '</tr>';
        }
    }

    $ret .= '</tbody></table>';
    return $ret;

}

?>
