<?php
/**
 * Cobiro Admin.
 *
 * @class    Cobiro_Admin
 *
 * @author   Cobiro
 *
 * @category Admin
 *
 * @version  1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Cobiro_Admin class.
 */
class Cobiro_Admin
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('init', [$this, 'includes']);
    }

    /**
     * Include any classes we need within admin.
     *
     * @return void
     */
    public function includes()
    {
        include_once __DIR__ . '/class-cobiro-admin-menus.php';
        include_once __DIR__ . '/class-cobiro-admin-page.php';
    }
}

return new Cobiro_Admin();
