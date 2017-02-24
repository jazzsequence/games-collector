<?php
/**
 * Unit tests for Shortcode namespace.
 *
 * @since   1.1.0
 * @package GC\GamesCollector
 */

use GC\GamesCollector\Shortcode as Shortcode;

/**
 * Games Collector Shortcode unit test class.
 *
 * @since 1.1.0
 */
class GC_Test_Shortcode extends WP_UnitTestCase {
	/**
	 * Hook into the unit test setup function to set some stuff up.
	 */
	public function setUp() {
		// Create some games.
		$chrononauts = $this->factory->post->create( [ 'Chrononauts' ] );
		$frog_juice  = $this->factory->post->create( [ 'Frog Juice' ] );
		$hanabi      = $this->factory->post->create( [ 'Hanabi' ] );
		$magic       = $this->factory->post->create( [ 'Magic: the Gathering' ] );
		$mp_fluxx    = $this->factory->post->create( [ 'Monty Python Fluxx' ] );
		$ramses      = $this->factory->post->create( [ 'Ramses Return' ] );

		// Add the post meta.
		update_post_meta( $chrononauts, '_gc_min_players', 1 );
		update_post_meta( $chrononauts, '_gc_max_players', 6 );
		update_post_meta( $chrononauts, '_gc_time', '20-45' );
		update_post_meta( $chrononauts, '_gc_age', 11 );
		update_post_meta( $chrononauts, '_gc_difficulty', 'easy' );

		update_post_meta( $frog_juice, '_gc_min_players', 2 );
		update_post_meta( $frog_juice, '_gc_max_players', 4 );
		update_post_meta( $frog_juice, '_gc_time', '25' );
		update_post_meta( $frog_juice, '_gc_age', 8 );
		update_post_meta( $frog_juice, '_gc_difficulty', 'easy' );

		update_post_meta( $hanabi, '_gc_min_players', 2 );
		update_post_meta( $hanabi, '_gc_max_players', 5 );
		update_post_meta( $hanabi, '_gc_time', '30' );
		update_post_meta( $hanabi, '_gc_age', 8 );
		update_post_meta( $hanabi, '_gc_difficulty', 'easy' );

		update_post_meta( $magic, '_gc_min_players', 1 );
		update_post_meta( $magic, '_gc_max_players', 8 );
		update_post_meta( $magic, '_gc_time', '15-60' );
		update_post_meta( $magic, '_gc_difficulty', 'moderate' );

		update_post_meta( $mp_fluxx, '_gc_min_players', 2 );
		update_post_meta( $mp_fluxx, '_gc_max_players', 6 );
		update_post_meta( $mp_fluxx, '_gc_time', '10-40' );
		update_post_meta( $mp_fluxx, '_gc_age', 13 );
		update_post_meta( $mp_fluxx, '_gc_difficulty', 'easy' );

		update_post_meta( $ramses, '_gc_min_players', 2 );
		update_post_meta( $ramses, '_gc_max_players', 4 );
		update_post_meta( $ramses, '_gc_time', '10-20' );
		update_post_meta( $ramses, '_gc_age', 7 );
		update_post_meta( $ramses, '_gc_difficulty', 'easy' );

		// Add the terms.
		wp_set_object_terms( $chrononauts, [ 'card', 'easy-to-learn', 'fast-paced', 'scifi', 'solo' ], 'gc_attribute' );
		wp_set_object_terms( $frog_juice, [ 'card', 'easy-to-learn', 'party' ], 'gc_attribute' );
		wp_set_object_terms( $hanabi, [ 'card', 'easy-to-learn', 'coop' ], 'gc_attribute' );
		wp_set_object_terms( $magic, [ 'card', 'deck-building', 'fantasy', 'strategy' ], 'gc_attribute' );
		wp_set_object_terms( $mp_fluxx, [ 'card', 'easy-to-learn', 'fast-paced', 'fantasy', 'based-on-film-tv' ], 'gc_attribute' );
		wp_set_object_terms( $ramses, [ 'dice', 'easy-to-learn', 'fantasy', 'historical' ], 'gc_attribute' );
	}

	private function games_list_markup() {
		return '<div class="games-filter-group">
		<button data-filter="*">Show All</button><button data-filter=".gc_attribute-based-on-film-tv">Based on a Film/TV Show</button><button data-filter=".gc_attribute-card">Card Game</button><button data-filter=".gc_attribute-coop">Cooperative</button><button data-filter=".gc_attribute-deck-building">Deck Building</button><button data-filter=".gc_attribute-dice">Dice Game</button><button data-filter=".gc_attribute-easy-to-learn">Easy-to-learn</button><button data-filter=".gc_attribute-fantasy">Fantasy</button><button data-filter=".gc_attribute-fast-paced">Fast-paced</button><button data-filter=".gc_attribute-strategy">Heavy Strategy</button><button data-filter=".gc_attribute-historical">Historical</button><button data-filter=".gc_attribute-party">Party Game</button><button data-filter=".gc_attribute-scifi">Sci-Fi</button><button data-filter=".gc_attribute-solo">Solo Play</button><button data-filter=".short">Short Games</button><button data-filter=".long">Long Games</button><button data-filter=".4-and-up,.5-and-up,.6-and-up,.7-and-up,.8-and-up,.9-and-up">Good for Kids</button><button data-filter=".mature">Adult Games</button><div class="player-filter"><label for="players-filter-select">How many players?:</label>
		<select class="players-filter-select">
			<option selected="">- Select one -</option>
			<option value=".2-players,.min-2-players,.max-2-players,.max-3-players,.max-4-players,.max-5-players,.max-6-players,.max-7-players,.8-or-more-players">2+ players</option>
			<option value=".4-players,.min-4-players,.max-4-players,.max-5-players,.max-6-players,.max-7-players,.8-or-more-players">4+ players</option>
			<option value=".5-players,.min-5-players,.max-5-players,.max-6-players,.max-7-players,.8-or-more-players">5+ players</option>
			<option value=".6-players,.min-6-players,.max-6-players,.max-7-players,.8-or-more-players">6+ players</option>
			<option value=".8-players,.min-8-players,.8-or-more-players">8+ players</option>
		</select>
	</div><div class="difficulty-filter"><label for="difficulty-filter-select">Difficulty:</label>
		<select class="difficulty-filter-select">
			<option selected="">- Select one -</option><option value=".hardcore">Hard Core (experienced gamers only!)</option>
		</select>
	</div>	</div>
	<div class="games-collector-list" style="position: relative; height: 480px;">
					<div class="game-single min-1-players max-6-players 11-and-up easy post-44 gc_game type-gc_game status-publish hentry gc_attribute-card gc_attribute-easy-to-learn gc_attribute-fast-paced gc_attribute-scifi gc_attribute-solo" id="game-44" style="position: absolute; left: 0px; top: 0px;">

				<span class="game-title" id="game-44-title">Chrononauts</span>
		<div class="game-info" id="game-44-info">

		<span class="gc-icon icon-game-players"><svg class="gc-icon svg gc-icon-players" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>players</title>
<path d="M9.266 14c-1.625 0.047-3.094 0.75-4.141 2h-2.094c-1.563 0-3.031-0.75-3.031-2.484 0-1.266-0.047-5.516 1.937-5.516 0.328 0 1.953 1.328 4.062 1.328 0.719 0 1.406-0.125 2.078-0.359-0.047 0.344-0.078 0.688-0.078 1.031 0 1.422 0.453 2.828 1.266 4zM26 23.953c0 2.531-1.672 4.047-4.172 4.047h-13.656c-2.5 0-4.172-1.516-4.172-4.047 0-3.531 0.828-8.953 5.406-8.953 0.531 0 2.469 2.172 5.594 2.172s5.063-2.172 5.594-2.172c4.578 0 5.406 5.422 5.406 8.953zM10 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4zM21 10c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zM30 13.516c0 1.734-1.469 2.484-3.031 2.484h-2.094c-1.047-1.25-2.516-1.953-4.141-2 0.812-1.172 1.266-2.578 1.266-4 0-0.344-0.031-0.688-0.078-1.031 0.672 0.234 1.359 0.359 2.078 0.359 2.109 0 3.734-1.328 4.062-1.328 1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z"></path>
</svg></span><span class="game-num-players" id="game-44-num-players">1 - 6 players</span>		<span class="gc-icon icon-game-time"><svg class="gc-icon svg gc-icon-time" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="28" viewBox="0 0 24 28">
<title>time</title>
<path d="M14 8.5v7c0 0.281-0.219 0.5-0.5 0.5h-5c-0.281 0-0.5-0.219-0.5-0.5v-1c0-0.281 0.219-0.5 0.5-0.5h3.5v-5.5c0-0.281 0.219-0.5 0.5-0.5h1c0.281 0 0.5 0.219 0.5 0.5zM20.5 14c0-4.688-3.813-8.5-8.5-8.5s-8.5 3.813-8.5 8.5 3.813 8.5 8.5 8.5 8.5-3.813 8.5-8.5zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12 12 5.375 12 12z"></path>
</svg></span><span class="game-playing-time" id="game-44-playing-time">20-45 minutes</span>

				<span class="gc-icon icon-game-age"><svg class="gc-icon svg gc-icon-age" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="28" viewBox="0 0 20 28">
<title>age</title>
<path d="M18.562 8.563l-4.562 4.562v12.875c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-6h-1v6c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-12.875l-4.562-4.562c-0.578-0.594-0.578-1.531 0-2.125 0.594-0.578 1.531-0.578 2.125 0l3.563 3.563h5.75l3.563-3.563c0.594-0.578 1.531-0.578 2.125 0 0.578 0.594 0.578 1.531 0 2.125zM13.5 6c0 1.937-1.563 3.5-3.5 3.5s-3.5-1.563-3.5-3.5 1.563-3.5 3.5-3.5 3.5 1.563 3.5 3.5z"></path>
</svg></span><span class="game-age" id="game-44-age">11+</span>

				<span class="gc-icon icon-game-difficulty"><svg class="gc-icon svg gc-icon-difficulty" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="28" viewBox="0 0 26 28">
<title>difficulty</title>
<path d="M26 17.156c0 1.609-0.922 2.953-2.625 2.953-1.906 0-2.406-1.734-4.125-1.734-1.25 0-1.719 0.781-1.719 1.937 0 1.219 0.5 2.391 0.484 3.594v0.078c-0.172 0-0.344 0-0.516 0.016-1.609 0.156-3.234 0.469-4.859 0.469-1.109 0-2.266-0.438-2.266-1.719 0-1.719 1.734-2.219 1.734-4.125 0-1.703-1.344-2.625-2.953-2.625-1.641 0-3.156 0.906-3.156 2.703 0 1.984 1.516 2.844 1.516 3.922 0 0.547-0.344 1.031-0.719 1.391-0.484 0.453-1.172 0.547-1.828 0.547-1.281 0-2.562-0.172-3.828-0.375-0.281-0.047-0.578-0.078-0.859-0.125l-0.203-0.031c-0.031-0.016-0.078-0.016-0.078-0.031v-16c0.063 0.047 0.984 0.156 1.141 0.187 1.266 0.203 2.547 0.375 3.828 0.375 0.656 0 1.344-0.094 1.828-0.547 0.375-0.359 0.719-0.844 0.719-1.391 0-1.078-1.516-1.937-1.516-3.922 0-1.797 1.516-2.703 3.172-2.703 1.594 0 2.938 0.922 2.938 2.625 0 1.906-1.734 2.406-1.734 4.125 0 1.281 1.156 1.719 2.266 1.719 1.797 0 3.578-0.406 5.359-0.5v0.031c-0.047 0.063-0.156 0.984-0.187 1.141-0.203 1.266-0.375 2.547-0.375 3.828 0 0.656 0.094 1.344 0.547 1.828 0.359 0.375 0.844 0.719 1.391 0.719 1.078 0 1.937-1.516 3.922-1.516 1.797 0 2.703 1.516 2.703 3.156z"></path>
</svg></span><span class="game-difficulty" id="game-44-difficulty">Easy</span>

		<div class="game-attributes"><span class="gc-icon icon-game-attributes"><svg class="gc-icon svg gc-icon-tags" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>tags</title>
<path d="M7 7c0-1.109-0.891-2-2-2s-2 0.891-2 2 0.891 2 2 2 2-0.891 2-2zM23.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578s-1.047-0.219-1.406-0.578l-11.172-11.188c-0.797-0.781-1.422-2.297-1.422-3.406v-6.5c0-1.094 0.906-2 2-2h6.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422zM29.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578-0.812 0-1.219-0.375-1.75-0.922l7.344-7.344c0.359-0.359 0.578-0.875 0.578-1.406s-0.219-1.047-0.578-1.422l-11.172-11.156c-0.797-0.797-2.312-1.422-3.422-1.422h3.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422z"></path>
</svg></span><span class="game-attributes" id="game-44-attributes"><span class="gc-attribute attribute-card">Card Game</span>, <span class="gc-attribute attribute-easy-to-learn">Easy-to-learn</span>, <span class="gc-attribute attribute-fast-paced">Fast-paced</span>, <span class="gc-attribute attribute-scifi">Sci-Fi</span>, <span class="gc-attribute attribute-solo">Solo Play</span></span></div>		</div>


			</div>
					<div class="game-single min-2-players max-4-players 8-and-up easy post-45 gc_game type-gc_game status-publish hentry gc_attribute-card gc_attribute-easy-to-learn gc_attribute-party" id="game-45" style="position: absolute; left: 0px; top: 80px;">

				<span class="game-title" id="game-45-title">Frog Juice</span>
		<div class="game-info" id="game-45-info">

		<span class="gc-icon icon-game-players"><svg class="gc-icon svg gc-icon-players" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>players</title>
<path d="M9.266 14c-1.625 0.047-3.094 0.75-4.141 2h-2.094c-1.563 0-3.031-0.75-3.031-2.484 0-1.266-0.047-5.516 1.937-5.516 0.328 0 1.953 1.328 4.062 1.328 0.719 0 1.406-0.125 2.078-0.359-0.047 0.344-0.078 0.688-0.078 1.031 0 1.422 0.453 2.828 1.266 4zM26 23.953c0 2.531-1.672 4.047-4.172 4.047h-13.656c-2.5 0-4.172-1.516-4.172-4.047 0-3.531 0.828-8.953 5.406-8.953 0.531 0 2.469 2.172 5.594 2.172s5.063-2.172 5.594-2.172c4.578 0 5.406 5.422 5.406 8.953zM10 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4zM21 10c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zM30 13.516c0 1.734-1.469 2.484-3.031 2.484h-2.094c-1.047-1.25-2.516-1.953-4.141-2 0.812-1.172 1.266-2.578 1.266-4 0-0.344-0.031-0.688-0.078-1.031 0.672 0.234 1.359 0.359 2.078 0.359 2.109 0 3.734-1.328 4.062-1.328 1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z"></path>
</svg></span><span class="game-num-players" id="game-45-num-players">2 - 4 players</span>		<span class="gc-icon icon-game-time"><svg class="gc-icon svg gc-icon-time" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="28" viewBox="0 0 24 28">
<title>time</title>
<path d="M14 8.5v7c0 0.281-0.219 0.5-0.5 0.5h-5c-0.281 0-0.5-0.219-0.5-0.5v-1c0-0.281 0.219-0.5 0.5-0.5h3.5v-5.5c0-0.281 0.219-0.5 0.5-0.5h1c0.281 0 0.5 0.219 0.5 0.5zM20.5 14c0-4.688-3.813-8.5-8.5-8.5s-8.5 3.813-8.5 8.5 3.813 8.5 8.5 8.5 8.5-3.813 8.5-8.5zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12 12 5.375 12 12z"></path>
</svg></span><span class="game-playing-time" id="game-45-playing-time">25 minutes</span>

				<span class="gc-icon icon-game-age"><svg class="gc-icon svg gc-icon-age" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="28" viewBox="0 0 20 28">
<title>age</title>
<path d="M18.562 8.563l-4.562 4.562v12.875c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-6h-1v6c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-12.875l-4.562-4.562c-0.578-0.594-0.578-1.531 0-2.125 0.594-0.578 1.531-0.578 2.125 0l3.563 3.563h5.75l3.563-3.563c0.594-0.578 1.531-0.578 2.125 0 0.578 0.594 0.578 1.531 0 2.125zM13.5 6c0 1.937-1.563 3.5-3.5 3.5s-3.5-1.563-3.5-3.5 1.563-3.5 3.5-3.5 3.5 1.563 3.5 3.5z"></path>
</svg></span><span class="game-age" id="game-45-age">8+</span>

				<span class="gc-icon icon-game-difficulty"><svg class="gc-icon svg gc-icon-difficulty" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="28" viewBox="0 0 26 28">
<title>difficulty</title>
<path d="M26 17.156c0 1.609-0.922 2.953-2.625 2.953-1.906 0-2.406-1.734-4.125-1.734-1.25 0-1.719 0.781-1.719 1.937 0 1.219 0.5 2.391 0.484 3.594v0.078c-0.172 0-0.344 0-0.516 0.016-1.609 0.156-3.234 0.469-4.859 0.469-1.109 0-2.266-0.438-2.266-1.719 0-1.719 1.734-2.219 1.734-4.125 0-1.703-1.344-2.625-2.953-2.625-1.641 0-3.156 0.906-3.156 2.703 0 1.984 1.516 2.844 1.516 3.922 0 0.547-0.344 1.031-0.719 1.391-0.484 0.453-1.172 0.547-1.828 0.547-1.281 0-2.562-0.172-3.828-0.375-0.281-0.047-0.578-0.078-0.859-0.125l-0.203-0.031c-0.031-0.016-0.078-0.016-0.078-0.031v-16c0.063 0.047 0.984 0.156 1.141 0.187 1.266 0.203 2.547 0.375 3.828 0.375 0.656 0 1.344-0.094 1.828-0.547 0.375-0.359 0.719-0.844 0.719-1.391 0-1.078-1.516-1.937-1.516-3.922 0-1.797 1.516-2.703 3.172-2.703 1.594 0 2.938 0.922 2.938 2.625 0 1.906-1.734 2.406-1.734 4.125 0 1.281 1.156 1.719 2.266 1.719 1.797 0 3.578-0.406 5.359-0.5v0.031c-0.047 0.063-0.156 0.984-0.187 1.141-0.203 1.266-0.375 2.547-0.375 3.828 0 0.656 0.094 1.344 0.547 1.828 0.359 0.375 0.844 0.719 1.391 0.719 1.078 0 1.937-1.516 3.922-1.516 1.797 0 2.703 1.516 2.703 3.156z"></path>
</svg></span><span class="game-difficulty" id="game-45-difficulty">Easy</span>

		<div class="game-attributes"><span class="gc-icon icon-game-attributes"><svg class="gc-icon svg gc-icon-tags" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>tags</title>
<path d="M7 7c0-1.109-0.891-2-2-2s-2 0.891-2 2 0.891 2 2 2 2-0.891 2-2zM23.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578s-1.047-0.219-1.406-0.578l-11.172-11.188c-0.797-0.781-1.422-2.297-1.422-3.406v-6.5c0-1.094 0.906-2 2-2h6.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422zM29.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578-0.812 0-1.219-0.375-1.75-0.922l7.344-7.344c0.359-0.359 0.578-0.875 0.578-1.406s-0.219-1.047-0.578-1.422l-11.172-11.156c-0.797-0.797-2.312-1.422-3.422-1.422h3.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422z"></path>
</svg></span><span class="game-attributes" id="game-45-attributes"><span class="gc-attribute attribute-card">Card Game</span>, <span class="gc-attribute attribute-easy-to-learn">Easy-to-learn</span>, <span class="gc-attribute attribute-party">Party Game</span></span></div>		</div>


			</div>
					<div class="game-single min-2-players max-5-players 8-and-up easy post-43 gc_game type-gc_game status-publish hentry gc_attribute-card gc_attribute-coop gc_attribute-easy-to-learn" id="game-43" style="position: absolute; left: 0px; top: 160px;">

				<span class="game-title" id="game-43-title">Hanabi</span>
		<div class="game-info" id="game-43-info">

		<span class="gc-icon icon-game-players"><svg class="gc-icon svg gc-icon-players" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>players</title>
<path d="M9.266 14c-1.625 0.047-3.094 0.75-4.141 2h-2.094c-1.563 0-3.031-0.75-3.031-2.484 0-1.266-0.047-5.516 1.937-5.516 0.328 0 1.953 1.328 4.062 1.328 0.719 0 1.406-0.125 2.078-0.359-0.047 0.344-0.078 0.688-0.078 1.031 0 1.422 0.453 2.828 1.266 4zM26 23.953c0 2.531-1.672 4.047-4.172 4.047h-13.656c-2.5 0-4.172-1.516-4.172-4.047 0-3.531 0.828-8.953 5.406-8.953 0.531 0 2.469 2.172 5.594 2.172s5.063-2.172 5.594-2.172c4.578 0 5.406 5.422 5.406 8.953zM10 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4zM21 10c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zM30 13.516c0 1.734-1.469 2.484-3.031 2.484h-2.094c-1.047-1.25-2.516-1.953-4.141-2 0.812-1.172 1.266-2.578 1.266-4 0-0.344-0.031-0.688-0.078-1.031 0.672 0.234 1.359 0.359 2.078 0.359 2.109 0 3.734-1.328 4.062-1.328 1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z"></path>
</svg></span><span class="game-num-players" id="game-43-num-players">2 - 5 players</span>		<span class="gc-icon icon-game-time"><svg class="gc-icon svg gc-icon-time" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="28" viewBox="0 0 24 28">
<title>time</title>
<path d="M14 8.5v7c0 0.281-0.219 0.5-0.5 0.5h-5c-0.281 0-0.5-0.219-0.5-0.5v-1c0-0.281 0.219-0.5 0.5-0.5h3.5v-5.5c0-0.281 0.219-0.5 0.5-0.5h1c0.281 0 0.5 0.219 0.5 0.5zM20.5 14c0-4.688-3.813-8.5-8.5-8.5s-8.5 3.813-8.5 8.5 3.813 8.5 8.5 8.5 8.5-3.813 8.5-8.5zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12 12 5.375 12 12z"></path>
</svg></span><span class="game-playing-time" id="game-43-playing-time">30 minutes</span>

				<span class="gc-icon icon-game-age"><svg class="gc-icon svg gc-icon-age" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="28" viewBox="0 0 20 28">
<title>age</title>
<path d="M18.562 8.563l-4.562 4.562v12.875c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-6h-1v6c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-12.875l-4.562-4.562c-0.578-0.594-0.578-1.531 0-2.125 0.594-0.578 1.531-0.578 2.125 0l3.563 3.563h5.75l3.563-3.563c0.594-0.578 1.531-0.578 2.125 0 0.578 0.594 0.578 1.531 0 2.125zM13.5 6c0 1.937-1.563 3.5-3.5 3.5s-3.5-1.563-3.5-3.5 1.563-3.5 3.5-3.5 3.5 1.563 3.5 3.5z"></path>
</svg></span><span class="game-age" id="game-43-age">8+</span>

				<span class="gc-icon icon-game-difficulty"><svg class="gc-icon svg gc-icon-difficulty" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="28" viewBox="0 0 26 28">
<title>difficulty</title>
<path d="M26 17.156c0 1.609-0.922 2.953-2.625 2.953-1.906 0-2.406-1.734-4.125-1.734-1.25 0-1.719 0.781-1.719 1.937 0 1.219 0.5 2.391 0.484 3.594v0.078c-0.172 0-0.344 0-0.516 0.016-1.609 0.156-3.234 0.469-4.859 0.469-1.109 0-2.266-0.438-2.266-1.719 0-1.719 1.734-2.219 1.734-4.125 0-1.703-1.344-2.625-2.953-2.625-1.641 0-3.156 0.906-3.156 2.703 0 1.984 1.516 2.844 1.516 3.922 0 0.547-0.344 1.031-0.719 1.391-0.484 0.453-1.172 0.547-1.828 0.547-1.281 0-2.562-0.172-3.828-0.375-0.281-0.047-0.578-0.078-0.859-0.125l-0.203-0.031c-0.031-0.016-0.078-0.016-0.078-0.031v-16c0.063 0.047 0.984 0.156 1.141 0.187 1.266 0.203 2.547 0.375 3.828 0.375 0.656 0 1.344-0.094 1.828-0.547 0.375-0.359 0.719-0.844 0.719-1.391 0-1.078-1.516-1.937-1.516-3.922 0-1.797 1.516-2.703 3.172-2.703 1.594 0 2.938 0.922 2.938 2.625 0 1.906-1.734 2.406-1.734 4.125 0 1.281 1.156 1.719 2.266 1.719 1.797 0 3.578-0.406 5.359-0.5v0.031c-0.047 0.063-0.156 0.984-0.187 1.141-0.203 1.266-0.375 2.547-0.375 3.828 0 0.656 0.094 1.344 0.547 1.828 0.359 0.375 0.844 0.719 1.391 0.719 1.078 0 1.937-1.516 3.922-1.516 1.797 0 2.703 1.516 2.703 3.156z"></path>
</svg></span><span class="game-difficulty" id="game-43-difficulty">Easy</span>

		<div class="game-attributes"><span class="gc-icon icon-game-attributes"><svg class="gc-icon svg gc-icon-tags" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>tags</title>
<path d="M7 7c0-1.109-0.891-2-2-2s-2 0.891-2 2 0.891 2 2 2 2-0.891 2-2zM23.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578s-1.047-0.219-1.406-0.578l-11.172-11.188c-0.797-0.781-1.422-2.297-1.422-3.406v-6.5c0-1.094 0.906-2 2-2h6.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422zM29.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578-0.812 0-1.219-0.375-1.75-0.922l7.344-7.344c0.359-0.359 0.578-0.875 0.578-1.406s-0.219-1.047-0.578-1.422l-11.172-11.156c-0.797-0.797-2.312-1.422-3.422-1.422h3.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422z"></path>
</svg></span><span class="game-attributes" id="game-43-attributes"><span class="gc-attribute attribute-card">Card Game</span>, <span class="gc-attribute attribute-coop">Cooperative</span>, <span class="gc-attribute attribute-easy-to-learn">Easy-to-learn</span></span></div>		</div>


			</div>
					<div class="game-single min-1-players 8-or-more-players moderate post-41 gc_game type-gc_game status-publish hentry gc_attribute-card gc_attribute-deck-building gc_attribute-fantasy gc_attribute-strategy" id="game-41" style="position: absolute; left: 0px; top: 240px;">

				<span class="game-title" id="game-41-title">Magic: the Gathering</span>
		<div class="game-info" id="game-41-info">

		<span class="gc-icon icon-game-players"><svg class="gc-icon svg gc-icon-players" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>players</title>
<path d="M9.266 14c-1.625 0.047-3.094 0.75-4.141 2h-2.094c-1.563 0-3.031-0.75-3.031-2.484 0-1.266-0.047-5.516 1.937-5.516 0.328 0 1.953 1.328 4.062 1.328 0.719 0 1.406-0.125 2.078-0.359-0.047 0.344-0.078 0.688-0.078 1.031 0 1.422 0.453 2.828 1.266 4zM26 23.953c0 2.531-1.672 4.047-4.172 4.047h-13.656c-2.5 0-4.172-1.516-4.172-4.047 0-3.531 0.828-8.953 5.406-8.953 0.531 0 2.469 2.172 5.594 2.172s5.063-2.172 5.594-2.172c4.578 0 5.406 5.422 5.406 8.953zM10 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4zM21 10c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zM30 13.516c0 1.734-1.469 2.484-3.031 2.484h-2.094c-1.047-1.25-2.516-1.953-4.141-2 0.812-1.172 1.266-2.578 1.266-4 0-0.344-0.031-0.688-0.078-1.031 0.672 0.234 1.359 0.359 2.078 0.359 2.109 0 3.734-1.328 4.062-1.328 1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z"></path>
</svg></span><span class="game-num-players" id="game-41-num-players">1 - 8 players</span>		<span class="gc-icon icon-game-time"><svg class="gc-icon svg gc-icon-time" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="28" viewBox="0 0 24 28">
<title>time</title>
<path d="M14 8.5v7c0 0.281-0.219 0.5-0.5 0.5h-5c-0.281 0-0.5-0.219-0.5-0.5v-1c0-0.281 0.219-0.5 0.5-0.5h3.5v-5.5c0-0.281 0.219-0.5 0.5-0.5h1c0.281 0 0.5 0.219 0.5 0.5zM20.5 14c0-4.688-3.813-8.5-8.5-8.5s-8.5 3.813-8.5 8.5 3.813 8.5 8.5 8.5 8.5-3.813 8.5-8.5zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12 12 5.375 12 12z"></path>
</svg></span><span class="game-playing-time" id="game-41-playing-time">15-60 minutes</span>

				<span class="gc-icon icon-game-difficulty"><svg class="gc-icon svg gc-icon-difficulty" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="28" viewBox="0 0 26 28">
<title>difficulty</title>
<path d="M26 17.156c0 1.609-0.922 2.953-2.625 2.953-1.906 0-2.406-1.734-4.125-1.734-1.25 0-1.719 0.781-1.719 1.937 0 1.219 0.5 2.391 0.484 3.594v0.078c-0.172 0-0.344 0-0.516 0.016-1.609 0.156-3.234 0.469-4.859 0.469-1.109 0-2.266-0.438-2.266-1.719 0-1.719 1.734-2.219 1.734-4.125 0-1.703-1.344-2.625-2.953-2.625-1.641 0-3.156 0.906-3.156 2.703 0 1.984 1.516 2.844 1.516 3.922 0 0.547-0.344 1.031-0.719 1.391-0.484 0.453-1.172 0.547-1.828 0.547-1.281 0-2.562-0.172-3.828-0.375-0.281-0.047-0.578-0.078-0.859-0.125l-0.203-0.031c-0.031-0.016-0.078-0.016-0.078-0.031v-16c0.063 0.047 0.984 0.156 1.141 0.187 1.266 0.203 2.547 0.375 3.828 0.375 0.656 0 1.344-0.094 1.828-0.547 0.375-0.359 0.719-0.844 0.719-1.391 0-1.078-1.516-1.937-1.516-3.922 0-1.797 1.516-2.703 3.172-2.703 1.594 0 2.938 0.922 2.938 2.625 0 1.906-1.734 2.406-1.734 4.125 0 1.281 1.156 1.719 2.266 1.719 1.797 0 3.578-0.406 5.359-0.5v0.031c-0.047 0.063-0.156 0.984-0.187 1.141-0.203 1.266-0.375 2.547-0.375 3.828 0 0.656 0.094 1.344 0.547 1.828 0.359 0.375 0.844 0.719 1.391 0.719 1.078 0 1.937-1.516 3.922-1.516 1.797 0 2.703 1.516 2.703 3.156z"></path>
</svg></span><span class="game-difficulty" id="game-41-difficulty">Moderate</span>

		<div class="game-attributes"><span class="gc-icon icon-game-attributes"><svg class="gc-icon svg gc-icon-tags" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>tags</title>
<path d="M7 7c0-1.109-0.891-2-2-2s-2 0.891-2 2 0.891 2 2 2 2-0.891 2-2zM23.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578s-1.047-0.219-1.406-0.578l-11.172-11.188c-0.797-0.781-1.422-2.297-1.422-3.406v-6.5c0-1.094 0.906-2 2-2h6.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422zM29.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578-0.812 0-1.219-0.375-1.75-0.922l7.344-7.344c0.359-0.359 0.578-0.875 0.578-1.406s-0.219-1.047-0.578-1.422l-11.172-11.156c-0.797-0.797-2.312-1.422-3.422-1.422h3.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422z"></path>
</svg></span><span class="game-attributes" id="game-41-attributes"><span class="gc-attribute attribute-card">Card Game</span>, <span class="gc-attribute attribute-deck-building">Deck Building</span>, <span class="gc-attribute attribute-fantasy">Fantasy</span>, <span class="gc-attribute attribute-strategy">Heavy Strategy</span></span></div>		</div>


			</div>
					<div class="game-single min-2-players max-6-players mature easy post-42 gc_game type-gc_game status-publish hentry gc_attribute-based-on-film-tv gc_attribute-card gc_attribute-easy-to-learn gc_attribute-fantasy gc_attribute-fast-paced" id="game-42" style="position: absolute; left: 0px; top: 320px;">

				<span class="game-title" id="game-42-title">Monty Python Fluxx</span>
		<div class="game-info" id="game-42-info">

		<span class="gc-icon icon-game-players"><svg class="gc-icon svg gc-icon-players" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>players</title>
<path d="M9.266 14c-1.625 0.047-3.094 0.75-4.141 2h-2.094c-1.563 0-3.031-0.75-3.031-2.484 0-1.266-0.047-5.516 1.937-5.516 0.328 0 1.953 1.328 4.062 1.328 0.719 0 1.406-0.125 2.078-0.359-0.047 0.344-0.078 0.688-0.078 1.031 0 1.422 0.453 2.828 1.266 4zM26 23.953c0 2.531-1.672 4.047-4.172 4.047h-13.656c-2.5 0-4.172-1.516-4.172-4.047 0-3.531 0.828-8.953 5.406-8.953 0.531 0 2.469 2.172 5.594 2.172s5.063-2.172 5.594-2.172c4.578 0 5.406 5.422 5.406 8.953zM10 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4zM21 10c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zM30 13.516c0 1.734-1.469 2.484-3.031 2.484h-2.094c-1.047-1.25-2.516-1.953-4.141-2 0.812-1.172 1.266-2.578 1.266-4 0-0.344-0.031-0.688-0.078-1.031 0.672 0.234 1.359 0.359 2.078 0.359 2.109 0 3.734-1.328 4.062-1.328 1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z"></path>
</svg></span><span class="game-num-players" id="game-42-num-players">2 - 6 players</span>		<span class="gc-icon icon-game-time"><svg class="gc-icon svg gc-icon-time" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="28" viewBox="0 0 24 28">
<title>time</title>
<path d="M14 8.5v7c0 0.281-0.219 0.5-0.5 0.5h-5c-0.281 0-0.5-0.219-0.5-0.5v-1c0-0.281 0.219-0.5 0.5-0.5h3.5v-5.5c0-0.281 0.219-0.5 0.5-0.5h1c0.281 0 0.5 0.219 0.5 0.5zM20.5 14c0-4.688-3.813-8.5-8.5-8.5s-8.5 3.813-8.5 8.5 3.813 8.5 8.5 8.5 8.5-3.813 8.5-8.5zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12 12 5.375 12 12z"></path>
</svg></span><span class="game-playing-time" id="game-42-playing-time">10-40 minutes</span>

				<span class="gc-icon icon-game-age"><svg class="gc-icon svg gc-icon-age" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="28" viewBox="0 0 20 28">
<title>age</title>
<path d="M18.562 8.563l-4.562 4.562v12.875c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-6h-1v6c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-12.875l-4.562-4.562c-0.578-0.594-0.578-1.531 0-2.125 0.594-0.578 1.531-0.578 2.125 0l3.563 3.563h5.75l3.563-3.563c0.594-0.578 1.531-0.578 2.125 0 0.578 0.594 0.578 1.531 0 2.125zM13.5 6c0 1.937-1.563 3.5-3.5 3.5s-3.5-1.563-3.5-3.5 1.563-3.5 3.5-3.5 3.5 1.563 3.5 3.5z"></path>
</svg></span><span class="game-age" id="game-42-age">13+</span>

				<span class="gc-icon icon-game-difficulty"><svg class="gc-icon svg gc-icon-difficulty" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="28" viewBox="0 0 26 28">
<title>difficulty</title>
<path d="M26 17.156c0 1.609-0.922 2.953-2.625 2.953-1.906 0-2.406-1.734-4.125-1.734-1.25 0-1.719 0.781-1.719 1.937 0 1.219 0.5 2.391 0.484 3.594v0.078c-0.172 0-0.344 0-0.516 0.016-1.609 0.156-3.234 0.469-4.859 0.469-1.109 0-2.266-0.438-2.266-1.719 0-1.719 1.734-2.219 1.734-4.125 0-1.703-1.344-2.625-2.953-2.625-1.641 0-3.156 0.906-3.156 2.703 0 1.984 1.516 2.844 1.516 3.922 0 0.547-0.344 1.031-0.719 1.391-0.484 0.453-1.172 0.547-1.828 0.547-1.281 0-2.562-0.172-3.828-0.375-0.281-0.047-0.578-0.078-0.859-0.125l-0.203-0.031c-0.031-0.016-0.078-0.016-0.078-0.031v-16c0.063 0.047 0.984 0.156 1.141 0.187 1.266 0.203 2.547 0.375 3.828 0.375 0.656 0 1.344-0.094 1.828-0.547 0.375-0.359 0.719-0.844 0.719-1.391 0-1.078-1.516-1.937-1.516-3.922 0-1.797 1.516-2.703 3.172-2.703 1.594 0 2.938 0.922 2.938 2.625 0 1.906-1.734 2.406-1.734 4.125 0 1.281 1.156 1.719 2.266 1.719 1.797 0 3.578-0.406 5.359-0.5v0.031c-0.047 0.063-0.156 0.984-0.187 1.141-0.203 1.266-0.375 2.547-0.375 3.828 0 0.656 0.094 1.344 0.547 1.828 0.359 0.375 0.844 0.719 1.391 0.719 1.078 0 1.937-1.516 3.922-1.516 1.797 0 2.703 1.516 2.703 3.156z"></path>
</svg></span><span class="game-difficulty" id="game-42-difficulty">Easy</span>

		<div class="game-attributes"><span class="gc-icon icon-game-attributes"><svg class="gc-icon svg gc-icon-tags" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>tags</title>
<path d="M7 7c0-1.109-0.891-2-2-2s-2 0.891-2 2 0.891 2 2 2 2-0.891 2-2zM23.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578s-1.047-0.219-1.406-0.578l-11.172-11.188c-0.797-0.781-1.422-2.297-1.422-3.406v-6.5c0-1.094 0.906-2 2-2h6.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422zM29.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578-0.812 0-1.219-0.375-1.75-0.922l7.344-7.344c0.359-0.359 0.578-0.875 0.578-1.406s-0.219-1.047-0.578-1.422l-11.172-11.156c-0.797-0.797-2.312-1.422-3.422-1.422h3.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422z"></path>
</svg></span><span class="game-attributes" id="game-42-attributes"><span class="gc-attribute attribute-based-on-film-tv">Based on a Film/TV Show</span>, <span class="gc-attribute attribute-card">Card Game</span>, <span class="gc-attribute attribute-easy-to-learn">Easy-to-learn</span>, <span class="gc-attribute attribute-fantasy">Fantasy</span>, <span class="gc-attribute attribute-fast-paced">Fast-paced</span></span></div>		</div>


			</div>
					<div class="game-single min-2-players max-4-players 7-and-up easy short post-47 gc_game type-gc_game status-publish hentry gc_attribute-dice gc_attribute-easy-to-learn gc_attribute-fantasy gc_attribute-historical" id="game-47" style="position: absolute; left: 0px; top: 400px;">

				<span class="game-title" id="game-47-title">Ramses Return</span>
		<div class="game-info" id="game-47-info">

		<span class="gc-icon icon-game-players"><svg class="gc-icon svg gc-icon-players" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>players</title>
<path d="M9.266 14c-1.625 0.047-3.094 0.75-4.141 2h-2.094c-1.563 0-3.031-0.75-3.031-2.484 0-1.266-0.047-5.516 1.937-5.516 0.328 0 1.953 1.328 4.062 1.328 0.719 0 1.406-0.125 2.078-0.359-0.047 0.344-0.078 0.688-0.078 1.031 0 1.422 0.453 2.828 1.266 4zM26 23.953c0 2.531-1.672 4.047-4.172 4.047h-13.656c-2.5 0-4.172-1.516-4.172-4.047 0-3.531 0.828-8.953 5.406-8.953 0.531 0 2.469 2.172 5.594 2.172s5.063-2.172 5.594-2.172c4.578 0 5.406 5.422 5.406 8.953zM10 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4zM21 10c0 3.313-2.688 6-6 6s-6-2.688-6-6 2.688-6 6-6 6 2.688 6 6zM30 13.516c0 1.734-1.469 2.484-3.031 2.484h-2.094c-1.047-1.25-2.516-1.953-4.141-2 0.812-1.172 1.266-2.578 1.266-4 0-0.344-0.031-0.688-0.078-1.031 0.672 0.234 1.359 0.359 2.078 0.359 2.109 0 3.734-1.328 4.062-1.328 1.984 0 1.937 4.25 1.937 5.516zM28 4c0 2.203-1.797 4-4 4s-4-1.797-4-4 1.797-4 4-4 4 1.797 4 4z"></path>
</svg></span><span class="game-num-players" id="game-47-num-players">2 - 4 players</span>		<span class="gc-icon icon-game-time"><svg class="gc-icon svg gc-icon-time" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="28" viewBox="0 0 24 28">
<title>time</title>
<path d="M14 8.5v7c0 0.281-0.219 0.5-0.5 0.5h-5c-0.281 0-0.5-0.219-0.5-0.5v-1c0-0.281 0.219-0.5 0.5-0.5h3.5v-5.5c0-0.281 0.219-0.5 0.5-0.5h1c0.281 0 0.5 0.219 0.5 0.5zM20.5 14c0-4.688-3.813-8.5-8.5-8.5s-8.5 3.813-8.5 8.5 3.813 8.5 8.5 8.5 8.5-3.813 8.5-8.5zM24 14c0 6.625-5.375 12-12 12s-12-5.375-12-12 5.375-12 12-12 12 5.375 12 12z"></path>
</svg></span><span class="game-playing-time" id="game-47-playing-time">10-20 minutes</span>

				<span class="gc-icon icon-game-age"><svg class="gc-icon svg gc-icon-age" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="28" viewBox="0 0 20 28">
<title>age</title>
<path d="M18.562 8.563l-4.562 4.562v12.875c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-6h-1v6c0 0.969-0.781 1.75-1.75 1.75s-1.75-0.781-1.75-1.75v-12.875l-4.562-4.562c-0.578-0.594-0.578-1.531 0-2.125 0.594-0.578 1.531-0.578 2.125 0l3.563 3.563h5.75l3.563-3.563c0.594-0.578 1.531-0.578 2.125 0 0.578 0.594 0.578 1.531 0 2.125zM13.5 6c0 1.937-1.563 3.5-3.5 3.5s-3.5-1.563-3.5-3.5 1.563-3.5 3.5-3.5 3.5 1.563 3.5 3.5z"></path>
</svg></span><span class="game-age" id="game-47-age">7+</span>

				<span class="gc-icon icon-game-difficulty"><svg class="gc-icon svg gc-icon-difficulty" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="28" viewBox="0 0 26 28">
<title>difficulty</title>
<path d="M26 17.156c0 1.609-0.922 2.953-2.625 2.953-1.906 0-2.406-1.734-4.125-1.734-1.25 0-1.719 0.781-1.719 1.937 0 1.219 0.5 2.391 0.484 3.594v0.078c-0.172 0-0.344 0-0.516 0.016-1.609 0.156-3.234 0.469-4.859 0.469-1.109 0-2.266-0.438-2.266-1.719 0-1.719 1.734-2.219 1.734-4.125 0-1.703-1.344-2.625-2.953-2.625-1.641 0-3.156 0.906-3.156 2.703 0 1.984 1.516 2.844 1.516 3.922 0 0.547-0.344 1.031-0.719 1.391-0.484 0.453-1.172 0.547-1.828 0.547-1.281 0-2.562-0.172-3.828-0.375-0.281-0.047-0.578-0.078-0.859-0.125l-0.203-0.031c-0.031-0.016-0.078-0.016-0.078-0.031v-16c0.063 0.047 0.984 0.156 1.141 0.187 1.266 0.203 2.547 0.375 3.828 0.375 0.656 0 1.344-0.094 1.828-0.547 0.375-0.359 0.719-0.844 0.719-1.391 0-1.078-1.516-1.937-1.516-3.922 0-1.797 1.516-2.703 3.172-2.703 1.594 0 2.938 0.922 2.938 2.625 0 1.906-1.734 2.406-1.734 4.125 0 1.281 1.156 1.719 2.266 1.719 1.797 0 3.578-0.406 5.359-0.5v0.031c-0.047 0.063-0.156 0.984-0.187 1.141-0.203 1.266-0.375 2.547-0.375 3.828 0 0.656 0.094 1.344 0.547 1.828 0.359 0.375 0.844 0.719 1.391 0.719 1.078 0 1.937-1.516 3.922-1.516 1.797 0 2.703 1.516 2.703 3.156z"></path>
</svg></span><span class="game-difficulty" id="game-47-difficulty">Easy</span>

		<div class="game-attributes"><span class="gc-icon icon-game-attributes"><svg class="gc-icon svg gc-icon-tags" aria-labelledby="title-ID" role="img" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="28" viewBox="0 0 30 28">
<title>tags</title>
<path d="M7 7c0-1.109-0.891-2-2-2s-2 0.891-2 2 0.891 2 2 2 2-0.891 2-2zM23.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578s-1.047-0.219-1.406-0.578l-11.172-11.188c-0.797-0.781-1.422-2.297-1.422-3.406v-6.5c0-1.094 0.906-2 2-2h6.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422zM29.672 16c0 0.531-0.219 1.047-0.578 1.406l-7.672 7.688c-0.375 0.359-0.891 0.578-1.422 0.578-0.812 0-1.219-0.375-1.75-0.922l7.344-7.344c0.359-0.359 0.578-0.875 0.578-1.406s-0.219-1.047-0.578-1.422l-11.172-11.156c-0.797-0.797-2.312-1.422-3.422-1.422h3.5c1.109 0 2.625 0.625 3.422 1.422l11.172 11.156c0.359 0.375 0.578 0.891 0.578 1.422z"></path>
</svg></span><span class="game-attributes" id="game-47-attributes"><span class="gc-attribute attribute-dice">Dice Game</span>, <span class="gc-attribute attribute-easy-to-learn">Easy-to-learn</span>, <span class="gc-attribute attribute-fantasy">Fantasy</span>, <span class="gc-attribute attribute-historical">Historical</span></span></div>		</div>


			</div>
			</div>';
	}

	public function test_games_list() {
		$expected = $this->games_list_markup();

		// Test that the wrapper function returns the same thing as the shortcode function.
		$this->assertSame(
			Shortcode\shortcode( [] ),
			gc_get_games(),
			'The shortcode function output didn\'t match the wrapper function output'
		);

		// Test that the wrapper function (which we know now should be identical to the shortcode), matches the expected output.
		$this->assertSame(
			$expected,
			gc_get_games(),
			'Shortcode output didn\'t match the expected output.'
		);
	}
}
