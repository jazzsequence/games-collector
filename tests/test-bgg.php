<?php
/**
 * Unit tests for Game namespace.
 *
 * @since   1.0.0
 * @package GC\GamesCollector
 */

use GC\GamesCollector\BGG;

/**
 * Games Collector Game unit test class.
 *
 * @since 1.2.0
 */
class GC_Test_BGG extends WP_UnitTestCase {
	/**
	 * Used to test queries.
	 *
	 * @var string
	 */
	public static $test_query;

	/**
	 * Used to test game ids.
	 *
	 * @var int
	 */
	public static $test_id;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		parent::__construct();

		self::$test_query = 'hero realms';
		self::$test_id    = 36218;
	}

	/**
	 * Get the queried game from search results.
	 *
	 * @since  1.2.0
	 * @param  array $results An array of BGG search results.
	 * @return array          The game matching the test search.
	 */
	private function get_game( $results ) {
		// Get all the names from the search results.
		$names = wp_list_pluck( $results, 'name' );
		// Get the index of the game that matches the query exactly (we're uppercasing the query so it will be an exact match).
		$index = array_search( ucwords( self::$test_query ), $names, true );
		// Save the matching result to a variable.
		$game  = $results[ $index ];

		return $game;
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

		$game = $this->get_game( $results );

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

	/**
	 * Test the helper function that returns the search results in an array that can be understood by CMB2.
	 *
	 * @since  1.2.0
	 * @covers GC\GamesCollector\BGG\bgg_search_results_options()
	 */
	public function test_search_results_options() {
		$search  = BGG\get_bgg_search_results( self::$test_query );
		$options = BGG\bgg_search_results_options( $search );

		$this->assertTrue(
			is_array( $options )
		);

		$this->assertEquals(
			count( $search ),
			count( $options )
		);

		$game = $this->get_game( $search );

		$this->assertTrue(
			array_key_exists( $game['id'], $options )
		);

		$this->assertEquals(
			sprintf(
				'<strong>%1$s</strong> [%2$s] (%3$s)',
				ucwords( self::$test_query ),
				'2016',
				$game['id']
			),
			$options[ $game['id'] ]
		);
	}

	/**
	 * Test inserting a game from BGG.
	 *
	 * @since  1.2.0
	 * @covers GC\GamesCollector\BGG\insert_game()
	 */
	public function test_insert_game() {
		// Create a nonce that matches what the insert game function looks for.
		$nonce = wp_create_nonce( 'nonce_CMB2phpbgg-search-2' );
		// Add the nonce to $_POST.
		$_POST['nonce_CMB2phpbgg-search-2'] = $nonce;
		// Add the game ID to $_POST.
		$_POST['bgg_search_results'] = self::$test_id;
		// Attempt to insert the game with the insert_game function.
		BGG\insert_game();

		// Get the game (if it was created).
		$game = get_posts([
			'post_type'   => 'gc_game',
			'post_status' => 'draft',
		])[0];

		// Get game data from BGG to compare against. We've already tested that get_bgg_game works.
		$game_data = BGG\get_bgg_game( self::$test_id );

		// Test that $game is a post object.
		$this->assertTrue(
			is_object( $game )
		);

		// Make sure the title is correct.
		$this->assertEquals(
			'Dominion',
			$game->post_title
		);

		// Test that post meta was inserted.
		$this->assertEquals(
			$game_data['minplaytime'],
			get_post_meta( $game->ID, '_gc_time', true )
		);

		$this->assertEquals(
			$game_data['minage'],
			get_post_meta( $game->ID, '_gc_age', true )
		);

		$this->assertEquals(
			self::$test_id,
			get_post_meta( $game->ID, '_gc_bgg_id', true )
		);

		// Test the attributes were added from BGG categories.
		$terms = wp_get_object_terms( $game->ID, 'gc_attribute', [ 'fields' => 'names' ] );

		foreach ( $game_data['categories'] as $attribute_name ) {
			$this->assertContains(
				$attribute_name,
				$terms
			);
		}

		// Test that the transient was deleted.
		$this->assertFalse(
			get_transient( 'gc_last_bgg_search' )
		);
	}

	/**
	 * Test the get_attribute_like function.
	 *
	 * @since  1.2.0
	 * @covers GC\GamesCollector\BGG\get_attribute_like()
	 */
	public function test_get_attribute_like() {
		$term_query = 'action';
		$term       = wp_insert_term( 'Action', 'gc_attribute' );
		$term_like  = BGG\get_attribute_like( $term_query );

		$this->assertEquals(
			$term['term_id'],
			$term_like
		);

		$this->assertSame(
			[ $term_query => $term['term_id'] ],
			get_transient( 'gc_frequently_used_attributes' )
		);
	}

	/**
	 * Test that BGG images get sideloaded and attached to posts.
	 *
	 * @since  1.2.0
	 * @covers GC\GamesCollector\BGG\attach_bgg_image()
	 */
	public function test_attach_bgg_image() {
		// Do a BGG search.
		$results = BGG\get_bgg_search_results( self::$test_query );
		// Get the matching game.
		$search = $this->get_game( $results );
		// Get the BGG data (for the image).
		$game = BGG\get_bgg_game( $search['id'] );

		// Insert a game.
		$post_id = $this->factory->post->create([
			'post_type' => 'gc_game',
		]);

		// Run attach_bgg_image for the test post with that game's image.
		BGG\attach_bgg_image( $post_id, $game );

		// Make sure the post has an image.
		$this->assertTrue(
			has_post_thumbnail( $post_id )
		);
	}
}
