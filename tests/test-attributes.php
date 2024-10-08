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
	 */
	public function test_attributes_taxonomy_exists() {
		$this->assertTrue(
			taxonomy_exists( 'gc_attribute' ),
			'The Game Attributes taxonomy does not exist.'
		);
	}

	/**
	 * Test that the default terms were inserted.
	 *
	 * @since 1.0.0
	 */
	public function test_base_terms_exist() {
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

	/**
	 * Test that the attribute list is displaying the expected output.
	 *
	 * @since  1.0.0
	 */
	public function test_attributes_list() {
		// Create the attributes.
		GC\GamesCollector\Attributes\create_default_attributes();

		$post_id = $this->factory->post->create( [ 'post_title' => 'Wizard School' ] );
		wp_set_object_terms( $post_id, [ 'Card Game', 'Fantasy', 'Cooperative' ], 'gc_attribute' );
		$attribute_list = GC\GamesCollector\Attributes\get_the_attribute_list( $post_id );
		$expected_output = '<span class="gc-attribute attribute-card">Card Game</span>, <span class="gc-attribute attribute-coop">Cooperative</span>, <span class="gc-attribute attribute-fantasy">Fantasy</span>';

		$this->assertEquals(
			$attribute_list,
			$expected_output,
			sprintf( 'Attribute list did not match expected output. Expected %1$s saw %2$s', $expected_output, $attribute_list )
		);
	}
}
