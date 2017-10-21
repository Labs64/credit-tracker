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
add_filter('post_thumbnail_html', 'creddit_tracker_thumbnail', 99, 3);

function credit_tracker_table_shortcode($atts)
{
    $columns_set_default = array(
        'ident_nr' => __('Ident-Nr.', CREDITTRACKER_SLUG),
        'author' => __('Author', CREDITTRACKER_SLUG),
        'publisher' => __('Publisher', CREDITTRACKER_SLUG),
        'copyright' => __('Copyright', CREDITTRACKER_SLUG),
        'license' => __('License', CREDITTRACKER_SLUG),
    );
    $columns_set_optional = array(
        'source' => __('Source', CREDITTRACKER_SLUG),
        'title' => __('Title', CREDITTRACKER_SLUG),
        'caption' => __('Caption', CREDITTRACKER_SLUG),
        'link' => __('Link', CREDITTRACKER_SLUG)
    );
    $columns_set_i18n = array_merge($columns_set_default, $columns_set_optional);

    $columns_set = implode(",", array_keys($columns_set_default));

    extract(shortcode_atts(
            array(
                'id' => '',
                'size' => 'thumbnail',
                'style' => 'default',
                'include_columns' => $columns_set,
                'only_current_post' => false,
            ), $atts)
    );

    if (empty($include_columns)) {
        $include_columns = $columns_set;
    }
    $columns = explode(",", $include_columns);
    foreach ($columns as $key => $value) {
        $columns[$key] = trim($columns[$key]);
    }

    if (is_numeric($size)) {
        $size = array($size, $size);
    } else if (stripos($size, 'x') !== false) {
        $size = explode('x', $size);
    }

    $request = array(
        'size' => $size,
        'include' => $id,
    );


    $request['only_current_post'] = isset( $atts['only_current_post'] ) ? boolval( $atts['only_current_post'] ) : false;

    $images = credittracker_get_images($request);

    $ret = '<table id="credit-tracker-table" class="credit-tracker-' . $style . '"><thead>';
    if ($size !== 'hidden') {
        $ret .= '<th>' . '&nbsp;' . '</th>';
    }

    foreach ($columns as $column) {
        if (!empty($column)) {
            $column_name = $columns_set_i18n[$column];
            if (empty($column_name)) {
                $column_name = $column;
            }
            $ret .= '<th>' . $column_name . '</th>';
        }
    }
    $ret .= '</thead><tbody>';

    if (empty($images)) {
        $ret .= '<tr class="credit-tracker-row"><td colspan="6" class="credit-tracker-column-empty">' . __('No images found', CREDITTRACKER_SLUG) . '</td></tr>';
    }

    foreach ($images as $image) {
        if (!empty($image['author']) or !empty($image['publisher'])) {
            $ct_copyright_format = credittracker_get_source_copyright($image['source']);
            if (empty($ct_copyright_format)) {
                $ct_copyright_format = credittracker_get_single_option('ct_copyright_format');
            }
            $ret .= '<tr>';
            if ($size == 'linkonly') {
                $imageName = $image['caption'];
                if (empty($imageName)) {
                    $imageName = $image['title'];
                }
                $ret .= '<td>' . '<a href="' . $image['url'] . '" class="attachment-linkonly" alt="' . $image['alt'] . '">' . $imageName . '</a>' . '</td>';
            } elseif ($size !== 'hidden') {
                $ret .= '<td>' . '<img width="' . $image['width'] . '" height="' . $image['height'] . '" src="' . $image['url'] . '" class="attachment-thumbnail" alt="' . $image['alt'] . '">' . '</td>';
            }

            foreach ($columns as $column) {
                if (!empty($column)) {
                    if ($column == 'copyright') {
                        $ret .= '<td>' . credittracker_process_item_copyright($image, $ct_copyright_format) . '</td>';
                    } else if ($column == 'source') {
                        $ret .= '<td>' . credittracker_get_source_caption($image[(string)$column], false) . '</td>';
                    } else {
                        $ret .= '<td>' . $image[(string)$column] . '</td>';
                    }
                }
            }
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

    $ct_override_caption_shortcode = credittracker_get_single_option('ct_override_caption_shortcode');
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
        $images = credittracker_get_images($request);
        if (empty($images)) {
            return $val;
        }
        $image = reset($images);

        $ct_copyright_format = credittracker_get_source_copyright($image['source']);
        if (empty($ct_copyright_format)) {
            $ct_copyright_format = credittracker_get_single_option('ct_copyright_format');
        }
        // override image caption via 'text' attribute
        if (!empty($text)) {
            $image['caption'] = $text;
        }
        $ct_copyright = htmlspecialchars_decode(credittracker_process_item_copyright($image, $ct_copyright_format));

        $content = str_replace('<img', '<img itemprop="contentUrl"', $content);
        $content = str_replace("%copyright%", $ct_copyright, $content);
        $content = credittracker_process_item_copyright($image, $content);

        $style = '';
        if ((int)$width > 0) {
            $style = 'style="width: ' . (int)$width . 'px"';
        }

        $ret = '<div id="' . $id_orig . '" class="wp-caption credit-tracker-caption ' . esc_attr($align) . '" itemscope itemtype="http://schema.org/ImageObject" ' . $style . '>';
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

/**
 * Filters the post thumbnail markup to add the 'credit' information as figure/ficaption markup.
 *
 * @param string $html       The post thumbnail HTML
 * @param $post_id           The post ID, currently unused.
 * @param $post_thumbnail_id The post thumbnail ID, used to get the attachment meta fields.
 *
 * @return string The modified markup for post thumbnail, if it contains an author for crediting.
 */
function creddit_tracker_thumbnail( $html, $post_id, $post_thumbnail_id )
{
  $ct_override_caption_thumbnail = credittracker_get_single_option('ct_override_caption_thumbnail');
  if ((bool)$ct_override_caption_thumbnail and !empty($post_thumbnail_id)) {
    // Get the post_meta for the attachment post.
  	$image_post_meta = get_post_meta( $post_thumbnail_id );

  	// Get the attachment meta for the attachment post.
  	$attachment_meta = wp_get_attachment_metadata( $post_thumbnail_id );

  	// Add title and caption to processed_meta array.
  	$processed_meta = array(
  		'title'   => $attachment_meta['image_meta']['title'],
  		'caption' => $attachment_meta['image_meta']['caption'],
  	);

  	// Modify the meta so that it matches what is expected in the `credittracker_process_item_copyright`
  	foreach ( $image_post_meta as $key => $meta ) {
  		$new_key = substr( $key, 15 );
  		$processed_meta[ $new_key ] = $meta[0];
  	}

  	// Get the correct copyright text, as set by user in site option, for this thumbnail.
  	$ct_copyright_format = credittracker_get_single_option('ct_copyright_format' );
  	$ct_copyright        = htmlspecialchars_decode( credittracker_process_item_copyright( $processed_meta, $ct_copyright_format ) );

  	if ( ! empty( $ct_copyright ) ) {
  		$html = sprintf(
  			'<figure>
  				%1$s
  				<figcaption class="credit-tracker-thumbnail">%2$s</figcaption>
  			</figure>',
  			$html,
  			esc_html( $ct_copyright )
  		);
  	}
  	return $html;
  }
}

?>
