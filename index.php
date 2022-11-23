<?php

/**
 * Plugin Name:       Woo Checkout Address Autocomplete
 * Plugin URI:        https://jhakim.com
 * Description:       Increase converison with Google Address Autocomplete at checkout for billing and shipping address fields.
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Jerrick Hakim
 * Author URI:        https://jhakim.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://jhakim.com
 * Text Domain:       woo-checkout-address-autocomplete
 * Domain Path:       /languages
 */


/**
 *  Class WooCheckoutAddressAutocomplete
 */

class WooCheckoutAddressAutocomplete
{

    /**
     *  Begin Plugin
     */

    function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'load_scripts']);

        add_action('admin_menu', [$this, 'register_settings_page']);
        add_action('wp_head', [$this, 'header']);
        add_action('admin_init', [$this, 'register_settings']);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'action_links']);
    }


    /**
     *  Add to header
     */
    function header()
    {

        $options = get_option('woo_address_autocomplete_options');
        echo "<script src='https://maps.googleapis.com/maps/api/js?key=$options[api_key]&libraries=places'></script>";
    }


    /**
     *  Load scripts
     */

    function load_scripts()
    {

        wp_enqueue_script(
            'WooCheckoutAddressAutocompleteScript',
            plugin_dir_url(__FILE__) . '/js/index.js',
            array(),
            '1.0.0',
            true
        );
    }

    /**
     *  Register settings page
     */

    function register_settings_page()
    {
        add_options_page('Address Autocomplete', 'Address Autocomplete', 'manage_options', 'woo_address_autocomplete', [$this, 'show_admin_page']);
    }

    /**
     *  Show admin page
     */

    function show_admin_page()
    {
?>
        <h1>Woo Address Autocomplete Settings</h1>
        <div class="wrap">

            <hr />
            <form action="options.php" method="post">
                <?php

                do_settings_sections('woo_address_autocomplete');
                settings_fields('woo_address_autocomplete_options');
                ?>
                <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
            </form>
        </div>
    <?php

    }

    /**
     *  Register settings
     */

    function register_settings()
    {
        register_setting('woo_address_autocomplete_options', 'woo_address_autocomplete_options', 'woo_address_autocomplete_options_validate');
        add_settings_section('api_settings', 'Woo Address Autocomplete Settings', [$this, 'woo_address_setup_text'], 'woo_address_autocomplete');
        add_settings_field('woo_address_autocomplete_api_key', 'API Key', [$this, 'woo_address_autocomplete_api_key'], 'woo_address_autocomplete', 'api_settings');
    }


    /**
     *  Register settings
     */


    function woo_address_setup_text()
    {
    ?>

        <p><a href="https://console.cloud.google.com/marketplace/product/google/places-backend.googleapis.com" target="_blank">1. Enable the Places API</a></p>
        <p><a href="https://console.cloud.google.com/marketplace/product/google/maps-backend.googleapis.com" target="_blank">2. Enable the Maps API</a></p>
        <p><a href="https://console.cloud.google.com/google/maps-apis/credentials" target="_blank">3. Click here to create your API Key</a></p>
        <p><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">For more help please refrence Google's Docs</a></p>
        <p><a href="https://www.paypal.com/donate/?hosted_button_id=HMVBT83KDMTMY" target="_blank">Link this free plugin? Buy me coffee? It helps!</a></p>



<?php

    }


    /**
     *  Input for API Key
     */

    function woo_address_autocomplete_api_key()
    {
        $options = get_option('woo_address_autocomplete_options');
        echo "<input id='woo_address_autocomplete_api_key' name='woo_address_autocomplete_options[api_key]' type='password' value='" . esc_attr($options['api_key']) . "' />";
    }

    /**
     *  Action links for quick settings access
     */

    function action_links($actions)
    {
        $mylinks = array(
            '<a href="' . admin_url('options-general.php?page=woo_address_autocomplete') . '">Settings</a>',
        );
        $actions = array_merge($actions, $mylinks);
        return $actions;
    }
}

new WooCheckoutAddressAutocomplete;
