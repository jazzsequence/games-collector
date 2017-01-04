<?php
/**
 * Games Collector Display.
 *
 * All the front-facing code.
 *
 * @package GC\GamesCollector\Display
 * @since   0.2
 */

namespace GC\GamesCollector\Display;
use GC\GamesCollector\Game;

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
	]);

	ob_start(); ?>

	<div class="games-collector-list">
		<?php foreach ( $games as $game ) { ?>
			<div class="game-single <?php echo esc_attr( $game->post_slug ); ?>" id="game-<?php echo absint( $game->ID ); ?>">
				<?php // Output a link if one was saved.
				if ( $link = get_post_meta( $game->ID, '_gc_link', true ) ) { ?>
					<a href="<?php echo esc_url( $link ); ?>">
				<?php } ?>

				<span class="game-title" id="game-<?php echo absint( $game->ID ); ?>-title"><?php echo wp_kses_post( $game->post_title ); ?></span>

				<?php if ( $link ) { ?>
					</a>
				<?php }

				$players_min_max = Game\get_players_min_max( $game->ID );
				// Output game info. ?>

				<div class="game-info" id="game-<?php echo absint( $game->ID ); ?>-info">
					<?php if ( isset( $players_min_max['min'] ) ) { ?>
						<span class="gc-icon icon-game-players"></span><span class="game-num-players" id="game-<?php echo absint( $game->ID ); ?>-num-players"><?php echo esc_attr( sprintf(
							// Translators: 1: Minimum number of players, 2: Maximum number of players.
							__( '%1$d %2$s players', 'games-collector' ),
							absint( $players_min_max['min'] ),
							isset( $players_min_max['max'] ) ? sprintf( '- %d', absint( $players_min_max['max'] ) ) : ''
						) ); ?></span>
					<?php } ?>

					<?php if ( $playing_time = get_post_meta( $game->ID, '_gc_time', true ) ) { ?>
						<span class="gc-icon icon-game-time"></span><span class="game-playing-time" id="game-<?php echo absint( $game->ID ); ?>-playing-time"><?php echo esc_html( sprintf( __( '%s minutes', 'games-collector' ), $playing_time ) ); ?></span>
					<?php } ?>

					<?php if ( $age = get_post_meta( $game->ID, '_gc_age', true ) ) { ?>
						<span class="gc-icon icon-game-age"></span><span class="game-age" id="game-<?php echo absint( $game->ID ); ?>-age"><?php echo esc_html( sprintf( '%d+', $age ) ); ?></span>
					<?php } ?>

					<?php if ( $difficulty = get_post_meta( $game->ID, '_gc_difficulty', true ) ) { ?>
						<span class="gc-icon icon-game-difficulty"></span><span class="game-difficulty" id="game-<?php echo absint( $game->ID ); ?>-difficulty"><?php echo esc_html( Game\get_difficulties( $difficulty ) ); ?></span>
					<?php } ?>

					<?php echo get_the_term_list( $game->ID, 'gc_attribute', '<span class="gc-icon icon-game-attributes"></span>
					<span class="game-attributes" id="game-' . absint( $game->ID ) . '-attributes">', ', ', '</span>' ); ?>
					</div>
					</div>
					<?php } ?>
	</div>

	<?php $content = ob_get_clean();
	return $content;
}
