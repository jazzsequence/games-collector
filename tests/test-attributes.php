<?php
/**
 * Unit tests for attributes namespace.
 *
 * @package GC\GamesCollector
 */

use GC\GamesCollector\Attributes;

/**
 * Games Collector Attributes unit test class.
 *
 * @since 1.0.0
 */
class GC_Test_Attributes extends WP_UnitTestCase {
	/**
	 * Test that the Attribute taxonomy was created.
	 *
	 * @since 1.0.0
	 * @covers GC\GamesCollector\Attributes\register_taxonomy
	 */
	function test_attributes_taxonomy_exists() {
		$this->assertTrue(
			taxonomy_exists( 'gc_attribute' ),
			'The Game Attributes taxonomy does not exist.'
		);
	}

	/**
	 * Test that the default terms were inserted.
	 *
	 * @since 1.0.0
	 * @covers GC\GamesCollector\Attributes\create_default_attributes
	 */
	function test_base_terms_exist() {
		$terms = [ 'Solo Play', 'Cooperative', 'Party Game', 'Easy-to-learn', 'Heavy Strategy', 'Expansion', 'City/Empire Building', 'Fast-paced', 'Card Game', 'Deck Building', 'Dice Game', 'Role-Playing Game', 'Sci-Fi', 'Horror', 'Fantasy', 'Based on a Film/TV Show', 'Mystery', 'Historical', 'Legacy' ];

		// Create the attributes.
		GC\GamesCollector\Attributes\create_default_attributes();

		foreach ( $terms as $term ) {
			$this->assertTrue(
				! is_null( term_exists( $term ) ),
				sprintf( 'Game attribute %s did not exist.', $term )
			);
		}
	}
}
