<?php
/**
 * Cobiro setup.
 *
 * @since   1.0.0
 */
defined('ABSPATH') || exit;

/**
 * Cobiro_GTM class.
 */
class Cobiro_GTM
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
        $gtm_id = get_option('cobiro_gtm_id');

        if (!$gtm_id) {
            return;
        }

        $url = esc_url('https://www.googletagmanager.com/gtag/js?id=' . $gtm_id);

        echo '<script async src="' . esc_html($url) . '"></script>';

        echo '<script type="text/javascript">
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                window.dataLayer.push(arguments);
            }
            gtag("js", new Date());

            gtag("config", "' . esc_js($gtm_id) . '");
        </script>';
    }
}

return new Cobiro_GTM();
