<?php
/**
 * Admin View: Page - Cobiro.
 */
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="card">
        <h2><?php echo __('Welcome to Cobiro for WooCommerce', 'cobiro'); ?></h2>

        <p>
            <?php echo __('Click the button below to get started and link your WooCommerce shop to Cobiro.', 'cobiro'); ?>
        </p>

        <button data-base-link="https://customer.cobiro.com/user/bind?" class="button button-primary" id="cobiro-start"><?php echo __('Start', 'cobiro'); ?></button>
    </div>
</div>

<script>
    (function($) {
        $('#cobiro-start').on('click', function (event) {
            var $this = $(this);

            $.ajax({
                method: "POST",
                dataType: 'json',
                url: "<?php echo $ajax_url; ?>",
                data: {
                    action: "woocommerce_update_api_key",
                    security: "<?php echo $security; ?>",
                    key_id: "0",
                    description: "Cobiro",
                    user: "<?php echo $user_id; ?>",
                    permissions: "read"
                },
                success: function(response) {
                    if (response.success) {
                        var href = $this.attr('data-base-link');

                        href += "<?php echo $basic_data; ?>" + "&";
                        href += $.param({
                            "shop_api_key": response.data.consumer_key + ":" + response.data.consumer_secret
                        });

                        location.href = href;
                    }
                }
            });
        });
    })(jQuery);
</script>
