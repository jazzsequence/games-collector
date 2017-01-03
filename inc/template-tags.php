<?php
/**
 * Game Collector Template Tags
 *
 * Public helper functions to use in themes and add-ons.
 *
 * @package GC\GamesCollector
 * @since   0.2
 */

/**
 * Returns the difficulty for a game.
 *
 * @since  0.2
 * @param  integer $post_id The ID for the game to get the difficulty for.
 * @return string           The human-readable difficulty level (not the meta value saved).
 */
function gc_get_difficulty( $post_id = 0 ) {
	return \GC\GamesCollector\Game\get_difficulty( $post_id );
}

/**
 * Echoes the difficulty for the current game. Must be used inside the Loop.
 *
 * @since 0.2
 */
function gc_the_difficulty() {
	echo esc_html( \GC\GamesCollector\Game\get_difficulty() );
}

/**
 * Returns the age range (e.g. 11+) for a game.
 *
 * @since  0.2
 * @param  int $post_id The Post ID to retrieve the minimum age for.
 * @return string      The age range for the game.
 */
function gc_get_age( $post_id = 0 ) {
	return \GC\GamesCollector\Game\get_age( $post_id );
}

/**
 * Echoes the age range (e.g. 11+) for the current game. Must be used inside the Loop.
 *
 * @since 0.2
 */
function gc_the_age() {
	echo esc_html( \GC\GamesCollector\Game\get_age() );
}

/**
 * Returns the number of players (min to max) for a game.
 *
 * @since  0.2
 * @param  int $post_id The Post ID to retrieve the number of players for.
 * @return string       The number of players for the game.
 */
function gc_get_number_of_players( $post_id = 0 ) {
	return \GC\GamesCollector\Game\get_number_of_players( $post_id );
}

/**
 * Echoes the number of players (min to max) for the current game. Must be used inside the Loop.
 *
 * @since 0.2
 */
function gc_the_number_of_players() {
	echo esc_html( \GC\GamesCollector\Game\get_number_of_players() );
}

/**
 * Get the min and max number of players for a game.
 *
 * @since  0.2
 * @param  integer $post_id The ID of the game to get the number of players from.
 * @return array            An array containing the minimum and maximum number of players for the game.
 */
function gc_get_players_min_max( $post_id = 0 ) {
	return \GC\GamesCollector\Game\get_players_min_max( $post_id );
}
