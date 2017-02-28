<?php
/**
 * Unit tests for Game Collector API integration.
 *
 * @since   1.0.0
 * @package GC\GamesCollector
 */

use GC\GamesCollector;
use GC\GamesCollector\Attributes as Attributes;

/**
 * Games Collector API unit test class.
 */
class GC_Test_Game_Collector_API extends WP_UnitTestCase {

	/**
	 * Get a game from WordPress (or create and get it).
	 *
	 * @since  1.1.0
	 * @return object WP_Post object for the game.
	 */
	private function get_game() {
		$game = get_page_by_title( 'Chrononauts', OBJECT, 'gc_game' );

	 	if ( ! $game ) {
			$post_id = $this->factory->post->create([
				'post_title' => 'Chrononauts',
				'post_type'  => 'gc_game',
			]);

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
	 * Test that the REST endpoint is accessible and returns data we expect.
	 *
	 * @since  1.1.0
	 * @covers nothing
	 */
	public function test_api_endpoint() {
		// Test that we can hit the endpoint.
		$response      = wp_remote_get( get_home_url( null, '/wp-json/wp/v2/games/' ) );
		$response_code = wp_remote_retrieve_response_code( $response );
		$this->assertEquals(
			$response_code,
			200,
			sprintf( 'Did not get a 200 OK status code fetching from the API. Response returned was %s: %s', $response_code, wp_remote_retrieve_response_message( $response ) )
		);

		// Test that we can reach a single game's endpoint.
		$post_id       = $this->get_game_by_api()->id;
		$response      = wp_remote_get( get_home_url( null, '/wp-json/wp/v2/games/' . $post_id ) );
		$response_code = wp_remote_retrieve_response_code( $response );
		$this->assertEquals(
			$response_code,
			200,
			sprintf( 'Did not get a 200 OK status code fetching a post from the API. Response returned was %s: %s', $response_code, wp_remote_retrieve_response_message( $response ) )
		);

		// Test that the game we're hitting is the one we expect.
		$wp_post  = $this->get_game();
		$api_post = $this->get_game_by_api();
		$this->assertSame(
			$wp_post->post_title,
			$api_post->title->rendered,
			'The post title for the API post did not match what we were expecting.'
		);
	}
}
