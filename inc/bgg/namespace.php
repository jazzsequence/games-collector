<?php
/**
 * Games Collector BoardGameGeek API integration.
 *
 * Integrates BoardGameGeek's XML API into Games Collector to allow game data to be imported.
 *
 * @package GC\GamesCollector\BGG
 * @since   1.2.0
 */

namespace GC\GamesCollector\BGG;

/**
 * Return the BGG v1 API endpoint.
 *
 * @since  1.2.0
 * @return string The BGG v1 endpoint.
 */
function bgg_api() {
	return esc_url( 'https://www.boardgamegeek.com/xmlapi/' );
}

/**
 * Return the BGG v2 (beta) API endpoint.
 *
 * @since  1.2.0
 * @return string The BGG v2 endpoint.
 */
function bgg_api2() {
	return esc_url( 'https://www.boardgamegeek.com/xmlapi2/' );
}

/**
 * Return the BGG search endpoint for a particular query.
 *
 * @since  1.2.0
 * @param  string $query The search query.
 * @param  string $type  The type of search (optional). Allowed values are rpgitem, videogame, boardgame, boardgameaccessory or boardgameexpansion.
 * @return string        The BGG search API URL.
 */
function bgg_search( $query, $type = 'boardgame' ) {
	$query = str_replace( ' ', '+', $query );
	$type  = in_array( $type, [ 'rpgitem', 'videogame', 'boardgame', 'boardgameaccessory', 'boardgameexpansion' ] ) ? $type : 'boardgame';

	return esc_url( sprintf(
		'%1$ssearch?search=%2$s&type=%3$s',
		bgg_api(),
		esc_html( $query ),
		esc_html( $type )
	) );
}
