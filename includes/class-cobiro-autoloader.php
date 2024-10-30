<?php
/**
 * Cobiro Autoloader.
 *
 * @version 1.0.0
 */
defined('ABSPATH') || exit;

/**
 * Autoloader class.
 */
class Cobiro_Autoloader
{
    /**
     * Path to the includes directory.
     *
     * @var string
     */
    private $include_path = '';

    /**
     * The Constructor.
     */
    public function __construct()
    {
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }

        spl_autoload_register([$this, 'autoload']);

        $this->include_path = untrailingslashit(plugin_dir_path(COBIRO_PLUGIN_FILE)) . '/includes/';
    }

    /**
     * Auto-load Cobiro classes on demand to reduce memory consumption.
     *
     * @param string $class Class name.
     *
     * @return void
     */
    public function autoload($class)
    {
        $class = mb_strtolower($class);

        if (0 !== mb_strpos($class, 'cobiro_')) {
            return;
        }

        $file = $this->get_file_name_from_class($class);

        if (!$this->load_file($file)) {
            $this->load_file($this->include_path . $file);
        }
    }

    /**
     * Take a class name and turn it into a file name.
     *
     * @param string $class Class name.
     *
     * @return string
     */
    private function get_file_name_from_class($class)
    {
        return 'class-' . str_replace('_', '-', $class) . '.php';
    }

    /**
     * Include a class file.
     *
     * @param string $path File path.
     *
     * @return bool Successful or not.
     */
    private function load_file($path)
    {
        if ($path && is_readable($path)) {
            include_once $path;

            return true;
        }

        return false;
    }
}

new Cobiro_Autoloader();
