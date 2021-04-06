<?php
/**
 * Novelist Plugin Integration
 *
 * Functions that hook into Novelist.
 *
 * @package   wordy
 * @copyright Copyright (c) 2021, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

if ( ! class_exists( 'Novelist' ) ) {
	return;
}

/**
 * Customizer: Static Front Page
 *
 * @param WP_Customize_Manager $wp_customize
 *
 * @since 1.0
 * @return void
 */
function wordy_novelist_customizer_static_front_page( $wp_customize ) {

	/* Book Section */
	$wp_customize->add_section( 'novelist_books', array(
		'title'    => esc_html__( 'Book Pages', 'wordy' ),
		'priority' => 113
	) );

	/* Show Sidebar on Book */
	$wp_customize->add_setting( 'show_sidebar_single_book', array(
		'default'           => false,
		'sanitize_callback' => 'wordy_sanitize_checkbox'
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'show_sidebar_single_book', array(
		'label'    => esc_html__( 'Show sidebar on single book pages', 'wordy' ),
		'type'     => 'checkbox',
		'section'  => 'novelist_books',
		'settings' => 'show_sidebar_single_book',
		'priority' => 10
	) ) );

	/* Featured Book */
	if ( function_exists( 'novelist_get_latest_book_id' ) ) {
		$latest_book = novelist_get_latest_book_id();
		$books       = novelist_get_books();
		$books_array = array( 0 => esc_html__( 'None', 'wordy' ) ) + $books;
		$wp_customize->add_setting( 'homepage_featured_book', array(
			'default'           => $latest_book ? $latest_book : 0,
			'sanitize_callback' => 'absint',
			'transport'         => $wp_customize->selective_refresh ? 'postMessage' : 'refresh'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'homepage_featured_book', array(
			'label'       => esc_html__( 'Featured Book', 'wordy' ),
			'description' => esc_html__( 'Choose a book to feature on your homepage.', 'wordy' ),
			'type'        => 'select',
			'choices'     => $books_array,
			'section'     => 'static_front_page',
			'settings'    => 'homepage_featured_book',
			'priority'    => 50
		) ) );
	}

}

add_action( 'wordy/customizer/register-sections', 'wordy_novelist_customizer_static_front_page' );

/**
 * Display Featured Book
 *
 * @since 1.0
 * @return void
 */
function wordy_featured_book() {
	$featured_book_id = get_theme_mod( 'homepage_featured_book', novelist_get_latest_book_id() );

	if ( ! $featured_book_id || ! is_numeric( $featured_book_id ) ) {
		return;
	}

	$book           = new Novelist_Book( $featured_book_id );
	$cover          = $book->get_cover_image( 'large' );
	$synopsis       = $book->get_synopsis();
	$purchase_links = $book->get_purchase_links();

	if ( $cover ) : ?>
		<div id="featured-book-cover">
			<?php echo $cover; ?>
			<a href="<?php echo esc_url( get_permalink( $featured_book_id ) ); ?>" class="button button-block"><?php _e( 'More Details &raquo;', 'wordy' ); ?></a>
		</div>
	<?php endif; ?>

	<?php if ( $synopsis ) : ?>
		<blockquote id="featured-book-synopsis">
			<?php echo wpautop( $synopsis ); ?>
		</blockquote>
	<?php endif; ?>

	<?php if ( $purchase_links ) : ?>
		<div id="featured-book-links">
			<?php wordy_novelist_purchase_links( $book ); ?>
		</div>
	<?php endif;
}

/**
 * Display Purchase Links
 *
 * @param Novelist_Book|int $book              Novelist book object or post ID.
 * @param bool              $include_goodreads Whether or not to include Goodreads.
 *
 * @since 1.0
 * @return void
 */
function wordy_novelist_purchase_links( $book, $include_goodreads = true ) {
	if ( is_numeric( $book ) ) {
		$book = new Novelist_Book( $book );
	}

	$purchase_links = $book->get_purchase_links();

	if ( ! is_array( $purchase_links ) ) {
		return;
	}

	$saved_links = novelist_get_option( 'purchase_links', false );
	$goodreads   = $book->get_goodreads_link();

	?>
	<ul class="wordy-novelist-purchase-links">
		<?php if ( $include_goodreads && $goodreads ) : ?>
			<li class="wordy-novelist-goodreads">
				<a href="<?php echo esc_url( $goodreads ); ?>" target="_blank" class="button button-block"><?php _e( 'Goodreads', 'wordy' ); ?></a>
			</li>
		<?php endif; ?>

		<?php foreach ( $saved_links as $link_info ) :
			$sanitized_key = esc_attr( sanitize_title( $link_info['name'] ) );
			$url = array_key_exists( $sanitized_key, $purchase_links ) ? $purchase_links[ $sanitized_key ] : '';

			if ( empty( $url ) ) {
				continue;
			}
			?>
			<li>
				<a href="<?php echo esc_url( $url ); ?>" target="_blank" class="button button-block"><?php echo $link_info['name']; ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Modify the size of 3D book covers on the homepage for the featured book.
 * We need to ensure the width doesn't exceed 300px.
 *
 * @param string $size    Chosen size.
 * @param int    $book_id ID of the book being displayed.
 *
 * @since 1.0
 * @return array
 */
function wordy_novelist_3d_cover_size( $size, $book_id ) {
	if ( ! is_front_page() ) {
		return $size;
	}

	if ( 'posts' == get_option( 'show_on_front' ) ) {
		return $size;
	}

	return array( 300, 450 );
}

add_filter( 'novelist-3d-book-covers/render/image-size', 'wordy_novelist_3d_cover_size', 10, 2 );

/**
 * Always use 3D covers on the homepage.
 *
 * This overrides the 3D Book Covers settings to always use the 3D cover on the
 * homepage for the featured book.
 *
 * @param bool $show_3d
 *
 * @since 1.0
 * @return bool
 */
function wordy_novelist_3d_cover_on_homepage( $show_3d ) {
	if ( is_front_page() ) {
		return true;
	}

	return $show_3d;
}

add_filter( 'novelist-3d-book-covers/display-3d-cover', 'wordy_novelist_3d_cover_on_homepage' );
