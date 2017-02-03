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

/**
 * Returns a list of all games.
 *
 * @since  0.2
 * @return string All the games in formatted HTML.
 */
function gc_get_games() {
	return \GC\GamesCollector\Display\shortcode();
}

/**
 * Echoes a list of all games.
 *
 * @since 0.2
 */
function gc_the_games() {
	echo \GC\GamesCollector\Display\shortcode(); // WPCS: XSS ok. Already sanitized.
}

/**
 * Get a list of attributes for the given post. Use instead of get_term_list.
 *
 * @since  1.0.0
 * @param  integer $post_id   The post ID. If none is given, will attempt to grab one from the WP_Post object.
 * @param  string  $before    Anything before the list of attributes.
 * @param  string  $seperator Seperator between attributes (default is ", ").
 * @param  string  $after     Anything after the list of attributes.
 * @return string             The sanitized list of attributes.
 */
function gc_get_the_attribute_list( $post_id = 0, $before = '', $seperator = ', ', $after = '' ) {
	return \GC\GamesCollector\Attributes\get_the_attribute_list( $post_id, $before, $seperator, $after );
}
