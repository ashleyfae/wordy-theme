<?php
/**
 * template-tags.php
 *
 * @package   catherine
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

/**
 * Maybe Display Region
 *
 * Include a template part if it's enabled for the current page.
 *
 * @param string $region
 *
 * @since 1.0
 * @return void
 */
function catherine_maybe_display( $region = '' ) {
	$allowed_regions = array(
		'header',
		'featured',
		'footer'
	);

	if ( ! in_array( $region, apply_filters( 'catherine/allowed-regions', $allowed_regions ) ) ) {
		return;
	}

	$view          = catherine_get_current_view();
	$page_template = get_page_template_slug();

	if ( $page_template == 'page-templates/landing.php' ) {
		return;
	}

	$show_region = get_theme_mod( 'show_' . $region . '_' . $view, true );

	if ( $show_region ) {
		get_template_part( 'template-parts/' . $region );
	}
}

/**
 * Maybe Show Sidebar
 *
 * Sidebar is included if it's turned on in the settings panel.
 *
 * @uses  catherine_get_current_view()
 * @uses  catherine_get_sidebar_defaults()
 *
 * @param string $location
 *
 * @since 1.0
 * @return void
 */
function catherine_maybe_show_sidebar( $location = 'right' ) {
	// Get the view.
	$view = catherine_get_current_view();

	// Default sidebar settings.
	$defaults = catherine_get_sidebar_defaults();

	// Get the default for THIS setting.
	$default = array_key_exists( 'sidebar_' . $location . '_' . $view, $defaults ) ? $defaults[ 'sidebar_' . $location . '_' . $view ] : false;

	// Get the option in the Customizer.
	$show_sidebar = get_theme_mod( 'sidebar_' . $location . '_' . $view, $default );

	if ( $show_sidebar ) {
		get_sidebar( $location );
	}
}

/**
 * Post Meta
 *
 * Converts the Customizer template into real, dynamic values.
 *
 * @since 1.0
 * @return void
 */
function catherine_entry_meta( $mod_name = '' ) {
	$defaults = array(
		'meta_config_blog'   => '[category]',
		'meta_config_single' => '[date] &bull; [category] &bull; [comments]'
	);

	$default  = array_key_exists( $mod_name, $defaults ) ? $defaults[ $mod_name ] : '[category]';
	$template = get_theme_mod( $mod_name, $default );
	$find     = array(
		'[date]',
		'[author]',
		'[category]',
		'[comments]'
	);
	$replace  = array(
		'<span class="entry-date">' . get_the_date() . '</span>',
		'<span class="entry-author">' . get_the_author() . '</span>',
		'<span class="entry-category">' . get_the_category_list( ', ' ) . '</span>'
	);

	// It takes some more work to get the comments number...
	$num_comments   = get_comments_number(); // get_comments_number returns only a numeric value
	$write_comments = '';

	if ( comments_open() ) {
		if ( $num_comments == 0 ) {
			$comments = __( 'Leave a Comment', 'catherine' );
		} elseif ( $num_comments > 1 ) {
			$comments = sprintf( __( '%s Comments', 'catherine' ), $num_comments );
		} else {
			$comments = __( '1 Comment', 'catherine' );
		}
		$write_comments = '<a href="' . esc_url( get_comments_link() ) . '" class="entry-comments">' . $comments . '</a>';
	}

	$replace[] = $write_comments;

	do_action( 'catherine/before-post-meta', get_post() );
	?>
	<div class="entry-meta">
		<?php echo str_replace( $find, $replace, $template ); ?>
	</div>
	<?php
	do_action( 'catherine/after-post-meta', get_post() );
}

/**
 * Post Footer
 *
 * Displays the list of tags.
 *
 * @since 1.0
 * @return void
 */
function catherine_entry_footer() {
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
function catherine_get_social_links() {
	$link_array = array();
	$link_type  = get_theme_mod( 'social_link_type', 'square' ) == 'square' ? 'icon-square' : 'icon';

	foreach ( catherine_get_social_sites() as $key => $options ) {
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
function catherine_main_menu() {
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
function catherine_get_copyright() {
	$default = sprintf( __( 'Copyright %s %s. &hearts; All Rights Reserved.', 'catherine' ), date( 'Y' ), get_bloginfo( 'name' ) );
	$message = get_theme_mod( 'copyright_message', $default );

	return apply_filters( 'catherine/get-copyright', '<span id="catherine-copyright">' . $message . '</span>' );
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
function catherine_get_theme_url() {
	$aff_id = get_theme_mod( 'affiliate_id' );
	$url    = 'https://shop.nosegraze.com/product/catherine-theme/';

	if ( empty( $aff_id ) || ! is_numeric( $aff_id ) ) {
		$new_url = $url;
	} else {
		$new_url = add_query_arg( array( 'ref' => intval( $aff_id ) ), $url );
	}

	return apply_filters( 'catherine/theme-link', '<a href="' . esc_url( $new_url ) . '" id="catherine-credit-link" target="_blank" rel="nofollow">' . __( 'Catherine Theme', 'catherine' ) . '</a>' );
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
function catherine_get_post_thumbnail( $width = null, $height = null, $crop = true, $class = 'post-thumbnail', $post = null ) {

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

	return apply_filters( 'catherine/featured-image', $final_html, $width, $height, $crop, $class, $post );

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
function catherine_excerpt( $content = '', $length = 50, $more = ' [...]' ) {
	$excerpt = strip_tags( $content );

	// If the string is already short, just return it.
	if ( strlen( $excerpt ) <= $length ) {
		return $excerpt;
	}

	$excerpt = substr( $excerpt, 0, absint( $length ) );

	return $excerpt . $more;
}