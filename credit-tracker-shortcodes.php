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
add_filter('img_caption_shortcode', 'credit_tracker_caption_shortcode_filter', 10, 3);

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
    } else if (stripos($size, 'x') !== false) {
        $size = explode('x', $size);
    }

    $request = array(
        'size' => $size,
        'include' => $id
    );
    $images = ct_get_images($request);

    $ct_intentnr_column_remove = ct_get_single_option('ct_intentnr_column_remove');

    $ret = '<table id="credit-tracker-table" class="credit-tracker-' . $style . '"><thead>';
    $ret .= '<th>' . '&nbsp;' . '</th>';
    if(!isset($ct_intentnr_column_remove)){
    $ret .= '<th>' . __('not set', CT_SLUG) . '</th>';    
    }
    $ret .= '<th>' . __('Ident-Nr.', CT_SLUG) . '</th>';
    $ret .= '<th>' . __('Author', CT_SLUG) . '</th>';
    $ret .= '<th>' . __('Publisher', CT_SLUG) . '</th>';
    $ret .= '<th>' . __('Copyright', CT_SLUG) . '</th>';
    $ret .= '<th>' . __('License', CT_SLUG) . '</th>';
    $ret .= '</thead><tbody>';

    if (empty($images)) {
        $ret .= '<tr class="credit-tracker-row"><td colspan="6" class="credit-tracker-column-empty">' . __('No images found', CT_SLUG) . '</td></tr>';
    }

    foreach ($images as $image) {
        if (!empty($image['author'])) {
            $ct_copyright_format = ct_get_source_copyright($image['source']);
            if (empty($ct_copyright_format)) {
                $ct_copyright_format = ct_get_single_option('ct_copyright_format');
            }

            $ret .= '<tr>';
            $ret .= '<td>' . '<img width="' . $image['width'] . '" height="' . $image['height'] . '" src="' . $image['url'] . '" class="attachment-thumbnail" alt="' . $image['alt'] . '">' . '</td>';
            $ret .= '<td>' . $image['ident_nr'] . '</td>';
            $ret .= '<td>' . $image['author'] . '</td>';
            $ret .= '<td>' . $image['publisher'] . '</td>';
            $ret .= '<td>' . ct_process_item_copyright($image, $ct_copyright_format) . '</td>';
            $ret .= '<td>' . $image['license'] . '</td>';
            $ret .= '</tr>';
        }
    }

    $ret .= '</tbody></table>';
    return $ret;

}

function credit_tracker_caption_shortcode_filter($val, $attr, $content = null)
{
    extract(shortcode_atts(
            array(
                'id' => '',
                'align' => 'aligncenter',
                'width' => '',
                'caption' => '',
                'text' => '',
                'type' => 'caption'
            ), $attr)
    );

    $ct_override_caption_shortcode = ct_get_single_option('ct_override_caption_shortcode');
    if ((bool)$ct_override_caption_shortcode) {

        $id_orig = $id;
        if ($id) {
            $id = esc_attr($id);
        }

        // extract attachment id
        preg_match("/\d+/", $id, $matches);
        if (!empty($matches)) {
            $id = $matches[0];
        }

        // find attachment
        $request = array(
            'size' => 'thumbnail',
            'include' => $id
        );
        $images = ct_get_images($request);
        if (empty($images)) {
            return $val;
        }
        $image = reset($images);

        $ct_copyright_format = ct_get_source_copyright($image['source']);
        if (empty($ct_copyright_format)) {
            $ct_copyright_format = ct_get_single_option('ct_copyright_format');
        }
        // override image caption via 'text' attribute
        if (!empty($text)) {
            $image['caption'] = $text;
        }
        $ct_copyright = htmlspecialchars_decode(ct_process_item_copyright($image, $ct_copyright_format));

        $content = str_replace('<img', '<img itemprop="contentUrl"', $content);

        $style = '';
        if ((int)$width > 0) {
            $style = 'style="width: ' . (int)$width . 'px"';
        }

        $ret = '<div id="' . $id_orig . '" class="wp-caption ' . esc_attr($align) . '" itemscope itemtype="http://schema.org/ImageObject" ' . $style . '>';
        $ret .= do_shortcode($content);
        $ret .= '<p class="wp-caption-text" itemprop="copyrightHolder">' . $ct_copyright . '</p>';
        $ret .= '<meta itemprop="name" content="' . $image['title'] . '">';
        $ret .= '<meta itemprop="caption" content="' . $image['caption'] . '">';
        $ret .= '<meta itemprop="author" content="' . $image['author'] . '">';
        $ret .= '<meta itemprop="publisher" content="' . $image['publisher'] . '">';
        $ret .= '</div>';

        return $ret;
    } else {
        return $val;
    }
}

?>
