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
	 * Used to test queries.
	 * @var string
	 */
	public static $test_query;

	/**
	 * Used to test game ids.
	 * @var int
	 */
	public static $test_id;

	public function __construct() {
		parent::__construct();

		self::$test_query = 'hero realms';
		self::$test_id    = 36218;
	}
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
		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', self::$test_query ) . '&type=boardgame',
			BGG\bgg_search( self::$test_query )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', self::$test_query ) . '&type=boardgame',
			BGG\bgg_search( self::$test_query, 'somearbitrarytypethatisnotsupported' )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', self::$test_query ) . '&type=videogame',
			BGG\bgg_search( self::$test_query, 'videogame' )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', self::$test_query ) . '&type=boardgameaccessory',
			BGG\bgg_search( self::$test_query, 'boardgameaccessory' )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', self::$test_query ) . '&type=rpgitem',
			BGG\bgg_search( self::$test_query, 'rpgitem' )
		);

		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi/search?search=' . str_replace( ' ', '+', self::$test_query ) . '&type=boardgameexpansion',
			BGG\bgg_search( self::$test_query, 'boardgameexpansion' )
		);
	}

	/**
	 * Test BGG game helper.
	 *
	 * @since  1.2.0
	 * @covers GC\GamesCollector\BGG\bgg_game()
	 */
	public function test_bgg_game() {
		$this->assertEquals(
			'https://www.boardgamegeek.com/xmlapi2/thing?id=36218',
			BGG\bgg_game( self::$test_id )
		);
	}

	/**
	 * Test get search results.
	 *
	 * @since  1.2.0
	 * @covers GC\GamesCollector\BGG\get_bgg_search_results()
	 */
	public function test_get_bgg_search_results() {
		$results = BGG\get_bgg_search_results( self::$test_query );

		$this->assertTrue(
			is_array( $results )
		);

		// Get all the names from the search results.
		$names = wp_list_pluck( $results, 'name' );
		// Get the index of the game that matches the query exactly (we're uppercasing the query so it will be an exact match).
		$index = array_search( ucwords( self::$test_query ), $names, true );
		// Save the matching result to a variable.
		$game  = $results[ $index ];

		$this->assertEquals(
			ucwords( self::$test_query ),
			$game['name']
		);

		$this->assertEquals(
			'2016',
			$game['year']
		);

		$this->assertTrue(
			is_int( $game['id'] )
		);
	}

	/**
	 * Test that the data we get from get_bgg_game matches what we expect for the test game id.
	 *
	 * @since  1.2.0
	 * @covers GC\GamesCollector\BGG\get_bgg_game()
	 */
	public function test_get_bgg_game() {
		$game = BGG\get_bgg_game( self::$test_id );

		$this->assertTrue(
			is_array( $game )
		);

		$this->assertEquals(
			'Dominion',
			$game['title']
		);

		$this->assertTrue(
			is_array( $game['categories'] )
		);
	}
}