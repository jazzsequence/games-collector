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
	add_action( 'cmb2_init',               __NAMESPACE__ . '\\Game\\fields' );
	add_action( 'init',                    __NAMESPACE__ . '\\Game\\register_cpt' );
	add_action( 'init',                    __NAMESPACE__ . '\\Attributes\\register_taxonomy' );
	add_action( 'admin_init',              __NAMESPACE__ . '\\Attributes\\create_default_attributes' );
	add_action( 'add_meta_boxes',          __NAMESPACE__ . '\\Attributes\\metabox' );
	add_action( 'admin_enqueue_scripts',   __NAMESPACE__ . '\\Attributes\\enqueue_scripts' );
	add_action( 'wp_enqueue_scripts',      __NAMESPACE__ . '\\Display\\enqueue_scripts' );
	add_action( 'register_shortcode_ui',   __NAMESPACE__ . '\\Shortcode\\register_all_games_shortcode' );
	add_action( 'register_shortcode_ui',   __NAMESPACE__ . '\\Shortcode\\register_individual_games_shortcode' );
	add_filter( 'rest_prepare_gc_game',    __NAMESPACE__ . '\\Api\\filter_games_json', 10, 2 );
	add_filter( 'gc_filter_players',       __NAMESPACE__ . '\\specific_number_of_players', 10, 3 );
	add_shortcode( 'games-collector',      __NAMESPACE__ . '\\Shortcode\\shortcode' );
	add_shortcode( 'games-collector-list', __NAMESPACE__ . '\\Shortcode\\shortcode' );
}

/**
 * Create a new page with the games-collector shortcode in it on activation if a Games page doesn't already exist.
 *
 * @since  1.1.0
 */
function activate() {
	if ( ! get_page_by_title( esc_html__( 'Games', 'games-collector' ) ) ) {
		wp_insert_post([
			'post_type'    => 'page',
			'post_title'   => esc_html__( 'Games', 'games-collector' ),
			'post_content' => '[games-collector]',
			'post_status'  => 'publish',
		] );
	}
}

/**
 * Update the number of players if a game only allows a specific number.
 *
 * @since  1.2.0
 * @param  int    $game_id         The game's post ID.
 * @param  array  $players_min_max The minimum and maximum number of players.
 * @param  string $output          The player output.
 * @return string                  The filtered output.
 */
function specific_number_of_players( $game_id, $players_min_max, $output ) {
	if ( absint( $players_min_max['min'] ) === absint( $players_min_max['max'] ) ) {
		ob_start(); ?>

		<span class="gc-icon icon-game-players"><?php Display\the_svg( 'players', false ); ?></span><span class="game-num-players" id="game-<?php echo absint( $game_id ); ?>-num-players">
		<?php
		echo esc_attr( sprintf(
			// Translators: %d is the number of players.
			_n( '%d player', '%d players', absint( $players_min_max['min'] ), 'games-collector' ),
			absint( $players_min_max['min'] )
		) );
		?>
		</span>
		<?php

		$output = ob_get_clean();
	}

	return $output;
}
