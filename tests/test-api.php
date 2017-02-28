<?php
/**
 * Unit tests for Game Collector API integration.
 *
 * @since   1.0.0
 * @package GC\GamesCollector
 */

use GC\GamesCollector;

/**
 * Games Collector API unit test class.
 */
class GC_Test_Game_Collector_API extends WP_UnitTestCase {

	/**
	 * Test that the REST endpoint is accessible and returns data we expect.
	 *
	 * @since  1.1.0
	 * @covers nothing
	 */
	public function test_api_endpoint() {
		// Test that we can hit the endpoint.
		$response = wp_remote_get( get_home_url( '/wp-json/wp/v2/games/' ) );
		$response_code = wp_remote_retrieve_response_code( $response );

		$this->assertEquals(
			$response_code,
			200,
			sprintf( 'Did not get a 200 OK status code fetching from the API. Response returned was %s: %s', $response_code, wp_remote_retrieve_response_message( $response ) )
		);

		// Test that we can retrieve the post we created from the endpoint.
		$response = wp_remote_get( get_home_url( '/wp-json/wp/v2/games/' . $post_id ) );
		$response_code = wp_remote_retrieve_response_code( $response );
		$this->assertEquals(
			$response_code,
			200,
			sprintf( 'Did not get a 200 OK status code fetching a post from the API. Response returned was %s: %s', $response_code, wp_remote_retrieve_response_message( $response ) )
		);
	}
}
