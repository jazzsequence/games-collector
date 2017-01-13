<?php
/**
 * Unit tests for attributes namespace.
 *
 * @package GC\GamesCollector
 */

use GC\GamesCollector\Attributes;

/**
 * Games Collector Attributes unit test class.
 */
class GC_Test_Attributes extends WP_UnitTestCase {
	/**
	 * Test that the Attribute taxonomy was created.
	 *
	 * @covers GC\GamesCollector\Attributes\register_taxonomy
	 */
	function test_attributes_taxonomy_exists() {
		$this->assertTrue(
			taxonomy_exists( 'gc_attribute' ),
			'The Game Attributes taxonomy does not exist.'
		);
	}

}
