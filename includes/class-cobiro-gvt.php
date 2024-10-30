<?php
/**
 * Cobiro setup.
 *
 * @since   1.0.0
 */
defined('ABSPATH') || exit;

/**
 * Cobiro_GVT class.
 */
class Cobiro_GVT
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('wp_head', [$this, 'output']);
    }

    /**
     * @return void
     */
    public function output()
    {
        $gmc = get_option('cobiro_gmc');

        if (!$gmc) {
            return;
        }

        echo '<meta name="google-site-verification" content="' . esc_html($gmc) . '" />';
    }
}

return new Cobiro_GVT();
