/**
 * Games Collector Editor
 *
 * All the js that is admin-only (e.g. Gutenberg).
 */

// Define the textdomain.
wp.i18n.setLocaleData( { '': {} }, 'games-collector' );

// Load fetch.
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
        attributes: {
            name: {
                type: 'string',
                source: 'children',
                selector: '.game-name-input input',
            }
        },
        edit: props => {
            const { attributes: { name }, className, setAttributes } = props;
            const apiUrl = 'http://vagrant.local/wp-json/wp/v2/games?search=';
            return (
                <div className={ className }>
                    <TextControl
                    	className="game-name-input"
                    	label={ __( 'Game', 'games-collector' ) }
                        placeholder={ __( 'The title of the game, e.g. Star Realms', 'games-collector' ) }
                  		onChange={ name => setAttributes( { name } ) }
                  		value={ name }
              		/>
                </div>
            );
        },
        save: props => {
            const { attributes: { name } } = props;
            return;
        },
} );
