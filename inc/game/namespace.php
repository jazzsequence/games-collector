<?php
/**
 * Games Collector Game CPT
 *
 * @package GC\GamesCollector\Game
 * @since   0.1
 */

namespace GC\GamesCollector\Game;

function register_cpt() {
	register_extended_post_type( 'gc_game', [
			'supports'      => [ 'title' ],
			'menu_icon'     => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj4KPHBhdGggZmlsbD0iI2EwYTVhYSIgZD0iTTI3IDZoLTE2Yy0yLjc1IDAtNSAyLjI1LTUgNXYxNmMwIDIuNzUgMi4yNSA1IDUgNWgxNmMyLjc1IDAgNS0yLjI1IDUtNXYtMTZjMC0yLjc1LTIuMjUtNS01LTV6TTEzIDI4Yy0xLjY1NyAwLTMtMS4zNDMtMy0zczEuMzQzLTMgMy0zIDMgMS4zNDMgMyAzLTEuMzQzIDMtMyAzek0xMyAxNmMtMS42NTcgMC0zLTEuMzQzLTMtM3MxLjM0My0zIDMtMyAzIDEuMzQzIDMgMy0xLjM0MyAzLTMgM3pNMTkgMjJjLTEuNjU3IDAtMy0xLjM0My0zLTNzMS4zNDMtMyAzLTMgMyAxLjM0MyAzIDMtMS4zNDMgMy0zIDN6TTI1IDI4Yy0xLjY1NyAwLTMtMS4zNDMtMy0zczEuMzQzLTMgMy0zIDMgMS4zNDMgMyAzLTEuMzQzIDMtMyAzek0yNSAxNmMtMS42NTcgMC0zLTEuMzQzLTMtM3MxLjM0My0zIDMtMyAzIDEuMzQzIDMgMy0xLjM0MyAzLTMgM3pNMjUuODk5IDRjLTAuNDY3LTIuMjc1LTIuNDkxLTQtNC44OTktNGgtMTZjLTIuNzUgMC01IDIuMjUtNSA1djE2YzAgMi40MDggMS43MjUgNC40MzIgNCA0Ljg5OXYtMTkuODk5YzAtMS4xIDAuOS0yIDItMmgxOS44OTl6Ij48L3BhdGg+Cjwvc3ZnPgo=',
			// Custom columns.
			'admin_cols'    => [],
			// Dropdown filters.
			'admin_filters' => [],
		], [
			'singular'      => __( 'Game', 'games-collector' ),
			'plural'        => __( 'Games', 'games-collector' ),
			'slug'          => 'game',
		]
	);
}

/**
 * Add custom fields to the CPT
 *
 * @since  0.1
 */
function fields() {
	$prefix = '_gc_';

	$cmb = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Games Collector Gc_game Meta Box', 'games-collector' ),
		'object_types'  => array( 'games-collector-gc-game' ),
	) );
}
