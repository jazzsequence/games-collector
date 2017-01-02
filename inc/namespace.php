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
	add_action( 'cmb2_init', __NAMESPACE__ . '\\Game\\fields' );
	add_action( 'init',      __NAMESPACE__ . '\\Game\\register_cpt' );
}
