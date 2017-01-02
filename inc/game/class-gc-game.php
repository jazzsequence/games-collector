<?php
/**
 * Games Collector Gc_game
 *
 * @ since0.1
 *
 * @package Games Collector
 */

namespace GC\GamesCollector\Game;

/**
 * Games Collector gc_game post type class.
 *
 * @see https://github.com/WebDevStudios/CPT_Core
 * @since 0.1
 */
class GC_Game extends CPT_Core {
	/**
	 * Parent plugin class
	 *
	 * @var Games_Collector
	 * @since  0.1
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 * Register Custom Post Types. See documentation in CPT_Core, and in wp-includes/post.php
	 *
	 * @since  0.1
	 */
	public function __construct() {}


	/**
	 * Add custom fields to the CPT
	 *
	 * @since  0.1
	 */
	public function fields() {
		$prefix = 'games_collector_gc_game_';

		$cmb = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => __( 'Games Collector Gc_game Meta Box', 'games-collector' ),
			'object_types'  => array( 'games-collector-gc-game' ),
		) );
	}

	/**
	 * Registers admin columns to display. Hooked in via CPT_Core.
	 *
	 * @since  0.1
	 * @param  array $columns Array of registered column names/labels.
	 * @return array          Modified array
	 */
	public function columns( $columns ) {
		$new_column = array();
		return array_merge( $new_column, $columns );
	}

	/**
	 * Handles admin column display. Hooked in via CPT_Core.
	 *
	 * @since 0.1
	 * @param array $column  Column currently being rendered.
	 * @param int   $post_id ID of post to display column for.
	 */
	public function columns_display( $column, $post_id ) {
		switch ( $column ) {
		}
	}
}
