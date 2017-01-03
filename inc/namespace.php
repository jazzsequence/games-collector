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
	add_action( 'cmb2_init',             __NAMESPACE__ . '\\Game\\fields' );
	add_action( 'init',                  __NAMESPACE__ . '\\Game\\register_cpt' );
	add_action( 'init',                  __NAMESPACE__ . '\\Attributes\\register_taxonomy' );
	add_action( 'admin_init',            __NAMESPACE__ . '\\Attributes\\create_default_attributes' );
	add_action( 'add_meta_boxes',        __NAMESPACE__ . '\\Attributes\\metabox' );
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\Attributes\\enqueue_scripts' );
	add_shortcode( 'games-collector',    __NAMESPACE__ . '\\Display\\shortcode' );
}
