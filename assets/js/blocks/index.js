/**
 * Games Collector Editor
 *
 * All the js that is admin-only (e.g. Gutenberg).
 */

// Define the textdomain.
wp.i18n.setLocaleData( { '': {} }, 'games-collector' );

// Load WP.
import WPAPI from 'wpapi';
import 'whatwg-fetch';

// Load the editor styles.
import '../../sass/editor.scss';

// Load the Gutenberg icon.
import icon from './icon';

// Load internal Gutenberg stuff.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
	Spinner,
	TextControl,
} = wp.components;
const { withSelect } = wp.data;
const apiUrl = 'http://vagrant.local/wp-json';
let wpapi = new WPAPI({endpoint: apiUrl});
wpapi.games = wpapi.registerRoute( 'wp/v2', '/games', {
	params: [ 'search' ]
} );

registerBlockType( 'games-collector/add-all-games', {
	title: __( 'All Games', 'games-collector' ),
	description: __( 'Add all games to any post or page.', 'games-collector' ),
	category: 'widgets',
	icon: {
		src: icon
	},
	keywords: [
		__( 'Games Collector', 'games-collector' ),
		__( 'game list', 'games-collector' ),
		__( 'all games', 'games-collector' ),
	],
	save: props => {
		return;
	}
});

registerBlockType( 'games-collector/add-single-game', {
		title: __( 'Single Game', 'games-collector' ),
		description: __( 'Add a single game to a post or page.', 'games-collector' ),
		category: 'widgets',
		icon: {
			src: icon
		},
		keywords: [
			__( 'Games Collector', 'games-collector' ),
			__( 'single game', 'games-collector' ),
			__( 'add game', 'games-collector' ),
		],
        attributes: {
            gameTitle: {
                type: 'string',
                source: 'children',
                selector: '.game-name-input input',
            }
        },
        edit: props => {
            const { attributes: { gameTitle }, className, setAttributes } = props;
            const getGame = function( gameTitle ) {
            	gameTitle = setAttributes( { gameTitle } );
            	console.log(gameTitle);
            	// let url = apiUrl + '/wp/v2/games?search=' + gameTitle;
            	// console.log(url);
            	// console.log(fetch(url)
            	// 	.then(response => {
            	// 		return response;
            	// 	}));
            	// console.log( wpapi.games.param('search',gameTitle) );
            	return gameTitle;
            }
            return (
                <div className={ className }>
                    <TextControl
                    	className="game-name-input"
                    	label={ __( 'Game', 'games-collector' ) }
                        placeholder={ __( 'The title of the game, e.g. Star Realms', 'games-collector' ) }
                  		onChange={ name => setAttributes( { name } ), getGame(name) }
                  		value={ gameTitle }
              		/>
                </div>
            );
        },
        save: props => {
            const { attributes: { name } } = props;
            return;
        },
} );
