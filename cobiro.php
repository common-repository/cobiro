<?php

/**
 * Plugin Name: Cobiro
 * Plugin URI: https://wordpress.org/plugins/cobiro
 * Description: Free Google Ads Automation Module.
 * Version: 1.1.2
 * Author: Cobiro
 * Author URI: https://cobiro.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: cobiro
 * Domain Path: /languages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/*
 * Check if WooCommerce is active
 */
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {
    return;
}

// Define COBIRO_PLUGIN_FILE.
if (!defined('COBIRO_PLUGIN_FILE')) {
    define('COBIRO_PLUGIN_FILE', __FILE__);
}

// Include the main Cobiro class.
if (!class_exists('Cobiro')) {
    include_once __DIR__ . '/includes/class-cobiro.php';
}

/**
 * Main instance of Cobiro.
 *
 * Returns the main instance of Cobiro to prevent the need to use globals.
 *
 * @since  1.0.0
 *
 * @return Cobiro
 */
function cobiro()
{
    return Cobiro::instance();
}

// Global for backwards compatibility.
$GLOBALS['cobiro'] = cobiro();
