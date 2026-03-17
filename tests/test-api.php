<?php
/**
 * Unit tests for Game Collector API integration.
 *
 * @since   1.0.0
 * @package GC\GamesCollector
 */

use GC\GamesCollector;
use GC\GamesCollector\Attributes;

/**
 * Games Collector API unit test class.
 *
 * @group rest-api
 */
class GC_Test_Game_Collector_API extends WP_UnitTestCase {

	/**
	 * The REST server.
	 *
	 * @var WP_Rest_Server
	 */
	protected $server;

	/**
	 * Kick off the rest api.
	 */
	public function setUp(): void {
		parent::setUp();
		global $wp_rest_server;
		$wp_rest_server = new \WP_Rest_Server();
		$this->server = $wp_rest_server;
		do_action( 'rest_api_init' );
	}

	/**
	 * Get a game from WordPress (or create and get it).
	 *
	 * @since  1.1.0
	 * @return object WP_Post object for the game.
	 */
	private function get_game() {
		$games = get_posts( [
			'title' => 'chrononauts',
			'post_type' => 'gc_game',
			'posts_per_page' => 1,
		] );

		$game = array_shift( $games ) ?: false;

		if ( ! $game ) {
			$post_id = $this->factory->post->create([
				'post_title' => 'Chrononauts',
				'post_type'  => 'gc_game',
			]);

			update_post_meta( $post_id, '_gc_min_players', 1 );
			update_post_meta( $post_id, '_gc_max_players', 6 );
			update_post_meta( $post_id, '_gc_time', '20-45' );
			update_post_meta( $post_id, '_gc_age', 11 );
			update_post_meta( $post_id, '_gc_difficulty', 'easy' );
			update_post_meta( $post_id, '_gc_link', 'https://boardgamegeek.com/boardgame/815/chrononauts' );

			return get_post( $post_id, OBJECT );
		}

		return $game;
	}

	/**
	 * Get a game from the API (or create and get it normally).
	 *
	 * @since  1.1.0
	 * @return object API post object for the game (or WP_Post object if created the normal way).
	 */
	private function get_game_by_api() {
		$response = wp_remote_get( get_home_url( null, '/wp-json/wp/v2/games?search=Chrononauts' ) );
		$results  = json_decode( wp_remote_retrieve_body( $response ) );

		if ( empty( $results ) ) {
			return $this->get_game();
		}

		return $results[0];
	}

	/**
	 * Get an attribute ID from the API for a given attribute title.
	 *
	 * @since  1.1.0
	 * @param  string $title The name of the attribute.
	 * @return int           The attribute ID from the API.
	 */
	private function get_attribute_id_by_title( $title ) {
		$response = wp_remote_get( get_home_url( null, '/wp-json/wp/v2/attributes/' ) );
		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$attributes = wp_list_pluck( json_decode( wp_remote_retrieve_body( $response ) ), 'name', 'id' );
			return array_search( $title, $attributes );
		}

		return '';
	}

	/**
	 * Test that the REST endpoint is accessible and returns data we expect.
	 *
	 * @since  1.1.0
	 */
	public function test_api_endpoint() {
		// Test that we can hit the endpoint.
		$my_route = '/wp/v2/games';
		$routes   = $this->server->get_routes();
		foreach ( $routes as $route => $route_config ) {
			if ( 0 === strpos( $my_route, $route ) ) {
				$this->assertTrue( is_array( $route_config ) );
				foreach ( $route_config as $i => $endpoint ) {
					$this->assertArrayHasKey( 'callback', $endpoint );
					$this->assertArrayHasKey( 0, $endpoint['callback'], get_class( $this ) );
					$this->assertArrayHasKey( 1, $endpoint['callback'], get_class( $this ) );
					$this->assertTrue( is_callable( [ $endpoint['callback'][0], $endpoint['callback'][1] ] ) );
				}
			}
		}

		$request = new \WP_Rest_Request( 'GET', $my_route );
		$response = $this->server->dispatch( $request );
		$this->assertEquals(
			$response->get_status(),
			200,
			sprintf( 'Did not get a 200 OK status code fetching from the API.', $response->get_status() )
		);

		// Test that we can reach a single game's endpoint.
		$post_id  = $this->get_game()->ID;
		$request  = new \WP_Rest_Request( 'GET', $my_route . '/' . $post_id );
		$response = $this->server->dispatch( $request );
		$this->assertEquals(
			$response->get_status(),
			200,
			sprintf( 'Did not get a 200 OK status code fetching a post from the API.', $response->get_status() )
		);

		// Test that the game we're hitting is the one we expect.
		$this->assertSame(
			$this->get_game()->post_title,
			$response->data['title']['rendered'],
			'The post title for the API post did not match what we were expecting.'
		);
	}

	/**
	 * Test that the attributes endpoints can be reached.
	 *
	 * @since  1.1.0
	 */
	public function test_attribute_api_endpoint() {
		// Test that we can hit the endpoint.
		$my_route = '/wp/v2/attributes';
		$routes   = $this->server->get_routes();
		foreach ( $routes as $route => $route_config ) {
			if ( 0 === strpos( $my_route, $route ) ) {
				$this->assertTrue( is_array( $route_config ) );
				foreach ( $route_config as $i => $endpoint ) {
					$this->assertArrayHasKey( 'callback', $endpoint );
					$this->assertArrayHasKey( 0, $endpoint['callback'], get_class( $this ) );
					$this->assertArrayHasKey( 1, $endpoint['callback'], get_class( $this ) );
					$this->assertTrue( is_callable( [ $endpoint['callback'][0], $endpoint['callback'][1] ] ) );
				}
			}
		}

		$request = new \WP_Rest_Request( 'GET', $my_route );
		$response = $this->server->dispatch( $request );
		$this->assertEquals(
			$response->get_status(),
			200,
			sprintf( 'Did not get a 200 OK status code fetching from the API.', $response->get_status() )
		);

		// Test that the API can return data on a specific attribute.
		Attributes\create_default_attributes();
		$term_name = 'Fantasy';
		$term     = get_term_by( 'name', $term_name, 'gc_attribute' );
		$request  = new \WP_Rest_Request( 'GET', $my_route . '/' . $term->term_id );
		$response = $this->server->dispatch( $request );
		$this->assertSame(
			$term->name,
			$response->data['name'],
			'Tried to get the attribute "Fantasy" via the API but was not able to retrieve attribute information from the API.'
		);
	}

	/**
	 * Test that the public gc/v1/games endpoint is registered.
	 *
	 * @since 1.4.0
	 */
	public function test_public_games_endpoint_is_registered() {
		$routes   = $this->server->get_routes();
		$my_route = '/gc/v1/games';

		$this->assertArrayHasKey(
			$my_route,
			$routes,
			'The /gc/v1/games route was not registered.'
		);

		$endpoint = $routes[ $my_route ];
		$this->assertArrayHasKey( 'callback', $endpoint[0], 'Endpoint is missing a callback.' );
		$this->assertTrue( is_callable( $endpoint[0]['callback'] ), 'Endpoint callback is not callable.' );
	}

	/**
	 * Test that the public gc/v1/games endpoint returns 200 and game data.
	 *
	 * @since 1.4.0
	 */
	public function test_public_games_endpoint_returns_games() {
		$this->get_game(); // Ensure at least one game exists.

		$request  = new \WP_Rest_Request( 'GET', '/gc/v1/games' );
		$response = $this->server->dispatch( $request );

		$this->assertEquals(
			200,
			$response->get_status(),
			'The /gc/v1/games endpoint did not return 200 OK.'
		);

		$data = $response->get_data();
		$this->assertIsArray( $data, 'Response data should be an array of games.' );
		$this->assertNotEmpty( $data, 'Response should contain at least one game.' );
	}

	/**
	 * Test that the public gc/v1/games endpoint requires no authentication.
	 *
	 * @since 1.4.0
	 */
	public function test_public_games_endpoint_is_public() {
		wp_set_current_user( 0 ); // Simulate unauthenticated request.

		$request  = new \WP_Rest_Request( 'GET', '/gc/v1/games' );
		$response = $this->server->dispatch( $request );

		$this->assertEquals(
			200,
			$response->get_status(),
			'The /gc/v1/games endpoint should be publicly accessible without authentication.'
		);
	}

	/**
	 * Test the response shape for a game returned by the public endpoint.
	 *
	 * @since 1.4.0
	 */
	public function test_public_games_endpoint_response_shape() {
		$this->get_game(); // Ensure a game with known meta exists.

		$request  = new \WP_Rest_Request( 'GET', '/gc/v1/games' );
		$response = $this->server->dispatch( $request );
		$games    = $response->get_data();
		$game     = $games[0];

		$required_keys = [ 'id', 'slug', 'date', 'title', 'min_players', 'max_players', 'time', 'age', 'difficulty', 'url', 'bgg_id', 'attributes', 'attribute_slugs', 'featured_image' ];
		foreach ( $required_keys as $key ) {
			$this->assertArrayHasKey(
				$key,
				$game,
				"Game response is missing expected field: {$key}"
			);
		}

		$this->assertArrayHasKey( 'rendered', $game['title'], 'title should have a rendered key.' );
	}

	/**
	 * Test that post meta values are correctly returned by the public endpoint.
	 *
	 * @since 1.4.0
	 */
	public function test_public_games_endpoint_meta_values() {
		$game_post = $this->get_game();

		$request  = new \WP_Rest_Request( 'GET', '/gc/v1/games' );
		$response = $this->server->dispatch( $request );
		$games    = $response->get_data();

		// Find our test game in the response.
		$found = null;
		foreach ( $games as $game ) {
			if ( $game['id'] === $game_post->ID ) {
				$found = $game;
				break;
			}
		}

		$this->assertNotNull( $found, 'Test game was not found in the /gc/v1/games response.' );
		$this->assertSame( 1, $found['min_players'], 'min_players should be cast to integer.' );
		$this->assertSame( 6, $found['max_players'], 'max_players should be cast to integer.' );
		$this->assertSame( '20-45', $found['time'], 'time should match the stored meta value.' );
		$this->assertSame( 11, $found['age'], 'age should be cast to integer.' );
		$this->assertSame( 'easy', $found['difficulty'], 'difficulty should match the stored meta value.' );
		$this->assertSame(
			'https://boardgamegeek.com/boardgame/815/chrononauts',
			$found['url'],
			'url should match the stored meta value.'
		);
		$this->assertIsArray( $found['attributes'], 'attributes should be an array.' );
		$this->assertIsArray( $found['attribute_slugs'], 'attribute_slugs should be an array.' );
	}

	/**
	 * Test that gc/v1/games supports pagination parameters.
	 *
	 * @since 1.4.0
	 */
	public function test_public_games_endpoint_pagination() {
		// Create a second game so we can paginate.
		$this->factory->post->create(
			[
				'post_title' => 'Ticket to Ride',
				'post_type'  => 'gc_game',
			]
		);
		$this->get_game(); // Ensure Chrononauts exists.

		$request = new \WP_Rest_Request( 'GET', '/gc/v1/games' );
		$request->set_param( 'per_page', 1 );
		$response = $this->server->dispatch( $request );

		$this->assertEquals( 200, $response->get_status() );
		$this->assertCount( 1, $response->get_data(), 'per_page=1 should return exactly one game.' );

		$headers = $response->get_headers();
		$this->assertArrayHasKey( 'X-WP-Total', $headers, 'Response should include X-WP-Total header.' );
		$this->assertArrayHasKey( 'X-WP-TotalPages', $headers, 'Response should include X-WP-TotalPages header.' );
		$this->assertGreaterThanOrEqual( 2, $headers['X-WP-Total'], 'X-WP-Total should reflect all published games.' );
	}

	/**
	 * Test the post meta values in the API JSON data.
	 *
	 * @since  1.1.0
	 */
	public function test_meta_json_filter() {
		// Get our post via the API.
		$post_id  = $this->get_game()->ID;
		$request  = new \WP_Rest_Request( 'GET', '/wp/v2/games/' . $post_id );
		$response = $this->server->dispatch( $request );

		// Test that the post meta matches what we set when we created the game.
		$this->assertSame(
			'1',
			$response->data['min_players'][0],
			'The min players in post meta did not match the value returned by the API.'
		);

		$this->assertSame(
			'6',
			$response->data['max_players'][0],
			'The max players in post meta did not match the value returned by the API.'
		);

		$this->assertSame(
			'20-45',
			$response->data['time'][0],
			'The playing time in post meta did not match the value returned by the API.'
		);

		$this->assertSame(
			'11',
			$response->data['age'][0],
			'The recommended age in post meta did not match the value returned by the API.'
		);

		$this->assertSame(
			'easy',
			$response->data['difficulty'][0],
			'The difficulty level in post meta did not match the value returned by the API.'
		);

		$this->assertSame(
			'https://boardgamegeek.com/boardgame/815/chrononauts',
			$response->data['url'][0],
			'The game link in post meta did not match the value returned by the API.'
		);
	}
}
