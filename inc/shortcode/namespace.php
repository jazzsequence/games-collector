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
function shortcode() {
	$games = get_posts([
		'posts_per_page' => -1,
		'post_type'      => 'gc_game',
		'orderby'        => 'title',
		'order'          => 'ASC',
	]);

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

function shortcode_ui() {
	shortcode_ui_register_for_shortcode(
		'games-collector',
		[
			'label' => esc_html__( 'Games', 'games-collector' ),
			'listItemImage' => '<img src="' . Display\get_svg( 'dice' ) . '" />',
		]
	);
}

