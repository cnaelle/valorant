<?php
/**
 * CoCart Products - Advanced Custom Fields
 *
 * Adds Advanced Custom Fields data to the products endpoint.
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
 * CoCart Products Advanced Custom Fields class.
 */
class CoCart_Products_ACF {

	/**
	 * Setup class.
	 *
	 * @access public
	 */
	public function __construct() {
		add_filter( 'cocart_prepare_product_object', array( $this, 'add_acf_fields' ), 10, 2 );
	}

	/**
	 * Add field data from Advanced Custom Fields to products.
	 * 
	 * @access public
	 * @param  object $response
	 * @param  object $object - WC_Product
	 */
	public function add_acf_fields( $response, $object ) {
		$id = $object->get_id();

		$field_objects = get_field_objects( $id );

		$response->data['acf_fields'] = array();

		foreach( $field_objects as $field ) {
			$response->data['acf_fields'][$field['name']] = array(
				'label' => $field['label'],
				'value' => get_field( $field['name'], $id ),
				'type'  => $field['type']
			);
		}

		return $response;
	} // add_acf_fields()

} // END class

return new CoCart_Products_ACF();