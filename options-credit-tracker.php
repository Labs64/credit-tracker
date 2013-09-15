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

function credit_tracker_options_page()
{
    if (!current_user_can('manage_options')) {
        ?>
        <div class="error"><p><strong><?php _e('You do not have sufficient permissions to edit this site.'); ?></strong>
            </p></div>
        <?php wp_die();
    }

    // save options
    if (isset($_POST['credit_tracker_update_options'])) {

        // Don't save if the user hasn't submitted the changes
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verify that the input is coming from the proper form
        if (!wp_verify_nonce($_POST['credit_tracker_nonce'], plugin_basename(__FILE__))) {
            return;
        }

        $author_l = '';
        $publisher_l = '';
        $ident_nr_l = '';
        $license_l = '';

        $_POST['credit_tracker_author_h'] = (isset($_POST['credit_tracker_author_h'])) ? 1 : 0;
        $author_h = $_POST['credit_tracker_author_h'];
        if (!$author_h) {
            $author_l = esc_attr($_POST['credit_tracker_author_l']);
        }

        $_POST['credit_tracker_publisher_h'] = (isset($_POST['credit_tracker_publisher_h'])) ? 1 : 0;
        $publisher_h = $_POST['credit_tracker_publisher_h'];
        if (!$publisher_h) {
            $publisher_l = esc_attr($_POST['credit_tracker_publisher_l']);
        }

        $_POST['credit_tracker_ident_nr_h'] = (isset($_POST['credit_tracker_ident_nr_h'])) ? 1 : 0;
        $ident_nr_h = $_POST['credit_tracker_ident_nr_h'];
        if (!$ident_nr_h) {
            $ident_nr_l = esc_attr($_POST['credit_tracker_ident_nr_l']);
        }

        $_POST['credit_tracker_license_h'] = (isset($_POST['credit_tracker_license_h'])) ? 1 : 0;
        $license_h = $_POST['credit_tracker_license_h'];
        if (!$license_h) {
            $license_l = esc_attr($_POST['credit_tracker_license_l']);
        }


        $save_options = array(
            'author' => array(
                'hide' => $author_h,
                'lbl' => $author_l
            ),
            'publisher' => array(
                'hide' => $publisher_h,
                'lbl' => $publisher_l
            ),
            'ident_nr' => array(
                'hide' => $ident_nr_h,
                'lbl' => $ident_nr_l
            ),
            'license' => array(
                'hide' => $license_h,
                'lbl' => $license_l
            )
        );

        update_option('credit_tracker_options', $save_options);

        ?>
        <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
    <?php
    }

    // delete options
    if (isset($_POST['credit_tracker_reset_options'])) {

        // Don't save if the user hasn't submitted the changes
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verify that the input is coming from the proper form
        if (!wp_verify_nonce($_POST['credit_tracker_nonce'], plugin_basename(__FILE__))) {
            return;
        }

        delete_option('credit_tracker_options');
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
    <?php
    }

    $options = credit_tracker_get_plugin_options();?>

    <div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <?php    echo "<h2>" . __('Credit Tracker', 'credit-tracker') . "</h2>";
    echo "<h4>" . __('Custom options. You can hide fields or customize field labels.', 'credit-tracker') . "</h4>";
    ?>

    <form name="credit_tracker-form" method="post"
          action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="credit_tracker_update_options" value="Y">

        <table class="form-table" style="width:500px;">
            <tbody>
            <?php
            $lbAuthor = __('Author', 'credit-tracker');
            $lbPublisher = __('Publisher', 'credit-tracker');
            $lbIdentNr = __('Ident-Nr.', 'credit-tracker');
            $lbLicense = __('License', 'credit-tracker');
            $lbLabel = __('Label', 'credit-tracker');
            $lbHide = __('Hide', 'credit-tracker');
            $n = 'credit_tracker_';

            echo credit_tracker_tr_lbl($lbAuthor . ' - ' . $lbLabel, $options['author']['lbl'], '', $n . 'author_l');
            echo credit_tracker_tr_chk($lbAuthor, $options['author']['hide'], '', $n . 'author_h', $lbHide);
            echo credit_tracker_tr_hdivider();

            echo credit_tracker_tr_lbl($lbPublisher . ' - ' . $lbLabel, $options['publisher']['lbl'], '', $n . 'publisher_l');
            echo credit_tracker_tr_chk($lbPublisher, $options['publisher']['hide'], '', $n . 'publisher_h', $lbHide);
            echo credit_tracker_tr_hdivider();

            echo credit_tracker_tr_lbl($lbIdentNr . ' - ' . $lbLabel, $options['ident_nr']['lbl'], '', $n . 'ident_nr_l');
            echo credit_tracker_tr_chk($lbIdentNr, $options['ident_nr']['hide'], '', $n . 'ident_nr_h', $lbHide);
            echo credit_tracker_tr_hdivider();

            echo credit_tracker_tr_lbl($lbLicense . ' - ' . $lbLabel, $options['license']['lbl'], '', $n . 'license_l');
            echo credit_tracker_tr_chk($lbLicense, $options['license']['hide'], '', $n . 'license_h', $lbHide);
            echo credit_tracker_tr_hdivider();
            ?>
            </tbody>
        </table>

        <?php wp_nonce_field(plugin_basename(__FILE__), 'credit_tracker_nonce'); ?>

        <p class="submit"><input class="button button-primary" type="submit" name="Submit"
                                 value="<?php _e('Update Options', 'credit-tracker') ?>"/></p>
    </form>

    <form name="credit_tracker-form" method="post"
          action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="credit_tracker_reset_options" value="Y">
        <?php wp_nonce_field(plugin_basename(__FILE__), 'credit_tracker_nonce'); ?>
        <p class="submit">
            <input class="button button-primary" type="submit" name="credit_tracker-reset"
                   value="<?php _e('Reset Options', 'credit-tracker') ?>"/>
        </p>
    </form>

    </div><?php
}

function credit_tracker_get_plugin_options()
{
    $options = array_merge(credit_tracker_default_options(), get_option('credit_tracker_options', array()));
    if (has_filter('credit_tracker_plugin_options')) {
        $options = apply_filters('credit_tracker_plugin_options', $options);
    }

    return wp_parse_args($options);
}

function credit_tracker_default_options()
{
    return array(
        'author' => array(
            'hide' => false,
            'lbl' => ''
        ),
        'publisher' => array(
            'hide' => false,
            'lbl' => ''
        ),
        'ident_nr' => array(
            'hide' => false,
            'lbl' => ''
        ),
        'license' => array(
            'hide' => false,
            'lbl' => ''
        )
    );
}

function credit_tracker_tr_hdivider()
{
    return '<tr valign="top"><td colspan="2"><div style="border-top-style: solid; border-width: 1px; border-color: #999999; width: 500px;"></div></td></tr>';
}

function credit_tracker_tr_lbl($lb, $value, $id, $name)
{
    return '<tr valign="top"><th scope="row"><label>' . $lb . '</label></th><td><input type="text" class="regular-text" value="' . $value .
    '" id="' . $id . '" name="' . $name . '" /></td></tr>';
}

function credit_tracker_tr_chk($lb1, $value, $id, $name, $lbHide)
{
    $checked = '';
    if ($value) {
        $checked = " checked=\"checked\" ";
    }

    return '<tr valign="top"><th scope="row">' . $lb1 . '</th><td><fieldset><label><input type="checkbox" value="' . $value .
    '" id="' . $id . '" name="' . $name . '"' . $checked . ' />' . $lbHide . '</label></fieldset></tr>';
}
