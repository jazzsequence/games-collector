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
	$js_file = plugin_dir_url( dirname( __DIR__, 1 ) ) . 'assets/js/editor.js';
	wp_enqueue_script( 'games-collector-gberg-editor', $js_file, [ 'wp-i18n', 'wp-blocks', 'wp-element' ], '1.3.4' );
	wp_enqueue_style( 'games-collector-gberg-editor', dirname( plugin_dir_url( __FILE__ ), 2 ) . '/assets/css/editor.css', [ 'wp-blocks' ], '1.3.4' );
	wp_enqueue_style( 'games-collector', dirname( plugin_dir_url( __FILE__ ), 2 ) . '/assets/css/main.css', [], '1.3.4' );
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
