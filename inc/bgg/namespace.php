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
function bgg_search( string $query, $type = 'boardgame' ) {
	$query = str_replace( ' ', '+', $query );
	$type  = in_array( $type, [ 'rpgitem', 'videogame', 'boardgame', 'boardgameaccessory', 'boardgameexpansion' ] ) ? $type : 'boardgame';

	return esc_url( sprintf(
		'%1$ssearch?search=%2$s&type=%3$s',
		bgg_api(),
		esc_html( $query ),
		esc_html( $type )
	) );
}

/**
 * Return the BGG API endpoint for a single game/entity.
 *
 * @since  1.2.0
 * @param  int $id The BGG entity ID.
 * @return string  The BGG URL.
 */
function bgg_game( int $id ) {
	return esc_url( bgg_api2() . 'thing?id=' . $id );
}

/**
 * Return the search results for a given query.
 *
 * @since  1.2.0
 * @param  string $query A search query for a game.
 * @return array         An array of possible matches.
 */
function get_bgg_search_results( $query ) {
	$response = wp_remote_get( bgg_search( $query ) );
	$results  = [];

	if ( isset( $response['response'] ) && 200 === $response['response']['code'] ) {
		$xml  = simplexml_load_string( wp_remote_retrieve_body( $response ) );

		if ( isset( $xml->boardgame ) ) {
			foreach ( $xml->boardgame as $game ) {
				$game = (array) $game;

				$results[] = [
					'id' => (int) $game['@attributes']['objectid'],
					'name' => $game['name'],
					'year' => $game['yearpublished'],
				];
			}
		}
	}

	return $results;
}

/**
 * Return the BGG data that maps to data used in Games Collector for a game.
 *
 * @since  1.2.0
 * @param  int $id The BGG game id.
 * @return array   An array of game information pulled from the entry on Board Game Geek.
 */
function get_bgg_game( $id ) {
	$response = wp_remote_get( bgg_game( $id ) );
	$data     = [];

	if ( isset( $response['response'] ) && 200 === $response['response']['code'] ) {
		$xml = simplexml_load_string( wp_remote_retrieve_body( $response ) );
		$game = $xml->item;
		$data = [
			'title'       => (string) $game->name->attributes()['value'],
			'image'       => (string) $game->image,
			'minplayers'  => (int) $game->minplayers->attributes()['value'],
			'maxplayers'  => (int) $game->maxplayers->attributes()['value'],
			'minplaytime' => (int) $game->minplaytime->attributes()['value'],
			'maxplaytime' => (int) $game->maxplaytime->attributes()['value'],
			'minage'      => (int) $game->minage->attributes()['value'],
			'categories'  => [],
		];

		$categories = [];

		foreach ( $game->link as $metadata ) {
			if ( 'boardgamecategory' === (string) $metadata->attributes()['type'] ) {
				$categories[] = (string) $metadata->attributes()['value'];
			}
		}

		$data['categories'] = ! empty( $categories ) ? $categories : [];
	}

	return $data;
}

/**
 * CMB2 field for BGG Search.
 *
 * @since 1.2.0
 */
function fields() {
	$search_results = get_transient( 'gc_last_bgg_search' );

	// First run.
	if ( ! $search_results ) {
		$cmb = new_cmb2_box( array(
			'id'           => 'bgg-search',
			'title'        => __( 'Add game from Board Game Geek', 'games-collector' ),
			'object_types' => [ 'options-page' ],
			'option_key'   => 'add_from_bgg',
			'parent_slug'  => 'edit.php?post_type=gc_game',
			'menu_title'   => __( 'Add New From BGG', 'games-collector' ),
			'save_button'  => __( 'Search for Game', 'games-collector' ),
		) );

		$cmb->add_field( array(
			'name'       => __( 'Search', 'games-collector' ),
			'id'         => 'bgg_searchform',
			'type'       => 'bgg_search',
			'desc'       => __( 'Type in the title of a game to search for that game on Board Game Geek.', 'games-collector' ),
		) );
	} else {
		// Choose the right game.
		$cmb = new_cmb2_box( array(
			'id'           => 'bgg-search-2',
			'title'        => __( 'Add game from Board Game Geek &mdash; Step 2', 'games-collector' ),
			'object_types' => [ 'options-page' ],
			'option_key'   => 'add_from_bgg',
			'parent_slug'  => 'edit.php?post_type=gc_game',
			'menu_title'   => __( 'Add New From BGG', 'games-collector' ),
			'save_button'  => __( 'Add Game', 'games-collector' ),
		) );

		$cmb->add_field( array(
			'name'       => __( 'Search Results', 'games-collector' ),
			'id'         => 'bgg_search_results',
			'type'       => 'radio',
			'desc'       => __( 'Select the game that matches your search.', 'games-collector' ),
			'options'    => bgg_search_results_options( $search_results ),
		) );

		$cmb->add_field( array(
			'id'         => 'bgg_search_results_hidden',
			'type'       => 'hidden',
			'attributes' => [
				'name' => 'action',
				'value' => 'bgg_insert_game',
			],
		) );
	}
}
