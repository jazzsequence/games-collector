<?php
/**
 * Plugin Name: Games Collector
 * Plugin URI:  https://github.com/jazzsequence/games-collector
 * Description: Catalog all your tabletop (or other) games in your WordPress site and display a list of games in your collection.
 * Version:     1.3.6
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
 * @version 1.3.4
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
	$cmb2_path = maybe_get_cmb2_path();

	// Add in some specific includes and vendor libraries.
	$files = [
		__DIR__ . '/inc/namespace.php',
		__DIR__ . '/inc/functions.php',
	];

	// Only add CMB2 if we found it.
	if ( $cmb2_path ) {
		$files[] = $cmb2_path;
	}

	// Check for extended cpts, load it if it hasn't already been loaded.
	if ( ! function_exists( 'register_extended_post_type' ) ) {
		$plugin_vendor = __DIR__ . '/vendor/johnbillion/extended-cpts/extended-cpts.php';
		$root_vendor = ABSPATH . '/vendor/johnbillion/extended-cpts/extended-cpts.php';
		
		// Check if extended cpts exists. If not, deactivate the plugin.
		$exists = file_exists( $plugin_vendor ) || file_exists( $root_vendor );
		if ( $exists ) {
			$files[] = $exists ? $plugin_vendor : $root_vendor;
		} else {
			// If it's not loaded, deactivate the plugin.
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	}

	// Autoload the namespaces.
	$namespaces = array_filter( glob( __DIR__ . '/inc/*' ), 'is_dir' );
	foreach ( $namespaces as $namespace ) {
		$files[] = $namespace . '/namespace.php';
	}

	// Loop through and load all the things!
	foreach ( $files as $file ) {
		require_once $file;
	}
}

/**
 * Try to get the path to the CMB2 library.
 *
 * @since 1.3.6
 */
function maybe_get_cmb2_path() {
	// If the CMB2 library is already loaded, we don't need to load it.
	if ( function_exists( 'cmb2_bootstrap' ) ) {
		return '';
	}

	// Maybe load from the vendor directory.
	if ( file_exists( __DIR__ . '/vendor/cmb2/init.php' ) ) {
		return __DIR__ . '/vendor/cmb2/init.php';
	}

	// Maybe load from the root /vendor directory.
	if ( file_exists( ABSPATH . '/vendor/cmb2/init.php' ) ) {
		return ABSPATH . '/vendor/cmb2/init.php';
	}

	// Was it installed as a plugin?
	if ( file_exists( WP_PLUGIN_DIR . '/cmb2/init.php' ) ) {
		// Activate the plugin.
		activate_plugin( 'cmb2' );
	}

	// Last chance, maybe it's in the mu-plugins directory. If it's here, it should already be activated.
	if ( file_exists( WPMU_PLUGIN_DIR . '/cmb2/init.php' ) ) {
		return WPMU_PLUGIN_DIR . '/cmb2/init.php';
	}

	// If we got here, we couldn't find CMB2.
	return '';
}

/**
 * Main initialization function.
 *
 * @since 1.1.0
 */
function init() {
	// Load all the required files.
	autoload_init();

	// If CMB2 was not loaded, deactivate ourself.
	if ( ! function_exists( 'cmb2_bootstrap' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		return;
	}

	// Register activation hook.
	register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );

	// Kick it off.
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
}

// Kick it off!
init();
