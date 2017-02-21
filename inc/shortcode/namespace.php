<?php
/**
 * Games Collector Shortcode.
 *
 * Shortcode and Shortcode UI integration.
 *
 * @package GC\GamesCollector\Shortcode
 * @since   1.1.0
 */

namespace GC\GamesCollector\Shortcode;

use GC\GamesCollector\Game;
use GC\GamesCollector\Display;

/**
 * Shortcode output. Can also be run as a standalone function to display the list.
 *
 * @since  0.2
 * @return string Displays a list of all games.
 */
function shortcode( $atts ) {
	$atts = shortcode_atts([
		'gc_game' => '',
	], $atts );

	// Get the ID from the atts, if one was set, so we can get a single game (or all games).
	if ( '' === $atts['gc_game'] ) {
		$post_ids = 0;
	} else {
		$post_ids = $atts['gc_game'];
	}

	$games = get_games( $post_ids );
	ob_start(); ?>

	<div class="games-filter-group">
		<?php
		echo Display\get_buttons(); // WPCS: XSS ok, already sanitized.
		echo Display\get_filters(); // WPCS: XSS ok, already sanitized.
		?>
	</div>

	<div class="games-collector-list">
		<?php foreach ( $games as $game ) { ?>
			<div <?php post_class( Game\get_game_classes( 'game-single', $game->ID ), $game->ID ); ?> id="game-<?php echo absint( $game->ID ); ?>">

				<?php
				echo Display\get_game_title( $game );    // WPCS: XSS ok, already sanitized.
				echo Display\get_game_info( $game->ID ); // WPCS: XSS ok, already sanitized.
				?>

			</div>
		<?php } ?>
	</div>

	<?php $content = ob_get_clean();
	return $content;
}

/**
 * Return an array of games. If an ID is passed, will return an array with a single, specific game. Used in the shortcode.
 *
 * @since  1.1.0
 * @param  mixed $post_ids A game post ID or comma-separated list of IDs.
 * @return array            An array of Game WP_Post objects.
 */
function get_games( $post_ids = 0 ) {
	if ( 0 === $post_ids ) {
		$post_ids = false;
	} elseif ( false !== strpos( $post_ids, ',' ) ) {
		$post_ids = explode( ',', $post_ids );
	} else {
		$post_ids = [ absint( $post_ids ) ];
	}

	if ( $post_ids ) {
		// If we're only displaying select games, don't show the filters.
		add_filter( 'gc_filter_buttons',      '__return_null' );
		add_filter( 'gc_filter_game_filters', '__return_null' );

		return get_posts([
			'posts_per_page' => count( $post_ids ),
			'post_type'      => 'gc_game',
			'post__in'       => $post_ids,
		]);
	}

	return get_posts([
		'posts_per_page' => -1,
		'post_type'      => 'gc_game',
		'orderby'        => 'title',
		'order'          => 'ASC',
	]);
}

/**
 * Register the shortcode to list all games with shortcode ui.
 *
 * @since 1.1.0
 */
function register_all_games_shortcode() {
	shortcode_ui_register_for_shortcode(
		'games-collector',
		[
			'label' => esc_html__( 'All Games List', 'games-collector' ),
			'listItemImage' => '<img src="' . Display\get_svg( 'dice-alt' ) . '" />',
		]
	);
}

/**
 * Register the shortcode to list select games with shortcode ui.
 *
 * @since 1.1.0
 */
function register_individual_games_shortcode() {
	shortcode_ui_register_for_shortcode(
		'games-collector-list',
		[
			'label' => esc_html__( 'Individual Games', 'games-collector' ),
			'listItemImage' => '<img src="' . Display\get_svg( 'dice-alt' ) . '" />',
			'attrs' => [
				[
					'label' => esc_html__( 'Select Game(s)', 'games-collector' ),
					'type'  => 'post_select',
					'attr'  => 'gc_game',
					'query' => [ 'post_type' => 'gc_game' ],
					'multiple' => true,

				],
			],
		]
	);
}

