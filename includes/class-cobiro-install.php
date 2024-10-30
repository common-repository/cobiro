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
class Cobiro_Install
{
    /**
     * Hook in tabs.
     *
     * @return void
     */
    public static function init()
    {
        add_action('init', [__CLASS__, 'check_version'], 5);
    }

    /**
     * Check Cobiro version and run the updater is required.
     *
     * This check is done on all requests and runs if the versions do not match.
     *
     * @return void
     */
    public static function check_version()
    {
        if (!defined('IFRAME_REQUEST') && version_compare(get_option('cobiro_version'), cobiro()->version, '<')) {
            self::install();

            do_action('cobiro_updated');
        }
    }

    /**
     * Install Cobiro.
     *
     * @return void
     */
    public static function install()
    {
        if (!is_blog_installed()) {
            return;
        }

        if (!class_exists('WooCommerce')) {
            return;
        }

        // Check if we are not already running this routine.
        if ('yes' === get_transient('cobiro_installing')) {
            return;
        }

        // If we made it till here nothing is running yet, lets set the transient now.
        set_transient('cobiro_installing', 'yes', MINUTE_IN_SECONDS * 10);
        wc_maybe_define_constant('COBIRO_INSTALLING', true);

        self::create_options();
        self::update_cobiro_version();

        delete_transient('cobiro_installing');

        do_action('cobiro_installed');
    }

    /**
     * Default options.
     *
     * @return void
     */
    private static function create_options()
    {
        add_option('cobiro_gmc', '');
        add_option('cobiro_gtm_id', '');
        add_option('cobiro_gtm_label', '');
    }

    /**
     * Update Cobiro version to current.
     *
     * @return void
     */
    private static function update_cobiro_version()
    {
        delete_option('cobiro_version');

        add_option('cobiro_version', cobiro()->version);
    }
}

Cobiro_Install::init();
