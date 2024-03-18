<?php

/*
Plugin Name: Monocle
Description: Monocle is a client side utility to detect VPNs, proxies, residential proxies, malware proxies, and other types of anonymization technologies at a session level. This allows you to make blocking decisions on busy IPs.
Author: Spur
Version: 1.0.1
Requires at least: 5.3
Requires PHP:  7.4
Author URI: https://spur.us
License: GPLv3 or later
*/

/*
© Copyright 2023  Spur Intelligence Corp  ( https://spur.us )
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define('MONOCLE_DIR', plugin_dir_path(__FILE__));

function monocle_activate()
{
    register_uninstall_hook(__FILE__, 'monocle_uninstall');
}

register_activation_hook(__FILE__, 'monocle_activate');

function monocle_uninstall()
{
    delete_option('monocle_plugin_options');
}


// Add a menu for our option page
add_action('admin_menu', 'monocle_plugin_add_settings_menu');

function monocle_plugin_add_settings_menu()
{
    add_options_page(
        'Monocle Plugin Settings', 'Monocle', 'manage_options',
        'monocle_plugin', 'monocle_plugin_option_page'
    );
}

// Create the option page
function monocle_plugin_option_page()
{
    ?>
    <div class="wrap">
        <h2>Monocle</h2>
        <form action="options.php" method="post">
            <?php
            settings_fields('monocle_plugin_options');
            do_settings_sections('monocle_plugin');
            submit_button('Save Changes', 'primary');
            ?>
        </form>
    </div>
    <?php
}

// Register and define the settings
add_action('admin_init', 'monocle_plugin_admin_init');

function monocle_plugin_admin_init()
{
    $args = array(
        'type' => 'string',
        'sanitize_callback' => 'monocle_plugin_validate_options',
        'default' => null
    );

    // Register our settings
    register_setting('monocle_plugin_options', 'monocle_plugin_options', $args);

    // Add a settings section
    add_settings_section(
        'monocle_plugin_main',
        'Monocle Plugin Settings',
        'monocle_plugin_section_text',
        'monocle_plugin'
    );

    // Create our settings field for site token
    add_settings_field(
        'monocle_plugin_site_token',
        'Site Token',
        'monocle_plugin_setting_site_token',
        'monocle_plugin',
        'monocle_plugin_main'
    );

    // Create our settings field for decrypt token
    add_settings_field(
        'monocle_plugin_decrypt_token',
        'API Token',
        'monocle_plugin_setting_decrypt_token',
        'monocle_plugin',
        'monocle_plugin_main'
    );

    // Create our strictness settings
    add_settings_field(
        'monocle_plugin_strictness',
        'Strictness Level',
        'monocle_plugin_setting_strictness',
        'monocle_plugin',
        'monocle_plugin_main'
    );

    // Create our error message
    add_settings_field(
        'monocle_plugin_error_message',
        'Form Error Message',
        'monocle_plugin_setting_error_message',
        'monocle_plugin',
        'monocle_plugin_main'
    );

}

// Draw the section header
function monocle_plugin_section_text()
{
    echo '<p>Create an account and sign in at Spur. (<a href="https://spur.us" target="_blank">https://spur.us</a>)';
    echo '<br>Tokens, documentation, and usage dashboard can be found in the <a href="https://app.spur.us/monocle" target="_blank">Monocle</a> menu.';
    echo '<br>The distinction between a VPN and Residential Proxy is discussed here: <a href="https://spur.us/what-is-a-residential-proxy/" target="_blank">What is a residential Proxy?</a></p>';
}

// Display and fill the site token form field
function monocle_plugin_setting_site_token()
{
    // get option 'text_string' value from the database
    $options = get_option('monocle_plugin_options');
    $site_token = "";
    if ($options != null && array_key_exists('site_token', $options)) {
        $site_token = $options['site_token'];
    }

    // echo the field
    echo "<textarea rows='6' cols='80' id='site_token' name='monocle_plugin_options[site_token]'>" . esc_attr($site_token) . '</textarea>';
}

// Display and fill the decrypt token form field
function monocle_plugin_setting_decrypt_token()
{
    // get option 'text_string' value from the database
    $options = get_option('monocle_plugin_options');
    $decrypt_token = "";
    if ($options != null && array_key_exists('decrypt_token', $options)) {
        $decrypt_token = $options['decrypt_token'];
    }

    // echo the field
    echo "<input id='decrypt_token' name='monocle_plugin_options[decrypt_token]'
    type='text' size='40' value='" . esc_attr($decrypt_token) . "'/>";
}

// Display and fill the strictness form field
function monocle_plugin_setting_strictness()
{
    // get option 'text_string' value from the database
    $options = get_option('monocle_plugin_options');
    $strictness = "log";
    if ($options != null && array_key_exists('strictness', $options)) {
        $strictness = $options['strictness'];
    }

    $vpnSelected = $strictness == 'vpn' ? "selected" : "";
    $proxySelected = $strictness == 'proxy' ? "selected" : "";
    $allSelected = $strictness == 'all' ? "selected" : "";
    $logSelected = $strictness == 'log' ? "selected" : "";

    // echo the field
    echo '<select name="monocle_plugin_options[strictness]" id="strictness">';
    echo "<option value='vpn'" . esc_html($vpnSelected) . ">No VPNs</option>";
    echo "<option value='proxy'" . esc_html($proxySelected) . ">No Residential Proxies</option>";
    echo "<option value='all'" . esc_html($allSelected) . ">No VPNs or Residential Proxies</option>";
    echo "<option value='log'" . esc_html($logSelected) . ">Log Only</option>";
    echo '</select>';
}

// Display and fill the error message form field
function monocle_plugin_setting_error_message()
{
    // get option 'text_string' value from the database
    $options = get_option('monocle_plugin_options');
    // default message to blocked users
    $errorMessage = "Our systems have detected unusual traffic from your computer network.";
    if ($options != null && array_key_exists('error_message', $options)) {
        $errorMessage = $options['error_message'];
    }

    // echo the field
    echo "<textarea rows='2' cols='80' id='error_message' name='monocle_plugin_options[error_message]'>" . esc_attr($errorMessage) . '</textarea>';
}

// Validate user input
function monocle_plugin_validate_options($input): array
{
    // nothing to do here, monocle won't load if you put in the wrong site_token
    $valid = array();
    $valid['site_token'] = $input['site_token'];
    $valid['decrypt_token'] = $input['decrypt_token'];
    $valid['strictness'] = $input['strictness'];
    $valid['error_message'] = $input['error_message'];
    return $valid;
}

// Add nonce to forms
function monocle_form_nonce()
{
    wp_nonce_field( 'monocle-form', 'monocle-nonce' );
}

add_action('login_form_top', 'monocle_form_nonce');
add_action('comment_form_top', 'monocle_form_nonce');
add_action('lostpassword_form', 'monocle_form_nonce');
add_action('register_form', 'monocle_form_nonce');

// Add the monocle javascript to login page
function monocle_enqueue_script()
{
    $options = get_option('monocle_plugin_options');
    if ($options != null && array_key_exists('site_token', $options)) {
        $site_token = $options['site_token'];
        wp_enqueue_script('monocle', "https://mcl.spur.us/d/mcl.js?tk=$site_token", false, 1.0, false);
    }
}

add_action('login_enqueue_scripts', 'monocle_enqueue_script');
add_action('wp_enqueue_scripts', 'monocle_enqueue_script');

// Add the monocle class to login/register form
function monocle_add_login_js()
{
    wp_enqueue_script('monocle-js', plugins_url('assets/js/add-monocle-class-login.js', __FILE__), array('jquery'), 1.0, true);
}

add_action('login_enqueue_scripts', 'monocle_add_login_js');
add_action('wp_enqueue_scripts', 'monocle_add_login_js');

function monocle_decrypt_bundle_api($threatBundle): string
{
    $options = get_option('monocle_plugin_options');
    if ($options == null || !array_key_exists('decrypt_token', $options)) {
        return "";
    }
    $decrypt_token = $options['decrypt_token'];

    // decrypt the assessment bundle
    $url = "https://decrypt.mcl.spur.us/api/v1/assessment";
    $response = wp_remote_post($url, array(
        'method' => 'POST',
        'headers'   => [
			'content-type' => 'text/plain; charset=utf-8',
			'TOKEN'     => $decrypt_token,
		],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify' => true,
        'body' => $threatBundle)
    );

    if (is_wp_error($response)) {
        error_log($response->get_error_message());
        return "";
    }
    elseif ($response['response']['code'] != 200 && $response['response']['code'] != 201) {
        error_log(wp_json_encode($response));
        return "";
    }
    else {
         return wp_remote_retrieve_body($response);
    }
}

function monocle_get_decoded_bundle($bundle): array
{
    // If we didn't get a bundle, deny registration
    if (empty($bundle)) {
        error_log("bundle was empty");
        return array();
    }

    $decrypted_bundle = monocle_decrypt_bundle_api($bundle);
    if (empty($decrypted_bundle)) {
        error_log("unable to decrypt bundle");
        return array();
    }

    $decoded_bundle = json_decode($decrypted_bundle, true);
    if (empty($decoded_bundle)) {
        error_log("unable to decode bundle");
        return array();
    }
    
    return $decoded_bundle;
}

function monocle_should_block($decoded_bundle): bool
{
    $options = get_option('monocle_plugin_options');
    $strictness = "log";
    if ($options != null && array_key_exists('strictness', $options)) {
        $strictness = $options['strictness'];
    }

    error_log(wp_json_encode($decoded_bundle));

    $proxy = false;
    $vpn = false;
    $anon = false;

    if (array_key_exists('proxy', $decoded_bundle)) {
        $proxy = $decoded_bundle['proxy'];
    }

    if (array_key_exists('vpn', $decoded_bundle)) {
        $vpn = $decoded_bundle['vpn'];
    }

    if (array_key_exists('anon', $decoded_bundle)) {
        $anon = $decoded_bundle['anon'];
    }

    switch ($strictness) {
    case "vpn":
        return $vpn === true && $anon === true;
    case "proxy":
        return $proxy === true && $anon === true;
    case "all":
        return ($proxy === true || $vpn === true) && $anon === true;
    }

    return false;
}

// Do monocle check on registrations
function monocle_check_registration_fields($errors, $sanitized_user_login, $user_email)
{
    if ( ! isset( $_POST['monocle-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['monocle-nonce'] ) ) , 'monocle-form' ) ) {
        $errors->add('monocle_nonce_error', __('<strong>ERROR</strong>: Invalid security nonce', 'monocle'));
        return $errors;
    }

    $bundle = sanitize_text_field( wp_unslash ( $_POST['monocle'] ));
    $decoded_bundle = monocle_get_decoded_bundle($bundle);
    if (empty($decoded_bundle)) {
        $errors->add('monocle_no_bundle_error', __('<strong>ERROR</strong>: Unable to validate with Monocle', 'monocle'));
        return $errors;
    }

    if(monocle_should_block($decoded_bundle)) {
        $options = get_option('monocle_plugin_options');
        $msg = "Monocle check failed";
        if ($options != null && array_key_exists('error_message', $options)) {
            $msg = $options['error_message'];
        }
        /* translators: %s: Custom error message from admin page */
        $tmsg = sprintf(__("<strong>ERROR</strong>: %s", 'monocle'), $msg);
        $errors->add('monocle_no_bundle_error', $tmsg);
    }

    return $errors;
}

add_filter('registration_errors', 'monocle_check_registration_fields', 10, 3);

function monocle_check_login_fields($error)
{
    if ( ! isset( $_POST['monocle-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['monocle-nonce'] ) ) , 'monocle-form' ) ) {
        return "<strong>ERROR</strong>: Invalid security nonce";
    }

    if(!array_key_exists('monocle', $_POST)) {
        return "<strong>ERROR</strong>: Unable to validate with Monocle";
    }

    $bundle = sanitize_text_field( wp_unslash ( $_POST['monocle'] ));
    $decoded_bundle = monocle_get_decoded_bundle($bundle);
    if(monocle_should_block($decoded_bundle)) {
        $options = get_option('monocle_plugin_options');
        $msg = "Monocle check failed";
        if ($options != null && array_key_exists('error_message', $options)) {
            $msg = $options['error_message'];
        }

        $error = "<strong>ERROR</strong>: $msg";
    }

    return $error;
}

add_filter('login_errors', 'monocle_check_login_fields', 10, 1);

function monocle_check_comment()
{
    if ( ! isset( $_POST['monocle-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['monocle-nonce'] ) ) , 'monocle-form' ) ) {
        wp_die('Invalid security nonce');
    }

    if(!array_key_exists('monocle', $_POST)) {
        wp_die('ERROR: Monocle check failed');
    }
    
    $bundle = sanitize_text_field( wp_unslash ( $_POST['monocle'] ));
    $decoded_bundle = monocle_get_decoded_bundle($bundle);
    if(monocle_should_block($decoded_bundle)) {
        $options = get_option('monocle_plugin_options');
        $msg = "Monocle check failed";
        if ($options != null && array_key_exists('error_message', $options)) {
            $msg = $options['error_message'];
        }
        /* translators: %s: Custom error message from admin page */
        $tmsg = sprintf(__("<strong>ERROR</strong>: %s", 'monocle'), $msg);
        wp_die(wp_kses($tmsg, "strong"));
    }
}

add_filter('pre_comment_on_post', 'monocle_check_comment');
