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
	wp_enqueue_script( 'games-collector-gberg-editor', dirname( plugin_dir_url( __FILE__ ), 2 ) . '/assets/js/editor.js', [ 'wp-i18n', 'wp-blocks', 'wp-element', 'wp-component' ], '1.3.0' );
	wp_enqueue_style( 'games-collector-gberg-editor', dirname( plugin_dir_url( __FILE__ ), 2 ) . '/assets/css/editor.css', [ 'wp-blocks' ], '1.3.0' );
}
