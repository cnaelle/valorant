<?php
/**
 * Admin View: CoCart Products not installed or activated notice.
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
<div class="notice notice-warning cocart-notice">
	<div class="cocart-notice-inner">
		<div class="cocart-notice-icon">
			<img src="<?php echo COCART_ACF_URL_PATH . '/assets/images/logo.jpg'; ?>" alt="<?php echo esc_attr__( 'CoCart, a WooCommerce REST-API extension', 'cocart-acf' ); ?>" />
		</div>

		<div class="cocart-notice-content">
			<h3><?php echo sprintf( __( '%1$s %2$s requires %3$s to be installed and activated.', 'cocart-acf' ), 'CoCart', 'Advanced Custom Fields', 'CoCart Products' ); ?></h3>

			<p>
			<?php
			if ( ! is_plugin_active( 'cocart-products/cocart-products.php' ) && file_exists( WP_PLUGIN_DIR . '/cocart-products/cocart-products.php' ) ) :

				if ( current_user_can( 'activate_plugin', 'cocart-products/cocart-products.php' ) ) :

					echo sprintf( '<a href="%1$s" class="button button-primary" aria-label="%2$s">%2$s</a>', esc_url( wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=cocart-products/cocart-products.php&plugin_status=active' ), 'activate-plugin_cocart-products/cocart-products.php' ) ), sprintf( esc_html__( 'Activate %s', 'cocart-acf' ), 'CoCart Products' ) );

				else :

					echo spritnf( esc_html__( 'As you do not have permission to activate a plugin. Please ask a site administrator to activate %s for you.', 'cocart-acf' ), 'CoCart Products' );

				endif;

			else:

				echo '<a href="' . esc_url( 'https://cocart.xyz/add-ons/products/' ) . '" class="button button-primary" aria-label="' . sprintf( esc_html__( 'Purchase %s', 'cocart-acf' ), 'CoCart Products' ) . '">' . sprintf( esc_html__( 'Purchase %s', 'cocart-acf' ), 'CoCart Products' ) . '</a>';

			endif;

			if ( current_user_can( 'deactivate_plugin', plugin_basename( COCART_ACF_FILE ) ) ) :

				echo sprintf( 
					' <a href="%1$s" class="button button-secondary" aria-label="%2$s">%2$s</a>', 
					esc_url( wp_nonce_url( 'plugins.php?action=deactivate&plugin=' . plugin_basename( COCART_ACF_FILE ) . '&plugin_status=inactive', 'deactivate-plugin_' . plugin_basename( COCART_ACF_FILE ) ) ),
					sprintf( esc_html__( 'Turn off the %s %s plugin', 'cocart-acf' ), 'CoCart', 'Advanced Custom Fields' )
				);

			endif;
			?>
			</p>
		</div>
	</div>
</div>