<?php
/**
 * Cobiro setup.
 *
 * @since   1.0.0
 */
defined('ABSPATH') || exit;

final class Cobiro
{
    /**
     * Cobiro version.
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class.
     *
     * @var Cobiro
     *
     * @since 1.0.0
     */
    private static $_instance = null;

    /**
     * Cobiro Constructor.
     */
    private function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();

        do_action('cobiro_loaded');
    }

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        wc_doing_it_wrong(__FUNCTION__, __('Cloning is forbidden.', 'cobiro'), '1.0.0');
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        wc_doing_it_wrong(__FUNCTION__, __('Unserializing instances of this class is forbidden.', 'cobiro'), '1.0.0');
    }

    /**
     * Main Cobiro Instance.
     *
     * Ensures only one instance of Cobiro is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     *
     * @return Cobiro - Main instance.
     */
    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Include required core files used in admin and on the frontend.
     *
     * @return void
     */
    public function includes()
    {
        /**
         * Class autoloader.
         */
        include_once COBIRO_ABSPATH . 'includes/class-cobiro-autoloader.php';

        /**
         * REST API.
         */
        include_once COBIRO_ABSPATH . 'includes/class-cobiro-api.php';

        if (is_admin()) {
            include_once COBIRO_ABSPATH . 'includes/admin/class-cobiro-admin.php';
        }

        if ((!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON') && !defined('REST_REQUEST')) {
            include_once COBIRO_ABSPATH . 'includes/class-cobiro-gvt.php';
            include_once COBIRO_ABSPATH . 'includes/class-cobiro-gtm.php';
            include_once COBIRO_ABSPATH . 'includes/class-cobiro-conversion.php';
        }
    }

    /**
     * Init Cobiro when WordPress Initialises.
     *
     * @return void
     */
    public function init()
    {
        // Before init action.
        do_action('before_cobiro_init');

        // Set up localisation.
        $this->load_plugin_textdomain();

        // Init action.
        do_action('cobiro_init');
    }

    /**
     * Load Localisation files.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present.
     *
     * @return void
     */
    public function load_plugin_textdomain()
    {
        $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, 'cobiro');

        unload_textdomain('cobiro');
        load_textdomain('cobiro', WP_LANG_DIR . '/cobiro/cobiro-' . $locale . '.mo');
        load_plugin_textdomain('cobiro', false, plugin_basename(dirname(COBIRO_PLUGIN_FILE)) . '/languages');
    }

    /**
     * Appends a key `cobiro_custom_url` within the products category response.
     *
     * @param mixed[] $product_categories Product categories array.
     *
     * @return mixed product categories array with extra custom key.
     */
    public function add_category_link_to_woocommerce_api($product_categories)
    {
        $product_categories->data['cobiro_custom_url'] = get_category_link($product_categories->data['id']);

        return $product_categories;
    }

    /**
     * Define Cobiro Constants.
     *
     * @return void
     */
    private function define_constants()
    {
        $this->define('COBIRO_ABSPATH', dirname(COBIRO_PLUGIN_FILE) . '/');
        $this->define('COBIRO_PLUGIN_BASENAME', plugin_basename(COBIRO_PLUGIN_FILE));
        $this->define('COBIRO_VERSION', $this->version);
    }

    /**
     * Define constant if not already set.
     *
     * @param string      $name  Constant name.
     * @param bool|string $value Constant value.
     *
     * @return void
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Hook into actions and filters.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function init_hooks()
    {
        register_activation_hook(COBIRO_PLUGIN_FILE, ['Cobiro_Install', 'install']);

        add_action('init', [$this, 'init'], 0);

        add_filter('woocommerce_rest_prepare_product_cat', [$this, 'add_category_link_to_woocommerce_api']);

        register_uninstall_hook(COBIRO_PLUGIN_FILE, ['Cobiro_Uninstall', 'uninstall']);
    }
}
