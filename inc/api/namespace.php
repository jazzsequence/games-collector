<?php
/**
 * Games Collector Api.
 *
 * Adds API endpoints for Games Collector games.
 *
 * @package GC\GamesCollector\Api
 * @since   1.1.0
 */

namespace GC\GamesCollector\Api;

/**
 * Filter the Games API JSON to add post meta to games.
 *
 * @since  1.1.0
 * @param  object $data    The data object being converted to JSON.
 * @param  object $post    The WP_Post object.
 * @return object          The filtered data object.
 * @link   http://wordpress.stackexchange.com/a/227517
 */
function filter_games_json( $data, $post ) {
	$min_players = get_post_meta( $post->ID, '_gc_min_players' );
	$max_players = get_post_meta( $post->ID, '_gc_max_players' );
	$time        = get_post_meta( $post->ID, '_gc_time' );
	$age         = get_post_meta( $post->ID, '_gc_age' );
	$difficulty  = get_post_meta( $post->ID, '_gc_difficulty' );
	$link        = get_post_meta( $post->ID, '_gc_link' );
	$attributes  = wp_get_object_terms( $post->ID, 'gc_attribute', [ 'fields' => 'names' ] );

	if ( $min_players ) {
		$data->data['min_players'] = $min_players;
	}

	if ( $max_players ) {
		$data->data['max_players'] = $max_players;
	}

	if ( $time ) {
		$data->data['time'] = $time;
	}

	if ( $age ) {
		$data->data['age'] = $age;
	}

	if ( $difficulty ) {
		$data->data['difficulty'] = $difficulty;
	}

	if ( $link ) {
		$data->data['url'] = $link;
	}

	if ( $attributes ) {
		$data->data['attributes'] = $attributes;
	}

	return $data;
}
