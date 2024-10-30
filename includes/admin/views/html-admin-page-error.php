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
        <h2><?php echo __('Something went wrong', 'cobiro'); ?></h2>

        <p>
            <?php echo __('Install the WooCommerce and reactivate the Cobiro plugin.', 'cobiro'); ?>
        </p>
    </div>
</div>
