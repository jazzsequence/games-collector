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
		$post_id = $this->factory->post->create([
			'post_title' => 'Wizard School',
			'post_type' => 'gc_game',
		]);
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

	function test_specific_number_of_players_filter() {
		$post_id = $this->factory->post->create([
			'post_title' => 'Chess',
			'post_type'  => 'gc_game',
		]);

		add_post_meta( $post_id, '_gc_min_players', 2 );
		add_post_meta( $post_id, '_gc_max_players', 2 );

		$output          = GC\GamesCollector\Display\get_players( $post_id );
		$players_min_max = GC\GamesCollector\Game\get_players_min_max( $post_id );

		$this->assertSame(
			preg_replace( '/\t+/', '', GC\GamesCollector\specific_number_of_players( $post_id, $players_min_max, $output ) ),
			'<span class="gc-icon icon-game-players"><svg class="gc-icon svg gc-icon-players" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>players</title>
<path d="M9.266 14c-1.625 0.047-3.094 0.75-4.141 2h-2.094c-1.563 0-3.031-0.75-3.031-2.484 0-1.266-0.047-5.516 1.937-5.516 0.328 0 1.953 1.328 4.062 1.328 0.719 0 1.406-0.125 2.078-0.359-0.047 0.344-0.078 0.688-0.078 1.031 0 1.422 0.453 2.828 1.266 4zM26 23.953c0 2.531-1.672 4.047-4.172 4.047h-13.656c-2.5 0-4.172-1.516-4.172-4.047 0-3.531 0.828-8.953 5.406-8.953 0.531 0 2.469 2.172 5.594 2.172s5.063-2.172 5.594-2.172c4.578 0 5.406 5.422 5.406 8.953zM10 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4zM21 10c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zM30 13.516c0 1.734-1.469 2.484-3.031 2.484h-2.094c-1.047-1.25-2.516-1.953-4.141-2 0.812-1.172 1.266-2.578 1.266-4 0-0.344-0.031-0.688-0.078-1.031 0.672 0.234 1.359 0.359 2.078 0.359 2.109 0 3.734-1.328 4.062-1.328 1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z"></path>
</svg></span><span class="game-num-players" id="game-14-num-players">
2 players</span>',
			'2 player output returned default output instead.'
		);
	}
}
