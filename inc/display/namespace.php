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
use GC\GamesCollector\Attributes;

/**
 * Return the game title. With link if a link was saved.
 *
 * @since  1.0.0
 * @param  object $game The game WP_Post object.
 * @return string       The HTML markup for the game title.
 */
function get_game_title( $game ) {
	$before = '';
	$after  = '';

	// Output a link if one was saved.
	$link = get_post_meta( $game->ID, '_gc_link', true );
	if ( $link ) {
		$before = '<a href="' . esc_url( $link ) . '">';
		$after  = '</a>';
	}

	// 1: Game ID, 2: Game title, 3: Link <a> tag, 4: Closing </a>.
	$output = sprintf( '%3$s<span class="game-title" id="game-%1$d-title">%2$s</span>%4$s',
		absint( $game->ID ),
		wp_kses_post( $game->post_title ),
		$before,
		$after
	);

	/**
	 * Allow game title to be filtered.
	 *
	 * @since 1.0.0
	 * @param string $output  The HTML markup for the game title.
	 * @param int    $game_id The game's post ID.
	 */
	return apply_filters( 'gc_filter_game_title', $output, $game->ID );
}

/**
 * Return the game info (post meta and taxonomies).
 *
 * @since  1.0.0
 * @param  int $game_id The game's post ID.
 * @return string       The HTML markup for the game information.
 */
function get_game_info( $game_id ) {
	ob_start(); ?>

		<div class="game-info" id="game-<?php echo absint( $game_id ); ?>-info">
			<?php
			echo get_players( $game_id );      // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo get_playing_time( $game_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo get_age( $game_id );          // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo get_difficulty( $game_id );   // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo get_attributes( $game_id );   // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>

	<?php
	return ob_get_clean();
}

/**
 * Return the markup for the buttons for filtering game lists.
 *
 * @since  1.0.0
 * @return string The markup for the buttons.
 */
function get_buttons() {
	$terms = get_terms( 'gc_attribute' );

	$show_all    = '<button data-filter="*">' . esc_html__( 'Show All', 'games-collector' ) . '</button>';
	$short_games = '<button data-filter=".short">' . esc_html__( 'Short Games', 'games-collector' ) . '</button>';
	$long_games  = '<button data-filter = ".long">' . esc_html__( 'Long Games', 'games-collector' ) . '</button>';
	$kids_games  = '<button data-filter = ".4-and-up,.5-and-up,.6-and-up,.7-and-up,.8-and-up,.9-and-up">' . esc_html__( 'Good for Kids', 'games-collector' ) . '</button>';
	$adult_games = '<button data-filter=".mature">' . esc_html__( 'Adult Games', 'games-collector' ) . '</button>';

	// Loop through terms to generate all those buttons. These won't be filterable.
	$terms_buttons = '';
	foreach ( $terms as $term ) {
		$terms_buttons .= '<button data-filter=".gc_attribute-' . esc_attr( $term->slug ) . '">' . esc_attr( $term->name ) . '</button>'; // phpcs:ignore WordPressVIPMinimum.Security.ProperEscapingFunction.notAttrEscAttr
	}

	/**
	 * Allow Show All button to be filtered. Can be hooked to __return_null to disable.
	 *
	 * @since 1.1.0
	 * @var   string HTML markup for Show All button.
	 */
	$show_all = apply_filters( 'gc_filter_button_show_all', $show_all );

	/**
	 * Allow the attribute term buttons to be filtered. This will filter _all_ the term buttons. Can be hooked to __return_null to disable term buttons.
	 *
	 * @since 1.1.0
	 * @var   string HTML markup for all attribute term buttons.
	 */
	$terms_buttons = apply_filters( 'gc_filter_term_buttons', $terms_buttons );

	/**
	 * Allow Short Games button to be filtered. Can be hooked to __return_null to disable.
	 *
	 * @since 1.1.0
	 * @var   string HTML markup for Short Games button.
	 */
	$short_games = apply_filters( 'gc_filter_button_short_games', $short_games );

	/**
	 * Allow Long Games button to be filtered. Can be hooked to __return_null to disable.
	 *
	 * @since 1.1.0
	 * @var   string HTML markup for Long Games button.
	 */
	$long_games = apply_filters( 'gc_filter_button_long_games', $long_games );

	/**
	 * Allow Kids Games button to be filtered. Can be hooked to __return_null to disable.
	 *
	 * @since 1.1.0
	 * @var   string HTML markup for Kids Games button.
	 */
	$kids_games = apply_filters( 'gc_filter_button_kids_games', $kids_games );

	/**
	 * Allow Adults Games button to be filtered. Can be hooked to __return_null to disable.
	 *
	 * @since 1.1.0
	 * @var   string HTML markup for Adults Games button.
	 */
	$adult_games = apply_filters( 'gc_filter_button_adult_games', $adult_games );

	$output = $show_all . $terms_buttons . $short_games . $long_games . $kids_games . $adult_games;

	/**
	 * Allow entire output to be filtered.
	 *
	 * @since 1.0.0
	 * @var   string HTML markup for buttons.
	 */
	return apply_filters( 'gc_filter_buttons', $output );
}

/**
 * Return the HTML markup for the other game filters.
 *
 * @since  1.0.0
 * @return string Select markup for filters.
 */
function get_filters() {
	$player_filter     = '<div class="player-filter"><label for="players-filter-select">' . esc_html__( 'How many players?', 'games-collector' ) . ':</label>
		<select class="players-filter-select">
			<option selected>- ' . esc_html__( 'Select one', 'games-collector' ) . ' -</option>
			<option value=".2-players,.min-2-players,.max-2-players,.max-3-players,.max-4-players,.max-5-players,.max-6-players,.max-7-players,.8-or-more-players">' . esc_html__( '2+ players', 'games-collector' ) . '</option>
			<option value=".4-players,.min-4-players,.max-4-players,.max-5-players,.max-6-players,.max-7-players,.8-or-more-players">' . esc_html__( '4+ players', 'games-collector' ) . '</option>
			<option value=".5-players,.min-5-players,.max-5-players,.max-6-players,.max-7-players,.8-or-more-players">' . esc_html__( '5+ players', 'games-collector' ) . '</option>
			<option value=".6-players,.min-6-players,.max-6-players,.max-7-players,.8-or-more-players">' . esc_html__( '6+ players', 'games-collector' ) . '</option>
			<option value=".8-players,.min-8-players,.8-or-more-players">' . esc_html__( '8+ players', 'games-collector' ) . '</option>
		</select>
	</div>';

	$game_difficulties = '';

	foreach ( Game\get_difficulties() as $key => $value ) {
		$game_difficulties .= '<option value=".' . esc_html( $key ) . '">' . esc_html( $value ) . '</option>';
	}

	/**
	 * Allow game difficulties select options to be filtered.
	 *
	 * @since 1.1.0
	 * @var   string HTML markup of <option> tags for difficulties select input.
	 */
	$game_difficulties = apply_filters( 'gc_filter_options_difficulties', $game_difficulties );

	$difficulty_filter = '<div class="difficulty-filter"><label for="difficulty-filter-select">' . esc_html__( 'Difficulty', 'games-collector' ) . ':</label>
		<select class="difficulty-filter-select">
			<option selected>- ' . esc_html__( 'Select one', 'games-collector' ) . ' -</option>' . $game_difficulties . '
		</select>
	</div>';

	/**
	 * Allow the player filter to be filtered. Can be disabled entirely by hooking to __return_null.
	 *
	 * @since 1.1.0
	 * @var   string HTML markup for player filter.
	 */
	$player_filter = apply_filters( 'gc_filter_player_filter', $player_filter );

	/**
	 * Allow the difficulty filter to be filtered. Can be disabled entirely by hooking to __return_null.
	 *
	 * @since 1.1.0
	 * @var   string HTML markup for difficulty filter.
	 */
	$difficulty_filter = apply_filters( 'gc_filter_difficulty_filter', $difficulty_filter );

	$output = $player_filter . $difficulty_filter;

	/**
	 * Allow the filters to be customized.
	 *
	 * @since 1.0.0
	 * @var   string HTML markup for the filters.
	 */
	return apply_filters( 'gc_filter_game_filters', $output );
}

/**
 * Return a list of game attributes.
 *
 * @since  1.0.0
 * @param  int $game_id The game's post ID.
 * @return string       The HTML markup for the game attributes.
 * @uses                Attributes\get_the_attribute_list
 */
function get_attributes( $game_id ) {
	$attribute_list = Attributes\get_the_attribute_list(
		$game_id,
		'<div class="game-attributes"><span class="gc-icon icon-game-attributes">' . get_svg( 'tags', false ) . '</span><span class="game-attributes" id="game-' . absint( $game_id ) . '-attributes">', // Before.
		', ',                                       // Seperator.
		'</span></div>'                             // After.
	);

	/**
	 * Allow the attribute list to be filtered.
	 *
	 * @since 1.0.0
	 * @var   string The HTML markup for the attribute list.
	 * @uses  Attributes\get_the_attribute_list
	 */
	return apply_filters( 'gc_filter_attribute_list', $attribute_list );
}

/**
 * Return the number of players.
 *
 * @since  1.0.0
 * @param  int $game_id The game's post ID.
 * @return mixed        The HTML markup for number of players or false if not set.
 * @uses                Game\get_players_min_max
 */
function get_players( $game_id ) {
	$players_min_max = Game\get_players_min_max( $game_id );

	if ( isset( $players_min_max['min'] ) ) {
		/**
		 * Allow the # of players to be filtered (but only if there actually are players).
		 *
		 * @since 1.0.0
		 * @var   $game_id         int    The game's post ID.
		 * @var   $players_min_max array  The minimum & maximum # of players.
		 * @var   $output          string The filtered # of players.
		 * @uses                          Game\get_players_min_max
		 */
		$num_players = apply_filters( 'gc_filter_players', $game_id, $players_min_max, sprintf(
			// Translators: 1: Minimum number of players, 2: Maximum number of players.
			__( '%1$d %2$s players', 'games-collector' ),
			absint( $players_min_max['min'] ),
			isset( $players_min_max['max'] ) ? sprintf( '- %d', absint( $players_min_max['max'] ) ) : '+'
		) );

		ob_start();
		?>

		<span class="gc-icon icon-game-players"><?php the_svg( 'players', false ); ?></span><span class="game-num-players" id="game-<?php echo absint( $game_id ); ?>-num-players"><?php echo esc_attr( $num_players ); // phpcs:ignore WordPressVIPMinimum.Security.ProperEscapingFunction.notAttrEscAttr ?></span>
		<?php
		$output = ob_get_clean();

		/**
		 * Allow the full output for the # of players to be filtered (but only if there actually are players).
		 *
		 * @since 1.0.0
		 * @var   $output      string The HTML markup for # of players.
		 * @var   $game_id     int    The game's post ID.
		 * @var   $num_players string The number of players (filtered from above).
		 * @uses                          Game\get_players_min_max
		 */
		return apply_filters( 'gc_filter_players_output', $output, $game_id, $num_players );
	}

	return false;
}

/**
 * Return the game difficulty.
 *
 * @since  1.0.0
 * @param  int $game_id The game's post ID.
 * @return mixed        The HTML markup for difficulty or false if not set.
 * @uses                Game\get_difficulties
 */
function get_difficulty( $game_id ) {
	$difficulty = get_post_meta( $game_id, '_gc_difficulty', true );
	if ( $difficulty ) {
		ob_start();
		?>
		<span class="gc-icon icon-game-difficulty"><?php the_svg( 'difficulty', false ); ?></span><span class="game-difficulty" id="game-<?php echo absint( $game_id ); ?>-difficulty"><?php echo esc_html( Game\get_difficulties( $difficulty ) ); ?></span>

		<?php
		$output = ob_get_clean();

		/**
		 * Allow the difficulty output to be filtered (but only if it has been set).
		 *
		 * @since 1.0.0
		 * @var   string The HTML markup for difficulty.
		 * @uses         Game\get_difficulties
		 */
		return apply_filters( 'gc_filter_difficulty', $output );
	}

	return false;
}

/**
 * Return the game playing time.
 *
 * @since  1.0.0
 * @param  int $game_id The game's post ID.
 * @return mixed        The HTML markup for playing time or false if not set.
 */
function get_playing_time( $game_id ) {
	$playing_time = get_post_meta( $game_id, '_gc_time', true );
	if ( $playing_time ) {
		ob_start();
		?>
		<span class="gc-icon icon-game-time"><?php the_svg( 'time', false ); ?></span><span class="game-playing-time" id="game-<?php echo absint( $game_id ); ?>-playing-time">
			<?php 
				// Translators: %s is the playing time in minutes.
				echo esc_html( sprintf( __( '%s minutes', 'games-collector' ), $playing_time ) ); 
			?>
		</span>

		<?php
		$output = ob_get_clean();

		/**
		 * Allow the playing time output to be filtered (but only if it has been set).
		 *
		 * @since 1.0.0
		 * @var   string The HTML markup for playing time.
		 */
		return apply_filters( 'gc_filter_playing_time', $output );
	}

	return false;
}

/**
 * Return the game age.
 *
 * @since  1.0.0
 * @param  int $game_id The game's post ID.
 * @return mixed        The HTML markup for age or false if not set.
 */
function get_age( $game_id ) {
	$age = get_post_meta( $game_id, '_gc_age', true );
	if ( $age ) {
		ob_start();
		?>
		<span class="gc-icon icon-game-age"><?php the_svg( 'age', false ); ?></span><span class="game-age" id="game-<?php echo absint( $game_id ); ?>-age"><?php echo esc_html( sprintf( '%d+', $age ) ); ?></span>

		<?php
		$output = ob_get_clean();

		/**
		 * Allow the age output to be filtered (but only if it has been set).
		 *
		 * @since 1.0.0
		 * @var   string The HTML markup for age.
		 */
		return apply_filters( 'gc_filter_age', $output );
	}

	return false;
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

	wp_enqueue_style( 'games-collector', dirname( plugin_dir_url( __FILE__ ), 2 ) . '/assets/css/main.css', [], '1.3.4' );
	wp_enqueue_script( 'games-collector', dirname( plugin_dir_url( __FILE__ ), 2 ) . '/assets/js/main.js', [ 'jquery', 'isotope' ], '1.3.4' );
	wp_enqueue_script( 'isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', [], '3.0.6' );
}

/**
 * Get a SVG icon by name.
 *
 * @since  0.2
 * @param  string $name    An icon name.
 * @param  bool   $encoded Whether to return the svg as a base64 encoded image (for background images) or the raw SVG XML.
 * @return string          The svg.
 */
function get_svg( $name = '', $encoded = true ) {
	// Bail if nothing was passed.
	if ( '' === $name ) {
		return;
	}

	if ( $encoded ) {
		$icons = [
			'dice'       => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIj4KPHBhdGggZmlsbD0iI2EwYTVhYSIgZD0iTTI3IDZoLTE2Yy0yLjc1IDAtNSAyLjI1LTUgNXYxNmMwIDIuNzUgMi4yNSA1IDUgNWgxNmMyLjc1IDAgNS0yLjI1IDUtNXYtMTZjMC0yLjc1LTIuMjUtNS01LTV6TTEzIDI4Yy0xLjY1NyAwLTMtMS4zNDMtMy0zczEuMzQzLTMgMy0zIDMgMS4zNDMgMyAzLTEuMzQzIDMtMyAzek0xMyAxNmMtMS42NTcgMC0zLTEuMzQzLTMtM3MxLjM0My0zIDMtMyAzIDEuMzQzIDMgMy0xLjM0MyAzLTMgM3pNMTkgMjJjLTEuNjU3IDAtMy0xLjM0My0zLTNzMS4zNDMtMyAzLTMgMyAxLjM0MyAzIDMtMS4zNDMgMy0zIDN6TTI1IDI4Yy0xLjY1NyAwLTMtMS4zNDMtMy0zczEuMzQzLTMgMy0zIDMgMS4zNDMgMyAzLTEuMzQzIDMtMyAzek0yNSAxNmMtMS42NTcgMC0zLTEuMzQzLTMtM3MxLjM0My0zIDMtMyAzIDEuMzQzIDMgMy0xLjM0MyAzLTMgM3pNMjUuODk5IDRjLTAuNDY3LTIuMjc1LTIuNDkxLTQtNC44OTktNGgtMTZjLTIuNzUgMC01IDIuMjUtNSA1djE2YzAgMi40MDggMS43MjUgNC40MzIgNCA0Ljg5OXYtMTkuODk5YzAtMS4xIDAuOS0yIDItMmgxOS44OTl6Ij48L3BhdGg+Cjwvc3ZnPgo=',
			'dice-alt'   => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMTAyNCIgaGVpZ2h0PSIxMDI0IiB2aWV3Qm94PSIwIDAgMTAyNCAxMDI0Ij48ZyBpZD0iaWNvbW9vbi1pZ25vcmUiPjwvZz48cGF0aCBkPSJNNjg4IDM1MmgtMjU2Yy00NCAwLTgwIDM2LTgwIDgwdjI1NmMwIDQ0IDM2IDgwIDgwIDgwaDI1NmM0NCAwIDgwLTM2IDgwLTgwdi0yNTZjMC00NC0zNi04MC04MC04MHpNNDY0IDcwNGMtMjYuNTA4IDAtNDgtMjEuNDkyLTQ4LTQ4czIxLjQ5Mi00OCA0OC00OCA0OCAyMS40OTIgNDggNDgtMjEuNDkyIDQ4LTQ4IDQ4ek00NjQgNTEyYy0yNi41MDggMC00OC0yMS40OTItNDgtNDhzMjEuNDkyLTQ4IDQ4LTQ4IDQ4IDIxLjQ5MiA0OCA0OC0yMS40OTIgNDgtNDggNDh6TTU2MCA2MDhjLTI2LjUwOCAwLTQ4LTIxLjQ5Mi00OC00OHMyMS40OTItNDggNDgtNDggNDggMjEuNDkyIDQ4IDQ4LTIxLjQ5MiA0OC00OCA0OHpNNjU2IDcwNGMtMjYuNTA4IDAtNDgtMjEuNDkyLTQ4LTQ4czIxLjQ5Mi00OCA0OC00OCA0OCAyMS40OTIgNDggNDgtMjEuNDkyIDQ4LTQ4IDQ4ek02NTYgNTEyYy0yNi41MDggMC00OC0yMS40OTItNDgtNDhzMjEuNDkyLTQ4IDQ4LTQ4IDQ4IDIxLjQ5MiA0OCA0OC0yMS40OTIgNDgtNDggNDh6TTY3MC4zNzUgMzIwYy03LjQ2NS0zNi40MDQtMzkuODU0LTY0LTc4LjM3NS02NGgtMjU2Yy00NCAwLTgwIDM2LTgwIDgwdjI1NmMwIDM4LjUxOSAyNy41OTYgNzAuOTE4IDY0IDc4LjM3NXYtMzE4LjM3NWMwLTE3LjYgMTQuNC0zMiAzMi0zMmgzMTguMzc1eiI+PC9wYXRoPjwvc3ZnPg==',
			'age'        => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDIwIDI4Ij4KPHBhdGggZD0iTTE4LjU2MiA4LjU2M2wtNC41NjIgNC41NjJ2MTIuODc1YzAgMC45NjktMC43ODEgMS43NS0xLjc1IDEuNzVzLTEuNzUtMC43ODEtMS43NS0xLjc1di02aC0xdjZjMCAwLjk2OS0wLjc4MSAxLjc1LTEuNzUgMS43NXMtMS43NS0wLjc4MS0xLjc1LTEuNzV2LTEyLjg3NWwtNC41NjItNC41NjJjLTAuNTc4LTAuNTk0LTAuNTc4LTEuNTMxIDAtMi4xMjUgMC41OTQtMC41NzggMS41MzEtMC41NzggMi4xMjUgMGwzLjU2MyAzLjU2M2g1Ljc1bDMuNTYzLTMuNTYzYzAuNTk0LTAuNTc4IDEuNTMxLTAuNTc4IDIuMTI1IDAgMC41NzggMC41OTQgMC41NzggMS41MzEgMCAyLjEyNXpNMTMuNSA2YzAgMS45MzctMS41NjMgMy41LTMuNSAzLjVzLTMuNS0xLjU2My0zLjUtMy41IDEuNTYzLTMuNSAzLjUtMy41IDMuNSAxLjU2MyAzLjUgMy41eiI+PC9wYXRoPgo8L3N2Zz4K',
			'time'       => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDI0IDI4Ij4KPHBhdGggZD0iTTE0IDguNXY3YzAgMC4yODEtMC4yMTkgMC41LTAuNSAwLjVoLTVjLTAuMjgxIDAtMC41LTAuMjE5LTAuNS0wLjV2LTFjMC0wLjI4MSAwLjIxOS0wLjUgMC41LTAuNWgzLjV2LTUuNWMwLTAuMjgxIDAuMjE5LTAuNSAwLjUtMC41aDFjMC4yODEgMCAwLjUgMC4yMTkgMC41IDAuNXpNMjAuNSAxNGMwLTQuNjg4LTMuODEzLTguNS04LjUtOC41cy04LjUgMy44MTMtOC41IDguNSAzLjgxMyA4LjUgOC41IDguNSA4LjUtMy44MTMgOC41LTguNXpNMjQgMTRjMCA2LjYyNS01LjM3NSAxMi0xMiAxMnMtMTItNS4zNzUtMTItMTIgNS4zNzUtMTIgMTItMTIgMTIgNS4zNzUgMTIgMTJ6Ij48L3BhdGg+Cjwvc3ZnPgo=',
			'players'    => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIzMCIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDMwIDI4Ij4KPHBhdGggZD0iTTkuMjY2IDE0Yy0xLjYyNSAwLjA0Ny0zLjA5NCAwLjc1LTQuMTQxIDJoLTIuMDk0Yy0xLjU2MyAwLTMuMDMxLTAuNzUtMy4wMzEtMi40ODQgMC0xLjI2Ni0wLjA0Ny01LjUxNiAxLjkzNy01LjUxNiAwLjMyOCAwIDEuOTUzIDEuMzI4IDQuMDYyIDEuMzI4IDAuNzE5IDAgMS40MDYtMC4xMjUgMi4wNzgtMC4zNTktMC4wNDcgMC4zNDQtMC4wNzggMC42ODgtMC4wNzggMS4wMzEgMCAxLjQyMiAwLjQ1MyAyLjgyOCAxLjI2NiA0ek0yNiAyMy45NTNjMCAyLjUzMS0xLjY3MiA0LjA0Ny00LjE3MiA0LjA0N2gtMTMuNjU2Yy0yLjUgMC00LjE3Mi0xLjUxNi00LjE3Mi00LjA0NyAwLTMuNTMxIDAuODI4LTguOTUzIDUuNDA2LTguOTUzIDAuNTMxIDAgMi40NjkgMi4xNzIgNS41OTQgMi4xNzJzNS4wNjMtMi4xNzIgNS41OTQtMi4xNzJjNC41NzggMCA1LjQwNiA1LjQyMiA1LjQwNiA4Ljk1M3pNMTAgNGMwIDIuMjAzLTEuNzk3IDQtNCA0cy00LTEuNzk3LTQtNCAxLjc5Ny00IDQtNCA0IDEuNzk3IDQgNHpNMjEgMTBjMCAzLjMxMy0yLjY4OCA2LTYgNnMtNi0yLjY4OC02LTYgMi42ODgtNiA2LTYgNiAyLjY4OCA2IDZ6TTMwIDEzLjUxNmMwIDEuNzM0LTEuNDY5IDIuNDg0LTMuMDMxIDIuNDg0aC0yLjA5NGMtMS4wNDctMS4yNS0yLjUxNi0xLjk1My00LjE0MS0yIDAuODEyLTEuMTcyIDEuMjY2LTIuNTc4IDEuMjY2LTQgMC0wLjM0NC0wLjAzMS0wLjY4OC0wLjA3OC0xLjAzMSAwLjY3MiAwLjIzNCAxLjM1OSAwLjM1OSAyLjA3OCAwLjM1OSAyLjEwOSAwIDMuNzM0LTEuMzI4IDQuMDYyLTEuMzI4IDEuOTg0IDAgMS45MzcgNC4yNSAxLjkzNyA1LjUxNnpNMjggNGMwIDIuMjAzLTEuNzk3IDQtNCA0cy00LTEuNzk3LTQtNCAxLjc5Ny00IDQtNCA0IDEuNzk3IDQgNHoiPjwvcGF0aD4KPC9zdmc+Cg==',
			'difficulty' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIyNiIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDI2IDI4Ij4KPHBhdGggZD0iTTI2IDE3LjE1NmMwIDEuNjA5LTAuOTIyIDIuOTUzLTIuNjI1IDIuOTUzLTEuOTA2IDAtMi40MDYtMS43MzQtNC4xMjUtMS43MzQtMS4yNSAwLTEuNzE5IDAuNzgxLTEuNzE5IDEuOTM3IDAgMS4yMTkgMC41IDIuMzkxIDAuNDg0IDMuNTk0djAuMDc4Yy0wLjE3MiAwLTAuMzQ0IDAtMC41MTYgMC4wMTYtMS42MDkgMC4xNTYtMy4yMzQgMC40NjktNC44NTkgMC40NjktMS4xMDkgMC0yLjI2Ni0wLjQzOC0yLjI2Ni0xLjcxOSAwLTEuNzE5IDEuNzM0LTIuMjE5IDEuNzM0LTQuMTI1IDAtMS43MDMtMS4zNDQtMi42MjUtMi45NTMtMi42MjUtMS42NDEgMC0zLjE1NiAwLjkwNi0zLjE1NiAyLjcwMyAwIDEuOTg0IDEuNTE2IDIuODQ0IDEuNTE2IDMuOTIyIDAgMC41NDctMC4zNDQgMS4wMzEtMC43MTkgMS4zOTEtMC40ODQgMC40NTMtMS4xNzIgMC41NDctMS44MjggMC41NDctMS4yODEgMC0yLjU2Mi0wLjE3Mi0zLjgyOC0wLjM3NS0wLjI4MS0wLjA0Ny0wLjU3OC0wLjA3OC0wLjg1OS0wLjEyNWwtMC4yMDMtMC4wMzFjLTAuMDMxLTAuMDE2LTAuMDc4LTAuMDE2LTAuMDc4LTAuMDMxdi0xNmMwLjA2MyAwLjA0NyAwLjk4NCAwLjE1NiAxLjE0MSAwLjE4NyAxLjI2NiAwLjIwMyAyLjU0NyAwLjM3NSAzLjgyOCAwLjM3NSAwLjY1NiAwIDEuMzQ0LTAuMDk0IDEuODI4LTAuNTQ3IDAuMzc1LTAuMzU5IDAuNzE5LTAuODQ0IDAuNzE5LTEuMzkxIDAtMS4wNzgtMS41MTYtMS45MzctMS41MTYtMy45MjIgMC0xLjc5NyAxLjUxNi0yLjcwMyAzLjE3Mi0yLjcwMyAxLjU5NCAwIDIuOTM4IDAuOTIyIDIuOTM4IDIuNjI1IDAgMS45MDYtMS43MzQgMi40MDYtMS43MzQgNC4xMjUgMCAxLjI4MSAxLjE1NiAxLjcxOSAyLjI2NiAxLjcxOSAxLjc5NyAwIDMuNTc4LTAuNDA2IDUuMzU5LTAuNXYwLjAzMWMtMC4wNDcgMC4wNjMtMC4xNTYgMC45ODQtMC4xODcgMS4xNDEtMC4yMDMgMS4yNjYtMC4zNzUgMi41NDctMC4zNzUgMy44MjggMCAwLjY1NiAwLjA5NCAxLjM0NCAwLjU0NyAxLjgyOCAwLjM1OSAwLjM3NSAwLjg0NCAwLjcxOSAxLjM5MSAwLjcxOSAxLjA3OCAwIDEuOTM3LTEuNTE2IDMuOTIyLTEuNTE2IDEuNzk3IDAgMi43MDMgMS41MTYgMi43MDMgMy4xNTZ6Ij48L3BhdGg+Cjwvc3ZnPgo=',
			'tags'       => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSIzMCIgaGVpZ2h0PSIyOCIgdmlld0JveD0iMCAwIDMwIDI4Ij4KPHBhdGggZD0iTTcgN2MwLTEuMTA5LTAuODkxLTItMi0ycy0yIDAuODkxLTIgMiAwLjg5MSAyIDIgMiAyLTAuODkxIDItMnpNMjMuNjcyIDE2YzAgMC41MzEtMC4yMTkgMS4wNDctMC41NzggMS40MDZsLTcuNjcyIDcuNjg4Yy0wLjM3NSAwLjM1OS0wLjg5MSAwLjU3OC0xLjQyMiAwLjU3OHMtMS4wNDctMC4yMTktMS40MDYtMC41NzhsLTExLjE3Mi0xMS4xODhjLTAuNzk3LTAuNzgxLTEuNDIyLTIuMjk3LTEuNDIyLTMuNDA2di02LjVjMC0xLjA5NCAwLjkwNi0yIDItMmg2LjVjMS4xMDkgMCAyLjYyNSAwLjYyNSAzLjQyMiAxLjQyMmwxMS4xNzIgMTEuMTU2YzAuMzU5IDAuMzc1IDAuNTc4IDAuODkxIDAuNTc4IDEuNDIyek0yOS42NzIgMTZjMCAwLjUzMS0wLjIxOSAxLjA0Ny0wLjU3OCAxLjQwNmwtNy42NzIgNy42ODhjLTAuMzc1IDAuMzU5LTAuODkxIDAuNTc4LTEuNDIyIDAuNTc4LTAuODEyIDAtMS4yMTktMC4zNzUtMS43NS0wLjkyMmw3LjM0NC03LjM0NGMwLjM1OS0wLjM1OSAwLjU3OC0wLjg3NSAwLjU3OC0xLjQwNnMtMC4yMTktMS4wNDctMC41NzgtMS40MjJsLTExLjE3Mi0xMS4xNTZjLTAuNzk3LTAuNzk3LTIuMzEyLTEuNDIyLTMuNDIyLTEuNDIyaDMuNWMxLjEwOSAwIDIuNjI1IDAuNjI1IDMuNDIyIDEuNDIybDExLjE3MiAxMS4xNTZjMC4zNTkgMC4zNzUgMC41NzggMC44OTEgMC41NzggMS40MjJ6Ij48L3BhdGg+Cjwvc3ZnPgo=',
		];

		/**
		 * Allow base64-encoded icons to be filtered.
		 *
		 * @var array
		 */
		$icons = apply_filters( 'gc_filter_svg_base64', $icons );
	} else {
		$icons = [
			'dice'       => '<svg class="gc-icon svg gc-icon-dice" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32">
<title>dice</title>
<path fill="#a0a5aa" d="M27 6h-16c-2.75 0-5 2.25-5 5v16c0 2.75 2.25 5 5 5h16c2.75 0 5-2.25 5-5v-16c0-2.75-2.25-5-5-5zM13 28c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM13 16c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM19 22c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM25 28c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM25 16c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM25.899 4c-0.467-2.275-2.491-4-4.899-4h-16c-2.75 0-5 2.25-5 5v16c0 2.408 1.725 4.432 4 4.899v-19.899c0-1.1 0.9-2 2-2h19.899z"></path>
</svg>
></path>
</svg>',
			'dice-alt'   => '<svg class="gc-icon svg gc-icon-dice-alt" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1024" height="1024" viewBox="0 0 1024 1024">
<title>dice-alt</title>
<path d="M688 352h-256c-44 0-80 36-80 80v256c0 44 36 80 80 80h256c44 0 80-36 80-80v-256c0-44-36-80-80-80zM464 704c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM464 512c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM560 608c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM656 704c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM656 512c-26.508 0-48-21.492-48-48s21.492-48 48-48 48 21.492 48 48-21.492 48-48 48zM670.375 320c-7.465-36.404-39.854-64-78.375-64h-256c-44 0-80 36-80 80v256c0 38.519 27.596 70.918 64 78.375v-318.375c0-17.6 14.4-32 32-32h318.375z"></path>
</svg>',
			'age'        => '<svg class="gc-icon svg gc-icon-age" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="28" viewBox="0 0 20 28">
<title>age</title>
<path d="M18.562 8.563l-4.562 4.562v12.875c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-6h-1v6c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-12.875l-4.562-4.562c-0.578-0.594-0.578-1.531 0-2.125 0.594-0.578 1.531-0.578 2.125 0l3.563 3.563h5.75l3.563-3.563c0.594-0.578 1.531-0.578 2.125 0 0.578 0.594 0.578 1.531 0 2.125zM13.5 6c0 1.937-1.563 3.5-3.5 3.5s-3.5-1.563-3.5-3.5 1.563-3.5 3.5-3.5 3.5 1.563 3.5 3.5z"></path>
</svg>',
			'time'       => '<svg class="gc-icon svg gc-icon-time" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="28" viewBox="0 0 24 28">
<title>time</title>
<path d="M14 8.5v7c0 0.281-0.219 0.5-0.5 0.5h-5c-0.281 0-0.5-0.219-0.5-0.5v-1c0-0.281 0.219-0.5 0.5-0.5h3.5v-5.5c0-0.281 0.219-0.5 0.5-0.5h1c0.281 0 0.5 0.219 0.5 0.5zM20.5 14c0-4.688-3.813-8.5-8.5-8.5s-8.5 3.813-8.5 8.5 3.813 8.5 8.5 8.5 8.5-3.813 8.5-8.5zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12 12 5.375 12 12z"></path>
</svg>',
			'players'    => '<svg class="gc-icon svg gc-icon-players" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>players</title>
<path d="M9.266 14c-1.625 0.047-3.094 0.75-4.141 2h-2.094c-1.563 0-3.031-0.75-3.031-2.484 0-1.266-0.047-5.516 1.937-5.516 0.328 0 1.953 1.328 4.062 1.328 0.719 0 1.406-0.125 2.078-0.359-0.047 0.344-0.078 0.688-0.078 1.031 0 1.422 0.453 2.828 1.266 4zM26 23.953c0 2.531-1.672 4.047-4.172 4.047h-13.656c-2.5 0-4.172-1.516-4.172-4.047 0-3.531 0.828-8.953 5.406-8.953 0.531 0 2.469 2.172 5.594 2.172s5.063-2.172 5.594-2.172c4.578 0 5.406 5.422 5.406 8.953zM10 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4zM21 10c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zM30 13.516c0 1.734-1.469 2.484-3.031 2.484h-2.094c-1.047-1.25-2.516-1.953-4.141-2 0.812-1.172 1.266-2.578 1.266-4 0-0.344-0.031-0.688-0.078-1.031 0.672 0.234 1.359 0.359 2.078 0.359 2.109 0 3.734-1.328 4.062-1.328 1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z"></path>
</svg>',
			'difficulty' => '<svg class="gc-icon svg gc-icon-difficulty" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="28" viewBox="0 0 26 28">
<title>difficulty</title>
<path d="M26 17.156c0 1.609-0.922 2.953-2.625 2.953-1.906 0-2.406-1.734-4.125-1.734-1.25 0-1.719 0.781-1.719 1.937 0 1.219 0.5 2.391 0.484 3.594v0.078c-0.172 0-0.344 0-0.516 0.016-1.609 0.156-3.234 0.469-4.859 0.469-1.109 0-2.266-0.438-2.266-1.719 0-1.719 1.734-2.219 1.734-4.125 0-1.703-1.344-2.625-2.953-2.625-1.641 0-3.156 0.906-3.156 2.703 0 1.984 1.516 2.844 1.516 3.922 0 0.547-0.344 1.031-0.719 1.391-0.484 0.453-1.172 0.547-1.828 0.547-1.281 0-2.562-0.172-3.828-0.375-0.281-0.047-0.578-0.078-0.859-0.125l-0.203-0.031c-0.031-0.016-0.078-0.016-0.078-0.031v-16c0.063 0.047 0.984 0.156 1.141 0.187 1.266 0.203 2.547 0.375 3.828 0.375 0.656 0 1.344-0.094 1.828-0.547 0.375-0.359 0.719-0.844 0.719-1.391 0-1.078-1.516-1.937-1.516-3.922 0-1.797 1.516-2.703 3.172-2.703 1.594 0 2.938 0.922 2.938 2.625 0 1.906-1.734 2.406-1.734 4.125 0 1.281 1.156 1.719 2.266 1.719 1.797 0 3.578-0.406 5.359-0.5v0.031c-0.047 0.063-0.156 0.984-0.187 1.141-0.203 1.266-0.375 2.547-0.375 3.828 0 0.656 0.094 1.344 0.547 1.828 0.359 0.375 0.844 0.719 1.391 0.719 1.078 0 1.937-1.516 3.922-1.516 1.797 0 2.703 1.516 2.703 3.156z"></path>
</svg>',
			'tags'       => '<svg class="gc-icon svg gc-icon-tags" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>tags</title>
<path d="M7 7c0-1.109-0.891-2-2-2s-2 0.891-2 2 0.891 2 2 2 2-0.891 2-2zM23.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578s-1.047-0.219-1.406-0.578l-11.172-11.188c-0.797-0.781-1.422-2.297-1.422-3.406v-6.5c0-1.094 0.906-2 2-2h6.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422zM29.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578-0.812 0-1.219-0.375-1.75-0.922l7.344-7.344c0.359-0.359 0.578-0.875 0.578-1.406s-0.219-1.047-0.578-1.422l-11.172-11.156c-0.797-0.797-2.312-1.422-3.422-1.422h3.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422z"></path>
</svg>',
		];

		/**
		 * Allow svg icons to be filtered.
		 *
		 * @var array
		 */
		$icons = apply_filters( 'gc_filter_svg_xml', $icons );
	} // End if().

	return $icons[ $name ];
}

/**
 * Get a SVG icon by name. Wrapper for get_svg.
 *
 * @since  1.1.0
 * @param  string $name    An icon name.
 * @param  bool   $encoded Whether to return the svg as a base64 encoded image (for background images) or the raw SVG XML.
 */
function the_svg( $name = '', $encoded = true ) {
	echo get_svg( $name, $encoded ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
