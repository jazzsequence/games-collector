<?php
/**
 * Unit tests for Game namespace.
 *
 * @since   1.0.0
 * @package GC\GamesCollector
 */

use GC\GamesCollector\Game;

/**
 * Games Collector Game unit test class.
 *
 * @since 1.0.0
 */
class GC_Test_Game extends WP_UnitTestCase {
	/**
	 * Creates a test post.
	 *
	 * @since  1.0.0
	 * @return int Test post ID.
	 */
	function create_post() {
		$post_id = $this->factory->post->create( [ 'post_title' => 'Wizard School' ] );
		return $post_id;
	}

	/**
	 * Adds players to test post.
	 *
	 * @since  1.0.0
	 * @param  int $post_id Optional. Post ID of the post to add players to.
	 */
	function add_players( $post_id = 0 ) {
		$post_id = ( 0 === $post_id ) ? $this->create_post() : absint( $post_id );
		add_post_meta( $post_id, '_gc_min_players', 2 );
		add_post_meta( $post_id, '_gc_max_players', 4 );
	}

	/**
	 * Adds an age to test post.
	 *
	 * @since 1.0.0
	 * @param integer $post_id Optional. Post ID of the post to add player age to.
	 */
	function add_age( $post_id = 0 ) {
		$post_id = ( 0 === $post_id ) ? $this->create_post() : absint( $post_id );
		add_post_meta( $post_id, '_gc_age', 8 );
	}

	/**
	 * Adds a difficulty to test post.
	 *
	 * @since  1.0.0
	 * @param integer $post_id Optional. Post ID of the post to add player age to.
	 */
	function add_difficulty( $post_id = 0 ) {
		$post_id = ( 0 === $post_id ) ? $this->create_post() : absint( $post_id );
		add_post_meta( $post_id, '_gc_difficulty', 'moderate' );
	}

	/**
	 * Adds a time range to test post.
	 *
	 * @since  1.0.0
	 * @param integer $post_id Optional. Post ID of the post to add player age to.
	 */
	function add_time( $post_id = 0 ) {
		$post_id = ( 0 === $post_id ) ? $this->create_post() : absint( $post_id );
		add_post_meta( $post_id, '_gc_time', '45 - 75' );
	}

	/**
	 * Make sure the CPT exists.
	 *
	 * @since  1.0.0
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
	 * @since  1.0.0
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
	 * @since  1.0.0
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

	/**
	 * Make sure that we can get the min/max players back in an array.
	 *
	 * @since  1.0.0
	 * @covers GC\GamesCollector\Game\get_players_min_max
	 */
	function test_get_players_min_max() {
		$post_id = $this->create_post();
		$this->add_players( $post_id );

		$this->assertEquals(
			GC\GamesCollector\Game\get_players_min_max( $post_id ),
			[ 'min' => 2, 'max' => 4 ],
			'Get players min/max did not return the correct number of players.'
		);
	}

	/**
	 * Make sure that the age range matches what was entered.
	 *
	 * @since  1.0.0
	 * @covers GC\GamesCollector\Game\get_age
	 */
	function test_get_age() {
		$post_id = $this->create_post();
		$this->add_age( $post_id );

		$this->assertEquals(
			GC\GamesCollector\Game\get_age( $post_id ),
			'8+',
			'Get age did not return the correct age range.'
		);
	}

	/**
	 * Make sure the difficulty matches the translated string.
	 *
	 * @since  1.0.0
	 * @covers GC\GamesCollector\Game\get_difficulty
	 * @covers GC\GamesCollector\Game\get_difficulties
	 */
	function test_difficulty() {
		$post_id = $this->create_post();
		$this->add_difficulty( $post_id );

		$this->assertTrue(
			is_array( GC\GamesCollector\Game\get_difficulties() ),
			'Get difficulties did not return an array.'
		);

		$this->assertEquals(
			GC\GamesCollector\Game\get_difficulty( $post_id ),
			'Moderate',
			'Get difficulty did not match the saved difficulty.'
		);
	}

	/**
	 * Make sure game length returns the correct classes.
	 *
	 * @since  1.0.0
	 * @covers GC\GamesCollector\Game\get_game_length
	 */
	function test_game_length() {
		$post_id = $this->create_post();
		$this->add_time( $post_id );
		$game_length = GC\GamesCollector\Game\get_game_length( $post_id );

		$this->assertEquals(
			$game_length,
			'long',
			( '' !== $game_length ) ? sprintf( 'Get game length returned %s instead of "long".', $game_length ) : 'Get game length did not return short or long when "long" was expected.'
		);
	}
}
