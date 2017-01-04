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

/**
 * Enqueue front end styles and scripts.
 *
 * @since 0.2
 */
function enqueue_scripts() {
	// Don't load in the admin.
	if ( is_admin() ) {
		return;
	}

	wp_enqueue_style( 'games-collector', dirname( dirname( plugin_dir_url( __FILE__ ) ) ) . '/assets/css/games-collector.css', [], '0.2' );
}

/**
 * Get a base64-encoded icon by name.
 *
 * @since  0.2
 * @param  string $name An icon name.
 * @return string       The base64-encoded svg.
 */
function get_svg( $name = '' ) {
	// Bail if nothing was passed.
	if ( '' === $name ) {
		return;
	}

	$icons = [
		'dice'       => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj4KPHBhdGggZmlsbD0iI2EwYTVhYSIgZD0iTTI3IDZoLTE2Yy0yLjc1IDAtNSAyLjI1LTUgNXYxNmMwIDIuNzUgMi4yNSA1IDUgNWgxNmMyLjc1IDAgNS0yLjI1IDUtNXYtMTZjMC0yLjc1LTIuMjUtNS01LTV6TTEzIDI4Yy0xLjY1NyAwLTMtMS4zNDMtMy0zczEuMzQzLTMgMy0zIDMgMS4zNDMgMyAzLTEuMzQzIDMtMyAzek0xMyAxNmMtMS42NTcgMC0zLTEuMzQzLTMtM3MxLjM0My0zIDMtMyAzIDEuMzQzIDMgMy0xLjM0MyAzLTMgM3pNMTkgMjJjLTEuNjU3IDAtMy0xLjM0My0zLTNzMS4zNDMtMyAzLTMgMyAxLjM0MyAzIDMtMS4zNDMgMy0zIDN6TTI1IDI4Yy0xLjY1NyAwLTMtMS4zNDMtMy0zczEuMzQzLTMgMy0zIDMgMS4zNDMgMyAzLTEuMzQzIDMtMyAzek0yNSAxNmMtMS42NTcgMC0zLTEuMzQzLTMtM3MxLjM0My0zIDMtMyAzIDEuMzQzIDMgMy0xLjM0MyAzLTMgM3pNMjUuODk5IDRjLTAuNDY3LTIuMjc1LTIuNDkxLTQtNC44OTktNGgtMTZjLTIuNzUgMC01IDIuMjUtNSA1djE2YzAgMi40MDggMS43MjUgNC40MzIgNCA0Ljg5OXYtMTkuODk5YzAtMS4xIDAuOS0yIDItMmgxOS44OTl6Ij48L3BhdGg+Cjwvc3ZnPgo=',
		'age'        => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDIwIDI4Ij4KPHBhdGggZD0iTTE4LjU2MiA4LjU2M2wtNC41NjIgNC41NjJ2MTIuODc1YzAgMC45NjktMC43ODEgMS43NS0xLjc1IDEuNzVzLTEuNzUtMC43ODEtMS43NS0xLjc1di02aC0xdjZjMCAwLjk2OS0wLjc4MSAxLjc1LTEuNzUgMS43NXMtMS43NS0wLjc4MS0xLjc1LTEuNzV2LTEyLjg3NWwtNC41NjItNC41NjJjLTAuNTc4LTAuNTk0LTAuNTc4LTEuNTMxIDAtMi4xMjUgMC41OTQtMC41NzggMS41MzEtMC41NzggMi4xMjUgMGwzLjU2MyAzLjU2M2g1Ljc1bDMuNTYzLTMuNTYzYzAuNTk0LTAuNTc4IDEuNTMxLTAuNTc4IDIuMTI1IDAgMC41NzggMC41OTQgMC41NzggMS41MzEgMCAyLjEyNXpNMTMuNSA2YzAgMS45MzctMS41NjMgMy41LTMuNSAzLjVzLTMuNS0xLjU2My0zLjUtMy41IDEuNTYzLTMuNSAzLjUtMy41IDMuNSAxLjU2MyAzLjUgMy41eiI+PC9wYXRoPgo8L3N2Zz4K',
		'time'       => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDI0IDI4Ij4KPHBhdGggZD0iTTE0IDguNXY3YzAgMC4yODEtMC4yMTkgMC41LTAuNSAwLjVoLTVjLTAuMjgxIDAtMC41LTAuMjE5LTAuNS0wLjV2LTFjMC0wLjI4MSAwLjIxOS0wLjUgMC41LTAuNWgzLjV2LTUuNWMwLTAuMjgxIDAuMjE5LTAuNSAwLjUtMC41aDFjMC4yODEgMCAwLjUgMC4yMTkgMC41IDAuNXpNMjAuNSAxNGMwLTQuNjg4LTMuODEzLTguNS04LjUtOC41cy04LjUgMy44MTMtOC41IDguNSAzLjgxMyA4LjUgOC41IDguNSA4LjUtMy44MTMgOC41LTguNXpNMjQgMTRjMCA2LjYyNS01LjM3NSAxMi0xMiAxMnMtMTItNS4zNzUtMTItMTIgNS4zNzUtMTIgMTItMTIgMTIgNS4zNzUgMTIgMTJ6Ij48L3BhdGg+Cjwvc3ZnPgo=',
		'players'    => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIzMCIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDMwIDI4Ij4KPHBhdGggZD0iTTkuMjY2IDE0Yy0xLjYyNSAwLjA0Ny0zLjA5NCAwLjc1LTQuMTQxIDJoLTIuMDk0Yy0xLjU2MyAwLTMuMDMxLTAuNzUtMy4wMzEtMi40ODQgMC0xLjI2Ni0wLjA0Ny01LjUxNiAxLjkzNy01LjUxNiAwLjMyOCAwIDEuOTUzIDEuMzI4IDQuMDYyIDEuMzI4IDAuNzE5IDAgMS40MDYtMC4xMjUgMi4wNzgtMC4zNTktMC4wNDcgMC4zNDQtMC4wNzggMC42ODgtMC4wNzggMS4wMzEgMCAxLjQyMiAwLjQ1MyAyLjgyOCAxLjI2NiA0ek0yNiAyMy45NTNjMCAyLjUzMS0xLjY3MiA0LjA0Ny00LjE3MiA0LjA0N2gtMTMuNjU2Yy0yLjUgMC00LjE3Mi0xLjUxNi00LjE3Mi00LjA0NyAwLTMuNTMxIDAuODI4LTguOTUzIDUuNDA2LTguOTUzIDAuNTMxIDAgMi40NjkgMi4xNzIgNS41OTQgMi4xNzJzNS4wNjMtMi4xNzIgNS41OTQtMi4xNzJjNC41NzggMCA1LjQwNiA1LjQyMiA1LjQwNiA4Ljk1M3pNMTAgNGMwIDIuMjAzLTEuNzk3IDQtNCA0cy00LTEuNzk3LTQtNCAxLjc5Ny00IDQtNCA0IDEuNzk3IDQgNHpNMjEgMTBjMCAzLjMxMy0yLjY4OCA2LTYgNnMtNi0yLjY4OC02LTYgMi42ODgtNiA2LTYgNiAyLjY4OCA2IDZ6TTMwIDEzLjUxNmMwIDEuNzM0LTEuNDY5IDIuNDg0LTMuMDMxIDIuNDg0aC0yLjA5NGMtMS4wNDctMS4yNS0yLjUxNi0xLjk1My00LjE0MS0yIDAuODEyLTEuMTcyIDEuMjY2LTIuNTc4IDEuMjY2LTQgMC0wLjM0NC0wLjAzMS0wLjY4OC0wLjA3OC0xLjAzMSAwLjY3MiAwLjIzNCAxLjM1OSAwLjM1OSAyLjA3OCAwLjM1OSAyLjEwOSAwIDMuNzM0LTEuMzI4IDQuMDYyLTEuMzI4IDEuOTg0IDAgMS45MzcgNC4yNSAxLjkzNyA1LjUxNnpNMjggNGMwIDIuMjAzLTEuNzk3IDQtNCA0cy00LTEuNzk3LTQtNCAxLjc5Ny00IDQtNCA0IDEuNzk3IDQgNHoiPjwvcGF0aD4KPC9zdmc+Cg==',
		'difficulty' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIyNiIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDI2IDI4Ij4KPHBhdGggZD0iTTI2IDE3LjE1NmMwIDEuNjA5LTAuOTIyIDIuOTUzLTIuNjI1IDIuOTUzLTEuOTA2IDAtMi40MDYtMS43MzQtNC4xMjUtMS43MzQtMS4yNSAwLTEuNzE5IDAuNzgxLTEuNzE5IDEuOTM3IDAgMS4yMTkgMC41IDIuMzkxIDAuNDg0IDMuNTk0djAuMDc4Yy0wLjE3MiAwLTAuMzQ0IDAtMC41MTYgMC4wMTYtMS42MDkgMC4xNTYtMy4yMzQgMC40NjktNC44NTkgMC40NjktMS4xMDkgMC0yLjI2Ni0wLjQzOC0yLjI2Ni0xLjcxOSAwLTEuNzE5IDEuNzM0LTIuMjE5IDEuNzM0LTQuMTI1IDAtMS43MDMtMS4zNDQtMi42MjUtMi45NTMtMi42MjUtMS42NDEgMC0zLjE1NiAwLjkwNi0zLjE1NiAyLjcwMyAwIDEuOTg0IDEuNTE2IDIuODQ0IDEuNTE2IDMuOTIyIDAgMC41NDctMC4zNDQgMS4wMzEtMC43MTkgMS4zOTEtMC40ODQgMC40NTMtMS4xNzIgMC41NDctMS44MjggMC41NDctMS4yODEgMC0yLjU2Mi0wLjE3Mi0zLjgyOC0wLjM3NS0wLjI4MS0wLjA0Ny0wLjU3OC0wLjA3OC0wLjg1OS0wLjEyNWwtMC4yMDMtMC4wMzFjLTAuMDMxLTAuMDE2LTAuMDc4LTAuMDE2LTAuMDc4LTAuMDMxdi0xNmMwLjA2MyAwLjA0NyAwLjk4NCAwLjE1NiAxLjE0MSAwLjE4NyAxLjI2NiAwLjIwMyAyLjU0NyAwLjM3NSAzLjgyOCAwLjM3NSAwLjY1NiAwIDEuMzQ0LTAuMDk0IDEuODI4LTAuNTQ3IDAuMzc1LTAuMzU5IDAuNzE5LTAuODQ0IDAuNzE5LTEuMzkxIDAtMS4wNzgtMS41MTYtMS45MzctMS41MTYtMy45MjIgMC0xLjc5NyAxLjUxNi0yLjcwMyAzLjE3Mi0yLjcwMyAxLjU5NCAwIDIuOTM4IDAuOTIyIDIuOTM4IDIuNjI1IDAgMS45MDYtMS43MzQgMi40MDYtMS43MzQgNC4xMjUgMCAxLjI4MSAxLjE1NiAxLjcxOSAyLjI2NiAxLjcxOSAxLjc5NyAwIDMuNTc4LTAuNDA2IDUuMzU5LTAuNXYwLjAzMWMtMC4wNDcgMC4wNjMtMC4xNTYgMC45ODQtMC4xODcgMS4xNDEtMC4yMDMgMS4yNjYtMC4zNzUgMi41NDctMC4zNzUgMy44MjggMCAwLjY1NiAwLjA5NCAxLjM0NCAwLjU0NyAxLjgyOCAwLjM1OSAwLjM3NSAwLjg0NCAwLjcxOSAxLjM5MSAwLjcxOSAxLjA3OCAwIDEuOTM3LTEuNTE2IDMuOTIyLTEuNTE2IDEuNzk3IDAgMi43MDMgMS41MTYgMi43MDMgMy4xNTZ6Ij48L3BhdGg+Cjwvc3ZnPgo=',
		'tags'       => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIzMCIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDMwIDI4Ij4KPHBhdGggZD0iTTcgN2MwLTEuMTA5LTAuODkxLTItMi0ycy0yIDAuODkxLTIgMiAwLjg5MSAyIDIgMiAyLTAuODkxIDItMnpNMjMuNjcyIDE2YzAgMC41MzEtMC4yMTkgMS4wNDctMC41NzggMS40MDZsLTcuNjcyIDcuNjg4Yy0wLjM3NSAwLjM1OS0wLjg5MSAwLjU3OC0xLjQyMiAwLjU3OHMtMS4wNDctMC4yMTktMS40MDYtMC41NzhsLTExLjE3Mi0xMS4xODhjLTAuNzk3LTAuNzgxLTEuNDIyLTIuMjk3LTEuNDIyLTMuNDA2di02LjVjMC0xLjA5NCAwLjkwNi0yIDItMmg2LjVjMS4xMDkgMCAyLjYyNSAwLjYyNSAzLjQyMiAxLjQyMmwxMS4xNzIgMTEuMTU2YzAuMzU5IDAuMzc1IDAuNTc4IDAuODkxIDAuNTc4IDEuNDIyek0yOS42NzIgMTZjMCAwLjUzMS0wLjIxOSAxLjA0Ny0wLjU3OCAxLjQwNmwtNy42NzIgNy42ODhjLTAuMzc1IDAuMzU5LTAuODkxIDAuNTc4LTEuNDIyIDAuNTc4LTAuODEyIDAtMS4yMTktMC4zNzUtMS43NS0wLjkyMmw3LjM0NC03LjM0NGMwLjM1OS0wLjM1OSAwLjU3OC0wLjg3NSAwLjU3OC0xLjQwNnMtMC4yMTktMS4wNDctMC41NzgtMS40MjJsLTExLjE3Mi0xMS4xNTZjLTAuNzk3LTAuNzk3LTIuMzEyLTEuNDIyLTMuNDIyLTEuNDIyaDMuNWMxLjEwOSAwIDIuNjI1IDAuNjI1IDMuNDIyIDEuNDIybDExLjE3MiAxMS4xNTZjMC4zNTkgMC4zNzUgMC41NzggMC44OTEgMC41NzggMS40MjJ6Ij48L3BhdGg+Cjwvc3ZnPgo=',
	];

	return $icons[ $name ];
}
