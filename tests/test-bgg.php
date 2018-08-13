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
	 * @covers GC\GamesCollector\BGG\bgg_api()
	 * @covers GC\GamesCollector\BGG\bgg_api2()
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

	/**
	 * Test the BGG search URL helper.
	 *
	 * @since  1.2.0
	 * @covers GC\GamesCollector\BGG\bgg_search()
	 */
	public function test_bgg_search() {
		$query = 'hero realms';

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', $query ) . '&type=boardgame',
			BGG\bgg_search( $query )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', $query ) . '&type=boardgame',
			BGG\bgg_search( $query, 'somearbitrarytypethatisnotsupported' )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', $query ) . '&type=videogame',
			BGG\bgg_search( $query, 'videogame' )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', $query ) . '&type=boardgameaccessory',
			BGG\bgg_search( $query, 'boardgameaccessory' )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', $query ) . '&type=rpgitem',
			BGG\bgg_search( $query, 'rpgitem' )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', $query ) . '&type=boardgameexpansion',
			BGG\bgg_search( $query, 'boardgameexpansion' )
		);
	}

	/**
	 * Test BGG game helper.
	 *
	 * @since  1.2.0
	 * @covers GC\GamesCollector\BGG\bgg_game
	 */
	public function test_bgg_game() {
		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi2/thing?id=36218',
			BGG\bgg_game( 36218 )
		);
	}
}
