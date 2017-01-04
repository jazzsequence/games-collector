<?php
/**
 * Games Collector Game CPT
 *
 * @package GC\GamesCollector\Game
 * @since   0.1
 */

namespace GC\GamesCollector\Game;
use GC\GamesCollector\Display;

/**
 * Register the Game CPT.
 *
 * @since 0.1
 */
function register_cpt() {
	register_extended_post_type( 'gc_game', [
			'supports'            => [ 'title' ],
			'menu_icon'           => Display\get_svg( 'dice' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_in_nav_menus'   => false,
			// Custom columns.
			'admin_cols'          => [
				'players'    => [
					'title'    => __( '# of Players', 'games-collector' ),
					'function' => __NAMESPACE__ . '\\the_number_of_players',
				],
				'time'       => [
					'title'    => __( 'Playing Time', 'games-collector' ),
					'meta_key' => '_gc_time',
				],
				'age'        => [
					'title'    => __( 'Age', 'games-collector' ),
					'function' => __NAMESPACE__ . '\\the_age',
				],
				'difficulty' => [
					'title'    => __( 'Difficulty', 'games-collector' ),
					'function' => __NAMESPACE__ . '\\the_difficulty',
				],
				'attributes' => [
					'taxonomy' => 'gc_attribute',
				],
				'date'       => [
					'title'    => __( 'Date added', 'games-collector' ),
				],
			],
			// Dropdown filters.
			'admin_filters'       => [],
		], [
			'singular'      => __( 'Game', 'games-collector' ),
			'plural'        => __( 'Games', 'games-collector' ),
			'slug'          => 'game',
		]
	);
}

/**
 * Add custom fields to the CPT
 *
 * @since  0.1
 */
function fields() {
	$prefix = '_gc_';

	$cmb = new_cmb2_box( [
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Game Details', 'games-collector' ),
		'object_types'  => [ 'gc_game' ],
	]);

	$cmb->add_field([
		'name'       => __( 'Minimum Number of Players', 'games-collector' ),
		'id'         => $prefix . 'min_players',
		'type'       => 'text_small',
		'attributes' => [
			'type'        => 'number',
			'placeholder' => '1',
		],
	]);

	$cmb->add_field([
		'name'       => __( 'Maximum Number of Players', 'games-collector' ),
		'id'         => $prefix . 'max_players',
		'type'       => 'text_small',
		'attributes' => [
			'type'        => 'number',
			'placeholder' => '4',
		],
	]);

	$cmb->add_field([
		'name'       => __( 'Playing Time', 'games-collector' ),
		'id'         => $prefix . 'time',
		'type'       => 'text_small',
		'desc'       => __( 'Average time range, in minutes (e.g. 45-90).', 'games-collector' ),
		'attributes' => [
			'placeholder' => '20-30',
		],
	]);

	$cmb->add_field([
		'name'       => __( 'Ages', 'games-collector' ),
		'id'         => $prefix . 'age',
		'type'       => 'text_small',
		'desc'       => __( 'Recommended minimum age (e.g. 10 would mean the game is best for ages 10+).', 'games-collector' ),
		'attributes' => [
			'type'        => 'number',
			'placeholder' => '10',
		],
	]);

	$cmb->add_field([
		'name'       => __( 'Difficulty Level', 'games-collector' ),
		'id'         => $prefix . 'difficulty',
		'type'       => 'radio',
		'desc'       => __( 'How difficult or complex is this game?', 'games-collector' ),
		'options'    => get_difficulties(),
		'default'    => 'easy',
	]);

	$cmb->add_field( array(
		'name'       => __( 'More Info Link', 'games-collector' ),
		'id'         => $prefix . 'link',
		'type'       => 'text_url',
		'desc'       => __( 'Link to more information for the game (e.g. Amazon, Wikipedia or Board Game Geek link).', 'games-collector' ),
		'attributes' => [
			'placeholder' => 'https://boardgamegeek.com/boardgame/random',
		],
	) );
}

/**
 * Returns the number of players (min to max) for a game.
 *
 * @since  0.2
 * @param  int $post_id The Post ID to retrieve the number of players for.
 * @return string       The number of players for the game.
 */
function get_number_of_players( $post_id = 0 ) {
	if ( 0 === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$players_min_max = get_players_min_max( $post_id );

	if ( isset( $players_min_max['min'] ) && isset( $players_min_max['max'] ) ) {
		return sprintf( '%1$s - %2$s', $players_min_max['min'], $players_min_max['max'] );
	}

	return;
}

/**
 * Get the min and max number of players for a game.
 *
 * @since  0.2
 * @param  integer $post_id The ID of the game to get the number of players from.
 * @return array            An array containing the minimum and maximum number of players for the game.
 */
function get_players_min_max( $post_id = 0 ) {
	if ( 0 === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	return [
		'min' => get_post_meta( $post_id, '_gc_min_players', true ),
		'max' => get_post_meta( $post_id, '_gc_max_players', true ),
	];
}

/**
 * Echoes the number of players (min to max) for the current game.
 *
 * @since 0.2
 */
function the_number_of_players() {
	global $post;
	echo esc_html( get_number_of_players( $post->ID ) );
}

/**
 * Returns the age range (e.g. 11+) for a game.
 *
 * @since  0.2
 * @param  int $post_id The Post ID to retrieve the minimum age for.
 * @return string      The age range for the game.
 */
function get_age( $post_id = 0 ) {
	if ( 0 === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$age = get_post_meta( $post_id, '_gc_age', true );

	if ( $age ) {
		return sprintf( '%s+', $age );
	}

	return;
}

/**
 * Echoes the age range (e.g. 11+) for the current game.
 *
 * @since 0.2
 */
function the_age() {
	global $post;
	echo esc_html( get_age( $post->ID ) );
}

/**
 * Returns the difficulty for a game.
 *
 * @since  0.2
 * @param  integer $post_id The ID for the game to get the difficulty for.
 * @return string           The human-readable difficulty level (not the meta value saved).
 */
function get_difficulty( $post_id = 0 ) {
	if ( 0 === $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$difficulty = get_post_meta( $post_id, '_gc_difficulty', true );
	return get_difficulties( $difficulty );
}

/**
 * Echoes the difficulty for the current game.
 *
 * @since 0.2
 */
function the_difficulty() {
	global $post;
	echo esc_html( get_difficulty( $post->ID ) );
}

/**
 * Returns an array of difficulties or a single difficulty if a valid difficulty is passed.
 *
 * @since 0.2
 * @param  string $difficulty A valid difficulty to match. If none is passed, will return all the difficulties.
 * @return array The array of game difficulties.
 */
function get_difficulties( $difficulty = '' ) {
	$difficulties = [
		'easy'      => __( 'Easy', 'games-collector' ),
		'moderate'  => __( 'Moderate', 'games-collector' ),
		'difficult' => __( 'Difficult', 'games-collector' ),
		'hardcore'  => __( 'Hard Core (experienced gamers only!)', 'games-collector' ),
	];

	if ( '' === $difficulty ) {
		return $difficulties;
	}

	if ( isset( $difficulties[ $difficulty ] ) ) {
		return $difficulties[ $difficulty ];
	}

	return __( 'Invalid difficulty given.', 'games-collector' );
}
