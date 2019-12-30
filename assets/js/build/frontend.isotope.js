/**
 * Games Collector Isotope handler.
 */
window.GamesCollector = {};
( function( window, $, plugin ) {

	/**
	 * Constructor
	 */
	plugin.init = function() {
		if ( plugin.meetsRequirements ) {
			plugin.cache();
			plugin.bindEvents();
		}
	};

	/**
	 * Cache all the things.
	 */
	plugin.cache = function() {
		plugin.$c = {
			window: $(window),
			list: $( '.games-collector-list' ),
			buttons: $( '.games-filter-group' ),
			grid: new Isotope( '.games-collector-list', {
				itemSelector: '.game-single',
				vertical: {
					horizontalAlignment: 0
				}
			}),
			playersFilter: $('.players-filter-select'),
			difficultyFilter: $('.difficulty-filter-select'),
		};
	};

	/**
	 * Combine all events.
	 */
	plugin.bindEvents = function() {
		plugin.$c.buttons.on( 'click', 'button', plugin.filter );
		plugin.$c.playersFilter.on( 'change', plugin.filterDropdown );
		plugin.$c.difficultyFilter.on( 'change', plugin.filterDropdown );
	};

	/**
	 * Check if we meet the requirements.
	 */
	plugin.meetsRequirements = function() {
		return plugin.$c.list.length;
	};

	/**
	 * Filter based on category buttons.
	 */
	}

	/**
	 * Filter based on dropdowns.
	 */
	plugin.filterDropdown = function() {
		// get filter value from option value
		var filterValue = this.value;
		plugin.$c.grid.isotope({ filter: filterValue });
	}

	// Engage!
	$( plugin.init );

})( window, jQuery, window.GamesCollector );