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

class Credit_Tracker_Options
{
    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var     string
     */
    const VERSION = '1.0.0';

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Unique identifier for your plugin.
     *
     * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
     * match the Text Domain file header in the main plugin file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_slug = 'credit-tracker';

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Start up
     */
    public function __construct()
    {
        // Add the options page and menu item.
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));

        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename(plugin_dir_path(__FILE__) . 'credit-tracker.php');
        add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links)
    {
        return array_merge(
            array(
                'settings' => '<a href="' . admin_url('options-general.php?page=credit-tracker') . '">' . __('Settings', $this->plugin_slug) . '</a>'
            ),
            $links
        );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        $this->plugin_screen_hook_suffix = add_options_page(
            __('Credit Tracker', $this->plugin_slug),
            __('Credit Tracker', $this->plugin_slug),
            'manage_options',
            $this->plugin_slug,
            array($this, 'create_admin_page')
        );
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles()
    {
        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->id == $this->plugin_screen_hook_suffix) {
            wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('css/admin.css', __FILE__), array(), self::VERSION);
        }

    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts()
    {
        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->id == $this->plugin_screen_hook_suffix) {
            wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('js/admin.js', __FILE__), array('jquery'), self::VERSION);
        }

    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('credit_tracker_option_name');
        ?>
        <div class="wrap">
            <a href="http://www.labs64.com" target="_blank" class="icon-labs64 icon32"></a>

            <h2><?php _e('Credit Tracker by Labs64', $this->plugin_slug); ?></h2>

            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('credit_tracker_option_group');
                do_settings_sections($this->plugin_slug);
                submit_button();
                ?>
            </form>

            <h3><?php _e('Feedback', 'credit-tracker'); ?></h3>

            <p><?php _e('Did you find a bug? Have an idea for a plugin? Please help us improve this plugin', 'credit-tracker'); ?>
                :</p>
            <ul>
                <li>
                    <a href="https://github.com/Labs64/credit-tracker/issues"
                       target="_blank"><?php _e('Report a bug, or suggest an improvement', 'credit-tracker'); ?></a>
                </li>
                <li><a href="http://www.facebook.com/labs64" target="_blank"><?php _e('Like us on Facebook'); ?></a>
                </li>
                <li><a href="http://www.labs64.com/blog" target="_blank"><?php _e('Read Labs64 Blog'); ?></a></li>
            </ul>
        </div>
    <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'credit_tracker_option_group', // Option group
            'credit_tracker_option_name', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            __('Credit Tracker Settings', $this->plugin_slug), // Title
            array($this, 'print_section_info'), // Callback
            $this->plugin_slug // Page
        );

        add_settings_field(
            'id_number', // ID
            __('Number', $this->plugin_slug), // Title
            array($this, 'id_number_callback'), // Callback
            $this->plugin_slug, // Page
            'setting_section_id' // Section           
        );

        add_settings_field(
            'copyright',
            __('Copyright format', $this->plugin_slug),
            array($this, 'copyright_callback'),
            $this->plugin_slug,
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        if (!is_numeric($input['id_number']))
            $input['id_number'] = '';

        if (!empty($input['copyright']))
            $input['copyright'] = sanitize_text_field($input['copyright']);

        return $input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print __('Enter your settings below:', $this->plugin_slug);
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="credit_tracker_option_name[id_number]" value="%s" />',
            esc_attr($this->options['id_number'])
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function copyright_callback()
    {
        printf(
            '<input type="text" id="copyright" name="credit_tracker_option_name[copyright]" value="%s" />',
            esc_attr($this->options['copyright'])
        );
    }
}

if (is_admin())
    $credit_tracker_options_page = new Credit_Tracker_Options();
