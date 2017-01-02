<?php
/**
 * Games Collector Game CPT
 *
 * @package GC\GamesCollector\Game
 * @since   0.1
 */

namespace GC\GamesCollector\Game;

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
