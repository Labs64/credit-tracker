<?php
/**
 * @package   Credit_Tracker
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2013 Labs64
 */

/**
 * Write DEBUG log.
 *
 * See also: https://codex.wordpress.org/Debugging_in_WordPress
 */
if (!function_exists('ct_write_log')) {
    function ct_write_log($log)
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

/**
 * Returns array of images with attributes.
 *
 * Sample input parameters:
 *     $attr = array(
 *         'size'         => 'thumbnail',     // image size (thumbnail, medium, large or full)
 *         'include'      => '121,122,123',   // image id or comma delimited list of image id's
 *         'numberposts'  => -1               // max quantity of records to return; default = -1 (all)
 *     );
 */
function ct_get_images($attr)
{
    $defaults = array(
        'size' => 'thumbnail',
        'include' => '0',
        'numberposts' => -1
    );

    // merge defaults with user input
    $attr = wp_parse_args($attr, $defaults);
    extract($attr);

    $args = array(
        'include' => $include,
        'post_status' => 'inherit',
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'orderby' => 'post__in',
        'numberposts' => $numberposts
    );

    $items = array();
    $item = array();

    // get all attachements
    $attachments = get_posts($args);
    if (empty($attachments)) {
        return $items;
    }

    foreach ($attachments as $attachment) {
        $imgSrc = wp_get_attachment_image_src($attachment->ID, $size, false);
        if (!empty($imgSrc)) {
            $item['ID'] = $attachment->ID;
            $item['url'] = $imgSrc[0];
            $item['width'] = $imgSrc[1];
            $item['height'] = $imgSrc[2];
            $item['alt'] = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            $item['title'] = wptexturize($attachment->post_title);
            $item['desc'] = wptexturize($attachment->post_content);
            $item['caption'] = wptexturize($attachment->post_excerpt);
            $item['ident_nr'] = wptexturize(get_post_meta($attachment->ID, 'credit-tracker-ident_nr', true));
            $item['source'] = wptexturize(get_post_meta($attachment->ID, 'credit-tracker-source', true));
            $item['author'] = wptexturize(get_post_meta($attachment->ID, 'credit-tracker-author', true));
            $item['publisher'] = wptexturize(get_post_meta($attachment->ID, 'credit-tracker-publisher', true));
            $item['license'] = wptexturize(get_post_meta($attachment->ID, 'credit-tracker-license', true));
            $item['link'] = wptexturize(get_post_meta($attachment->ID, 'credit-tracker-link', true));

            $items[] = $item;
        }
    }

    return $items;
}

/**
 * Returns copyright string formatted according to the given format.
 */
function ct_process_item_copyright($item, $string)
{
    $string = str_replace("%ident_nr%", $item['ident_nr'], $string);
    $string = str_replace("%source%", $item['source'], $string);
    $string = str_replace("%author%", $item['author'], $string);
    $string = str_replace("%publisher%", $item['publisher'], $string);
    $string = str_replace("%license%", $item['license'], $string);
    $string = str_replace("%link%", $item['link'], $string);

    $string = str_replace("%title%", $item['title'], $string);
    $string = str_replace("%caption%", $item['caption'], $string);

    return $string;
}

/**
 * Prints a combobox based on options and selected=match value
 *
 * Parameters:
 * $options = array of options (suggest using helper functions)
 * $selected = which of those options should be selected (allows just one; is case sensitive)
 *
 * Outputs (based on array ( $key => $value ):
 * <option value=$key>$value</option>
 * <option value=$key selected="selected">$value</option>
 */
function ct_get_combobox_options($options, $selected)
{
    $ret = '';
    foreach ($options as $key => $value) {
        $ret .= '<option value="' . $key . '"';
        if ($key == $selected) {
            $ret .= ' selected="selected"';
        }
        $ret .= '>' . $value . '</option>';
    }
    return $ret;
}

/**
 * Shorten an URL
 *
 * @param string $url
 * @return string
 */
function ct_strip_url($url, $len = 20)
{
    $short_url = str_replace(array('http://', 'https://', 'www.'), '', $url);
    $short_url = preg_replace('/[^a-zA-Z0-9_-]/', '', $short_url);
    if (strlen($short_url) > $len) {
        $short_url = substr($short_url, 0, $len);
    }
    return $short_url;
}
