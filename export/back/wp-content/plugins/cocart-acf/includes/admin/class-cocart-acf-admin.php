<?php
/**
 * CoCart Advanced Custom Fields - Admin.
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

if ( ! class_exists( 'CoCart_ACF_Admin' ) ) {

	class CoCart_ACF_Admin {

		/**
		 * Constructor
		 *
		 * @access public
		 */
		public function __construct() {
			register_activation_hook( COCART_ACF_FILE, array( $this, 'activated' ) );
			register_deactivation_hook( COCART_ACF_FILE, array( $this, 'deactivated' ) );

			// Include classes.
			self::includes();
		} // END __construct()

		/**
		 * Include any classes we need within admin.
		 *
		 * @access public
		 */
		public function includes() {
			include( COCART_ACF_FILE_PATH . '/includes/admin/class-cocart-acf-admin-action-links.php' ); // Action Links
			include( COCART_ACF_FILE_PATH . '/includes/admin/class-cocart-acf-admin-assets.php' );  // Admin Assets
			include( COCART_ACF_FILE_PATH . '/includes/admin/class-cocart-acf-admin-notices.php' ); // Plugin Notices
			include( COCART_ACF_FILE_PATH . '/includes/admin/class-cocart-acf-admin-updater.php' ); // Plugin Updater
		} // END includes()

		/**
		 * Checks if CoCart is installed.
		 *
		 * @access public
		 * @static
		 */
		public static function is_cocart_installed() {
			$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( is_multisite() ) {
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			}
	
			return in_array( 'cart-rest-api-for-woocommerce/cart-rest-api-for-woocommerce.php', $active_plugins ) || array_key_exists( 'cart-rest-api-for-woocommerce/cart-rest-api-for-woocommerce.php', $active_plugins );
		} // END is_cocart_installed()

		/**
		 * These are the only screens CoCart will focus 
		 * on displaying notices or equeue scripts/styles.
		 *
		 * @access public
		 * @static
		 * @return array
		 */
		public static function cocart_get_admin_screens() {
			return array(
				'dashboard',
				'plugins',
				'toplevel_page_cocart'
			);
		} // END cocart_get_admin_screens()

		/**
		 * Returns true if CoCart Advanced Custom Fields is a beta/pre-release.
		 *
		 * @access public
		 * @static
		 * @return boolean
		 */
		public static function is_cocart_acf_beta() {
			if ( 
				strpos( COCART_ACF_VERSION, 'beta' ) ||
				strpos( COCART_ACF_VERSION, 'rc' )
			) {
				return true;
			}

			return false;
		} // END is_cocart_acf_beta()

		/**
		 * Checks if the current user has the capabilities to install a plugin.
		 *
		 * @access public
		 * @static
		 * @since  2.1.0
		 * @return bool
		 */
		public static function user_has_capabilities() {
			if ( current_user_can( apply_filters( 'cocart_install_capability', 'install_plugins' ) ) ) {
				return true;
			}

			// If the current user can not install plugins then return nothing!
			return false;
		} // END user_has_capabilities()

		/**
		 * Seconds to words.
		 *
		 * Forked from: https://github.com/thatplugincompany/login-designer/blob/master/includes/admin/class-login-designer-feedback.php
		 *
		 * @access public
		 * @static
		 * @param  string $seconds Seconds in time.
		 * @return string
		 */
		public static function cocart_seconds_to_words( $seconds ) {
			// Get the years.
			$years = ( intval( $seconds ) / YEAR_IN_SECONDS ) % 100;
			if ( $years > 1 ) {
				/* translators: Number of years */
				return sprintf( __( '%s years', 'cocart-acf' ), $years );
			} elseif ( $years > 0 ) {
				return __( 'a year', 'cocart-acf' );
			}

			// Get the months.
			$months = ( intval( $seconds ) / MONTH_IN_SECONDS ) % 52;
			if ( $months > 1 ) {
				return sprintf( __( '%s months', 'cocart-acf' ), $months );
			} elseif ( $months > 0 ) {
				return __( '1 month', 'cocart-acf' );
			}

			// Get the weeks.
			$weeks = ( intval( $seconds ) / WEEK_IN_SECONDS ) % 52;
			if ( $weeks > 1 ) {
				/* translators: Number of weeks */
				return sprintf( __( '%s weeks', 'cocart-acf' ), $weeks );
			} elseif ( $weeks > 0 ) {
				return __( 'a week', 'cocart-acf' );
			}

			// Get the days.
			$days = ( intval( $seconds ) / DAY_IN_SECONDS ) % 7;
			if ( $days > 1 ) {
				/* translators: Number of days */
				return sprintf( __( '%s days', 'cocart-acf' ), $days );
			} elseif ( $days > 0 ) {
				return __( 'a day', 'cocart-acf' );
			}

			// Get the hours.
			$hours = ( intval( $seconds ) / HOUR_IN_SECONDS ) % 24;
			if ( $hours > 1 ) {
				/* translators: Number of hours */
				return sprintf( __( '%s hours', 'cocart-acf' ), $hours );
			} elseif ( $hours > 0 ) {
				return __( 'an hour', 'cocart-acf' );
			}

			// Get the minutes.
			$minutes = ( intval( $seconds ) / MINUTE_IN_SECONDS ) % 60;
			if ( $minutes > 1 ) {
				/* translators: Number of minutes */
				return sprintf( __( '%s minutes', 'cocart-acf' ), $minutes );
			} elseif ( $minutes > 0 ) {
				return __( 'a minute', 'cocart-acf' );
			}

			// Get the seconds.
			$seconds = intval( $seconds ) % 60;
			if ( $seconds > 1 ) {
				/* translators: Number of seconds */
				return sprintf( __( '%s seconds', 'cocart-acf' ), $seconds );
			} elseif ( $seconds > 0 ) {
				return __( 'a second', 'cocart-acf' );
			}
		} // END cocart_seconds_to_words()

		/**
		 * Runs when the plugin is activated.
		 *
		 * Adds plugin to list of installed CoCart add-ons.
		 *
		 * @access public
		 */
		public function activated() {
			$addons_installed = get_site_option( 'cocart_addons_installed', array() );

			$plugin = plugin_basename( COCART_ACF_FILE );

			// Check if plugin is already added to list of installed add-ons.
			if ( ! in_array( $plugin, $addons_installed, true ) ) {
				array_push( $addons_installed, $plugin );
				update_site_option( 'cocart_addons_installed', $addons_installed );
			}
		} // END activated()

		/**
		 * Runs when the plugin is deactivated.
		 *
		 * Removes plugin from list of installed CoCart add-ons.
		 *
		 * @access public
		 */
		public function deactivated() {
			$addons_installed = get_site_option( 'cocart_addons_installed', array() );

			$plugin = plugin_basename( COCART_ACF_FILE );
			
			// Remove plugin from list of installed add-ons.
			if ( in_array( $plugin, $addons_installed, true ) ) {
				$addons_installed = array_diff( $addons_installed, array( $plugin ) );
				update_site_option( 'cocart_addons_installed', $addons_installed );
			}
		} // END deactivated()

	} // END class

} // END if class exists

return new CoCart_ACF_Admin();
