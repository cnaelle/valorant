<?php
/**
 * CoCart Advanced Custom Fields - Admin Assets.
 *
 * @author   Sébastien Dumont
 * @category Admin
 * @package  CoCart Advanced Custom Fields/Admin/Assets
 * @license  GPL-2.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CoCart_ACF_Admin_Assets' ) ) {

	class CoCart_ACF_Admin_Assets {

		/**
		 * Constructor
		 *
		 * @access  public
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ), 10 );
		} // END __construct()

		/**
		 * Registers and enqueue Stylesheets.
		 *
		 * @access public
		 */
		public function admin_styles() {
			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';
			$suffix    = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

			// CoCart Page and Notices
			if ( ! CoCart_ACF_Admin::is_cocart_installed() && in_array( $screen_id, CoCart_ACF_Admin::cocart_get_admin_screens() ) ) {
				wp_register_style( COCART_ACF_SLUG . '_admin', COCART_ACF_URL_PATH . '/assets/css/admin/cocart' . $suffix . '.css' );
				wp_enqueue_style( COCART_ACF_SLUG . '_admin' );
			}

			// Modal
			if ( in_array( 'plugins', CoCart_ACF_Admin::cocart_get_admin_screens() ) ) {
				wp_register_style( COCART_ACF_SLUG . '_modal', COCART_ACF_URL_PATH . '/assets/css/admin/modal' . $suffix . '.css' );
				wp_enqueue_style( COCART_ACF_SLUG . '_modal' );
			}
		} // END admin_styles()

	} // END class

} // END if class exists

return new CoCart_ACF_Admin_Assets();
