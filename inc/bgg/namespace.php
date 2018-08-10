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
