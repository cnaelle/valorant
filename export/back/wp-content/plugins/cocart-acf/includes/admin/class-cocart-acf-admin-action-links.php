<?php
/**
 * CoCart Advanced Custom Fields - Admin Action Links.
 *
 * Adds links to CoCart Advanced Custom Fields on the plugins page.
 *
 * @author   Sébastien Dumont
 * @category Admin
 * @package  CoCart Advanced Custom Fields/Admin
 * @license  GPL-2.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CoCart_ACF_Admin_Action_Links' ) ) {

	class CoCart_ACF_Admin_Action_Links {

		/**
		 * Constructor
		 *
		 * @access public
		 */
		public function __construct() {
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta'), 10, 3 );
		} // END __construct()

		/**
		 * Plugin row meta links
		 *
		 * @access public
		 * @param  array  $metadata An array of the plugin's metadata.
		 * @param  string $file     Path to the plugin file.
		 * @param  array  $data     Plugin Information
		 * @return array  $metadata
		 */
		public function plugin_row_meta( $metadata, $file, $data ) {
			if ( $file == plugin_basename( COCART_ACF_FILE ) ) {
				$metadata[ 1 ] = sprintf( __( 'Developed By %s', 'cocart-acf' ), '<a href="' . $data[ 'AuthorURI' ] . '" aria-label="' . esc_attr__( 'View the developers site', 'cocart-acf' ) . '">' . $data[ 'Author' ] . '</a>' );

				$row_meta = array(
					'review' => '<a href="' . esc_url( add_query_arg( 'wpf15410_12', 'CoCart%20Advanced%20Custom%20Fields', COCART_ACF_REVIEW_URL ) ) . '" aria-label="' . sprintf( esc_attr__( 'Review %s on CoCart.xyz', 'cocart-acf' ), 'CoCart' ) . '" target="_blank">' . esc_attr__( 'Leave a Review', 'cocart-acf' ) . '</a>',
				);

				$metadata = array_merge( $metadata, $row_meta );
			}

			return $metadata;
		} // END plugin_row_meta()

	} // END class

} // END if class exists

return new CoCart_ACF_Admin_Action_Links();
