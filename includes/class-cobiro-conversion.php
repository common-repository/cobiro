<?php
/**
 * Cobiro setup.
 *
 * @since   1.0.0
 */
defined('ABSPATH') || exit;

/**
 * Cobiro_Conversion class.
 */
class Cobiro_Conversion
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('woocommerce_thankyou', [$this, 'output']);
    }

    /**
     * @param int $order_id
     *
     * @return void
     */
    public function output($order_id)
    {
        $order     = wc_get_order($order_id);
        $gtm_id    = get_option('cobiro_gtm_id');
        $gtm_label = get_option('cobiro_gtm_label');

        if (!$order || !$gtm_id || !$gtm_label) {
            return;
        }

        $gtm_id         = esc_js($gtm_id);
        $gtm_label      = esc_js($gtm_label);
        $order_total    = esc_js($order->get_total());
        $order_currency = esc_js($order->get_currency());
        $order_id       = esc_js($order->get_id());

        echo '<script>
            gtag("event", "conversion", {
                "send_to": "' . $gtm_id . '/' . $gtm_label . '",
                "value": ' . $order_total . ',
                "currency": "' . $order_currency . '",
                "transaction_id": "' . $order_id . '"
            });
        </script>';
    }
}

return new Cobiro_Conversion();
