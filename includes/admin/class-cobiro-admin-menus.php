<?php
/**
 * Setup menus in WP admin.
 *
 * @version 1.0.0
 */
defined('ABSPATH') || exit;

if (class_exists('Cobiro_Admin_Menus', false)) {
    return new Cobiro_Admin_Menus();
}

/**
 * Cobiro_Admin_Menus Class.
 */
class Cobiro_Admin_Menus
{
    /**
     * Hook in tabs.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'admin_menu'], 9);
    }

    /**
     * Add menu items.
     *
     * @return void
     */
    public function admin_menu()
    {
        add_menu_page(__('Cobiro', 'cobiro'), __('Cobiro', 'cobiro'), 'manage_woocommerce', 'cobiro', [$this, 'cobiro']);
    }

    /**
     * @return void
     */
    public function cobiro()
    {
        Cobiro_Admin_Page::output();
    }
}

return new Cobiro_Admin_Menus();
