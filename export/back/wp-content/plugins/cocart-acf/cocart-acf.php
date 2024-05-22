<?php
/*
 * Plugin Name: CoCart - Advanced Custom Fields
 * Plugin URI:  https://cocart.xyz
 * Description: Returns Advanced Custom Fields data for products.
 * Author:      Sébastien Dumont
 * Author URI:  https://sebastiendumont.com
 * Version:     1.0.0-rc.1
 * Text Domain: cocart-acf
 * Domain Path: /languages/
 *
 * WC requires at least: 3.6.0
 * WC tested up to: 4.2.0
 *
 * Copyright: © 2020 Sébastien Dumont, (mailme@sebastiendumont.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! class_exists( 'CoCart_ACF' ) ) {
	class CoCart_ACF {

		/**
		 * @var CoCart_ACF - the single instance of the class.
		 *
		 * @access protected
		 * @static
		 */
		protected static $_instance = null;

		/**
		 * Plugin Version
		 *
		 * @access public
		 * @static
		 */
		public static $version = '1.0.0-rc.1';

		/**
		 * Required CoCart Version
		 *
		 * @access public
		 * @static
		 */
		public static $required_cocart = '2.0.0';

		/**
		 * Main CoCart Advanced Custom Fields Instance.
		 *
		 * Ensures only one instance of CoCart Advanced Custom Fields is loaded or can be loaded.
		 *
		 * @access  public
		 * @static
		 * @see     CoCart_ACF()
		 * @return  CoCart_ACF - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @access public
		 * @return void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cloning this object is forbidden.', 'cocart-acf' ), self::$version );
		} // END __clone()

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @access public
		 * @return void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'cocart-acf' ), self::$version );
		} // END __wakeup()

		/**
		 * Load the plugin.
		 *
		 * @access public
		 */
		public function __construct() {
			// Setup Constants.
			$this->setup_constants();

			// Include admin classes to handle all back-end functions.
			$this->admin_includes();

			// Include required files.
			add_action( 'init', array( $this, 'includes' ) );

			// Load translation files.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		} // END __construct()

		/**
		 * Setup Constants
		 *
		 * @access public
		 */
		public function setup_constants() {
			$this->define('COCART_ACF_VERSION', self::$version);
			$this->define('COCART_ACF_FILE', __FILE__);
			$this->define('COCART_ACF_SLUG', 'cocart-acf');

			$this->define('COCART_ACF_URL_PATH', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
			$this->define('COCART_ACF_FILE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

			$this->define('COCART_STORE_URL', 'https://cocart.xyz/');
			$this->define('COCART_ACF_REVIEW_URL', 'https://cocart.xyz/submit-review/');
		} // END setup_constants()

		/**
		 * Define constant if not already set.
		 *
		 * @access private
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		} // END define()

		/**
		 * Includes additional data for products api.
		 *
		 * @access public
		 * @return void
		 */
		public function includes() {
			include_once( COCART_ACF_FILE_PATH . '/includes/class-cocart-acf-autoloader.php' );
			include_once( COCART_ACF_FILE_PATH . '/includes/class-cocart-acf-init.php' );
		} // END includes()

		/**
		 * Include admin class to handle all back-end functions.
		 *
		 * @access public
		 * @return void
		 */
		public function admin_includes() {
			if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
				include_once( COCART_ACF_FILE_PATH . '/includes/admin/class-cocart-acf-admin.php' );
				require_once( COCART_ACF_FILE_PATH . '/includes/class-cocart-acf-install.php' );
			}
		} // END admin_includes()

		/**
		 * Make the plugin translation ready.
		 *
		 * Translations should be added in the WordPress language directory:
		 *      - WP_LANG_DIR/plugins/cocart-acf-LOCALE.mo
		 *
		 * @access public
		 * @return void
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'cocart-acf', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		} // END load_plugin_textdomain()

	} // END class

} // END if class exists

/**
 * Returns the main instance of CoCart Advanced Custom Fields.
 *
 * @return CoCart Advanced Custom Fields
 */
function CoCart_ACF() {
	return CoCart_ACF::instance();
}

// Run CoCart Advanced Custom Fields
CoCart_ACF();