<?php
/**
 * Games Collector Game Attributes Taxonomy
 *
 * @package GC\GamesCollector\Attributes
 * @since   0.1
 */

namespace GC\GamesCollector\Attributes;

/**
 * Register the taxonomies.
 *
 * @since 0.1
 */
function register_taxonomy() {
	register_extended_taxonomy( 
		'gc_attribute', 
		'gc_game', 
		[
			'dashboard_glance' => true,   // Show this taxonomy in the 'At a Glance' widget.
			'hierarchical'     => false,
			'show_in_rest'     => true,
			'rest_base'        => 'attributes',
		], 
		[
			'singular'      => __( 'Game Attribute', 'games-collector' ),
			'plural'        => __( 'Game Attributes', 'games-collector' ),
			'slug'          => 'attribute',
		] 
	);
}

/**
 * Remove the default taxonomy metabox and add a new one using Chosen.
 *
 * @since 0.1
 */
function metabox() {
	remove_meta_box( 'tagsdiv-gc_attribute', 'gc_game', 'side' );
	add_meta_box( 'game-attributes-chosen', __( 'Game Attributes', 'games-collector' ), __NAMESPACE__ . '\\meta_box_display', 'gc_game', 'side', 'default' );
}

/**
 * Enqueue the Chosen CSS and JS.
 *
 * @since 0.1
 */
function enqueue_scripts() {
	$screen = get_current_screen();

	if ( 'post' !== $screen->base || 'gc_game' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_script( 'chosen', plugin_dir_url( dirname( __DIR__ ) ) . 'vendor/harvesthq/chosen/chosen.jquery.min.js', [ 'jquery' ], '1.8', true );
	wp_enqueue_style( 'chosen', plugin_dir_url( dirname( __DIR__ ) ) . 'vendor/harvesthq/chosen/chosen.min.css', [], '1.8' );
}

/**
 * Display the metabox.
 *
 * @since 0.1
 * @link  https://helen.wordpress.com/2012/01/08/using-chosen-for-a-replacement-taxonomy-metabox/
 * @link  https://gist.github.com/helen/1573966#file-wp-chosen-tax-metabox-php
 */
function meta_box_display() {
	wp_nonce_field( 'chosen-save-tax-terms', 'chosen_taxonomy_meta_box_nonce' );
	$tax = 'gc_attribute';
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		$( '.chzn-select' ).chosen();
	});
	</script>
	<?php
	if ( current_user_can( 'edit_posts' ) ) {

		$terms = get_terms( $tax );
		$current_terms = wp_get_post_terms( get_the_ID(), $tax, [ 'fields' => 'ids' ] );
		$name = "tax_input[$tax]";
		?>
		<p><select name="<?php echo esc_attr( $name ); ?>[]" class="chzn-select widefat" data-placeholder="<?php esc_attr_e( 'Select one or more attributes', 'games-collector' ); ?>" multiple="multiple">
		<?php
		foreach ( $terms as $term ) {
			$value = $term->slug;
			?>
			<option value="<?php echo esc_attr( $value ); // phpcs:ignore WordPressVIPMinimum.Security.ProperEscapingFunction.notAttrEscAttr ?>"<?php selected( in_array( $term->term_id, $current_terms ) ); ?>><?php echo esc_attr( $term->name ); ?></option>
		<?php } ?>
		</select>
		</p>
		<?php
	}
}

/**
 * Create some default game attributes.
 *
 * @since 0.1
 */
function create_default_attributes() {
	if ( ! term_exists( 'Solo Play', 'gc_attribute' ) ) {
		wp_insert_term( 'Solo Play', 'gc_attribute', [ 'slug' => 'solo' ] );
	}

	if ( ! term_exists( 'Cooperative', 'gc_attribute' ) ) {
		wp_insert_term( 'Cooperative', 'gc_attribute', [ 'slug' => 'coop' ] );
	}

	if ( ! term_exists( 'Party Game', 'gc_attribute' ) ) {
		wp_insert_term( 'Party Game', 'gc_attribute', [ 'slug' => 'party' ] );
	}

	if ( ! term_exists( 'Easy-to-learn', 'gc_attribute' ) ) {
		wp_insert_term( 'Easy-to-learn', 'gc_attribute', [ 'slug' => 'easy-to-learn' ] );
	}

	if ( ! term_exists( 'Heavy Strategy', 'gc_attribute' ) ) {
		wp_insert_term( 'Heavy Strategy', 'gc_attribute', [ 'slug' => 'strategy' ] );
	}

	if ( ! term_exists( 'Expansion', 'gc_attribute' ) ) {
		wp_insert_term( 'Expansion', 'gc_attribute', [ 'slug' => 'expansion' ] );
	}

	if ( ! term_exists( 'City/Empire Building', 'gc_attribute' ) ) {
		wp_insert_term( 'City/Empire Building', 'gc_attribute', [ 'slug' => 'city-building' ] );
	}

	if ( ! term_exists( 'Fast-paced', 'gc_attribute' ) ) {
		wp_insert_term( 'Fast-paced', 'gc_attribute', [ 'slug' => 'fast-paced' ] );
	}

	if ( ! term_exists( 'Card Game', 'gc_attribute' ) ) {
		wp_insert_term( 'Card Game', 'gc_attribute', [ 'slug' => 'card' ] );
	}

	if ( ! term_exists( 'Deck Building', 'gc_attribute' ) ) {
		wp_insert_term( 'Deck Building', 'gc_attribute', [ 'slug' => 'deck-building' ] );
	}

	if ( ! term_exists( 'Dice Game', 'gc_attribute' ) ) {
		wp_insert_term( 'Dice Game', 'gc_attribute', [ 'slug' => 'dice' ] );
	}

	if ( ! term_exists( 'Role-Playing Game', 'gc_attribute' ) ) {
		wp_insert_term( 'Role-Playing Game', 'gc_attribute', [ 'slug' => 'rpg' ] );
	}

	if ( ! term_exists( 'Sci-Fi', 'gc_attribute' ) || ! term_exists( 'Science Fiction', 'gc_attribute' ) ) {
		wp_insert_term( 'Sci-Fi', 'gc_attribute', [ 'slug' => 'sci-fi' ] );
	}

	if ( ! term_exists( 'Horror', 'gc_attribute' ) ) {
		wp_insert_term( 'Horror', 'gc_attribute', [ 'slug' => 'horror' ] );
	}

	if ( ! term_exists( 'Fantasy', 'gc_attribute' ) ) {
		wp_insert_term( 'Fantasy', 'gc_attribute', [ 'slug' => 'fantasy' ] );
	}

	if ( ! term_exists( 'Based on a Film/TV Show', 'gc_attribute' ) ) {
		wp_insert_term( 'Based on a Film/TV Show', 'gc_attribute', [ 'slug' => 'based-on-film-tv' ] );
	}

	if ( ! term_exists( 'Mystery', 'gc_attribute' ) ) {
		wp_insert_term( 'Mystery', 'gc_attribute', [ 'slug' => 'mystery' ] );
	}

	if ( ! term_exists( 'Historical', 'gc_attribute' ) ) {
		wp_insert_term( 'Historical', 'gc_attribute', [ 'slug' => 'historical' ] );
	}

	if ( ! term_exists( 'Legacy', 'gc_attribute' ) ) {
		wp_insert_term( 'Legacy', 'gc_attribute', [ 'slug' => 'legacy' ] );
	}
}

/**
 * When saving the post, check to see if the taxonomy has been emptied out.
 * If so, it will not exist in the tax_input array and thus WP won't be aware of it, so we have to take of emptying the terms for the object.
 *
 * @since 0.1
 * @link  https://helen.wordpress.com/2012/01/08/using-chosen-for-a-replacement-taxonomy-metabox/
 * @link  https://gist.github.com/helen/1573966#file-wp-chosen-tax-metabox-php
 * 
 * @param int $post_id The post ID.
 */
function save_post( $post_id ) {
	// Verify nonce.
	if ( ! isset( $_POST['chosen_taxonomy_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['chosen_taxonomy_meta_box_nonce'], 'chosen-save-tax-terms' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		return;
	}
	// Check autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$input = isset( $_POST['tax_input']['gc_attribute'] ) ? $_POST['tax_input']['gc_attribute'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( empty( $input ) ) {
		$taxonomy = get_taxonomy( 'gc_attribute' );
		if ( $taxonomy && current_user_can( $taxonomy->cap->assign_terms ) ) {
			wp_set_object_terms( $post_id, '', 'gc_attribute' );
		}
	}
}

/**
 * Get a list of attributes for the given post. Use instead of get_term_list.
 *
 * @since  1.0.0
 * @param  integer $post_id   The post ID. If none is given, will attempt to grab one from the WP_Post object.
 * @param  string  $before    Anything before the list of attributes.
 * @param  string  $seperator Seperator between attributes (default is ", ").
 * @param  string  $after     Anything after the list of attributes.
 * @return string             The sanitized list of attributes.
 */
function get_the_attribute_list( $post_id = 0, $before = '', $seperator = ', ', $after = '' ) {
	if ( 0 === $post_id ) {
		global $post;
		if ( isset( $post->ID ) ) {
			$post_id = $post->ID;
		} else {
			return new WP_Error( 'cannot_get_attribute', esc_html__( 'No post ID given for game. Cannot get attributes.', 'games-collector' ) );
		}
	}

	$terms      = '';
	$attributes = get_the_terms( $post_id, 'gc_attribute' );
	if ( ! is_wp_error( $attributes ) ) {
		$count    = count( $attributes );
		$iterator = 1;
		foreach ( $attributes as $term ) {
			$seperator = ( $iterator < $count ) ? $seperator : '';
			$terms .= '<span class="gc-attribute attribute-' . $term->slug . '">' . $term->name . '</span>' . $seperator;
			$iterator++;
		}
	}

	// Allow the terms to be filtered.
	$terms = apply_filters( 'gc_filter_the_terms', $terms );

	return apply_filters( 'gc_filter_the_attribute_list', gc_kses( $before ) . $terms . gc_kses( $after ) );
}

/**
 * Internal wp_kses which allows SVG tags.
 *
 * @since  1.1.0
 * @param  string $string The string to be sanitized.
 * @return string         The sanitized string.
 */
function gc_kses( $string = '' ) {
	$allowed_html = array_merge( wp_kses_allowed_html( 'post' ), [
		'svg'   => [
			'class'           => [],
			'aria-labelledby' => [],
			'role'            => [],
			'version'         => [],
			'xmlns'           => [],
			'xmlns:xlink'     => [],
			'height'          => [],
			'width'           => [],
			'viewbox'         => [],
		],
		'title' => [],
		'path'  => [
			'd'               => [],
		],
	] );

	return wp_kses( $string, $allowed_html );
}
