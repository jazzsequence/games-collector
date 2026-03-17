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
 * @param  array $atts Array of shortcode attributes.
 * @return string      Displays a list of all games.
 */
function shortcode( $atts ) {
	$atts = shortcode_atts([
		'gc_game' => '',
	], $atts );

	// Get the ID from the atts, if one was set, so we can get a single game (or all games).
	$post_ids = null;
	if ( '' === $atts['gc_game'] ) {
		$post_ids = [];
	} elseif ( is_array( $atts['gc_game'] ) ) {
		$post_ids = $atts['gc_game'];
	} elseif ( false !== strpos( (string) $atts['gc_game'], ',' ) ) {
		$post_ids = explode( ',', $atts['gc_game'] );
	} elseif ( ! is_array( $atts['gc_game'] ) ) {
		$post_ids = [ $atts['gc_game'] ];
	}

	return render_games( $post_ids );
}

/**
 * Front-end display of games.
 *
 * @since  1.3.0
 * @param  array $post_ids An array of post IDs (or an empty array).
 * @return string          The games HTML.
 */
function render_games( $post_ids = [] ) {
	$games = get_games( $post_ids );
	ob_start(); ?>

	<div class="games-filter-group">
		<?php
		echo Display\get_buttons(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo Display\get_filters(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>

	<div class="games-collector-list">
		<?php foreach ( $games as $game ) { ?>
			<div <?php post_class( Game\get_game_classes( 'game-single', $game->ID ), $game->ID ); ?> id="game-<?php echo absint( $game->ID ); ?>">

				<?php
				echo Display\get_game_title( $game );    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo Display\get_game_info( $game->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>

			</div>
		<?php } ?>
	</div>

	<?php
	return ob_get_clean();
}

/**
 * Return an array of games. If an ID is passed, will return an array with a single, specific game. Used in the shortcode.
 *
 * @since  1.1.0
 * @param  array $post_ids  An array of game post IDs.
 * @return array            An array of Game WP_Post objects.
 */
function get_games( $post_ids = [] ) {
	if ( empty( $post_ids ) ) {
		$post_ids = false;
	}

	if ( $post_ids ) {
		// If we're only displaying select games, don't show the filters.
		add_filter( 'gc_filter_buttons', '__return_null' );
		add_filter( 'gc_filter_game_filters', '__return_null' );

		return get_posts([
			'posts_per_page' => count( $post_ids ),
			'post_type'      => 'gc_game',
			'post__in'       => $post_ids,
			'orderby'        => 'title',
			'order'          => 'ASC',
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

