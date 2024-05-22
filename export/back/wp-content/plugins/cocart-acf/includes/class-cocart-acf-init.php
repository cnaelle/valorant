<?php
/**
 * CoCart Advanced Custom Fields
 *
 * @author   Sébastien Dumont
 * @category API
 * @package  CoCart Advanced Custom Fields/API
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CoCart Advanced Custom Fields REST API class.
 */
class CoCart_Advanced_Custom_Fields_Rest_API {

	/**
	 * Setup class.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->cocart_acf_rest_api_init();
	} // END __construct()

	/**
	 * Init CoCart Advanced Custom Fields REST API.
	 *
	 * @access private
	 */
	private function cocart_acf_rest_api_init() {
		// REST API was included starting WordPress 4.4.
		if ( ! class_exists( 'WP_REST_Server' ) ) {
			return;
		}

		// Advanced Custom Fields.
		add_action( 'rest_api_init', array( $this, 'support_third_parties' ), 12 );
	} // cart_rest_api_init()

	/**
	 * Include Advanced Custom Fields.
	 *
	 * @access public
	 */
	public function support_third_parties() {
		// Enable support if CoCart Products is installed.
		if ( defined( 'COCART_PRODUCTS_VERSION' ) ) {
			if ( defined( 'ACF_VERSION' ) ) {
				include_once( dirname( __FILE__) . '/3rd-party/class-cocart-products-acf.php' );
			}
		}
	} // END support_third_parties()

} // END class

return new CoCart_Advanced_Custom_Fields_Rest_API();
