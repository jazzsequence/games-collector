<?php
/**
 * Unit tests for Shortcode namespace.
 *
 * @since   1.1.0
 * @package GC\GamesCollector
 */

use GC\GamesCollector\Shortcode as Shortcode;

/**
 * Games Collector Shortcode unit test class.
 *
 * @since 1.1.0
 */
class GC_Test_Shortcode extends WP_UnitTestCase {
	/**
	 * Hook into the unit test setup function to set some stuff up.
	 */
	public function setUp() {
		// Create some games.
		$chrononauts = $this->factory->post->create( [ 'Chrononauts' ] );
		$frog_juice  = $this->factory->post->create( [ 'Frog Juice' ] );
		$hanabi      = $this->factory->post->create( [ 'Hanabi' ] );
		$magic       = $this->factory->post->create( [ 'Magic: the Gathering' ] );
		$mp_fluxx    = $this->factory->post->create( [ 'Monty Python Fluxx' ] );
		$ramses      = $this->factory->post->create( [ 'Ramses Return' ] );

		// Add the post meta.
		update_post_meta( $chrononauts, '_gc_min_players', 1 );
		update_post_meta( $chrononauts, '_gc_max_players', 6 );
		update_post_meta( $chrononauts, '_gc_time', '20-45' );
		update_post_meta( $chrononauts, '_gc_age', 11 );
		update_post_meta( $chrononauts, '_gc_difficulty', 'easy' );

		update_post_meta( $frog_juice, '_gc_min_players', 2 );
		update_post_meta( $frog_juice, '_gc_max_players', 4 );
		update_post_meta( $frog_juice, '_gc_time', '25' );
		update_post_meta( $frog_juice, '_gc_age', 8 );
		update_post_meta( $frog_juice, '_gc_difficulty', 'easy' );

		update_post_meta( $hanabi, '_gc_min_players', 2 );
		update_post_meta( $hanabi, '_gc_max_players', 5 );
		update_post_meta( $hanabi, '_gc_time', '30' );
		update_post_meta( $hanabi, '_gc_age', 8 );
		update_post_meta( $hanabi, '_gc_difficulty', 'easy' );

		update_post_meta( $magic, '_gc_min_players', 1 );
		update_post_meta( $magic, '_gc_max_players', 8 );
		update_post_meta( $magic, '_gc_time', '15-60' );
		update_post_meta( $magic, '_gc_difficulty', 'moderate' );

		update_post_meta( $mp_fluxx, '_gc_min_players', 2 );
		update_post_meta( $mp_fluxx, '_gc_max_players', 6 );
		update_post_meta( $mp_fluxx, '_gc_time', '10-40' );
		update_post_meta( $mp_fluxx, '_gc_age', 13 );
		update_post_meta( $mp_fluxx, '_gc_difficulty', 'easy' );

		update_post_meta( $ramses, '_gc_min_players', 2 );
		update_post_meta( $ramses, '_gc_max_players', 4 );
		update_post_meta( $ramses, '_gc_time', '10-20' );
		update_post_meta( $ramses, '_gc_age', 7 );
		update_post_meta( $ramses, '_gc_difficulty', 'easy' );

		// Add the terms.
		wp_set_object_terms( $chrononauts, [ 'card', 'easy-to-learn', 'fast-paced', 'scifi', 'solo' ], 'gc_attribute' );
		wp_set_object_terms( $frog_juice, [ 'card', 'easy-to-learn', 'party' ], 'gc_attribute' );
		wp_set_object_terms( $hanabi, [ 'card', 'easy-to-learn', 'coop' ], 'gc_attribute' );
		wp_set_object_terms( $magic, [ 'card', 'deck-building', 'fantasy', 'strategy' ], 'gc_attribute' );
		wp_set_object_terms( $mp_fluxx, [ 'card', 'easy-to-learn', 'fast-paced', 'fantasy', 'based-on-film-tv' ], 'gc_attribute' );
		wp_set_object_terms( $ramses, [ 'dice', 'easy-to-learn', 'fantasy', 'historical' ], 'gc_attribute' );
	}
}
