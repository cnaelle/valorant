<?php
defined( 'WP_UNINSTALL_PLUGIN' ) || die( 'Cheatin&#8217; uh?' );

// Delete all transients.
delete_site_transient( md5( COCART_ACF_SLUG ) . '_latest' ); // Clear latest release.
delete_site_transient( md5( COCART_ACF_SLUG ) . '_timeout' ); // Clear timeout if any.

// Delete options.
delete_site_option( 'cocart_acf_install_date' );
delete_option( 'cocart_acf_version' );