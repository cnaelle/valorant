<?php
/**
 * Admin View: Plugin Review Notice.
 *
 * @author   Sébastien Dumont
 * @category Admin
 * @package  CoCart Advanced Custom Fields/Admin/Views
 * @license  GPL-2.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();

$time = CoCart_ACF_Admin::cocart_seconds_to_words( time() - $install_date );
?>
<div class="notice notice-info cocart-notice">
	<div class="cocart-notice-inner">
		<div class="cocart-notice-icon">
			<img src="<?php echo COCART_ACF_URL_PATH . '/assets/images/logo.jpg'; ?>" alt="<?php echo esc_attr__( 'CoCart, a WooCommerce REST-API extension', 'cocart-acf' ); ?>" />
		</div>

		<div class="cocart-notice-content">
			<h3><?php printf( esc_html__( 'Hi %1$s, are you enjoying %2$s?', 'cocart-acf' ), $current_user->display_name, 'CoCart Advanced Custom Fields' ); ?></h3>
			<p><?php printf( esc_html__( 'You have been using %1$s for %2$s now! Mind leaving a review and let me know know what you think of the plugin? I\'d really appreciate it!', 'cocart-acf' ), 'CoCart Advanced Custom Fields', esc_html( $time ) ); ?></p>
		</div>

		<div class="cocart-action">
			<?php printf( '<a href="%1$s" class="button button-primary cocart-button" target="_blank">%2$s</a>', esc_url( add_query_arg( 'wpf15410_12', 'CoCart%20Advanced%20Custom%20Fields', COCART_ACF_REVIEW_URL ) ), esc_html__( 'Leave a Review', 'cocart-acf' ) ); ?>
			<a href="<?php echo esc_url( add_query_arg( 'hide_cocart_acf_review_notice', 'true' ) ); ?>" class="no-thanks"><?php echo esc_html__( 'No thank you / I already have', 'cocart-acf' ); ?></a>
		</div>
	</div>
</div>
