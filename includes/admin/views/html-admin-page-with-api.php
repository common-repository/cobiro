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

        <a target="_blank" class="button button-primary" href="https://customer.cobiro.com"><?php echo __('Start', 'cobiro'); ?></a>
    </div>
</div>
