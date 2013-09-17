<?php
/**
 * Returns array of images with attributes.
 *
 * Sample input parameters:
 *     $attr = array(
 *         'size'         => 'thumbnail',     // image size (thumbnail, medium, large or full)
 *         'include'      => '121,122,123',   // image id or comma delimited list of image id's
 *         'numberposts'  => -1               // max quantity of records to return; default = -1 (all)
 *     );
 *
 * @package   Credit_Tracker
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2013 Labs64
 */

function get_images($attr)
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
    $imgSrc = '';

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

            $items[] = $item;
        }
    }

    return $items;
}
