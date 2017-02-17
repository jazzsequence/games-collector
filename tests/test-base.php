<?php
/**
 * Unit tests for base Game Collector plugin.
 *
 * @since   1.0.0
 * @package GC\GamesCollector
 */

/**
 * Games Collector base unit test class.
 */
class GC_Test_Game_Collector_Base extends WP_UnitTestCase {
	/**
	 * Ensure the plugin is loaded and the callback is registered to 'plugins_loaded'.
	 *
	 * @since  1.0.0
	 * @covers GC\GamesCollector\bootstrap
	 */
	function test_plugin_loaded() {
		$this->assertTrue(
			function_exists( 'gc_the_games' ),
			'Couldn\'t find function \'gc_the_games\'.'
		);
		$this->assertEquals(
			has_action( 'plugins_loaded', 'GC\GamesCollector\bootstrap' ),
			10,
			'Bootstrap function not found.'
		);
	}

	/**
	 * Ensure that Games page was created on activation.
	 *
	 * @since  1.1.0
	 * @covers GC\GamesCollector\activate
	 */
	function test_games_page_created_on_activation() {
		$page = get_page_by_title( 'Games' );
		$this->assertTrue(
			is_object( $page ),
			'Games page was not created on activation.'
		);
	}
}
