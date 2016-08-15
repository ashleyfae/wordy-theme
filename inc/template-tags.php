<?php
/**
 * Template Tags
 *
 * Functions used in theme template files.
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

function wordy_entry_meta() {

}

/**
 * Post Footer
 *
 * Displays the list of tags.
 *
 * @since 1.0
 * @return void
 */
function wordy_entry_footer() {
	?>
	<footer class="entry-footer">
		<?php the_tags( '<span class="post-tags"><i class="fa fa-tags"></i> ', ', ', '</span>' ); ?>
	</footer>
	<?php
}

/**
 * Get Social Media Links
 *
 * @since 1.0
 * @return string
 */
function wordy_get_social_links() {
	$link_array = array();
	$link_type  = get_theme_mod( 'social_link_type', 'square' ) == 'square' ? 'icon-square' : 'icon';

	foreach ( wordy_get_social_sites() as $key => $options ) {
		$value = get_theme_mod( $key );

		if ( empty( $value ) ) {
			continue;
		}

		$url = is_email( $value ) ? 'mailto:' . esc_attr( $value ) : esc_url( $value );

		$link_array[] = '<li class="social-site-' . esc_attr( $key ) . '"><a href="' . $url . '" target="_blank"><i class="fa fa-' . esc_attr( $options[ $link_type ] ) . '"></i></a></li>';
	}

	if ( empty( $link_array ) || ! count( $link_array ) ) {
		return '';
	}

	return '<ul>' . implode( '', $link_array ) . '</ul>';
}

/**
 * Main Navigation Menu
 *
 * @since 1.0
 * @return void
 */
function wordy_main_menu() {
	wp_nav_menu( array(
		'theme_location' => 'main',
		'menu_id'        => 'main-menu'
	) );
}

/**
 * Get Copyright Message
 *
 * @since 1.0
 * @return string
 */
function wordy_get_copyright() {
	$default = sprintf( __( 'Copyright %s %s. &hearts; All Rights Reserved.', 'wordy' ), date( 'Y' ), get_bloginfo( 'name' ) );
	$message = get_theme_mod( 'copyright_message', $default );

	return apply_filters( 'wordy/get-copyright', '<span id="wordy-copyright">' . $message . '</span>' );
}

/**
 * Get Theme URL
 *
 * Returns an HTML formatted link to where to purchase the theme.
 * This adds the user's affiliate ID to the URL if entered in the Customizer options.
 *
 * @since 1.0
 * @return string
 */
function wordy_get_theme_url() {
	$aff_id = get_theme_mod( 'affiliate_id' );
	$url    = 'https://novelistplugin.com/download/wordy-theme/';

	if ( empty( $aff_id ) || ! is_numeric( $aff_id ) ) {
		$new_url = $url;
	} else {
		$new_url = add_query_arg( array( 'ref' => intval( $aff_id ) ), $url );
	}

	return apply_filters( 'wordy/theme-link', '<a href="' . esc_url( $new_url ) . '" id="wordy-credit-link" target="_blank" rel="nofollow">' . __( 'Wordy Theme', 'wordy' ) . '</a>' );
}

/**
 * Get Featured Image
 *
 * Retrieves the thumbnail for a given post using the following priorities:
 *
 *      1) Featured image
 *      2) UBB book cover image
 *      3) First image in the post text
 *
 * @param int     $width  Desired width in pixels
 * @param int     $height Desired height in pixels
 * @param bool    $crop   Whether or not to crop to exact dimensions
 * @param string  $class  Class name(s) to add to the image
 * @param WP_Post $post   Post object (if omitted, current global $post is used)
 *
 * @since 1.0
 * @return bool|string False if no image is found
 */
function wordy_get_post_thumbnail( $width = null, $height = null, $crop = true, $class = 'post-thumbnail', $post = null ) {

	if ( empty( $post ) ) {
		$post = get_post();
	}

	$width  = $width ? $width : 520;
	$height = $height ? $height : 400;

	$image_url = '';

	// Pre-emptively check to see if an UBB book cover exists.
	$ubb_book_cover = get_post_meta( $post->ID, '_ubb_book_image', true );

	// Pre-emptively get the featured image.
	$featured = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

	// Now let's try to get an image URL!
	if ( has_post_thumbnail( $post->ID ) && ! empty( $featured ) ) {
		$image_url = $featured[0];
	} elseif ( ! empty( $ubb_book_cover ) ) {
		$image_url = $ubb_book_cover;
	} elseif ( preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post->post_content, $matches ) ) {
		$image_url = $matches[1][0];
	}

	// If we don't have an image, bail.
	if ( empty( $image_url ) ) {
		return false;
	}

	// Now let's resize the image, woot woot.
	$resized_image = false;

	// If Photon is activated, we'll try to use that first.
	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
		$args = array(
			'resize' => array( $width, $height )
		);

		$resized_image = jetpack_photon_url( $image_url, $args );
	} elseif ( function_exists( 'aq_resize' ) ) {
		// Otherwise, we'll use aq_resizer.
		$resized_image = aq_resize( $image_url, $width, $height, $crop, true, true );
	}

	$final_image = $resized_image ? $resized_image : $image_url;

	$final_html = '<a href="' . esc_url( get_permalink( $post ) ) . '" title="' . esc_attr( strip_tags( get_the_title( $post ) ) ) . '"><img src="' . esc_url( $final_image ) . '" alt="' . esc_attr( strip_tags( get_the_title( $post ) ) ) . '" class="' . esc_attr( $class ) . '" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '"></a>';

	return apply_filters( 'wordy/featured-image', $final_html, $width, $height, $crop, $class, $post );

}

/**
 * Excerpt
 *
 * Trims a given string down to a certain length.
 *
 * @param string $content String to trim down
 * @param int    $length  Desired length in characters
 * @param string $more    Text to append to the excerpt
 *
 * @since 1.0
 * @return string
 */
function wordy_excerpt( $content = '', $length = 50, $more = ' [...]' ) {
	$excerpt = strip_tags( $content );

	// If the string is already short, just return it.
	if ( strlen( $excerpt ) <= $length ) {
		return $excerpt;
	}

	$excerpt = substr( $excerpt, 0, absint( $length ) );

	return $excerpt . $more;
}