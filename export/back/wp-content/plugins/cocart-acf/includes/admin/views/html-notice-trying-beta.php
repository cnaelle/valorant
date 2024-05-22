<?php
/**
 * Admin View: Trying Beta Notice.
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
?>
<div class="notice notice-info cocart-notice">
	<div class="cocart-notice-inner">
		<div class="cocart-notice-icon">
			<img src="<?php echo COCART_ACF_URL_PATH . '/assets/images/logo.jpg'; ?>" alt="<?php echo esc_attr__( 'CoCart, a WooCommerce REST-API extension', 'cocart-acf' ); ?>" />
		</div>

		<div class="cocart-notice-content">
			<h3><?php echo sprintf( esc_html__( 'Thanks for trying out this beta/pre-release of %s Advanced Custom Fields!', 'cocart-acf' ), 'CoCart' ); ?></h3>
			<p><?php echo esc_html__( 'If you have any questions or any feedback at all, please let me know. Any little bit you\'re willing to share helps.', 'cocart-acf' ); ?></p>
		</div>

		<div class="cocart-action">
			<?php printf( '<a href="%1$s" class="button button-primary cocart-button" aria-label="' . esc_html__( 'Give Feedback for %2$s', 'cocart-acf' ) . '" target="_blank">%3$s</a>', esc_url( COCART_STORE_URL . 'feedback/' ), 'CoCart', esc_html__( 'Give Feedback', 'cocart-acf' ) ); ?>
			<span class="no-thanks"><a href="<?php echo esc_url( add_query_arg( 'hide_cocart_acf_beta_notice', 'true' ) ); ?>" aria-label="<?php echo esc_html__( 'Hide this notice and ask me again for feedback in 2 weeks', 'cocart-acf' ); ?>"><?php echo esc_html__( 'Ask me again in 2 weeks', 'cocart-acf' ); ?></a> / <a href="<?php echo esc_url( add_query_arg( 'hide_forever_cocart_acf_beta_notice', 'true' ) ); ?>" aria-label="<?php echo esc_html__( 'Hide this notice forever.', 'cocart-acf' ); ?>"><?php echo esc_html__( 'Don\'t ask me again', 'cocart-acf' ); ?></a></span>
		</div>
	</div>
</div>
