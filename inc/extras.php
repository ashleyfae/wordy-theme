<?php
/**
 * Extra Functions
 *
 * Mostly actions and filters that act independently of template files.
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since 1.0
 */

/**
 * Whether or not to cache CSS, fonts, etc.
 *
 * @since 1.0
 * @return bool
 */
function wordy_cache() {
	$cache = true;

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
 * Allow shortcodes in widgets.
 *
 * @since 1.0
 */
add_filter( 'widget_text', 'do_shortcode' );