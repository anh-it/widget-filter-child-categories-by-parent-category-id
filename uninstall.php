<?php
/**
 * Uninstall plugin
 */

// If uninstall not called from WordPress exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( defined( 'WFCCBPCI_URL' ) ) {
	unset( $GLOBALS['WFCCBPCI_URL'] );
}

function dequeue_plugin_assets(): void {
	wp_dequeue_style( 'WFCCBPCI-style-widget-css' );
	wp_dequeue_script( 'WFCCBPCI-main-js' );
	wp_dequeue_style( 'WFCCBPCI-admin-style-widget-css' );
	wp_dequeue_script( 'WFCCBPCI-admin-main-js' );
}

add_action( 'wp_enqueue_scripts', 'dequeue_plugin_assets' );

/**
 * Uninstall completely WFCCBPCI from the site
 *
 * @return void
 * @since 1.0.0
 */

function WFCCBPCI_uninstall(): void {
	register_deactivation_hook( __FILE__, 'dequeue_plugin_assets' );
}

WFCCBPCI_uninstall();
