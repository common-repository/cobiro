<?php
/**
 * Debug/Status page.
 *
 * @version 2.2.0
 */
defined('ABSPATH') || exit;

/**
 * Cobiro_Admin_Page Class.
 */
class Cobiro_Admin_Page
{
    /**
     * Handles output of the reports page in admin.
     *
     * @return void
     */
    public static function output()
    {
        if (!class_exists('WooCommerce')) {
            include_once __DIR__ . '/views/html-admin-page-error.php';

            return;
        }

        if (self::api_key()) {
            include_once __DIR__ . '/views/html-admin-page-with-api.php';

            return;
        }

        $ajax_url = esc_js(admin_url('admin-ajax.php'));
        $security = esc_js(wp_create_nonce('update-api-key'));
        $user_id  = esc_js(get_current_user_id());

        $basic_data = http_build_query([
            'shop_id'   => 1,
            'shop_url'  => get_option('siteurl'),
            'shop_type' => 'woocommerce',
            'shop_name' => get_option('blogname'),
            'currency'  => get_option('woocommerce_currency'),
        ]);

        include_once __DIR__ . '/views/html-admin-page-without-api.php';
    }

    /**
     * @return array
     */
    private static function api_key()
    {
        global $wpdb;

        $data = [
            'description' => 'Cobiro',
            'permissions' => 'read',
        ];

        $q = 'SELECT * FROM `' . $wpdb->prefix . 'woocommerce_api_keys` WHERE `description` = %s AND `permissions` = %s';

        return $wpdb->get_results($wpdb->prepare($q, $data));
    }
}
