<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since      0.1.0
 *
 * @package    Monk
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$wpdb->query( "DELETE meta_id, term_id, meta_key, meta_value FROM wp_termmeta WHERE meta_key LIKE '_monk_%';" );
$wpdb->query( "DELETE meta_id, post_id, meta_key, meta_value FROM wp_postmeta WHERE meta_key LIKE '_monk_%';" );

delete_option( 'monk_default_language' );
delete_option( 'monk_active_languages' );
