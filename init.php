<?php
/**
 * Plugin Name: WFCCBPCI | Filter Child Categories By Parent Category ID
 * Plugin URI: https://tu-dev.com/plugins/widget-filter-child-categories-by-parent-category-id
 * Description: <code><strong>WSSCBIDP | Filter child categories by parent category ID</strong></code>
 * Version: 1.0.0
 * Author: TUNGUYEN
 * Author URI: https://tu-dev.com
 * Text Domain: widget-filter-child-categories-by-parent-category-id
 * Domain Path: /languages/
 * WC requires at least: 7.9
 * WC tested up to: 8.1
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! defined( 'WFCCBPCI' ) ) {
	define( 'WFCCBPCI', true );
}

if ( ! defined( 'WFCCBPCI_URL' ) ) {
	define( 'WFCCBPCI_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'WFCCBPCI_DIR' ) ) {
	define( 'WFCCBPCI_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WFCCBPCI_BASE' ) ) {
	define( 'WFCCBPCI_BASE', plugin_basename( __DIR__ ) );
}


if ( ! function_exists( 'enqueue_plugin_assets' ) && ! function_exists( 'client_enqueue_plugin_assets' ) && ! function_exists( 'admin_enqueue_plugin_assets' ) ) {

	function client_enqueue_plugin_assets(): void {
		wp_register_style( 'WFCCBPCI-style-widget', WFCCBPCI_URL . 'assets/css/client/' . 'WFCCBPCI-style-widget.css' );
		wp_register_script( 'WFCCBPCI-main', WFCCBPCI_URL . 'assets/js/client/' . 'WFCCBPCI-main.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'WFCCBPCI-style-widget' );
		wp_enqueue_script( 'WFCCBPCI-main' );
	}

	function admin_enqueue_plugin_assets(): void {
		wp_register_style( 'WFCCBPCI-admin-style-widget', WFCCBPCI_URL . 'assets/css/admin/' . 'WFCCBPCI-admin-style-widget.css' );
		wp_register_script( 'WFCCBPCI-admin-main', WFCCBPCI_URL . 'assets/js/admin/' . 'WFCCBPCI-admin-main.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_style( 'WFCCBPCI-admin-style-widget' );
		wp_enqueue_script( 'WFCCBPCI-admin-main' );
	}

	add_action( 'wp_enqueue_scripts', 'client_enqueue_plugin_assets', 10, 1 );
	add_action( 'admin_enqueue_scripts', 'admin_enqueue_plugin_assets', 10, 1 );

	function enqueue_plugin_assets(): void {
		client_enqueue_plugin_assets();
		admin_enqueue_plugin_assets();
	}

	register_activation_hook( __FILE__, 'enqueue_plugin_assets' );
}
require_once( WFCCBPCI_DIR . 'settings.php' );
require_once( WFCCBPCI_DIR . 'widgets/filter-child-categories-by-parent-category-id.widget.php' );