<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Monk
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/monk.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

/**
 * Manually loads a fresh install of Monk.
 */
function _manually_activate_plugin() {
	// Uninstall existing installation.
	require dirname( dirname( __FILE__ ) ) . '/uninstall.php';

	// Initialize plugin.
	activate_monk();
}
tests_add_filter( 'setup_theme', '_manually_activate_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
