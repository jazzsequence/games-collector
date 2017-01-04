/**
 * Games Collector isotope handler.
 */
window.GamesCollector = {};
( function( window, $, plugin ) {

	// Constructor.
	plugin.init = function() {
		plugin.cache();

		if ( plugin.meetsRequirements ) {
			plugin.bindEvents();
		}
	};

	// Cache all the things.
	plugin.cache = function() {
		plugin.$c = {
			window: $(window),
			list: $( '.games-collector-list' ),
			buttons: $( '.games-filter-group' ),
			grid: $( '.games-collector-list' ).isotope({
				itemSelector: '.game-single',
				vertical: {
					horizontalAlignment: 0
				}
			}),
		};
	};

	// Combine all events.
	plugin.bindEvents = function() {
		plugin.$c.buttons.on( 'click', 'button', plugin.filter );
	};

	// Do we meet the requirements?
	plugin.meetsRequirements = function() {
		return plugin.$c.list.length;
	};


	plugin.filter = function() {
		let filterValue = $(this).attr( 'data-filter' );
		plugin.$c.grid.isotope({ filter: filterValue });
	}

	// Engage!
	$( plugin.init );

})( window, jQuery, window.GamesCollector );