<?php
/**
 * CoCart REST API Store controller.
 *
 * Returns store details and all public routes.
 *
 * @author  Sébastien Dumont
 * @package CoCart\API\v2
 * @since   3.0.0
 * @version 3.1.0
 * @license GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CoCart REST API v2 - Store controller class.
 *
 * @package CoCart REST API/API
 */
class CoCart_Store_V2_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'cocart/v2';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'store';

	/**
	 * Register routes.
	 *
	 * @access  public
	 * @since   3.0.0 Introduced
	 * @since   3.1.0 Added schema information.
	 * @version 3.1.0
	 */
	public function register_routes() {
		// Get Store - cocart/v2/store (GET).
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_store' ),
					'permission_callback' => '__return_true',
				),
				'schema' => array( $this, 'get_public_object_schema' ),
			),
		);
	} // register_routes()

	/**
	 * Retrieves the store index.
	 *
	 * This endpoint describes the general store details.
	 *
	 * @access  public
	 * @since   3.0.0 Introduced.
	 * @version 3.1.0
	 * @param   WP_REST_Request $request - Full details about the request.
	 * @return  WP_REST_Response The API root index data.
	 */
	public function get_store( $request ) {
		// General store data.
		$available = array(
			'version'         => COCART_VERSION,
			'title'           => get_option( 'blogname' ),
			'description'     => get_option( 'blogdescription' ),
			'home_url'        => home_url(),
			'language'        => get_bloginfo( 'language' ),
			'gmt_offset'      => get_option( 'gmt_offset' ),
			'timezone_string' => wp_timezone_string(),
			'store_address'   => $this->get_store_address(),
			'routes'          => $this->get_routes(),
		);

		$response = new WP_REST_Response( $available );

		$response->add_link( 'help', 'https://docs.cocart.xyz/' );

		/**
		 * Filters the API store index data.
		 *
		 * This contains the data describing the API. This includes information
		 * about the store, routes available on the API, and a small amount
		 * of data about the site.
		 *
		 * @param WP_REST_Response $response Response data.
		 */
		return apply_filters( 'cocart_store_index', $response );
	} // END get_store()

	/**
	 * Returns the store address.
	 *
	 * @access public
	 * @return array
	 */
	public function get_store_address() {
		return apply_filters(
			'cocart_store_address',
			array(
				'address'   => get_option( 'woocommerce_store_address' ),
				'address_2' => get_option( 'woocommerce_store_address_2' ),
				'city'      => get_option( 'woocommerce_store_city' ),
				'country'   => get_option( 'woocommerce_default_country' ),
				'postcode'  => get_option( 'woocommerce_store_postcode' ),
			)
		);
	} // END get_store_address()

	/**
	 * Returns the list of all public CoCart API routes.
	 *
	 * @access  public
	 * @since   3.0.0 Introduced.
	 * @since   3.1.0 Added login, logout, cart update and product routes.
	 * @version 3.1.0
	 * @return  array
	 */
	public function get_routes() {
		$prefix = trailingslashit( home_url() . '/' . rest_get_url_prefix() . '/cocart/v2/' );

		return apply_filters(
			'cocart_routes',
			array(
				'cart'                => $prefix . 'cart',
				'cart-add-item'       => $prefix . 'cart/add-item',
				'cart-add-items'      => $prefix . 'cart/add-items',
				'cart-item'           => $prefix . 'cart/item',
				'cart-items'          => $prefix . 'cart/items',
				'cart-items-count'    => $prefix . 'cart/items/count',
				'cart-calculate'      => $prefix . 'cart/calculate',
				'cart-clear'          => $prefix . 'cart/clear',
				'cart-totals'         => $prefix . 'cart/totals',
				'cart-update'         => $prefix . 'cart/update',
				'login'               => $prefix . 'login',
				'logout'              => $prefix . 'logout',
				'products'            => $prefix . 'products',
				'products-attributes' => $prefix . 'products/attributes',
				'products-categories' => $prefix . 'products/categories',
				'products-reviews'    => $prefix . 'products/reviews',
				'products-tags'       => $prefix . 'products/tags',
			)
		);
	} // END get_routes()

	/**
	 * Get the schema for returning the store.
	 *
	 * @access public
	 * @since  3.1.0 Introduced.
	 * @return array
	 */
	public function get_public_object_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'CoCart - ' . __( 'Store Information', 'cart-rest-api-for-woocommerce' ),
			'type'       => 'object',
			'properties' => array(
				'version'         => array(
					'description' => sprintf(
						/* translators: %s: CoCart */
						__( 'Version of the %s (core) plugin.', 'cart-rest-api-for-woocommerce' ),
						'CoCart'
					),
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'title'           => array(
					'description' => __( 'Title of the site.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'description'     => array(
					'description' => __( 'The site tag line.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'home_url'        => array(
					'description' => __( 'The site home URL.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'language'        => array(
					'description' => __( 'The site language, by default.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'gmt_offset'      => array(
					'description' => __( 'The time offset for the timezone.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'timezone_string' => array(
					'description' => __( 'The timezone from site settings as a string.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'store_address'   => array(
					'description' => __( 'The full store address.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'object',
					'context'     => array( 'view' ),
					'properties'  => array(
						'address'   => array(
							'description' => __( 'The store address line one.', 'cart-rest-api-for-woocommerce' ),
							'type'        => 'string',
							'context'     => array( 'view' ),
							'readonly'    => true,
						),
						'address_2' => array(
							'description' => __( 'The store address line two.', 'cart-rest-api-for-woocommerce' ),
							'type'        => 'string',
							'context'     => array( 'view' ),
							'readonly'    => true,
						),
						'city'      => array(
							'description' => __( 'The store address city.', 'cart-rest-api-for-woocommerce' ),
							'type'        => 'string',
							'context'     => array( 'view' ),
							'readonly'    => true,
						),
						'country'   => array(
							'description' => __( 'The store address country.', 'cart-rest-api-for-woocommerce' ),
							'type'        => 'string',
							'context'     => array( 'view' ),
							'readonly'    => true,
						),
						'postcode'  => array(
							'description' => __( 'The store address postcode or zip.', 'cart-rest-api-for-woocommerce' ),
							'type'        => 'string',
							'context'     => array( 'view' ),
							'readonly'    => true,
						),
					),
				),
				'routes'          => array(
					'description' => __( 'The public routes of CoCart.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'object',
					'context'     => array( 'view' ),
					'properties'  => array(),
				),
			),
		);

		$routes = $this->get_routes();

		if ( count( $routes ) > 0 ) {
			// Apply each route to the properties.
			foreach ( $routes as $route => $endpoint ) {
				$schema['properties']['routes']['properties'][ $route ] = array(
					'description' => sprintf(
						/* translators: %s: Route URL */
						__( 'The "%s" route URL.', 'cart-rest-api-for-woocommerce' ),
						$route
					),
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				);
			}
		} else {
			// Remove routes property if none exist.
			unset( $schema['properties']['routes'] );
		}

		return $schema;
	} // END get_public_object_schema()

} // END class
