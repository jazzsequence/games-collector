/**
 * Games Collector Editor
 *
 * All the js that is admin-only (e.g. Gutenberg).
 */

// Define the textdomain.
wp.i18n.setLocaleData( { '': {} }, 'games-collector' );

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
                selector: '.game-name-input',
            }
        },
        edit: props => {
            const { attributes: { name }, className, setAttributes } = props;
            return (
                <div className={ className }>
                    <TextControl
                    	label={ __( 'Game', 'games-collector' ) }
                        placeholder={ __( 'The title of the game, e.g. Star Realms', 'games-collector' ) }
                  		onChange={ name => setAttributes( { name } ) }
                  		value={ name }
              		/>
                </div>
            );
        },
        save: props => {
            const { attributes: { message } } = props;
            return (
                <div>
                    <h2>{ __( 'Call to Action', 'games-collector' ) }</h2>
                    <div class="message-body">
                        { message }
                    </div>
                </div>
            );
        },
} );
