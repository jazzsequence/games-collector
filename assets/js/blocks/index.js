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

// Load the editor styles.
import '../../sass/editor.scss';

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
// const apiUrl = 'http://vagrant.local/wp-json';
// let wpapi = new WPAPI({endpoint: apiUrl});
// wpapi.games = wpapi.registerRoute( 'wp/v2', '/games', {
// 	params: [ 'search' ]
// } );

function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

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
                    	console.log(post);
						let divId       = `game-${ post.id }-info`,
							title       = {
								id: `game-${ post.id }-title`,
							},
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
                                <a href={ post.link }>
                                    <span className="game-title" id={ title.id }>{ post.title.rendered }</span>
                                </a>
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
        } ) // end withAPIData
    , // end edit
    save() {
        // Rendering in PHP
        return null;
    },
});


// registerBlockType( 'games-collector/add-single-game', {
// 		title: __( 'Single Game', 'games-collector' ),
// 		description: __( 'Add a single game to a post or page.', 'games-collector' ),
// 		category: 'widgets',
// 		icon: {
// 			src: icon
// 		},
// 		keywords: [
// 			__( 'Games Collector', 'games-collector' ),
// 			__( 'single game', 'games-collector' ),
// 			__( 'add game', 'games-collector' ),
// 		],
//         attributes: {
//             gameTitle: {
//                 type: 'string',
//                 source: 'children',
//                 selector: '.game-name-input input',
//             }
//         },
//         edit: props => {
//             const { attributes: { gameTitle }, className, setAttributes } = props;
// 			const DEFAULT_QUERY = {
// 				per_page: -1,
// 				orderby: 'count',
// 				order: 'desc',
// 				_fields: 'id,name',
// 			}
//             const getGame = function( gameTitle ) {
//             	// Set the gameTitle to be the updated title that was entered into the form.
//             	gameTitle = setAttributes( { gameTitle } );

//             	// Create a new title from the updated gameTitle.
//             	let title = props.attributes.gameTitle;

//             	// This logs the game title as it's updated.
//             	console.log(props.attributes.gameTitle);
//             	console.log(apiFetch({
//             		path: addQueryArgs( '/wp/v2/games/', { ...DEFAULT_QUERY, search: title })
//             	}));
//             	// This logs the URL that is going to be hit.
//             	// console.log(wpapi.games().search(title).toString());
//             	// This logs the response against a search for that game.
//             	// console.log( wpapi.games().search(title).get());
//             	// let gamesResult = wpapi.games().search(title).get();
//             	// gamesResult.then(function(result) {
//             	// 	console.log(result);
//             	// 	console.log(result.length);
//             	// 	wp.render(renderGameHtml(result),autosuggestGames);
//             	// });

//             	// console.log(gamesResult.length);
//             	// Return the gameTitle that was set.
//             	return gameTitle;
//             }
//             return (
//                 <div className={ className }>
//                     <TextControl
//                     	className="game-name-input"
//                     	label={ __( 'Game', 'games-collector' ) }
//                         placeholder={ __( 'The title of the game, e.g. Star Realms', 'games-collector' ) }
//                   		onChange={ gameTitle => getGame( gameTitle ) }
//                   		value={ gameTitle }
//               		/>
//               		<ul ref="autosuggestGames" />
//                 </div>
//             );
//         },
//         save: props => {
//             const { gameTitle } = props.attributes;
//             return gameTitle;
//         },
// } );

// function renderGameHtml( game ) {
// 	let liClass = 'game-suggestion game-' + game.id;
// 	return { __html:<li className="liClass">
// 		game.title
// 	</li>};
// }
