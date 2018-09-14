<?php
/**
 * Games Collector Gutenberg Integration
 *
 * @package GC\GamesCollector\Gutenberg
 * @since   1.3.0
 */

namespace GC\GamesCollector\Gutenberg;

/**
 * Enqueue the Gutenberg editor js and css.
 */
function enqueue_block_editor_assets() {
	$js_file = plugin_dir_url( dirname( __FILE__, 2 ) ) . 'assets/js/editor.js';
	wp_enqueue_script( 'games-collector-gberg-editor', $js_file, [ 'wp-i18n', 'wp-blocks', 'wp-element' ], '1.3.0' );
	wp_enqueue_style( 'games-collector-gberg-editor', dirname( plugin_dir_url( __FILE__ ), 2 ) . '/assets/css/editor.css', [ 'wp-blocks' ], '1.3.0' );
	wp_enqueue_style( 'games-collector', dirname( plugin_dir_url( __FILE__ ), 2 ) . '/assets/css/main.css', [], '1.3.0' );
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

function render_add_all_games() {
	var_dump('this is a thing i am doing');
	return Shortcode\render_games();
}
