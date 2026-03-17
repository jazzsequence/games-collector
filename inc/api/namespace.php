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
 * Register the public REST API routes.
 *
 * Exposes gc_game posts via a public endpoint so headless frontends
 * can access game data without requiring authentication. The built-in
 * WP REST endpoint for gc_game is not publicly accessible because the
 * CPT is registered with publicly_queryable = false (no single-page URLs).
 *
 * Endpoint: GET /wp-json/gc/v1/games
 *
 * @since 1.4.0
 */
function register_routes() {
	register_rest_route(
		'gc/v1',
		'/games',
		[
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => __NAMESPACE__ . '\\get_games',
			'permission_callback' => '__return_true',
			'args'                => [
				'per_page' => [
					'type'              => 'integer',
					'default'           => 100,
					'minimum'           => 1,
					'maximum'           => 500,
					'sanitize_callback' => 'absint',
				],
				'page'     => [
					'type'              => 'integer',
					'default'           => 1,
					'minimum'           => 1,
					'sanitize_callback' => 'absint',
				],
				'orderby'  => [
					'type'    => 'string',
					'default' => 'title',
					'enum'    => [ 'title', 'date', 'modified', 'rand' ],
				],
				'order'    => [
					'type'    => 'string',
					'default' => 'ASC',
					'enum'    => [ 'ASC', 'DESC' ],
				],
			],
		]
	);
}

/**
 * Return all published games with their metadata.
 *
 * @since  1.4.0
 * @param  \WP_REST_Request $request The REST request object.
 * @return \WP_REST_Response         The REST response.
 */
function get_games( \WP_REST_Request $request ) {
	$per_page = $request->get_param( 'per_page' );
	$page     = $request->get_param( 'page' );
	$orderby  = $request->get_param( 'orderby' );
	$order    = $request->get_param( 'order' );

	$args = [
		'post_type'      => 'gc_game',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'paged'          => $page,
		'orderby'        => $orderby,
		'order'          => $order,
	];

	$query = new \WP_Query( $args );
	$games = [];

	foreach ( $query->posts as $post ) {
		$games[] = format_game( $post );
	}

	$response = new \WP_REST_Response( $games, 200 );
	$response->header( 'X-WP-Total', $query->found_posts );
	$response->header( 'X-WP-TotalPages', $query->max_num_pages );

	return $response;
}

/**
 * Format a single game post for the REST API response.
 *
 * @since  1.4.0
 * @param  \WP_Post $post The game post object.
 * @return array          Formatted game data.
 */
function format_game( \WP_Post $post ) {
	$min_players = get_post_meta( $post->ID, '_gc_min_players', true );
	$max_players = get_post_meta( $post->ID, '_gc_max_players', true );
	$time        = get_post_meta( $post->ID, '_gc_time', true );
	$age         = get_post_meta( $post->ID, '_gc_age', true );
	$difficulty  = get_post_meta( $post->ID, '_gc_difficulty', true );
	$link        = get_post_meta( $post->ID, '_gc_link', true );
	$bgg_id      = get_post_meta( $post->ID, '_gc_bgg_id', true );
	$attributes  = wp_get_object_terms( $post->ID, 'gc_attribute', [ 'fields' => 'slugs' ] );
	$attr_names  = wp_get_object_terms( $post->ID, 'gc_attribute', [ 'fields' => 'names' ] );

	$thumbnail_id  = get_post_thumbnail_id( $post->ID );
	$featured_image = null;

	if ( $thumbnail_id ) {
		$image_data = wp_get_attachment_image_src( $thumbnail_id, 'full' );
		$alt_text   = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
		if ( $image_data ) {
			$featured_image = [
				'id'     => $thumbnail_id,
				'url'    => $image_data[0],
				'width'  => $image_data[1],
				'height' => $image_data[2],
				'alt'    => $alt_text ?: '',
			];
		}
	}

	return [
		'id'             => $post->ID,
		'slug'           => $post->post_name,
		'date'           => $post->post_date_gmt,
		'title'          => [ 'rendered' => get_the_title( $post ) ],
		'min_players'    => $min_players ? absint( $min_players ) : null,
		'max_players'    => $max_players ? absint( $max_players ) : null,
		'time'           => $time ?: null,
		'age'            => $age ? absint( $age ) : null,
		'difficulty'     => $difficulty ?: null,
		'url'            => $link ?: null,
		'bgg_id'         => $bgg_id ? absint( $bgg_id ) : null,
		'attributes'     => is_wp_error( $attr_names ) ? [] : array_values( $attr_names ),
		'attribute_slugs' => is_wp_error( $attributes ) ? [] : array_values( $attributes ),
		'featured_image' => $featured_image,
	];
}

/**
 * Filter the Games API JSON to add post meta to games.
 *
 * Used for the built-in WP REST API endpoint (when accessible).
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
