<?php
/**
 * Unit tests for Game namespace.
 *
 * @since   1.0.0
 * @package GC\GamesCollector
 */

use GC\GamesCollector\BGG as BGG;

/**
 * Games Collector Game unit test class.
 *
 * @since 1.2.0
 */
class GC_Test_BGG extends WP_UnitTestCase {
	/**
	 * Test the API endpoint helpers.
	 *
	 * @since  1.2.0
	 * @covers BGG\bgg_api()
	 * @covers BGG\bgg_api2()
	 */
	public function test_bgg_apis() {
		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/',
			BGG\bgg_api()
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi2/',
			BGG\bgg_api2()
		);
	}
}
