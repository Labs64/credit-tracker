<?php
/**
 * @package   Credit_Tracker
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      https://www.labs64.com
 * @copyright 2013 Labs64
 */

/**
 * Write DEBUG log.
 *
 * See also: https://codex.wordpress.org/Debugging_in_WordPress
 */
if (!function_exists('credittracker_write_log')) {
    function credittracker_write_log($log)
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
function credittracker_get_images($attr)
{
	global $post;
    $defaults = array(
        'size' => 'thumbnail',
        'include' => '0',
        'numberposts' => -1,
	    'orderby' => 'post__in',
	    'order' => 'DESC',
    );

    // merge defaults with user input
    $attr = wp_parse_args($attr, $defaults);
    extract($attr);

    // User input sanity checking. Don't allow invalid values.
	if ( 'date' != $orderby && 'post__in' != $orderby ) {
		$orderby = 'post__in';
	}
	if ( 'asc' != $order && 'desc' != $order ) {
		$order = 'DESC';
	}

    $args = array(
        'include' => $include,
        'post_status' => 'inherit',
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'orderby' => $orderby,
        'order' => $order,
        'numberposts' => $numberposts
    );

    // If the 'only_current_post' value is true, only get attachments for the displayed post for this table.
    if (isset($attr['only_current_post']) && true === $attr['only_current_post'] ) {
      $attachments = get_attached_media( 'image', $post->ID );
    } else {
      $attachments = get_posts( apply_filters( 'credittracker_get_images_args', $args ) );
    }

    $items = array();
    $item = array();

    // get all attachements
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
function credittracker_process_item_copyright($item, $string)
{
	$meta_fields = array(
		'ident_nr',
		'source',
		'author',
		'publisher',
		'license',
		'link',
		'title',
		'caption',
	);

	// Loop through the defined meta fields, updating the string if the field is set.
	foreach( $meta_fields as $field ) {
		$string = isset( $item[ $field ] ) ? str_replace( "%{$field}%", $item[ $field ], $string) : $string;
	}

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
function credittracker_get_combobox_options($options, $selected)
{
    $ret = '';
    foreach ($options as $key => $value) {
        $ret .= '<option value="' . $key . '"';
        if ($key == $selected) {
            $ret .= ' selected="selected"';
        }
        $ret .= '>' . (empty($value) ? '- ' . $key . ' -' : $value) . '</option>';
    }
    return $ret;
}

/**
 * Shorten an URL
 *
 * @param string $url
 * @return string
 */
function credittracker_strip_url($url, $len = 20)
{
    $short_url = str_replace(array('http://', 'https://', 'www.'), '', $url);
    $short_url = preg_replace('/[^a-zA-Z0-9_-]/', '', $short_url);
    if (strlen($short_url) > $len) {
        $short_url = substr($short_url, 0, $len);
    }
    return $short_url;
}
