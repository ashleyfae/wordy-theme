<?php
/**
 * Extra Functions
 *
 * Mostly actions and filters that act independently of template files.
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

function wordy_body_classes( $classes ) {
	if ( is_post_type_archive( array( 'book' ) ) ) {
		return $classes;
	}

	if ( is_tax( array( 'novelist-genre', 'novelist-series' ) ) ) {
		return $classes;
	}

	if ( is_page_template( 'page-templates/full-width.php' ) ) {
		return $classes;
	}

	if ( is_singular() || is_home() || is_archive() ) {
		$classes[] = 'has-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'wordy_body_classes' );

/**
 * Whether or not to cache CSS, fonts, etc.
 *
 * @since 1.0
 * @return bool
 */
function wordy_cache() {
	$cache = false;

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG == true ) {
		$cache = false;
	}

	return apply_filters( 'wordy/cache', $cache );
}

/**
 * Get Current View
 *
 * @since 1.0
 * @return string
 */
function wordy_get_current_view() {
	$view = '';

	if ( is_post_type_archive( 'book' ) || is_tax( array( 'novelist-genre', 'novelist-series' ) ) ) {
		$view = 'book_archive';
	} elseif ( is_singular( 'book' ) ) {
		$view = 'book_single';
	} elseif ( is_home() || is_archive() || is_search() ) {
		$view = 'blog';
	} elseif ( is_page() ) {
		$view = 'page';
	} elseif ( is_singular() ) {
		$view = 'single';
	}

	return apply_filters( 'wordy/get-current-view', $view );
}

/**
 * Custom CSS
 *
 * Generates custom CSS based on Customizer settings. This CSS gets merged into
 * our main theme stylesheet.
 *
 * @since 1.0
 * @return string
 */
function wordy_custom_css() {
	if ( ! is_customize_preview() && wordy_cache() ) {
		$saved_css = get_transient( 'wordy_customizer_css' );

		if ( $saved_css ) {
			return apply_filters( 'wordy/custom-css', $saved_css );
		}
	}

	$css = '';

	// Font family.
	$font_style = get_theme_mod( 'typography_style', 'serif' );
	if ( $font_style == 'sans-serif' || $font_style == 'slab-serif' ) {
		if ( $font_style == 'sans-serif' ) {
			$family = "'Open Sans', sans-serif";
		} else {
			$family = "'Roboto Slab', serif";
		}

		$css .= 'body { font-family: ' . $family . '; }';
	}

	// Primary colour.
	$primary_colour = get_theme_mod( 'primary_colour' );
	if ( $primary_colour ) {
		$css .= '#header, #footer, .button, button, input[type="submit"], .pagination .current { background-color: ' . esc_html( $primary_colour ) . '; }';
		$css .= '.button:hover, button:hover, input[type="submit"]:hover { background: ' . esc_html( wordy_adjust_brightness( $primary_colour, - 30 ) ) . ' }';
		$css .= '#footer { color: ' . esc_html( wordy_adjust_brightness( $primary_colour, 100 ) ) . ' }';

		$css .= '@media (min-width: 768px) {';
		$css .= '.main-navigation .sub-menu { background: ' . esc_html( $primary_colour ) . ' }';
		$css .= '.main-navigation .sub-menu a:hover { background: ' . esc_html( wordy_adjust_brightness( $primary_colour, 20 ) ) . ' }';
		$css .= '}';
	}

	// CTA Box Backgrounds
	foreach ( range( 1, 3 ) as $number ) {
		$bg_colour = get_theme_mod( 'cta_colour_' . $number, false );
		$image     = get_theme_mod( 'cta_image_' . $number, false );

		if ( empty( $image ) && empty( $bg_colour ) ) {
			continue;
		}

		$css .= '#cta-box-' . absint( $number ) . ' { ';

		if ( $bg_colour ) {
			$css .= 'background-color: ' . esc_html( $bg_colour ) . ';';
		}
		if ( $image ) {
			$css .= 'background-image: url(' . esc_url( $image ) . ');';
		}

		$css .= '}';
	}

	// Cache this.
	set_transient( 'wordy_customizer_css', $css );

	return apply_filters( 'wordy/custom-css', $css );
}

/**
 * Convert Hex Colour to RGBA Format
 *
 * @param string     $color   Colour in hex format
 * @param bool|float $opacity Opacity (optional)
 *
 * @since 1.0
 * @return string
 */
function wordy_hex_to_rgba( $color, $opacity = false ) {
	$default = 'rgb(0,0,0)';

	// Return default if no color provided
	if ( empty( $color ) ) {
		return $default;
	}

	// Sanitize $color if "#" is provided
	if ( $color[0] == '#' ) {
		$color = substr( $color, 1 );
	}

	// Check if color has 6 or 3 characters and get values
	if ( strlen( $color ) == 6 ) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) == 3 ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $default;
	}

	// Convert hexadec to rgb
	$rgb = array_map( 'hexdec', $hex );

	// Check if opacity is set(rgba or rgb)
	if ( $opacity ) {
		if ( abs( $opacity ) > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode( ",", $rgb ) . ')';
	}

	// Return rgb(a) color string
	return $output;
}

/**
 * Adjust Colour Brightness
 *
 * @param string $hex   Base hex colour
 * @param int    $steps Number between -255 (darker) and 255 (lighter)
 *
 * @since 1.0.4
 * @return string
 */
function wordy_adjust_brightness( $hex, $steps ) {
	$steps = max( - 255, min( 255, $steps ) );

	// Normalize into a six character long hex string
	$hex = str_replace( '#', '', $hex );
	if ( strlen( $hex ) == 3 ) {
		$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	}

	// Split into three parts: R, G and B
	$color_parts = str_split( $hex, 2 );
	$return      = '#';

	foreach ( $color_parts as $color ) {
		$color = hexdec( $color ); // Convert to decimal
		$color = max( 0, min( 255, $color + $steps ) ); // Adjust color
		$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code
	}

	return $return;
}

/**
 * Get Social Sites
 *
 * Returns an array of all the available social media sites. Contains the
 * site name and two icon possibilities (regular / square).
 *
 * @since 1.0
 * @return array
 */
function wordy_get_social_sites() {
	$sites = array(
		'twitter'   => array(
			'name'        => esc_html__( 'Twitter', 'wordy' ),
			'icon'        => 'twitter',
			'icon-square' => 'twitter-square'
		),
		'facebook'  => array(
			'name'        => esc_html__( 'Facebook', 'wordy' ),
			'icon'        => 'facebook',
			'icon-square' => 'facebook-square'
		),
		'instagram' => array(
			'name'        => esc_html__( 'Instagram', 'wordy' ),
			'icon'        => 'instagram',
			'icon-square' => 'instagram'
		),
		'pinterest' => array(
			'name'        => esc_html__( 'Pinterest', 'wordy' ),
			'icon'        => 'pinterest',
			'icon-square' => 'pinterest-square'
		),
		'google'    => array(
			'name'        => esc_html__( 'Google+', 'wordy' ),
			'icon'        => 'google-plus',
			'icon-square' => 'google-plus-square'
		),
		'youtube'   => array(
			'name'        => esc_html__( 'YouTube', 'wordy' ),
			'icon'        => 'youtube-play',
			'icon-square' => 'youtube-square'
		),
		'linkedin'  => array(
			'name'        => esc_html__( 'LinkedIn', 'wordy' ),
			'icon'        => 'linkedin',
			'icon-square' => 'linkedin-square'
		),
		'spotify'   => array(
			'name'        => esc_html__( 'Spotify', 'wordy' ),
			'icon'        => 'spotify',
			'icon-square' => 'spotify'
		),
		'rss'       => array(
			'name'        => esc_html__( 'RSS', 'wordy' ),
			'icon'        => 'rss',
			'icon-square' => 'rss-square'
		),
		'email'     => array(
			'name'        => esc_html__( 'Email', 'wordy' ),
			'icon'        => 'envelope',
			'icon-square' => 'envelope-square'
		)
	);

	return apply_filters( 'wordy/get-social-sites', $sites );
}

/**
 * Compile Social Media Links
 *
 * Generates the HTML for the social media links.
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

		$link_array[] = '<a href="' . $url . '" class="social-site-' . esc_attr( $key ) . '" target="_blank"><i class="fa fa-' . esc_attr( $options[ $link_type ] ) . '"></i></a>';
	}

	return implode( "\n", $link_array );
}

/**
 * Append Social Media Links to the Navigation
 *
 * @uses  wordy_get_social_links()
 *
 * @param string $items Compiled menu `<li>` tags.
 * @param array  $args  Menu arguments.
 *
 * @since 1.0
 * @return string New menu with social media links appended.
 */
function wordy_append_social_media_navigation( $items, $args ) {
	return $items . '<li id="social-links">' . wordy_get_social_links();
}

add_filter( 'wp_nav_menu_items', 'wordy_append_social_media_navigation', 10, 2 );

/**
 * Delete Customizer Transients
 *
 * @param WP_Customize_Manager $wp_customize_manager
 *
 * @since 1.0
 * @return void
 */
function wordy_clear_customizer_transients( $wp_customize_manager ) {
	delete_transient( 'wordy_google_fonts_url' );
	delete_transient( 'wordy_customizer_css' );
}

add_action( 'customize_save_after', 'wordy_clear_customizer_transients' );

/**
 * Get Google Fonts URL
 *
 * @since 1.0
 * @return string
 */
function wordy_get_google_fonts_url() {
	$text_style = get_theme_mod( 'typography_style', 'serif' );

	if ( $text_style == 'serif' ) {
		$url = 'https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i';
	} elseif ( $text_style == 'slab-serif' ) {
		$url = 'https://fonts.googleapis.com/css?family=Roboto+Slab:400,700';
	} else {
		$url = 'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i';
	}

	return apply_filters( 'wordy/google-fonts-url', $url, $text_style );
}

/**
 * Excerpt Length
 *
 * @param int $length
 *
 * @since 1.0
 * @return int
 */
function wordy_excerpt_length( $length ) {
	return get_theme_mod( 'excerpt_length', 30 );
}

add_filter( 'excerpt_length', 'wordy_excerpt_length' );

/**
 * Text Before Copyright
 *
 * Adds the text from the Customizer setting before the copyright message.
 *
 * @since 1.0
 * @return void
 */
function wordy_text_before_copyright() {
	$text = get_theme_mod( 'before_credits' );

	if ( ! empty( $text ) || is_customize_preview() ) {
		?>
		<div id="wordy-text-before-copyright">
			<?php echo wpautop( $text ); ?>
		</div>
		<?php
	}
}

add_action( 'wordy/before-copyright', 'wordy_text_before_copyright' );

/**
 * Bypass the `front-page.php` template when the blog posts index is being displayed.
 *
 * @param string $template
 *
 * @since 1.0
 * @return string
 */
function wordy_filter_front_page_template( $template ) {
	return is_home() ? '' : $template;
}

add_filter( 'frontpage_template', 'wordy_filter_front_page_template' );

/**
 * Get Default CTA Box Values
 *
 * Returns the default values (text and URL) for a given CTA box.
 *
 * @param int $number CTA box number.
 *
 * @since 1.0
 * @return array
 */
function wordy_get_default_cta_values( $number = 1 ) {
	$text = $url = '';

	switch ( $number ) {
		case 1 :
			$text = __( 'YA Books', 'wordy' );
			$url  = home_url( '/' );
			break;

		case 2 :
			$text = __( 'Adult Books', 'wordy' );
			$url  = home_url( '/' );
			break;

		case 3 :
			$text = __( 'Blog', 'wordy' );
			$url  = get_permalink( get_option( 'page_for_posts' ) );
			break;
	}

	return array(
		'text' => $text,
		'url'  => $url
	);
}

/**
 * Allow shortcodes in widgets.
 *
 * @since 1.0
 */
add_filter( 'widget_text', 'do_shortcode' );