<?php
/**
 * CoCart Advanced Custom Fields - Display notices in the WordPress admin.
 *
 * @author   Sébastien Dumont
 * @category Admin
 * @package  CoCart Advanced Custom Fields/Admin/Notices
 * @license  GPL-2.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CoCart_ACF_Admin_Notices' ) ) {

	class CoCart_ACF_Admin_Notices {

		/**
		 * Activation date.
		 *
		 * @access public
		 * @static
		 * @var    string
		 */
		public static $install_date;

		/**
		 * Constructor
		 *
		 * @access public
		 */
		public function __construct() {
			self::$install_date = get_site_option( 'cocart_acf_install_date', time() );

			// Don't bug the user if they don't want to see any notices.
			add_action( 'admin_init', array( $this, 'dont_bug_me' ), 15 );

			// Check CoCart Advanced Custom Fields dependency.
			add_action( 'admin_print_styles', array( $this, 'check_cocart_acf_dependency' ), 0 );

			// Display other admin notices when required. All are dismissible.
			add_action( 'admin_print_styles', array( $this, 'add_notices' ), 10 );
		} // END __construct()

		/**
		 * Check CoCart Advanced Custom Fields Dependency.
		 *
		 * @access public
		 */
		public function check_cocart_acf_dependency() {
			// If the current user can not install plugins then return nothing!
			if ( ! CoCart_ACF_Admin::user_has_capabilities() ) {
				return false;
			}

			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			// Notices should only show on the main dashboard and on the plugins screen.
			if ( ! in_array( $screen_id, CoCart_ACF_Admin::cocart_get_admin_screens() ) ) {
				return false;
			}

			if ( ! defined( 'COCART_PRODUCTS_VERSION' ) ) {
				add_action( 'admin_notices', array( $this, 'cocart_products_not_installed' ) );
				return false;
			}

			else if ( version_compare( COCART_VERSION, CoCart_ACF::$required_cocart, '<' ) ) {
				add_action( 'admin_notices', array( $this, 'required_cocart_version_failed' ) );
				return false;
			}
		} // END check_cocart_acf_dependency()

		/**
		 * Don't bug the user if they don't want to see any notices.
		 *
		 * @access public
		 * @global $current_user
		 */
		public function dont_bug_me() {
			global $current_user;

			$user_hidden_notice = false;

			// If the user is allowed to install plugins and requested to hide the review notice then hide it for that user.
			if ( ! empty( $_GET['hide_cocart_acf_review_notice'] ) && CoCart_ACF_Admin::user_has_capabilities() ) {
				add_user_meta( $current_user->ID, 'cocart_acf_hide_review_notice', '1', true );
				$user_hidden_notice = true;
			}

			// Hide the beta notice for two weeks if requested.
			if ( ! empty( $_GET['hide_cocart_acf_beta_notice'] ) && CoCart_ACF_Admin::user_has_capabilities() ) {
				set_transient( 'cocart_acf_beta_notice_hidden', 'hidden', apply_filters( 'cocart_acf_beta_notice_expiration', WEEK_IN_SECONDS * 2 ) );
				$user_hidden_notice = true;
			}

			// If the user is allowed to install plugins and requested to dismiss beta notice forever.
			if ( ! empty( $_GET['hide_forever_cocart_acf_beta_notice'] ) && CoCart_ACF_Admin::user_has_capabilities() ) {
				set_transient( 'cocart_acf_beta_notice_hidden', 'hidden' );
				$user_hidden_notice = true;
			}

			if ( $user_hidden_notice ) {
				// Redirect to the plugins page.
				wp_safe_redirect( admin_url( 'plugins.php' ) );
				exit;
			}
		} // END dont_bug_me()

		/**
		 * Displays admin notices for the following:
		 *
		 * 1. Plugin review, shown after 14 days or more from the time the plugin was installed.
		 * 2. Testing a beta/pre-release version of the plugin.
		 * 
		 * @access public
		 * @global $current_user
		 * @return void|bool
		 */
		public function add_notices() {
			global $current_user;

			// If the current user can not install plugins then return nothing!
			if ( ! CoCart_ACF_Admin::user_has_capabilities() ) {
				return false;
			}

			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			// Notices should only show on the main dashboard and on the plugins screen.
			if ( ! in_array( $screen_id, CoCart_ACF_Admin::cocart_get_admin_screens() ) ) {
				return false;
			}

			// Is admin review notice hidden?
			$hide_review_notice = get_user_meta( $current_user->ID, 'cocart_acf_hide_review_notice', true );

			// Check if we need to display the review plugin notice.
			if ( empty( $hide_review_notice ) ) {
				// If it has been 2 weeks or more since activating the plugin then display the review notice.
				if ( ( intval( time() - self::$install_date ) ) > WEEK_IN_SECONDS * 2 ) {
					add_action( 'admin_notices', array( $this, 'plugin_review_notice' ) );
				}
			}

			// Is this version of CoCart Advanced Custom Fields a beta/pre-release?
			if ( CoCart_ACF_Admin::is_cocart_acf_beta() && empty( get_transient( 'cocart_acf_beta_notice_hidden' ) ) ) {
				add_action( 'admin_notices', array( $this, 'beta_notice' ) );
			}
		} // END add_notices()

		/**
		 * CoCart Products is Not Installed or Activated Notice.
		 *
		 * @access public
		 * @return void
		 */
		public function cocart_products_not_installed() {
			include_once( COCART_ACF_FILE_PATH . '/includes/admin/views/html-notice-cocart-products-not-installed.php' );
		} // END cocart_not_installed()

		/**
		 * Display a warning message if minimum version of CoCart check fails and
		 * provide an update button if the user has admin capabilities to update plugins.
		 *
		 * @access public
		 * @return void
		 */
		public function required_cocart_version_failed() {
			include_once( COCART_ACF_FILE_PATH . '/includes/admin/views/html-notice-required-cocart.php' );
		} // END required_cocart_version_failed()

		/**
		 * Show the beta notice.
		 *
		 * @access public
		 */
		public function beta_notice() {
			include_once( COCART_ACF_FILE_PATH . '/includes/admin/views/html-notice-trying-beta.php' );
		} // END beta_notice()

		/**
		 * Show the plugin review notice.
		 *
		 * @access public
		 */
		public function plugin_review_notice() {
			$install_date = self::$install_date;

			include_once( COCART_ACF_FILE_PATH . '/includes/admin/views/html-notice-please-review.php' );
		} // END plugin_review_notice()

	} // END class.

} // END if class exists.

return new CoCart_ACF_Admin_Notices();
