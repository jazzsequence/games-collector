<?php
/**
 * Games Collector
 *
 * @package GC\GamesCollector
 */

namespace GC\GamesCollector;

/**
 * Hook all the things.
 *
 * @since  0.1
 */
function bootstrap() {
	// Add all your plugin hooks here.
	add_action( 'cmb2_admin_init', __NAMESPACE__ . '\\BGG\\fields' );
	add_action( 'cmb2_init', __NAMESPACE__ . '\\Game\\fields' );
	add_action( 'cmb2_render_bgg_search', __NAMESPACE__ . '\\BGG\\render_cmb2_bgg_search', 10, 5 );
	add_action( 'cmb2_render_bgg_search_reset', __NAMESPACE__ . '\\BGG\\render_cmb2_bgg_search_reset', 10, 5 );
	add_action( 'init', __NAMESPACE__ . '\\Game\\register_cpt' );
	add_action( 'init', __NAMESPACE__ . '\\Attributes\\register_taxonomy' );
	add_action( 'admin_init', __NAMESPACE__ . '\\Attributes\\create_default_attributes' );
	add_action( 'add_meta_boxes', __NAMESPACE__ . '\\Attributes\\metabox' );
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\Attributes\\enqueue_scripts' );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\Display\\enqueue_scripts' );
	add_action( 'register_shortcode_ui', __NAMESPACE__ . '\\Shortcode\\register_all_games_shortcode' );
	add_action( 'register_shortcode_ui', __NAMESPACE__ . '\\Shortcode\\register_individual_games_shortcode' );
	add_action( 'admin_post_bgg_search_response', __NAMESPACE__ . '\\BGG\\search_response' );
	add_action( 'admin_post_bgg_insert_game', __NAMESPACE__ . '\\BGG\\insert_game' );
	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\Gutenberg\\enqueue_block_editor_assets' );
	add_action( 'wp', __NAMESPACE__ . '\\Gutenberg\\register_blocks' );
	add_filter( 'rest_prepare_gc_game', __NAMESPACE__ . '\\Api\\filter_games_json', 10, 2 );
	add_filter( 'gc_filter_players', __NAMESPACE__ . '\\numbers_of_players', 10, 3 );
	add_filter( 'custom_menu_order', __NAMESPACE__ . '\\BGG\\submenu_order' );
	add_shortcode( 'games-collector', __NAMESPACE__ . '\\Shortcode\\shortcode' );
	add_shortcode( 'games-collector-list', __NAMESPACE__ . '\\Shortcode\\shortcode' );
}

/**
 * Create a new page with the games-collector shortcode in it on activation if a Games page doesn't already exist.
 *
 * @since  1.1.0
 */
function activate() {
	$existing_page = get_posts( [
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'title'          => esc_html__( 'Games', 'games-collector' ),
	] );

	if ( ! $existing_page ) {
		wp_insert_post([
			'post_type'    => 'page',
			'post_title'   => esc_html__( 'Games', 'games-collector' ),
			'post_content' => '[games-collector]',
			'post_status'  => 'publish',
		] );
	}
}

/**
 * Update the number of players.
 *
 * Adds conditions for games with specific number of players only (e.g. 1 player, 2 players) or an indeterminate or very large number of players (e.g. 2+, 2-99 players).
 *
 * @since  1.2.0
 * @param  int    $game_id         The game's post ID.
 * @param  array  $players_min_max The minimum and maximum number of players.
 * @param  string $output          The player output.
 * @return string                  The filtered output.
 */
function numbers_of_players( $game_id, $players_min_max, $output ) {

	// Deal with max number of players matching min number of players.
	if ( absint( $players_min_max['min'] ) === absint( $players_min_max['max'] ) ) {
		return esc_attr( sprintf(
			// Translators: %d is the number of players.
			_n( '%d player', '%d players', absint( $players_min_max['min'] ), 'games-collector' ),
			absint( $players_min_max['min'] )
		) );
	}

	// Deal with indeterminate or large number of max players.
	if ( 0 === absint( $players_min_max['max'] ) || 20 <= absint( $players_min_max['max'] ) ) {
		return esc_attr( sprintf(
			// Translators: %d is the minimum number players.
			__( '%d+ players', 'games-collector' ),
			absint( $players_min_max['min'] )
		) );
	}

	return $output;
}
