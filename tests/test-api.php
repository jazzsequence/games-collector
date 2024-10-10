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
