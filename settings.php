<?php

function enable_auto_update_plugin(): void {
	$plugin_slug = 'filter-child-categories-by-parent-category-id';
	$plugins     = get_site_transient( 'update_plugins' );
	if ( isset( $plugins->response[ $plugin_slug ] ) ) {
		unset( $plugins->response[ $plugin_slug ] );
		set_site_transient( 'update_plugins', $plugins );
	}
}

add_action( 'admin_init', 'enable_auto_update_plugin' );