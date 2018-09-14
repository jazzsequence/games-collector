/**
 * Games Collector Editor
 *
 * All the js that is admin-only (e.g. Gutenberg).
 */

// Define the textdomain.
wp.i18n.setLocaleData( { '': {} }, 'games-collector' );

// Load WP.
// import WPAPI from 'wpapi';
// import 'whatwg-fetch';
// import apiFetch from '@wordpress/api-fetch';
// import { addQueryArgs } from '@wordpress/url';

// Load the editor-specific styles.
import '../../sass/editor.scss';

// Load front-end styles.
import '../../sass/style.scss';

// Load the Gutenberg icons.
import icons from './icons';

// Load internal Gutenberg stuff.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
	Spinner,
	TextControl,
} = wp.components;
const { withSelect } = wp.data;

/**
 * Make the first letter of a string uppercase.
 *
 * Mirror's PHP's ucfirst function.
 *
 * @param  {string} string The string to process.
 * @return {string}        The string with the first letter capitalized.
 */
function ucfirst( string ) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

// Register the all games Gutenberg block.
registerBlockType( 'games-collector/add-all-games', {
	title: __( 'All Games', 'games-collector' ),
	description: __( 'Add all games to any post or page.', 'games-collector' ),
	category: 'widgets',
	icon: {
		src: icons.dice
	},
	keywords: [
		__( 'Games Collector', 'games-collector' ),
		__( 'game list', 'games-collector' ),
		__( 'all games', 'games-collector' ),
	],
	edit: withSelect( query => {
			return {
				posts: query( 'core' ).getEntityRecords( 'postType', 'gc_game', { per_page: -1, orderby: 'title', order: 'asc' } )
			};
		} )( ( { posts, className, isSelected } ) => {
			if ( ! posts ) {
				return (
					<p className={ className } >
						<Spinner />
						{ __( 'Loading Posts', 'games-collector' ) }
					</p>
				);
			}
			if ( 0 === posts.length ) {
				return <p>{ __( 'No Posts', 'games-collector' ) }</p>;
			}
			return (
				<div className={ className }>
					{ posts.map( post => {
						let divId       = `game-${ post.id }-info`,
							title       = ( 'undefined' !== typeof post.url ) ? `
								<a href=${ post.url.toString() }><span className="game-title" id="game-${ post.id }-title">${ post.title.rendered }</span></a>` : `<span className="game-title" id="game-${ post.id }-title">${ post.title.rendered }</span>`,
							numPlayers  = {
								id: `game-${ post.id }-num-players`,
								total: (
									'undefined' === typeof post.max_players ||
									post.min_players[0] === post.max_players[0]
								) ? post.min_players[0] : `${ post.min_players[0] } - ${ post.max_players[0] }`,
							},
							playingTime = {
								id: `game-${ post.id }-playing-time`,
								message: `${ post.time } minutes`,
							},
							age         = {
								id: `game-${ post.id }-age`,
								message: `${ post.age }+`,
							},
							difficulty  = {
								id: `game-${ post.id }-difficulty`,
							},
							attributes  = {
								id: `game-${ post.id }-attributes`,
								message: `${ post.attributes.join( ', ' ) }`,
							};

						return (
							<div className={ className }>
								<div dangerouslySetInnerHTML={{ __html: title }} />
								<div className="game-info" id={ divId }>
									<span className="gc-icon icon-game-players">{ icons.players }</span><span className="game-num-players" id={ numPlayers.id }>{ numPlayers.total }</span>
									<span className="gc-icon icon-game-time">{ icons.time }</span><span className="game-playing-time" id={ playingTime.id }>{ playingTime.message }</span>
									<span className="gc-icon icon-game-age">{ icons.age }</span><span className="game-age" id={ age.id }>{ age.message }</span>
									<span className="gc-icon icon-game-difficulty">{ icons.difficulty }</span><span className="game-difficulty" id={ difficulty.id }>{ ucfirst( post.difficulty[0] ) }</span>
									<div className="game-attributes">
										<span className="gc-icon icon-game-attributes">{ icons.tags }</span><span className="game-attributes" id={ attributes.id }>{ attributes.message }</span>
									</div>
								</div>
							</div>
						);
					}) }
				</div>
			);
		} ) // end withSelect
	, // end edit
	save() {
		// Rendering in PHP
		return null;
	},
});
