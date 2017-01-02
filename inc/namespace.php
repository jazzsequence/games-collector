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
	$game = new GC_Game;
	add_action( 'cmb2_init', [ $game, 'fields' ] );
}
