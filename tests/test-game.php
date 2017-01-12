<?php
/**
 * Unit tests for Game namespace.
 *
 * @package GC\GamesCollector
 */

/**
 * Games Collector Game unit test class.
 */
class GC_Test_Game extends WP_UnitTestCase {
	/**
	 * Make sure the CPT exists.
	 *
	 * @covers GC\GamesCollector\Game\register_cpt
	 */
	function test_cpt_exists() {
		$this->assertTrue(
			post_type_exists( 'gc_game' ),
			'Game post type does not exist.'
		);
	}

}
