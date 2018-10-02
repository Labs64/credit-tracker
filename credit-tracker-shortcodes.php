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
add_filter('img_caption_shortcode', 'credit_tracker_image_caption_shortcode_filter', 10, 3);
add_filter('post_gallery', 'credit_tracker_gallery_caption_shortcode_filter', 10, 3);
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

function credit_tracker_image_caption_shortcode_filter($val, $attr, $content = null)
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
  } else {
  	return $html;
  }
}

/*MOD
This function generates the captions for all the images in a gallery.
Major part of the code is simply a slightly modified copy of gallery_shortcode function in WP media.php (lines 1665-1795 and 1801-1815)
Its modified lines are marked in the code*/
function credit_tracker_gallery_caption_shortcode_filter($output, $attr, $instance)
{

    $ct_override_caption_shortcode = credittracker_get_single_option('ct_override_caption_shortcode');
    if ((bool)$ct_override_caption_shortcode) {


	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}



	$html5 = current_theme_supports( 'html5', 'gallery' );
	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => $html5 ? 'figure'     : 'dl',
		'icontag'    => $html5 ? 'div'        : 'dt',
		'captiontag' => $html5 ? 'figcaption' : 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$ret = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$ret .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}
		return $ret;
	}

	$itemtag = tag_escape( $atts['itemtag'] );
	$captiontag = tag_escape( $atts['captiontag'] );
	$icontag = tag_escape( $atts['icontag'] );
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) ) {
		$itemtag = 'dl';
	}
	if ( ! isset( $valid_tags[ $captiontag ] ) ) {
		$captiontag = 'dd';
	}
	if ( ! isset( $valid_tags[ $icontag ] ) ) {
		$icontag = 'dt';
	}

	$columns = intval( $atts['columns'] );
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = '';

	/**
	 * Filters whether to print default gallery styles.
	 *
	 * @since 3.1.0
	 *
	 * @param bool $print Whether to print default gallery styles.
	 *                    Defaults to false if the theme supports HTML5 galleries.
	 *                    Otherwise, defaults to true.
	 */

		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";


	$size_class = sanitize_html_class( $atts['size'] );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

	/**
	 * Filters the default gallery shortcode CSS styles.
	 *
	 * @since 2.5.0
	 *
	 * @param string $gallery_style Default CSS styles and opening HTML div container
	 *                              for the gallery shortcode output.
	 */
	$ret = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {

		$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
		if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
			$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
		} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
			$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
		} else {
			$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
		}
		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
		}
		$ret .= "<{$itemtag} class='gallery-item'>";
		$ret .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {

			/*Modified part that outputs the caption text itself*/
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

			// override image caption via 'text' attribute
			if (!empty($text)) {
				$image['caption'] = $text;
			}


			$ct_copyright_format = credittracker_get_source_copyright($image['source']);
			if (empty($ct_copyright_format)) {
				$ct_copyright_format = credittracker_get_single_option('ct_copyright_format');
			}
			$ct_copyright = htmlspecialchars_decode(credittracker_process_item_copyright($image, $ct_copyright_format));
			$ret .= "
				<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
				" . $ct_copyright . "
				</{$captiontag}>";
		}

		/*identical to media.php of WP*/
		$ret .= "</{$itemtag}>";
		if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
			$ret .= '<br style="clear: both" />';
		}
	}

	if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
		$ret .= "
			<br style='clear: both' />";
	}

	$ret .= "</div>\n";


        return $ret;
		/**/

    } else {
        return $output;
    }
}
?>
