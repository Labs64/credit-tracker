<?php
/**
 * Plugin class.
 *
 * @package   Credit_Tracker
 * @author    Labs64 <info@labs64.com>
 * @license   GPL-2.0+
 * @link      http://www.labs64.com
 * @copyright 2013 Labs64
 */

class Credit_Tracker
{

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin by setting localization, filters, and administration functions.
     */
    private function __construct()
    {
        // Load plugin text domain
        add_action('init', array($this, 'load_plugin_textdomain'));

        // Activate plugin when new blog is added
        add_action('wpmu_new_blog', array($this, 'activate_new_site'));

        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
        add_filter('attachment_fields_to_edit', array($this, 'get_attachment_fields'), null, 2);
        add_filter('attachment_fields_to_save', array($this, 'save_attachment_fields'), null, 2);

        add_filter('manage_media_columns', array($this, 'credit_tracker_attachment_columns'), null, 2);
        add_action('manage_media_custom_column', array($this, 'credit_tracker_attachment_show_column'), null, 2);
    }

    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance()
    {
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @param    boolean $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public static function activate($network_wide)
    {
        if (function_exists('is_multisite') && is_multisite()) {
            if ($network_wide) {
                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {
                    switch_to_blog($blog_id);
                    self::single_activate();
                }
                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @param    boolean $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
     */
    public static function deactivate($network_wide)
    {
        if (function_exists('is_multisite') && is_multisite()) {
            if ($network_wide) {
                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {
                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }
                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @param    int $blog_id ID of the new blog.
     */
    public function activate_new_site($blog_id)
    {
        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @return    array|false    The blog ids, false if no matches.
     */
    private static function get_blog_ids()
    {
        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";
        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     */
    private static function single_activate()
    {
        // activation functionality
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     */
    private static function single_deactivate()
    {
        // deactivation functionality
    }

    /**
     * Load the plugin text domain for translation.
     */
    public function load_plugin_textdomain()
    {
        $domain = CREDITTRACKER_SLUG;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, basename(dirname(__FILE__)) . '/languages');
    }

    /**
     * Register and enqueue public-facing style sheet.
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(CREDITTRACKER_SLUG . '-plugin-styles', plugins_url('css/ct-public.css', __FILE__), array(), CREDITTRACKER_VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(CREDITTRACKER_SLUG . '-plugin-script', plugins_url('js/ct-public.js', __FILE__), array('jquery'), CREDITTRACKER_VERSION);
    }

    public function get_attachment_fields($form_fields, $post)
    {
        $selected_source = get_post_meta($post->ID, "credit-tracker-source", true);
        $ct_retriever_enabled = credittracker_get_single_option('ct_feature_retriever');

        $form_fields["credit-tracker-ident_nr"] = array(
            "label" => __('Ident-Nr.', CREDITTRACKER_SLUG),
            "input" => "text",
            "value" => get_post_meta($post->ID, "credit-tracker-ident_nr", true),
            "helps" => __("The original object number at the source", CREDITTRACKER_SLUG),
        );

        if ($ct_retriever_enabled == '1') {
            $btn_state = '';
            $link_activate = "";
        } else {
            $btn_state = 'disabled';
            $link_activate = "<a href='" . admin_url('options-general.php?page=credit-tracker') . "'>" . __("activate", CREDITTRACKER_SLUG) . "</a>";
        }

        // Note: only attachment (media edit) page is enabled at the moment for the media data retrieval
        $screen = get_current_screen();
        $ct_btn_allowed_screens = array("attachment");
        if (isset($screen) && in_array($screen->id, $ct_btn_allowed_screens)) {
            $btn_retriever_code = "&nbsp;&nbsp;<button id='ct-mediadata' type='button' " . $btn_state . ">" . __("GET MEDIA DATA", CREDITTRACKER_SLUG) . "</button>" . "&nbsp;" . $link_activate;
        } else {
            $btn_retriever_code = '';
        }

        $form_fields["credit-tracker-source"] = array(
            "label" => __('Source', CREDITTRACKER_SLUG),
            "input" => "html",
            "value" => $selected_source,
            "html" => "<select name='attachments[$post->ID][credit-tracker-source]' id='attachments-{$post->ID}-credit-tracker-source'>" . credittracker_get_combobox_options(credittracker_get_sources_names_array(), $selected_source) . "</select>" . $btn_retriever_code,
            "helps" => __("Source where to locate the original media", CREDITTRACKER_SLUG),
        );

        $form_fields["credit-tracker-author"] = array(
            "label" => __('Author', CREDITTRACKER_SLUG),
            "input" => "text",
            "value" => get_post_meta($post->ID, "credit-tracker-author", true),
            "helps" => __("Media author/owner", CREDITTRACKER_SLUG),
        );

        $form_fields["credit-tracker-publisher"] = array(
            "label" => __('Publisher', CREDITTRACKER_SLUG),
            "input" => "text",
            "value" => get_post_meta($post->ID, "credit-tracker-publisher", true),
            "helps" => __("Media publisher (e.g. image agency)", CREDITTRACKER_SLUG),
        );

        $form_fields["credit-tracker-license"] = array(
            "label" => __('License', CREDITTRACKER_SLUG),
            "input" => "text",
            "value" => get_post_meta($post->ID, "credit-tracker-license", true),
            "helps" => __("Media license", CREDITTRACKER_SLUG),
        );

        $form_fields["credit-tracker-link"] = array(
            "label" => __('Link', CREDITTRACKER_SLUG),
            "input" => "text",
            "value" => get_post_meta($post->ID, "credit-tracker-link", true),
            "helps" => __("Media link", CREDITTRACKER_SLUG),
        );

        return $form_fields;
    }

    public function save_attachment_fields($post, $attachment)
    {
        if (isset($attachment['credit-tracker-ident_nr'])) {
            update_post_meta($post['ID'], 'credit-tracker-ident_nr', $attachment['credit-tracker-ident_nr']);
        } else {
            delete_post_meta($post['ID'], 'credit-tracker-ident_nr');
        }

        if (isset($attachment['credit-tracker-source'])) {
            update_post_meta($post['ID'], 'credit-tracker-source', $attachment['credit-tracker-source']);
        } else {
            delete_post_meta($post['ID'], 'credit-tracker-source');
        }

        if (isset($attachment['credit-tracker-author'])) {
            update_post_meta($post['ID'], 'credit-tracker-author', $attachment['credit-tracker-author']);
        } else {
            delete_post_meta($post['ID'], 'credit-tracker-author');
        }

        if (isset($attachment['credit-tracker-publisher'])) {
            update_post_meta($post['ID'], 'credit-tracker-publisher', $attachment['credit-tracker-publisher']);
        } else {
            delete_post_meta($post['ID'], 'credit-tracker-publisher');
        }

        if (isset($attachment['credit-tracker-license'])) {
            update_post_meta($post['ID'], 'credit-tracker-license', $attachment['credit-tracker-license']);
        } else {
            delete_post_meta($post['ID'], 'credit-tracker-license');
        }

        if (isset($attachment['credit-tracker-link'])) {
            update_post_meta($post['ID'], 'credit-tracker-link', $attachment['credit-tracker-link']);
        } else {
            delete_post_meta($post['ID'], 'credit-tracker-link');
        }

        return $post;
    }

    function credit_tracker_attachment_columns($columns)
    {
        $columns['credit-tracker-ident_nr'] = __('Ident-Nr.', CREDITTRACKER_SLUG);
        $columns['credit-tracker-source'] = __('Source', CREDITTRACKER_SLUG);
        $columns['credit-tracker-author'] = __('Author', CREDITTRACKER_SLUG);
        return $columns;
    }

    function credit_tracker_attachment_show_column($name)
    {
        global $post;
        switch ($name) {
            case 'credit-tracker-ident_nr':
                $value = get_post_meta($post->ID, "credit-tracker-ident_nr", true);
                echo $value;
                break;
            case 'credit-tracker-source':
                $value = get_post_meta($post->ID, "credit-tracker-source", true);
                echo credittracker_get_source_caption($value);
                break;
            case 'credit-tracker-author':
                $value = get_post_meta($post->ID, "credit-tracker-author", true);
                echo $value;
                break;
        }
    }

}
