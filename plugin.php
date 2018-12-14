<?php
/**
 * Plugin Name: Games Collector
 * Plugin URI:  https://github.com/jazzsequence/games-collector
 * Description: Catalog all your tabletop (or other) games in your WordPress site and display a list of games in your collection.
 * Version:     1.3.1
 * Author:      Chris Reynolds
 * Author URI:  https://jazzsequence.com
 * Donate link: https://paypal.me/jazzsequence
 * License:     GPLv3
 * Text Domain: games-collector
 * Domain Path: /languages
 *
 * @link https://github.com/jazzsequence/games-collector
 *
 * @package GamesCollector
 * @version 1.3.1
 */

/**
 * Copyright (c) 2017 Chris Reynolds (email : me@chrisreynolds.io)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace GC\GamesCollector;

/**
 * Handles autoloading the namespaces and other required files.
 *
 * @since 1.1.0
 */
function autoload_init() {
	// Add in some specific includes and vendor libraries.
	$files = [
		dirname( __FILE__ ) . '/vendor/cmb2/cmb2/init.php',
		dirname( __FILE__ ) . '/inc/namespace.php',
		dirname( __FILE__ ) . '/inc/functions.php',
	];

	// Check for extended cpts, load it if it hasn't already been loaded.
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		$files[] = dirname( __FILE__ ) . '/vendor/johnbillion/extended-cpts/extended-cpts.php';
	}

	// Autoload the namespaces.
	$namespaces = array_filter( glob( dirname( __FILE__ ) . '/inc/*' ), 'is_dir' );
	foreach ( $namespaces as $namespace ) {
		$files[] = $namespace . '/namespace.php';
	}

	// Loop through and load all the things!
	foreach ( $files as $file ) {
		require_once( $file );
	}
}

/**
 * Main initialization function.
 *
 * @since 1.1.0
 */
function init() {
	// Load all the required files.
	autoload_init();

	// Register activation hook.
	register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );

	// Kick it off.
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
}

// Kick it off!
init();
