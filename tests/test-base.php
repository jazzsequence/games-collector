<?php
/**
 * Unit tests for base Game Collector plugin.
 *
 * @package GC\GamesCollector
 */

/**
 * Games Collector base unit test class.
 */
class GC_Test_Game_Collector_Base extends WP_UnitTestCase {
	/**
	 * Ensure the plugin is loaded and the callback is registered to 'plugins_loaded'.
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
}
