<?php
/**
 * Cobiro setup.
 *
 * @since   1.0.0
 */
defined('ABSPATH') || exit;

/**
 * Cobiro_API class.
 */
class Cobiro_API
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('rest_api_init', function () {
            register_rest_route('cobiro/v1', '/gmc', [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'gmc'],
                'permission_callback' => [$this, 'permissions_check'],
                'args'                => [
                    'key' => [
                        'description' => __('GMC key.', 'cobiro'),
                        'type'        => 'string',
                    ],
                ],
            ]);

            register_rest_route('cobiro/v1', '/gtm', [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'gtm'],
                'permission_callback' => [$this, 'permissions_check'],
                'args'                => [
                    'id' => [
                        'description' => __('GTM id.', 'cobiro'),
                        'type'        => 'string',
                    ],
                    'label' => [
                        'description' => __('GTM label.', 'cobiro'),
                        'type'        => 'string',
                    ],
                ],
            ]);
        });
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function gmc($data)
    {
        update_option('cobiro_gmc', $data['key'], 'yes');
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function gtm($data)
    {
        update_option('cobiro_gtm_id', $data['id'], 'yes');
        update_option('cobiro_gtm_label', $data['label'], 'yes');
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return bool|WP_Error
     */
    public function permissions_check($request)
    {
        $token = str_replace('Basic ', '', $request->get_header('Authorization'));
        $token = base64_decode($token, true);
        $token = explode(':', $token);

        if (count($token) === 2 && count(self::api_keys($token)) > 0) {
            return true;
        }

        if (is_ssl() && !empty($_GET['consumer_key']) && !empty($_GET['consumer_secret'])) {
            $token    = [];
            $token[0] = $_GET['consumer_key'];
            $token[1] = $_GET['consumer_secret'];

            if (count(self::api_keys($token)) > 0) {
                return true;
            }
        }

        return new WP_Error(
            'cobiro_rest_cannot_create',
            __('Sorry, you are not allowed to create resources.', 'cobiro'),
            ['status' => rest_authorization_required_code()]
        );
    }

    /**
     * @param array $token
     *
     * @return ?array
     */
    private static function api_keys($token)
    {
        global $wpdb;

        $data = [
            'consumer_key'    => wc_api_hash(sanitize_key($token[0])),
            'consumer_secret' => sanitize_key($token[1]),
            'description'     => 'Cobiro',
            'permissions'     => 'read',
        ];

        $q = 'SELECT * FROM `' . $wpdb->prefix . 'woocommerce_api_keys`
        WHERE `consumer_key` = %s AND `consumer_secret` = %s AND `description` = %s AND `permissions` = %s';

        return $wpdb->get_results($wpdb->prepare($q, $data));
    }
}

return new Cobiro_API();
