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
		'id' => '',
	], $atts );

	// Get the ID from the atts, if one was set, so we can get a single game (or all games).
	if ( isset( $atts['id'] ) ) {
		$post_id = absint( $atts['id'] );
	} else {
		$post_id = 0;
	}

	$games = get_games( $post_id );
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
 * @param  integer $post_id A game post ID.
 * @return array            An array of Game WP_Post objects.
 */
function get_games( $post_id = 0 ) {
	$post_id = absint( $post_id );

	if ( 0 !== $post_id ) {
		return get_posts([
			'posts_per_page' => 1,
			'post_type'      => 'gc_game',
			'ID'             => $post_id,
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
 * Register the shortcode with shortcode ui.
 *
 * @since 1.1.0
 */
function shortcode_ui() {
	shortcode_ui_register_for_shortcode(
		'games-collector',
		[
			'label' => esc_html__( 'Games', 'games-collector' ),
			'listItemImage' => '<img src="' . Display\get_svg( 'dice-alt' ) . '" />',
		]
	);
}

