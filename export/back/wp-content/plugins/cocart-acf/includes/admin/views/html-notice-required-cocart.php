<?php
/**
 * Admin View: Required CoCart Notice.
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
			<h3><?php echo esc_html__( 'Update Required!', 'cocart-acf' ); ?></h3>
			<p><?php echo sprintf( __( '%1$s Advanced Custom Fields requires at least %1$s v%2$s or higher.', 'cocart-acf' ), 'CoCart', CoCart_ACF::$required_cocart ); ?></p>
		</div>

		<?php if ( current_user_can( 'update_plugins' ) ) { ?>
		<div class="cocart-action">
			<?php $upgrade_url = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=cart-rest-api-for-woocommerce' ), 'upgrade-plugin_cart-rest-api-for-woocommerce' ); ?>

			<p><a href="<?php echo esc_url( $upgrade_url ); ?>" class="button button-primary cocart-button" aria-label="<?php echo sprintf( esc_html__( 'Update %s', 'cocart-acf' ), 'CoCart' ); ?>"><?php echo sprintf( esc_html__( 'Update %s', 'cocart-acf' ), 'CoCart' ); ?></a></p>
		</div>
		<?php } ?>
	</div>
</div>
