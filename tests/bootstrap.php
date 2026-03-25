<?php
/**
 * PHPUnit bootstrap file
 *
 * @since   1.0.0
 * @package GC\GamesCollector
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
	require dirname( __DIR__ ) . '/plugin.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

/* If a BGG API token is available in the environment (e.g. injected by CI), define it as a constant so the test suite can skip mocks and use the live API. */
if ( getenv( 'GC_BGG_API_TOKEN' ) ) {
	define( 'GC_BGG_API_TOKEN', getenv( 'GC_BGG_API_TOKEN' ) );
}

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
