<?php
/**
 * Unit tests for Game namespace.
 *
 * @package GC\GamesCollector
 */

use GC\GamesCollector\Game;

/**
 * Games Collector Game unit test class.
 */
class GC_Test_Game extends WP_UnitTestCase {
	/**
	 * Creates a test post.
	 *
	 * @covers Nothing.
	 * @return int Test post ID.
	 */
	function create_post() {
		$post_id = $this->factory->post->create( [ 'post_title' => 'Wizard School' ] );
		return $post_id;
	}

	/**
	 * Adds players to test post.
	 *
	 * @param  int $post_id Optional. Post ID of the post to add players to.
	 * @covers Nothing.
	 */
	function add_players( $post_id = 0 ) {
		$post_id = ( 0 === $post_id ) ? $this->create_post() : absint( $post_id );
		add_post_meta( $post_id, '_gc_min_players', 2 );
		add_post_meta( $post_id, '_gc_max_players', 4 );
	}

	/**
	 * Adds an age to test post.
	 *
	 * @param integer $post_id Optional. Post ID of the post to add player age to.
	 */
	function add_age( $post_id = 0 ) {
		$post_id = ( 0 === $post_id ) ? $this->create_post() : absint( $post_id );
		add_post_meta( $post_id, '_gc_age', 8 );
	}

	/**
	 * Adds a difficulty to test post.
	 *
	 * @param integer $post_id Optional. Post ID of the post to add player age to.
	 */
	function add_difficulty( $post_id = 0 ) {
		$post_id = ( 0 === $post_id ) ? $this->create_post() : absint( $post_id );
		add_post_meta( $post_id, '_gc_difficulty', 'moderate' );
	}

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

	/**
	 * Makes sure a game can be created.
	 *
	 * @covers GC\GamesCollector\Game\register_cpt
	 */
	function test_game_post() {
		$post_id = $this->create_post();
		$this->assertTrue(
			! is_wp_error( $post_id ),
			'Test game was not created successfully.'
		);
	}

	/**
	 * Make sure that the number of players saves and returns the right range.
	 *
	 * @covers GC\GamesCollector\Game\get_number_of_players
	 */
	function test_get_number_of_players() {
		$post_id = $this->create_post();
		$this->add_players( $post_id );

		$this->assertEquals(
			GC\GamesCollector\Game\get_number_of_players( $post_id ),
			'2 - 4',
			'Get number of players did not return correct number of players.'
		);
	}

}
