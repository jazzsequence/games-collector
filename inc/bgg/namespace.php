<?php
/**
 * Games Collector BoardGameGeek API integration.
 *
 * Integrates BoardGameGeek's XML API into Games Collector to allow game data to be imported.
 *
 * @package GC\GamesCollector\BGG
 * @since   1.2.0
 */

namespace GC\GamesCollector\BGG;

/**
 * Return the BGG v1 API endpoint.
 *
 * @since  1.2.0
 * @return string The BGG v1 endpoint.
 */
function bgg_api() {
	return esc_url( 'https://www.boardgamegeek.com/xmlapi/' );
}

/**
 * Return the BGG v2 (beta) API endpoint.
 *
 * @since  1.2.0
 * @return string The BGG v2 endpoint.
 */
function bgg_api2() {
	return esc_url( 'https://www.boardgamegeek.com/xmlapi2/' );
}

/**
 * Return the BGG search endpoint for a particular query.
 *
 * @since  1.2.0
 * @param  string $query The search query.
 * @param  string $type  The type of search (optional). Allowed values are rpgitem, videogame, boardgame, boardgameaccessory or boardgameexpansion.
 * @return string        The BGG search API URL.
 */
function bgg_search( string $query, $type = 'boardgame' ) {
	$query = str_replace( ' ', '+', $query );
	$type  = in_array( $type, [ 'rpgitem', 'videogame', 'boardgame', 'boardgameaccessory', 'boardgameexpansion' ] ) ? $type : 'boardgame';

	return esc_url_raw( sprintf(
		'%1$ssearch?search=%2$s&type=%3$s',
		bgg_api(),
		esc_html( $query ),
		esc_html( $type )
	) );
}

/**
 * Return the BGG API endpoint for a single game/entity.
 *
 * @since  1.2.0
 * @param  int $id The BGG entity ID.
 * @return string  The BGG URL.
 */
function bgg_game( int $id ) {
	return esc_url( bgg_api2() . 'thing?id=' . $id );
}

/**
 * Return the search results for a given query.
 *
 * @since  1.2.0
 * @param  string $query A search query for a game.
 * @return array         An array of possible matches.
 */
function get_bgg_search_results( $query ) {
	$response = wp_remote_get( bgg_search( $query ) );
	$results  = [];

	if ( isset( $response['response'] ) && 200 === $response['response']['code'] ) {
		$xml  = simplexml_load_string( wp_remote_retrieve_body( $response ) );

		if ( isset( $xml->boardgame ) ) {
			foreach ( $xml->boardgame as $game ) {
				$game = (array) $game;

				$results[] = [
					'id'   => ( isset( $game['@attributes']['objectid'] ) ) ? (int) $game['@attributes']['objectid'] : '',
					'name' => ( isset( $game['name'] ) ) ? $game['name'] : '',
					'year' => ( isset( $game['yearpublished'] ) ) ? $game['yearpublished'] : '',
				];
			}
		}
	}

	return $results;
}

/**
 * Return the BGG data that maps to data used in Games Collector for a game.
 *
 * @since  1.2.0
 * @param  int $id The BGG game id.
 * @return array   An array of game information pulled from the entry on Board Game Geek.
 */
function get_bgg_game( $id ) {
	$response = wp_remote_get( bgg_game( $id ) );
	$data     = [];

	if ( isset( $response['response'] ) && 200 === $response['response']['code'] ) {
		$xml = simplexml_load_string( wp_remote_retrieve_body( $response ) );
		$game = $xml->item;
		$data = [
			'title'       => (string) $game->name->attributes()['value'],
			'image'       => (string) $game->image,
			'minplayers'  => (int) $game->minplayers->attributes()['value'],
			'maxplayers'  => (int) $game->maxplayers->attributes()['value'],
			'minplaytime' => (int) $game->minplaytime->attributes()['value'],
			'maxplaytime' => (int) $game->maxplaytime->attributes()['value'],
			'minage'      => (int) $game->minage->attributes()['value'],
			'categories'  => [],
		];

		$categories = [];

		foreach ( $game->link as $metadata ) {
			if ( 'boardgamecategory' === (string) $metadata->attributes()['type'] ) {
				$categories[] = (string) $metadata->attributes()['value'];
			}
		}

		$data['categories'] = ! empty( $categories ) ? $categories : [];
	}

	return $data;
}

/**
 * CMB2 field for BGG Search.
 *
 * @since 1.2.0
 */
function fields() {
	$search_results = get_transient( 'gc_last_bgg_search' );

	// First run.
	if ( ! $search_results || isset( $_GET['reset_search'] ) ) {

		// If we're clearing the search, delete the transient and start over.
		if ( isset( $_GET['bgg_search_reset_nonce'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['bgg_search_reset_nonce'] ) ), 'bgg_search_reset_nonce' ) ) {
			delete_transient( 'gc_last_bgg_search' );
			add_action( 'admin_notices', __NAMESPACE__ . '\\search_cleared_notice' );
		}

		$cmb = new_cmb2_box( [
			'id'           => 'bgg-search',
			'title'        => __( 'Add game from Board Game Geek', 'games-collector' ),
			'object_types' => [ 'options-page' ],
			'option_key'   => 'add_from_bgg',
			'parent_slug'  => 'edit.php?post_type=gc_game',
			'menu_title'   => __( 'Add New From BGG', 'games-collector' ),
			'save_button'  => __( 'Search for Game', 'games-collector' ),
		] );

		$cmb->add_field( [
			'name'       => __( 'Search', 'games-collector' ),
			'id'         => 'bgg_searchform',
			'type'       => 'bgg_search',
			'desc'       => __( 'Type in the title of a game to search for that game on Board Game Geek.', 'games-collector' ),
		] );
	} else {
		// Choose the right game.
		$cmb = new_cmb2_box( [
			'id'           => 'bgg-search-2',
			'title'        => __( 'Add game from Board Game Geek &mdash; Step 2', 'games-collector' ),
			'object_types' => [ 'options-page' ],
			'option_key'   => 'add_from_bgg',
			'parent_slug'  => 'edit.php?post_type=gc_game',
			'menu_title'   => __( 'Add New From BGG', 'games-collector' ),
			'save_button'  => __( 'Add Game', 'games-collector' ),
		] );

		$cmb->add_field( [
			'name'       => __( 'Search Results', 'games-collector' ),
			'id'         => 'bgg_search_results',
			'type'       => 'radio',
			'desc'       => __( 'Select the game that matches your search.', 'games-collector' ),
			'options'    => bgg_search_results_options( $search_results ),
		] );

		$cmb->add_field( [
			'id'         => 'bgg_search_results_hidden',
			'type'       => 'hidden',
			'attributes' => [
				'name'  => 'action',
				'value' => 'bgg_insert_game',
			],
		] );

		$cmb->add_field( [
			'id'          => 'bgg_search_reset',
			'type'        => 'bgg_search_reset',
			'button_name' => __( 'Reset Search', 'games-collector' ),
		] );
	}
}

/**
 * Render the BGG search field in CMB2.
 *
 * @since  1.2.0
 * @param  string $field             Not used.
 * @param  string $escaped_value     Not used.
 * @param  int    $object_id         Not used.
 * @param  string $object_type       Not used.
 * @param  object $field_type_object The CMB2 field type object.
 */
function render_cmb2_bgg_search( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	$description = '<p class="description">' . esc_html( $field_type_object->field->args()['desc'] ) . '</p>';
	$form = sprintf( '<input id="%1$s" class="regular-text" name="%2$s" value="" placeholder="%3$s" type="text">',
		esc_attr( $field_type_object->field->args()['id'] ),
		esc_attr( $field_type_object->field->args()['id'] ),
		__( 'A game title or search, e.g. &ldquo;betrayal house hill&rdquo;', 'usat' )
	);
	$hidden = '<input type="hidden" name="action" value="bgg_search_response">';
	$output = $hidden . $form . $description;

	echo wp_kses( $output, [
		'p'     => [
			'class'       => [],
		],
		'input' => [
			'id'          => [],
			'class'       => [],
			'name'        => [],
			'value'       => [],
			'type'        => [],
			'placeholder' => [],
		],
	] );
}

/**
 * Render the bgg_search_reset field in CMB2.
 *
 * This adds a link styled like a button which will clear out the current BGG game search.
 *
 * @since  1.2.0
 * @param  string $field             Not used.
 * @param  string $escaped_value     Not used.
 * @param  int    $object_id         Not used.
 * @param  string $object_type       Not used.
 * @param  object $field_type_object The CMB2 field object.
 */
function render_cmb2_bgg_search_reset( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {

	$nonce = wp_create_nonce( 'bgg_search_reset_nonce' );
	$url   = add_query_arg( [
		'post_type'              => 'gc_game',
		'page'                   => 'add_from_bgg',
		'reset_search'           => true,
		'bgg_search_reset_nonce' => $nonce,
	], admin_url( 'edit.php' ) );

	ob_start();
	?>
	<a href="<?php echo esc_url_raw( $url ); ?>">
		<div class="button alignright" name="bgg_search_reset">
			<?php echo esc_html( $field_type_object->field->args['button_name'] ); ?>
		</div>
	</a>
	<input type="hidden" name="action" value="bgg_search_reset" />
	<?php

	echo wp_kses( ob_get_clean(), [
		'a' => [
			'href' => [],
		],
		'div' => [
			'class' => [],
		],
		'input' => [
			'type'  => [],
			'name'  => [],
			'value' => [],
		],
	]);
}

/**
 * Store the Board Game Geek search results in a transient so we can access it later.
 *
 * @since  1.2.0
 * @return void|wp_die
 */
function search_response() {
	if ( isset( $_POST['nonce_CMB2phpbgg-search'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce_CMB2phpbgg-search'] ) ), 'nonce_CMB2phpbgg-search' ) ) {

		$search_query = isset( $_POST['bgg_searchform'] ) ? sanitize_text_field( wp_unslash( $_POST['bgg_searchform'] ) ) : '';
		$results      = get_bgg_search_results( $search_query );
		set_transient( 'gc_last_bgg_search', $results, HOUR_IN_SECONDS );
		wp_safe_redirect( admin_url( 'edit.php?post_type=gc_game&page=add_from_bgg&step=2' ) );
		exit;
	}

	return wp_die( esc_html__( 'Security check failed. What were you doing?', 'games-collector' ), esc_html__( 'Nonce check failed', 'games-collector' ) );
}

/**
 * Display a notice when the search was cleared.
 *
 * @since 1.2.0
 */
function search_cleared_notice() {
	?>
	<div class="notice updated">
		<p>
			<?php esc_html_e( 'Board Game Geek game search reset.', 'games-collector' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Rearrange the Games Collector submenu.
 *
 * This moves the Add New from BGG link to directly below Add New.
 *
 * @since  1.2.0
 * @param  bool $menu_order Returns true if successful.
 * @return bool             Returns the $menu_order unchanged.
 */
function submenu_order( $menu_order ) {
	global $submenu;

	// Store the Games Collector menu to a variable.
	$items = $submenu['edit.php?post_type=gc_game'];

	// Item 11 is right after Add New. Item 16 is the link for Add New from BGG.
	$submenu['edit.php?post_type=gc_game'][11] = $items[16]; // WPCS: override ok.
	// Remove item 16, the old Add New from BGG link.
	unset( $submenu['edit.php?post_type=gc_game'][16] );
	// Re-sort the menu by index.
	ksort( $submenu['edit.php?post_type=gc_game'] );

	return $menu_order;
}

/**
 * Dislplay the BGG search results in an option array for CMB2.
 *
 * @since  1.2.0
 * @param  array $results The array of BGG search results.
 * @return array          An array of options for CMB2.
 */
function bgg_search_results_options( $results ) {
	$options = [];
	foreach ( $results as $game ) {
		$options[ absint( $game['id'] ) ] = sprintf( '%1$s [%2$s] (%3$s)',
			'<strong>' . esc_html( $game['name'] ) . '</strong>',
			esc_html( $game['year'] ),
			esc_html( $game['id'] )
		);
	}

	return $options;
}

/**
 * Insert the game using BGG data from the API.
 *
 * @since  1.2.0
 * @return void|wp_die
 */
function insert_game() {
	if ( isset( $_POST['nonce_CMB2phpbgg-search-2'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce_CMB2phpbgg-search-2'] ) ), 'nonce_CMB2phpbgg-search-2' ) ) {

		$game_id      = isset( $_POST['bgg_search_results'] ) ? absint( wp_unslash( $_POST['bgg_search_results'] ) ) : false;
		$redirect_url = admin_url( 'edit.php?post_type=gc_game&page=add_from_bgg' );

		if ( $game_id ) {
			$game = get_bgg_game( $game_id );

			// Check if game already exists.
			$existing_game = get_posts( [ 
				'title' => $game['title'],
				'post_type' => 'gc_game',
				'numberposts' => 1,
			] );

			if ( count( $existing_game ) > 0 ) {
				return wp_die(
					esc_html__( 'A game with that title already exists. Please try again.', 'games-collector' ),
					esc_html__( 'Duplicate game found', 'games-collector' ),
					[ 'back_link' => true ]
				);
			}

			$post_id = wp_insert_post( [
				'post_type'   => 'gc_game',
				'post_title'  => esc_html( $game['title'] ),
				'post_status' => 'draft',
			] );

			if ( ! is_wp_error( $post_id ) ) {
				$redirect_url = admin_url( sprintf( 'post.php?post=%d&action=edit', $post_id ) );

				// Add game meta.
				add_post_meta( $post_id, '_gc_min_players', absint( $game['minplayers'] ) );
				add_post_meta( $post_id, '_gc_max_players', absint( $game['maxplayers'] ) );
				add_post_meta( $post_id, '_gc_age', absint( $game['minage'] ) );
				add_post_meta( $post_id, '_gc_link', sprintf( 'https://www.boardgamegeek.com/boardgame/%d/', $game_id ) );
				add_post_meta( $post_id, '_gc_bgg_id', $game_id );

				if ( absint( $game['minplaytime'] ) === absint( $game['maxplaytime'] ) ) {
					add_post_meta( $post_id, '_gc_time', esc_html( $game['minplaytime'] ) );
				} else {
					add_post_meta( $post_id, '_gc_time', esc_html( $game['minplaytime'] . '-' . $game['maxplaytime'] ) );
				}

				if ( isset( $game['categories'] ) ) {
					foreach ( $game['categories'] as $game_attribute ) {
						$similar_attribute = get_attribute_like( $game_attribute );

						// If there's an existing attribute that matches the BGG category, use that.
						if ( $similar_attribute ) {
							wp_set_post_terms( $post_id, [ $similar_attribute ], 'gc_attribute', true );
						}

						// Otherwise insert a new term.
						wp_set_post_terms( $post_id, $game_attribute, 'gc_attribute', true );
					}
				}

				// Sideload the image from BGG.
				attach_bgg_image( $post_id, $game );
			}
		}

		// Delete the transient so we can do this again.
		delete_transient( 'gc_last_bgg_search' );

		// Redirect to the edit page for this game.
		if ( is_user_logged_in() ) {
			wp_safe_redirect( esc_url_raw( $redirect_url ) );
			exit;
		}

		return;
	}

	return wp_die( esc_html__( 'Security check failed. What were you doing?', 'games-collector' ), esc_html__( 'Nonce check failed', 'games-collector' ) );
}

/**
 * Check if an existing game attribute term exists and return the ID if it does.
 *
 * @since  1.2.0
 * @param  string $search The game attribute name.
 * @return int|bool       The term ID if a matching term exists, false if it doesn't.
 */
function get_attribute_like( $search ) {
	// Check if a previously cached attribute for this term exists already.
	$cached_term_search = get_transient( 'gc_frequently_used_attributes' );
	if ( $cached_term_search && array_key_exists( $search, $cached_term_search ) ) {
		return $cached_term_search[ $search ];
	}

	$terms     = [];
	$all_terms = get_terms( [
		'taxonomy' => 'gc_attribute',
		'hide_empty' => false,
	] );

	foreach ( $all_terms as $term ) {
		similar_text( $term->name, $search, $similarity );
		if ( $similarity > 75 ) {
			$terms[] = $term->term_id;
		}
	}


	if ( ! is_wp_error( $terms ) && count( $terms ) > 0 ) {
		// Cache this term combination so we can access it faster later.
		if ( ! $cached_term_search ) {
			set_transient( 'gc_frequently_used_attributes', [
				$search => $terms[0],
			], 999 * YEAR_IN_SECONDS );
		} else {
			$cached_term_search = array_merge( $cached_term_search, [ $search => $terms[0] ] );
			set_transient( 'gc_frequently_used_attributes', $cached_term_search, 999 * YEAR_IN_SECONDS );
		}

		return $terms[0];
	}

	return false;
}

/**
 * Sideload image for a BGG image.
 *
 * @since  1.2.0
 * @param  int   $post_id The game ID.
 * @param  array $game    The array of game data from BGG.
 */
function attach_bgg_image( $post_id, $game ) {
	$image_id = media_sideload_image( esc_url_raw( $game['image'] ), $post_id, esc_html( $game['title'] ), 'id' );
	set_post_thumbnail( $post_id, $image_id );
}
