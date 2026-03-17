<?php
/**
 * Games Collector Gutenberg Integration
 *
 * @package GC\GamesCollector\Gutenberg
 * @since   1.3.0
 */

namespace GC\GamesCollector\Gutenberg;

use GC\GamesCollector\Shortcode;

/**
 * Enqueue the Gutenberg editor js and css.
 */
function enqueue_block_editor_assets() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$base   = dirname( plugin_dir_url( __FILE__ ), 2 );
	wp_enqueue_script( 'games-collector-gberg-editor', $base . '/assets/js/editor' . $suffix . '.js', [ 'wp-i18n', 'wp-blocks', 'wp-element' ], GC_VERSION );
	wp_enqueue_style( 'games-collector-gberg-editor', $base . '/assets/css/editor' . $suffix . '.css', [ 'wp-blocks' ], GC_VERSION );
	wp_enqueue_style( 'games-collector', $base . '/assets/css/main' . $suffix . '.css', [], GC_VERSION );
}

/**
 * Register the Games Collector Gutenberg blocks.
 */
function register_blocks() {
	// Bail if not using Gutenberg.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	register_block_type( 'games-collector/add-all-games', [
		'render_callback' => __NAMESPACE__ . '\\render_add_all_games',
	] );
}

/**
 * Piggyback off the shortcode for rendering the games list.
 *
 * @return string HTML for the games list.
 */
function render_add_all_games() {
	return Shortcode\render_games();
}
