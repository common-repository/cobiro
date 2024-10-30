<?php
/**
 * Installation related functions and actions.
 *
 * @version 1.0.0
 */
defined('ABSPATH') || exit;

/**
 * Cobiro_Install Class.
 */
class Cobiro_Uninstall
{
    /**
     * Hook in tabs.
     *
     * @return void
     */
    public static function init()
    {
        add_action('init', [__CLASS__, 'uninstall'], 5);
    }

    /**
     * Install Cobiro.
     *
     * @return void
     */
    public static function uninstall()
    {
        if (!is_blog_installed()) {
            return;
        }

        if (!class_exists('WooCommerce')) {
            return;
        }

        // Check if we are not already running this routine.
        if ('yes' === get_transient('cobiro_uninstalling')) {
            return;
        }

        // If we made it till here nothing is running yet, lets set the transient now.
        set_transient('cobiro_uninstalling', 'yes', MINUTE_IN_SECONDS * 10);
        wc_maybe_define_constant('COBIRO_UNINSTALLING', true);

        self::delete_api_key();
        self::delete_options();

        delete_transient('cobiro_uninstalling');

        do_action('cobiro_uninstalling');
    }

    /**
     * @return void
     */
    private static function delete_api_key()
    {
        global $wpdb;

        $data = [
            'description' => 'Cobiro',
            'permissions' => 'read',
        ];

        $wpdb->delete($wpdb->prefix . 'woocommerce_api_keys', $data, ['%d', '%s', '%s']);
    }

    /**
     * Delete default options.
     *
     * @return void
     */
    private static function delete_options()
    {
        delete_option('cobiro_gmc');
        delete_option('cobiro_gtm_id');
        delete_option('cobiro_gtm_label');
        delete_option('cobiro_version');
    }
}

Cobiro_Uninstall::init();
