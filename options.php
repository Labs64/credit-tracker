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


define('CREDITTRACKER_OPTIONS', 'CREDITTRACKER_OPTIONS');
define('CREDITTRACKER_API_KEY', '31c7bc4e-90ff-44fb-9f07-b88eb06ed9dc');


if (is_admin()) {
    // Add the options page and menu item.
    add_action('admin_menu', 'credittracker_add_plugin_page');
    add_action('admin_init', 'credittracker_page_init');

    // Add an action link pointing to the options page.
    $plugin_basename = plugin_basename(plugin_dir_path(__FILE__) . 'credit-tracker.php');
    add_filter('plugin_action_links_' . $plugin_basename, 'credittracker_add_action_links');

    // Load admin style sheet and JavaScript.
    add_action('admin_enqueue_scripts', 'credittracker_enqueue_admin_styles');
    add_action('admin_enqueue_scripts', 'credittracker_enqueue_admin_scripts');

    // Get media data callback registration
    add_action('wp_ajax_validate', 'credittracker_validate_callback');
    add_action('wp_ajax_get_media_data', 'credittracker_get_media_data_callback');
}

/**
 * Add settings action link to the plugins page.
 */
function credittracker_add_action_links($links)
{
    return array_merge(
        array(
            'settings' => '<a href="' . admin_url('options-general.php?page=credit-tracker') . '">' . __('Settings', CREDITTRACKER_SLUG) . '</a>'
        ),
        $links
    );
}

/**
 * Add options page
 */
function credittracker_add_plugin_page()
{
    global $plugin_screen_hook_suffix;
    $plugin_screen_hook_suffix = add_options_page(
        __('Credit Tracker', CREDITTRACKER_SLUG),
        __('Credit Tracker', CREDITTRACKER_SLUG),
        'manage_options',
        CREDITTRACKER_SLUG,
        'credittracker_create_admin_page'
    );
}

/**
 * Register and enqueue admin-specific style sheet.
 *
 * @return    null    Return early if no settings page is registered.
 */
function credittracker_enqueue_admin_styles()
{
    global $plugin_screen_hook_suffix;

    if (!isset($plugin_screen_hook_suffix)) {
        return;
    }

    $screen = get_current_screen();
    $ct_allowed_screens = array($plugin_screen_hook_suffix, "attachment", "upload");
    if (isset($screen) && in_array($screen->id, $ct_allowed_screens)) {
        wp_enqueue_style(CREDITTRACKER_SLUG . '-admin-styles', plugins_url('css/ct-admin.css', __FILE__), array(), CREDITTRACKER_VERSION);
    }
}

/**
 * Register and enqueue admin-specific JavaScript.
 *
 * @return    null    Return early if no settings page is registered.
 */
function credittracker_enqueue_admin_scripts()
{
    global $plugin_screen_hook_suffix;

    if (!isset($plugin_screen_hook_suffix)) {
        return;
    }

    $screen = get_current_screen();
    $ct_allowed_screens = array($plugin_screen_hook_suffix, "attachment", "upload");
    if (isset($screen) && in_array($screen->id, $ct_allowed_screens)) {
        wp_enqueue_script(CREDITTRACKER_SLUG . '-admin-script', plugins_url('js/ct-admin.js', __FILE__), array('jquery'), CREDITTRACKER_VERSION);
    }
}

/**
 * Options page callback
 */
function credittracker_create_admin_page()
{
    ?>
    <div class="wrap credit-tracker" xmlns="http://www.w3.org/1999/html">
        <a href="http://www.labs64.com" target="_blank" class="icon-labs64 icon32"></a>

        <h2><?php _e('Credit Tracker by Labs64', CREDITTRACKER_SLUG); ?></h2>

        <form method="post" action="options.php">
            <?php
            // This prints out all hidden setting fields
            settings_fields('CREDITTRACKER_OPTIONS_GROUP');
            credittracker_settings_fields_hidden();
            do_settings_sections(CREDITTRACKER_SLUG);
            submit_button();
            ?>
        </form>
        <hr/>
        <?php
        credittracker_print_reference_section();
        ?>
    </div>
    <div class="info_menu">
        <?php
        credittracker_print_features_section();
        credittracker_print_divider();
        credittracker_print_feedback_section();
        ?>
    </div>
<?php
}

/**
 * Print sections divider
 */
function credittracker_print_divider()
{
    ?>
    <hr/>
<?php
}

/**
 * Print the Section info text
 */
function credittracker_get_on_off($opt)
{
    if ($opt == '1') {
        return "<span class='label-on'>ON</span>";
    } else {
        return "<span class='label-off'>OFF</span>";
    }
}

/**
 * Print the Common-Section info text
 */
function credittracker_print_common_section_info()
{
}

/**
 * Print the Retriever-Section info text
 */
function credittracker_print_retriever_section_info()
{
    print __('Some Image Data Retriever needs additional configuration', CREDITTRACKER_SLUG);
}

/**
 * Returns available plugin features
 */
function credittracker_get_features_array()
{
    $features = array(
        'ct_feature_retriever' => __('Image data retriever (Free)', CREDITTRACKER_SLUG)
    );
    return $features;
}

/**
 * Get features list.
 */
function credittracker_print_features_list($features)
{
    $ret = '<ul id="credittracker_features">';
    foreach ($features as $key => $value) {
        $ret .= '<li id="' . $key . '">&nbsp;' . $value . ' - ' . credittracker_get_on_off(credittracker_get_single_option($key)) . '</li>';
    }
    $ret .= '</ul>';
    print $ret;
}

/**
 * Print the features section
 */
function credittracker_print_features_section()
{
    $ct_feature_retriever = credittracker_get_single_option('ct_feature_retriever');

    ?>
    <h3><?php _e('Features', CREDITTRACKER_SLUG); ?></h3>
    <p><?php _e('Available plugin features', CREDITTRACKER_SLUG); ?>:</p>

    <?php credittracker_print_features_list(credittracker_get_features_array()); ?>

    <button id="ct-validate" type="button""><?php _e('Validate'); ?></button>
    <br/>
    <div style="font-style: italic; color: rgb(102, 102, 102); font-size: smaller;"><p>Powered by <a
                href="https://netlicensing.io/?utm_source=credit-tracker&utm_medium=wordpress_plugin&utm_campaign=credit-tracker&utm_content=wordpress_admin"
                target="_blank">NetLicensing</a></p>
    </div>
<?php
}

/**
 * Print the feedback section
 */
function credittracker_print_feedback_section()
{
    ?>
    <h3><?php _e('Feedback', CREDITTRACKER_SLUG); ?></h3>

    <p><?php _e('Did you find a bug? Have an idea for a plugin? Please help us improve this plugin', CREDITTRACKER_SLUG); ?>:</p>
    <ul>
        <li>
            <a href="https://github.com/Labs64/credit-tracker/issues"
               target="_blank"><?php _e('Report a bug, or suggest an improvement', CREDITTRACKER_SLUG); ?></a>
        </li>
        <li><a href="http://www.facebook.com/labs64" target="_blank"><?php _e('Like us on Facebook'); ?></a>
        </li>
        <li><a href="http://www.labs64.com/blog" target="_blank"><?php _e('Read Labs64 Blog'); ?></a></li>
    </ul>
<?php
}

/**
 * Print the reference section
 */
function credittracker_print_reference_section()
{
    ?>
    <h3><?php _e('Shortcodes Reference', CREDITTRACKER_SLUG); ?></h3>
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row">
                [caption]
            </th>
            <td>
                <p>Override WordPress [caption] shortcode.</p>

                <p><strong>Attributes:</strong></p>

                <p>&nbsp;&nbsp;<strong>id</strong> <i>(mandatory)</i> - Attachment ID.</p>

                <p>&nbsp;&nbsp;<strong>width</strong> <i>(optional)</i> - Caption area width in pixels.</p>

                <p>&nbsp;&nbsp;<strong>text</strong> <i>(optional)</i> - custom attribute to override standard media
                    caption. The
                    default behavior, if not specified standard media caption will be used.</p>
                <br/>

                <p><strong>Examples:</strong></p>

                <p><code>[caption id="attachment_11" width="111"]...[/caption]</code></p>

                <p>Override [caption] shortcode</p>
                <br/>

                <p><code>[caption id="11" text="image caption"]...[/caption]</code></p>

                <p>Override [caption] shortcode and use <i>text</i> instead of the standard media property</p>
                <br/>

                <p><code>[caption id="11"]&lt;a href="%link%"&gt;&lt;img src="11.png" alt="%caption%" title="%copyright%"/&gt;&lt;/a&gt;[/caption]</code></p>

                <p>Substitute media attribution within [caption] shortcode</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                [credit_tracker_table]
            </th>
            <td>
                <p>Generate 'Image Credits' table.</p>

                <p><strong>Attributes:</strong></p>

                <p>&nbsp;&nbsp;<strong>id</strong> <i>(optional)</i> - Attachment ID (one or more). The
                    default behavior, if no ID is specified, is to display all images containing author info.</p>

                <p>&nbsp;&nbsp;<strong>size</strong> <i>(optional)</i> - Image size to use for the thumbnail
                    display. Valid values include "hidden", "linkonly", "thumbnail", "medium", "large", "full" or numeric values (e.g. "50" or
                    "100x50").
                    The default value is "thumbnail".</p>

                <p>&nbsp;&nbsp;<strong>style</strong> <i>(optional)</i> - Table CSS style. Valid values
                    include "default", "mercury", "mars". The default value is "default".</p>

                <p>&nbsp;&nbsp;<strong>include_columns</strong> <i>(optional)</i> - Table columns order and visibility.
                    Valid values include "ident_nr", "author", "publisher", "copyright", "license".
                    The default value is "" - show all columns in the default order.</p>
                <br/>

                <p><strong>Examples:</strong></p>

                <p><code>[credit_tracker_table]</code></p>

                <p>Generate table for all images with non-empty attribute 'author' and small (thumbnail) preview
                    image</p>
                <br/>

                <p><code>[credit_tracker_table id="11,22,33" size="medium" style="mercury"]</code></p>

                <p>Generate table for with image ids (11, 22 and 33) and medium preview image. Table will be styled with
                    "mercury" CSS style</p>
                <br/>

                <p><code>[credit_tracker_table include_columns="ident_nr,copyright,license,author,publisher"]</code></p>

                <p>Generate table with the specified columns only and defined columns order</p>
            </td>
        </tr>
        </tbody>
    </table>
<?php
}

/**
 * Register and add settings
 */
function credittracker_page_init()
{
    register_setting(
        'CREDITTRACKER_OPTIONS_GROUP', // Option group
        CREDITTRACKER_OPTIONS, // Option name
        'credittracker_sanitize' // Sanitize
    );

    add_settings_section(
        'CREDITTRACKER_COMMON_SETTINGS', // ID
        __('Credit Tracker Settings', CREDITTRACKER_SLUG), // Title
        'credittracker_print_common_section_info', // Callback
        CREDITTRACKER_SLUG // Page
    );

    add_settings_section(
        'CREDITTRACKER_RETRIEVER_SETTINGS', // ID
        __('Retriever Settings', CREDITTRACKER_SLUG), // Title
        'credittracker_print_retriever_section_info', // Callback
        CREDITTRACKER_SLUG // Page
    );

    add_settings_field(
        'ct_copyright_format',
        __('Copyright format', CREDITTRACKER_SLUG),
        'credittracker_text_field_callback',
        CREDITTRACKER_SLUG,
        'CREDITTRACKER_COMMON_SETTINGS',
        array(
            'id' => 'ct_copyright_format',
            'description' => __('Default copyright format (HTML allowed)<br/>Allowed placeholders: %ident_nr%, %source%, %author%, %publisher%, %license%, %link%<br/>...as well as standard attributes: %title%, %caption%', CREDITTRACKER_SLUG),
        )
    );

    add_settings_field(
        'ct_override_caption_shortcode',
        __('Override shortcodes', CREDITTRACKER_SLUG),
        'credittracker_checkbox_field_callback',
        CREDITTRACKER_SLUG,
        'CREDITTRACKER_COMMON_SETTINGS',
        array(
            'id' => 'ct_override_caption_shortcode',
            'caption' => __('Override WordPress [caption] shortcode', CREDITTRACKER_SLUG),
            'description' => __('Replaces output of standard WordPress [caption] shortcode with improved version (add Image Microdata and Image Credit)<br/>Following placeholders can be used to substitute media attribution within [caption] shortcode: standard placeholders and %copyright%', CREDITTRACKER_SLUG),
        )
    );

    add_settings_field(
        'ct_override_caption_thumbnail',
        __('Thumbnail', CREDITTRACKER_SLUG),
        'credittracker_checkbox_field_callback',
        CREDITTRACKER_SLUG,
        'CREDITTRACKER_COMMON_SETTINGS',
        array(
            'id' => 'ct_override_caption_thumbnail',
            'caption' => __('Add credit to the post thumbnail (featured image)', CREDITTRACKER_SLUG),
            'description' => __('', CREDITTRACKER_SLUG),
        )
    );

    add_settings_field(
        'ct_auth_flickr_apikey',
        __('Flickr api_key', CREDITTRACKER_SLUG),
        'credittracker_text_field_callback',
        CREDITTRACKER_SLUG,
        'CREDITTRACKER_RETRIEVER_SETTINGS',
        array(
            'id' => 'ct_auth_flickr_apikey',
            'description' => __('To use the Flickr data retriever you need to have an Flickr API application key.' . ' <a href="https://www.flickr.com/services/api/misc.api_keys.html" target="_blank">See here</a>' . ' for more details.', CREDITTRACKER_SLUG),
        )
    );
}

/**
 * Sanitize each setting field as needed
 *
 * @param array $input Contains all settings fields as array keys
 */
function credittracker_sanitize($input)
{
    if (empty($input['ct_copyright_format'])) {
        if (is_admin()) {
            add_settings_error(CREDITTRACKER_OPTIONS, 'empty-copyright-format', 'Please specify copyright format.');
        }
    } else {
        $input['ct_copyright_format'] = $input['ct_copyright_format'];
    }

    if (isset($input['ct_auth_flickr_apikey'])) {
        $input['ct_auth_flickr_apikey'] = sanitize_text_field($input['ct_auth_flickr_apikey']);
    }

    return $input;
}

/**
 */
function credittracker_settings_fields_hidden()
{
    credittracker_print_settings_field_hidden('ct_feature_retriever');
}

/**
 */
function credittracker_print_settings_field_hidden($id)
{
    $value = credittracker_get_single_option($id);
    echo "<input type='hidden' id='$id' name='CREDITTRACKER_OPTIONS[$id]' value='$value' />";
}

/**
 */
function credittracker_text_field_callback($args)
{
    $id = $args['id'];
    $description = $args['description'];
    $value = credittracker_get_single_option($id);
    echo "<input type='text' id='$id' name='CREDITTRACKER_OPTIONS[$id]' value='$value' class='regular-text' />";
    echo "<p class='description'>$description</p>";
}

function credittracker_checkbox_field_callback($args)
{
    $id = $args['id'];
    $caption = $args['caption'];
    $description = $args['description'];
    $value = credittracker_get_single_option($id);
    echo "<input type='checkbox' id='$id' name='CREDITTRACKER_OPTIONS[$id]' value='1' class='code' " . checked(1, $value, false) . " /> $caption";
    echo "<p class='description'>$description</p>";
}

/**
 * Returns default options.
 * If you override the options here, be careful to use escape characters!
 */
function credittracker_get_default_options()
{
    $default_options = array(
        'ct_feature_retriever' => '0',
        'ct_copyright_format' => '&copy; %author%',
        'ct_auth_flickr_apikey' => '',
        'ct_override_caption_shortcode' => '0',
        'ct_override_caption_thumbnail' => '0'
    );
    return $default_options;
}

/**
 * Retrieves (and sanitises) options
 */
function credittracker_get_options()
{
    $options = credittracker_get_default_options();

    $stored_options = get_option(CREDITTRACKER_OPTIONS);
    if (empty($stored_options)) {
        // restore old options
        $stored_options = get_option('CT_OPTIONS');
    }

    if (!empty($stored_options)) {
        credittracker_sanitize($stored_options);
        $options = wp_parse_args($stored_options, $options);
    }
    update_option(CREDITTRACKER_OPTIONS, $options);
    return $options;
}

/**
 * Retrieves single option
 */
function credittracker_get_single_option($name)
{
    $options = credittracker_get_options();
    return $options[$name];
}

/**
 * Set single option value
 */
function credittracker_set_single_option($name, $value)
{
    $options = credittracker_get_options();
    $options[$name] = $value;
    update_option(CREDITTRACKER_OPTIONS, $options);
}

/**
 * Returns available sources meta
 */
function credittracker_get_sources_names_array()
{
    $names = array();

    foreach (credittracker_get_sources_array() as $k => $v) {
        $names[$k] = $v['caption'];
    }

    return $names;
}

/**
 * Returns source caption
 */
function credittracker_get_source_caption($source, $useSource = false)
{
    $sources = credittracker_get_sources_array();
    if (isset($sources[$source]) && !empty($sources[$source]['caption'])) {
        return $sources[$source]['caption'];
    } else {
        if ($useSource) {
            return $source;
        } else {
            return '';
        }
    }
}

/**
 * Returns source copyright format
 */
function credittracker_get_source_copyright($source)
{
    $sources = credittracker_get_sources_array();
    if (isset($sources[$source]) && !empty($sources[$source]['copyright'])) {
        return call_user_func($sources[$source]['copyright']);
    }
}

/**
 * Returns source metadata
 */
function credittracker_get_source_metadata($source, $number)
{
    $sources = credittracker_get_sources_array();
    if (isset($sources[$source]) && !empty($sources[$source]['retriever'])) {
        return call_user_func($sources[$source]['retriever'], $number);
    }
}

/**
 * Validate allowed features against Labs64 Netlicensing
 */
function credittracker_validate_callback()
{
    // validate features
    $nlic = new NetLicensing(CREDITTRACKER_API_KEY);
    $res = $nlic->validate('CT', credittracker_strip_url(get_site_url(), 1000), urlencode(get_site_url()));

    // NOTE: no NetLicensing response processing at the moment necessary; only product usage tracking functionality

    // update options
    credittracker_set_single_option('ct_feature_retriever', '1');

    // prepare return values
    $licenses = array(
        'netlicensing_response' => $res,
        'ct_feature_retriever' => credittracker_get_single_option('ct_feature_retriever')
    );
    echo json_encode($licenses);

    die(); // this is required to return a proper result
}

/**
 * Media data callback
 */
function credittracker_get_media_data_callback()
{
    $item = credittracker_get_source_metadata($_POST['source'], $_POST['ident_nr']);

    $mediadata = array(
        'source' => $_POST['source'],
        'ident_nr' => $_POST['ident_nr'],
        'author' => isset($item['author']) ? $item['author'] : '',
        'publisher' => $item['publisher'],
        'license' => $item['license'],
        'link' => $item['link']
    );

    echo json_encode($mediadata);

    die(); // this is required to return a proper result
}

/**
 * Returns available sources meta
 */
function credittracker_get_sources_array()
{
    $sources = array(
        'custom' => array(
            'caption' => '',
            'copyright' => '',
            'retriever' => ''
        ),
        'Fotolia' => array(
            'caption' => 'Fotolia',
            'copyright' => 'credittracker_get_fotolia_copyright',
            'retriever' => 'credittracker_get_fotolia_metadata'
        ),
        'iStockphoto' => array(
            'caption' => 'iStockphoto',
            'copyright' => 'credittracker_get_istockphoto_copyright',
            'retriever' => 'credittracker_get_istockphoto_metadata'
        ),
        'Shutterstock' => array(
            'caption' => 'Shutterstock',
            'copyright' => 'credittracker_get_shutterstock_copyright',
            'retriever' => 'credittracker_get_shutterstock_metadata'
        ),
        'Corbis_Images' => array(
            'caption' => 'Corbis Images',
            'copyright' => 'credittracker_get_corbis_images_copyright',
            'retriever' => 'credittracker_get_corbis_images_metadata'
        ),
        'Getty_Images' => array(
            'caption' => 'Getty Images',
            'copyright' => 'credittracker_get_getty_images_copyright',
            'retriever' => 'credittracker_get_getty_images_metadata'
        ),
        'pixelio' => array(
            'caption' => 'Pixelio',
            'copyright' => 'credittracker_get_pixelio_copyright',
            'retriever' => 'credittracker_get_pixelio_metadata'
        ),
        'flickr' => array(
            'caption' => 'Flickr',
            'copyright' => 'credittracker_get_flickr_copyright',
            'retriever' => 'credittracker_get_flickr_metadata'
        ),
        'freeimages' => array(
            'caption' => 'Freeimages',
            'copyright' => 'credittracker_get_freeimages_copyright',
            'retriever' => 'credittracker_get_freeimages_metadata'
        ),
        'wikimedia' => array(
            'caption' => 'Wikimedia',
            'copyright' => 'credittracker_get_wikimedia_copyright',
            'retriever' => 'credittracker_get_wikimedia_metadata'
        ),
        'unsplash' => array(
            'caption' => 'Unsplash',
            'copyright' => 'credittracker_get_unsplash_copyright',
            'retriever' => 'credittracker_get_unsplash_metadata'
        )
    );
    return $sources;
}

/**
 * Fotolia: copyright
 */
function credittracker_get_fotolia_copyright()
{
    return CTFotolia::COPYRIGHT;
}

/**
 * Fotolia: metadata
 */
function credittracker_get_fotolia_metadata($number)
{
    $parser = new CTFotolia();
    return $parser->execute($number);
}

/**
 * iStockphoto: copyright
 */
function credittracker_get_istockphoto_copyright()
{
    return CTIStockphoto::COPYRIGHT;
}

/**
 * iStockphoto: metadata
 */
function credittracker_get_istockphoto_metadata($number)
{
    $parser = new CTIStockphoto();
    return $parser->execute($number);
}

/**
 * Shutterstock: copyright
 */
function credittracker_get_shutterstock_copyright()
{
    return CTShutterstock::COPYRIGHT;
}

/**
 * Shutterstock: metadata
 */
function credittracker_get_shutterstock_metadata($number)
{
    $parser = new CTShutterstock();
    return $parser->execute($number);
}

/**
 * Corbis Images: copyright
 */
function credittracker_get_corbis_images_copyright()
{
    return '&copy; %author%/Corbis';
}

/**
 * Corbis Images: metadata
 */
function credittracker_get_corbis_images_metadata($number)
{
    $item = array();

    $item['source'] = 'Corbis Images';
    $item['author'] = '';
    $item['publisher'] = 'Corbis Images';
    $item['license'] = 'Royalty-free';
    $item['link'] = 'http://www.corbisimages.com';

    return $item;
}

/**
 * Getty Images: copyright
 */
function credittracker_get_getty_images_copyright()
{
    return '&copy; %author% / Getty Images';
}

/**
 * Getty Images: metadata
 */
function credittracker_get_getty_images_metadata($number)
{
    $item = array();

    $item['source'] = 'Getty Images';
    $item['author'] = '';
    $item['publisher'] = 'Getty Images';
    $item['license'] = 'Royalty-free';
    $item['link'] = 'http://www.gettyimages.com';

    return $item;
}

/**
 * Pixelio: copyright
 */
function credittracker_get_pixelio_copyright()
{
    return CTPixelio::COPYRIGHT;
}

/**
 * Pixelio: metadata
 */
function credittracker_get_pixelio_metadata($number)
{
    $parser = new CTPixelio();
    return $parser->execute($number);
}

/**
 * Flickr: copyright
 */
function credittracker_get_flickr_copyright()
{
    return CTFlickr::COPYRIGHT;
}

/**
 * Flickr: metadata
 */
function credittracker_get_flickr_metadata($number)
{
    $parser = new CTFlickr(credittracker_get_single_option('ct_auth_flickr_apikey'));
    return $parser->execute($number);
}

/**
 * Freeimages: copyright
 */
function credittracker_get_freeimages_copyright()
{
    return CTFreeimages::COPYRIGHT;
}

/**
 * Freeimages: metadata
 */
function credittracker_get_freeimages_metadata($number)
{
    $parser = new CTFreeimages();
    return $parser->execute($number);
}

/**
 * Wikimedia: copyright
 */
function credittracker_get_wikimedia_copyright()
{
    return 'By %author% [Creative Commons], via Wikimedia Commons';
}

/**
 * Wikimedia: metadata
 */
function credittracker_get_wikimedia_metadata($number)
{
    $item = array();

    $item['source'] = 'Wikimedia';
    $item['author'] = '';
    $item['publisher'] = 'Wikimedia';
    $item['license'] = 'Creative Commons';
    $item['link'] = 'https://commons.wikimedia.org';

    return $item;
}

/**
 * Unsplash: copyright
 */
function credittracker_get_unsplash_copyright()
{
    return CTUnsplash::COPYRIGHT;
}

/**
 * Unsplash: metadata
 */
function credittracker_get_unsplash_metadata($number)
{
    $parser = new CTUnsplash();
    return $parser->execute($number);
}

?>
