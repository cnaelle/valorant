<?php
/**
 * Admin View: Licence Manager.
 *
 * @author   Sébastien Dumont
 * @category Admin
 * @package  CoCart Pro/Admin/Views
 * @license  GPL-2.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap cocart licence-manager">

	<div class="container">

		<div class="content">
			<div class="logo">
				<a href="<?php echo COCART_STORE_URL; ?>" target="_blank">
					<img src="<?php echo COCART_URL_PATH . '/assets/images/logo.jpg'; ?>" alt="<?php echo esc_attr__( 'CoCart, a WooCommerce REST-API extension', 'cocart-pro' ); ?>" />
				</a>
			</div>

			<h1><?php printf( __( 'Licence Manager for %s.', 'cocart-pro' ), 'CoCart Pro' ); ?></h1>

			<p><strong><?php _e( 'This section is currently in development.', 'cocart-pro' ); ?></strong></p>

			<!--p><?php esc_html_e( 'Enter your licence key or email address above and press "Check Licence".', 'cocart-pro' ); ?></p-->

			<!--p style="text-align: center;">
				<?php printf( '<a class="button button-primary button-large" href="%1$s" target="_blank">%2$s</a>', '#', esc_html__( 'Check Licence', 'cocart-pro' ) ); ?>
			</p-->
		</div>

	</div>

</div>
