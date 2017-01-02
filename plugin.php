<?php
/**
 * Plugin Name: Games Collector
 * Plugin URI:  https://github.com/jazzsequence/games-collector
 * Description: Catalog all your tabletop (or other) games in your WordPress site and display a list of games in your collection.
 * Version:     0.1
 * Author:      Chris Reynolds
 * Author URI:  https://jazzsequence.com
 * Donate link: https://github.com/jazzsequence/games-collector
 * License:     GPLv3
 * Text Domain: games-collector
 * Domain Path: /languages
 *
 * @link https://github.com/jazzsequence/games-collector
 *
 * @package Games Collector
 * @version 0.1
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

/**
 * Built using the Human Made plugin generator!
 */

namespace GC\GamesCollector;

require_once dirname( __FILE__ ) . '/vendor/extended-cpts/extended-cpts.php';
require_once dirname( __FILE__ ) . '/vendor/cmb2/init.php';
require_once dirname( __FILE__ ) . '/inc/game/class-gc-game.php';
require_once dirname( __FILE__ ) . '/inc/namespace.php';

// Kick it off.
add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
