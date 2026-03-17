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
			buttons: $( '.games-filter-group' ),
			grid: $( '.games-collector-list' ),
			playersFilter: $('.players-filter-select'),
			difficultyFilter: $('.difficulty-filter-select'),
		};
	};

	/**
	 * Combine all events.
	 */
	plugin.bindEvents = function() {
		plugin.$c.buttons.on( 'click', 'button', plugin.filterCategories );
		plugin.$c.playersFilter.on( 'change', plugin.filterDropdown );
		plugin.$c.difficultyFilter.on( 'change', plugin.filterDropdown );
	};

	/**
	 * Check if we meet the requirements.
	 */
	plugin.meetsRequirements = function() {
		return plugin.$c.grid.length;
	};

	/**
	 * Filter based on category buttons.
	 */
	plugin.filterCategories = function() {
		filterValue = $(this).attr( 'data-filter' );

		plugin.filter( filterValue );
	}

	/**
	 * Filter based on dropdowns.
	 */
	plugin.filterDropdown = function() {
		plugin.filter( this.value );
	}

	/**
	 * Run Isotope to filter the list.
	 */
	plugin.filter = function( filterValue ) {
		plugin.$c.grid.isotope({
			itemSelector: '.game-single',
			vertical: {
				horizontalAlignment: 0
			},
			filter: filterValue
		});
	}

	// Engage!
	$( plugin.init );

})( window, jQuery, window.GamesCollector );
