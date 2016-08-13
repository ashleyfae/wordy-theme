<?php
/**
 * Extra Functions
 *
 * Mostly actions and filters that act independently of template files.
 *
 * @package   catherine
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

/**
 * Whether or not to cache CSS, fonts, etc.
 *
 * @since 1.0
 * @return bool
 */
function catherine_cache() {
	$cache = true;

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG == true ) {
		$cache = false;
	}

	return apply_filters( 'catherine/cache', $cache );
}

/**
 * Get Current View
 *
 * @since 1.0
 * @return string
 */
function catherine_get_current_view() {
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

	return apply_filters( 'catherine/get-current-view', $view );
}

/**
 * Body Classes
 *
 * Adds extra class names to the <body> tag.
 *
 * @uses  catherine_get_current_view()
 * @uses  catherine_get_sidebar_locations()
 * @uses  catherine_get_sidebar_defaults()
 *
 * @param array $classes
 *
 * @since 1.0
 * @return array
 */
function catherine_body_classes( $classes ) {
	/*
	 * Sidebar Classes
	 */

	// Get current page template.
	$page_template = get_page_template_slug();

	if ( $page_template != 'page-templates/full-width.php' && $page_template != 'page-templates/landing.php' ) {

		// Get the view.
		$view = catherine_get_current_view();

		// Default sidebar settings.
		$defaults = catherine_get_sidebar_defaults();

		// Get the option in the Customizer.
		foreach ( catherine_get_sidebar_locations() as $location ) {
			$default      = array_key_exists( 'sidebar_' . $location . '_' . $view, $defaults ) ? $defaults[ 'sidebar_' . $location . '_' . $view ] : false;
			$show_sidebar = get_theme_mod( 'sidebar_' . $location . '_' . $view, $default );

			if ( $show_sidebar ) {
				$classes[] = $location . '-sidebar-is-on';
			}
		}
	}

	return $classes;
}

add_filter( 'body_class', 'catherine_body_classes' );

/**
 * Get Sidebar Locations
 *
 * Returns an array of all the sidebar options we have.
 *
 * @since 1.0
 * @return array
 */
function catherine_get_sidebar_locations() {
	$locations = array(
		'left',
		'right'
	);

	return apply_filters( 'catherine/get-sidebar-locations', $locations );
}

/**
 * Get Sidebar Default Settings
 *
 * @since 1.0
 * @return array
 */
function catherine_get_sidebar_defaults() {
	$defaults = array(
		'sidebar_left_blog'          => false,
		'sidebar_right_blog'         => true,
		'sidebar_left_single'        => false,
		'sidebar_right_single'       => true,
		'sidebar_left_page'          => false,
		'sidebar_right_page'         => true,
		'sidebar_left_book_archive'  => false,
		'sidebar_right_book_archive' => false,
		'sidebar_left_book_single'   => false,
		'sidebar_right_book_single'  => false,
	);

	return apply_filters( 'catherine/get-sidebar-defaults', $defaults );
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
function catherine_custom_css() {
	if ( ! is_customize_preview() && catherine_cache() ) {
		$saved_css = get_transient( 'catherine_customizer_css' );

		if ( $saved_css ) {
			return apply_filters( 'catherine/custom-css', $saved_css );
		}
	}

	$css = '';

	// Link Colour
	$link_colour = get_theme_mod( 'link_color' );
	if ( $link_colour ) {
		$css .= 'a { color: ' . esc_html( $link_colour ) . ' }';
	}

	// Link Colour - Hover
	$link_colour_hover = get_theme_mod( 'link_color_hover' );
	if ( $link_colour_hover ) {
		$css .= 'a:hover { color: ' . esc_html( $link_colour_hover ) . ' }';
	}

	// Button BG
	$button_bg = get_theme_mod( 'button_bg_color' );
	if ( $button_bg ) {
		$css .= '.button, button, input[type="submit"], .expanding-archives-title, .naked-social-share ul .nss-site-count, .naked-social-share ul a:hover, .widget.highlight { background: ' . esc_html( $button_bg ) . ' }';
		$css .= '.expanding-archives-collapse-section, .naked-social-share ul a { border-color: ' . esc_html( $button_bg ) . ' }';
		$css .= '.naked-social-share ul a, .widget.highlight .button, .widget.highlight button, .widget.highlight input[type="submit"] { color: ' . esc_html( $button_bg ) . ' }';
		$css .= '.widget.highlight .widget-title span { background: ' . esc_html( $button_bg ) . ' !important }';
	}

	// Button Text
	$button_text = get_theme_mod( 'button_text_color' );
	if ( $button_text ) {
		$css .= '.button, .button:hover, button, button:hover, input[type="submit"], input[type="submit"]:hover, .expanding-archives-title a, .expanding-archives-title a:hover, .naked-social-share ul a:hover, .widget.highlight, .widget.highlight .widget-title { color: ' . esc_html( $button_text ) . ' }';
		$css .= '.widget.highlight .widget-title:before { color: ' . esc_html( $button_text ) . ' !important }';
		$css .= '.widget.highlight .button, .widget.highlight button, .widget.highlight input[type="submit"] { background: ' . esc_html( $button_text ) . ' }';
	}

	// Button BG - Hover
	$button_bg_hover = get_theme_mod( 'button_bg_color_hover' );
	if ( $button_bg_hover ) {
		$css .= '.button:hover, button:hover, input[type="submit"]:hover, .expanding-archives-title:hover { background: ' . esc_html( $button_bg_hover ) . ' }';
	}

	// All font CSS
	foreach ( catherine_typography_settings() as $key => $options ) {
		$typography = get_theme_mod( $key . '_font' );
		$font_css   = catherine_create_font_css( $key, $typography );

		if ( $font_css ) {
			$css .= $options['tag'] . '{ ' . $font_css . ' }';

			// We need to change the link colour on post titles and site title.
			if ( ( $key == 'post_titles' || $key == 'site_title' ) && array_key_exists( 'color', $typography ) ) {
				$css .= $options['tag'] . ' a { color: ' . esc_html( $typography['color'] ) . ' }';
			}
		}
	}

	// Header margin
	$header_margin = get_theme_mod( 'header_margin' );
	if ( $header_margin ) {
		$css .= '@media (min-width: 768px) { .site-branding { margin: ' . esc_html( $header_margin ) . 'px auto; } }';
	}

	// Header BG
	$header_bg = get_theme_mod( 'header_text_bg' );
	if ( $header_bg ) {
		$css .= '.site-branding { background: ' . esc_html( $header_bg ) . '; }';
	}

	// Announcement
	$announcement_bg = get_theme_mod( 'announcement_bg_colour' );
	if ( $announcement_bg ) {
		$css .= '#announcement { background: ' . esc_html( $announcement_bg ) . '; }';
	}

	$announcement_text = get_theme_mod( 'announcement_text_colour' );
	if ( $announcement_text ) {
		$css .= '#announcement { color: ' . esc_html( $announcement_text ) . '; }';
	}

	// Meta Alignment
	$meta_align = get_theme_mod( 'meta_alignment_blog' );
	if ( $meta_align ) {
		$css .= '.entry-meta { text-align: ' . esc_html( $meta_align ) . '; }';
	}

	// Meta Alignment - Single
	$meta_align_single = get_theme_mod( 'meta_alignment_single' );
	if ( $meta_align_single ) {
		$css .= '.single .entry-meta { text-align: ' . esc_html( $meta_align_single ) . '; }';
	}

	// Cache this.
	set_transient( 'catherine_customizer_css', $css );

	return apply_filters( 'catherine/custom-css', $css );
}

/**
 * Create Font CSS
 *
 * Generates all the typography CSS we need from the Customizer typography control.
 *
 * @param string     $theme_mod  Key value
 * @param array|bool $typography Theme mod from the Customizer
 *
 * @since 1.0
 * @return string
 */
function catherine_create_font_css( $theme_mod, $typography = null ) {
	if ( empty( $typography ) ) {
		$typography = get_theme_mod( $theme_mod . '_font' );
	}

	if ( empty( $typography ) || ! is_array( $typography ) ) {
		return '';
	}

	$css = '';

	foreach ( $typography as $property => $value ) {
		if ( $property == 'subsets' ) {
			continue;
		}

		$value   = str_replace( 'regular', '400', $value );
		$italics = false;

		if ( $property == 'variant' && strpos( $value, 'italic' ) !== false ) {
			$value   = ( $value === 'italic' ) ? '400' : str_replace( 'italic', '', $value );
			$italics = true;
		}

		if ( $property == 'font-family' ) {
			$css .= $property . ': "' . esc_html( $value ) . '";';
		} else {
			$css .= str_replace( 'variant', 'font-weight', $property ) . ': ' . esc_html( $value ) . ';';
		}

		if ( $italics ) {
			$css .= 'font-style: italic;';
		}
	}

	return apply_filters( 'catherine/create-font-css', $css, $theme_mod, $typography );
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
function catherine_hex_to_rgba( $color, $opacity = false ) {
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
function catherine_adjust_brightness( $hex, $steps ) {
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
 * Get Google Fonts
 *
 * Returns an array of all the Google Fonts that are in use with the font
 * family as the key and an array of variants as the value. We make sure that
 * each font and variant is only included once.
 *
 * @since 1.0
 * @return array
 */
function catherine_get_google_fonts() {
	$fonts   = array();
	$find    = array( 'regular', 'italic' );
	$replace = array( '400', '400italic' );

	foreach ( catherine_typography_settings() as $key => $options ) {
		$settings = get_theme_mod( $key . '_font' );

		if ( empty( $settings ) || ! is_array( $settings ) || ! array_key_exists( 'font-family', $settings ) ) {
			$variant     = $options['settings']['variant'];
			$font_family = $options['settings']['font-family'];
		} else {
			$variant     = $settings['variant'];
			$font_family = $settings['font-family'];
		}

		$variant_final = array( str_replace( $find, $replace, $variant ) );

		// Add italics and bold for body font.
		if ( $key == 'body' ) {
			$variant_final = array(
				$variant,
				$variant . 'italic',
				'700',
				'700italic'
			);
		}

		// Font doesn't already exist - let's add it.
		if ( ! array_key_exists( $font_family, $fonts ) ) {
			$fonts[ $font_family ] = $variant_final;

			continue;
		}

		// The font already exists, so let's add variants.
		foreach ( $variant_final as $single_variant ) {
			if ( ! in_array( $single_variant, $fonts[ $font_family ] ) ) {
				$fonts[ $font_family ][] = $single_variant;
			}
		}
	}

	return apply_filters( 'catherine/get-google-fonts', $fonts );
}

/**
 * Get Google Fonts URL
 *
 * Turns our array of Google Fonts into a proper API URL.
 *
 * @uses  catherine_get_google_fonts()
 *
 * @since 1.0
 * @return string
 */
function catherine_get_google_fonts_url() {
	if ( ! is_customize_preview() && catherine_cache() ) {
		$saved_url = get_transient( 'catherine_google_fonts_url' );

		if ( $saved_url ) {
			return $saved_url;
		}
	}

	$fonts_array    = catherine_get_google_fonts();
	$compiled_fonts = array();

	foreach ( $fonts_array as $font_family => $variants ) {
		$compiled_fonts[] = $font_family . ':' . implode( ',', $variants );
	}

	$url = add_query_arg( array( 'family' => implode( '|', $compiled_fonts ) ), 'https://fonts.googleapis.com/css' );

	set_transient( 'catherine_google_fonts_url', $url );

	return apply_filters( 'catherine/get-google-fonts-url', $url, $fonts_array );
}

/**
 * Get Typography Settings
 *
 * Returns an array of all the typography settings. This array is used to
 * quickly generate the different Customizer options and the accompanying
 * CSS code.
 *
 * @since 1.0
 * @return array
 */
function catherine_typography_settings() {
	$settings = array(
		'body'             => array(
			'name'     => esc_html__( 'Body', 'catherine' ),
			'tag'      => 'body',
			'settings' => array(
				'color'          => '#333333',
				'font-family'    => 'Lora',
				'variant'        => 'regular',
				'font-size'      => '15px',
				'letter-spacing' => '0',
				'line-height'    => '1.8',
				'text-align'     => 'left'
			)
		),
		'site_title'       => array(
			'name'        => esc_html__( 'Site Title', 'catherine' ),
			'description' => esc_html__( 'Change the text colour in the "Colors" panel.', 'catherine' ),
			'tag'         => '.site-title',
			'settings'    => array(
				'font-family'    => 'Playfair Display',
				'variant'        => 'regular',
				'font-size'      => '30px',
				'letter-spacing' => '0',
				'line-height'    => '1.2',
				'text-align'     => 'center',
				'text-transform' => 'lowercase'
			)
		),
		'site_description' => array(
			'name'        => esc_html__( 'Site Description', 'catherine' ),
			'description' => esc_html__( 'Change the text colour in the "Colors" panel.', 'catherine' ),
			'tag'         => '.site-description',
			'settings'    => array(
				'font-family'    => 'Lora',
				'variant'        => 'italic',
				'font-size'      => '15px',
				'letter-spacing' => '0',
				'line-height'    => 'inherit',
				'text-align'     => 'center',
				'text-transform' => 'lowercase'
			)
		),
		'headings'         => array(
			'name'        => esc_html__( 'Headings', 'catherine' ),
			'description' => esc_html__( 'Affects all h1, h2, h3, etc. tags. Size is adjusteds automatically.', 'catherine' ),
			'tag'         => 'h1, h2, h3, h4, h5, h6',
			'settings'    => array(
				'color'          => '#333333',
				'font-family'    => 'Playfair Display',
				'variant'        => 'regular',
				'letter-spacing' => '0',
				'line-height'    => '1.3',
				'text-align'     => 'left',
				'text-transform' => 'none'
			)
		),
		'post_titles'      => array(
			'name'     => esc_html__( 'Post &amp; Page Titles', 'catherine' ),
			'tag'      => '.entry-title',
			'settings' => array(
				'color'          => '#333333',
				'font-family'    => 'Playfair Display',
				'variant'        => 'regular',
				'font-size'      => '24px',
				'letter-spacing' => '0',
				'line-height'    => '1.3',
				'text-align'     => 'left',
				'text-transform' => 'none'
			)
		),
		'widget_titles'    => array(
			'name'     => esc_html__( 'Widget Titles', 'catherine' ),
			'tag'      => '.widget-title',
			'settings' => array(
				'color'          => '#323232',
				'font-family'    => 'Playfair Display',
				'variant'        => 'regular',
				'font-size'      => '20px',
				'letter-spacing' => '0',
				'line-height'    => 'inherit',
				'text-align'     => 'center',
				'text-transform' => 'uppercase'
			)
		)
	);

	return apply_filters( 'catherine/typography-settings', $settings );
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
function catherine_get_social_sites() {
	$sites = array(
		'twitter'   => array(
			'name'        => esc_html__( 'Twitter', 'catherine' ),
			'icon'        => 'twitter',
			'icon-square' => 'twitter-square'
		),
		'facebook'  => array(
			'name'        => esc_html__( 'Facebook', 'catherine' ),
			'icon'        => 'facebook',
			'icon-square' => 'facebook-square'
		),
		'instagram' => array(
			'name'        => esc_html__( 'Instagram', 'catherine' ),
			'icon'        => 'instagram',
			'icon-square' => 'instagram'
		),
		'pinterest' => array(
			'name'        => esc_html__( 'Pinterest', 'catherine' ),
			'icon'        => 'pinterest',
			'icon-square' => 'pinterest-square'
		),
		'google'    => array(
			'name'        => esc_html__( 'Google+', 'catherine' ),
			'icon'        => 'google-plus',
			'icon-square' => 'google-plus-square'
		),
		'youtube'   => array(
			'name'        => esc_html__( 'YouTube', 'catherine' ),
			'icon'        => 'youtube-play',
			'icon-square' => 'youtube-square'
		),
		'linkedin'  => array(
			'name'        => esc_html__( 'LinkedIn', 'catherine' ),
			'icon'        => 'linkedin',
			'icon-square' => 'linkedin-square'
		),
		'spotify'   => array(
			'name'        => esc_html__( 'Spotify', 'catherine' ),
			'icon'        => 'spotify',
			'icon-square' => 'spotify'
		),
		'rss'       => array(
			'name'        => esc_html__( 'RSS', 'catherine' ),
			'icon'        => 'rss',
			'icon-square' => 'rss-square'
		),
		'email'     => array(
			'name'        => esc_html__( 'Email', 'catherine' ),
			'icon'        => 'envelope',
			'icon-square' => 'envelope-square'
		)
	);

	return apply_filters( 'catherine/get-social-sites', $sites );
}

/**
 * Delete Customizer Transients
 *
 * @param WP_Customize_Manager $wp_customize_manager
 *
 * @since 1.0
 * @return void
 */
function catherine_clear_customizer_transients( $wp_customize_manager ) {
	delete_transient( 'catherine_google_fonts_url' );
	delete_transient( 'catherine_customizer_css' );
}

add_action( 'customize_save_after', 'catherine_clear_customizer_transients' );

/**
 * Excerpt Length
 *
 * @param int $length
 *
 * @since 1.0
 * @return int
 */
function catherine_excerpt_length( $length ) {
	return get_theme_mod( 'excerpt_length', 30 );
}

add_filter( 'excerpt_length', 'catherine_excerpt_length' );

/**
 * Text Before Copyright
 *
 * Adds the text from the Customizer setting before the copyright message.
 *
 * @since 1.0
 * @return void
 */
function catherine_text_before_copyright() {
	$text = get_theme_mod( 'before_credits' );

	if ( ! empty( $text ) || is_customize_preview() ) {
		?>
		<div id="catherine-text-before-copyright">
			<?php echo wpautop( $text ); ?>
		</div>
		<?php
	}
}

add_action( 'catherine/before-copyright', 'catherine_text_before_copyright' );

/**
 * Comment Form Args
 *
 * We put these in a function since they're used in two places.
 *
 * @see   comments.php
 *
 * @since 1.0
 * @return array
 */
function catherine_comment_form_args() {
	$args = array(
		'comment_notes_before' => get_theme_mod( 'comment_notes_before', '' ),
		'comment_notes_after'  => get_theme_mod( 'comment_notes_after', '' ),
		'title_reply_before'   => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h2>'
	);

	return apply_filters( 'catherine/comments/form-args', $args );
}

/**
 * Allow shortcodes in widgets.
 *
 * @since 1.0
 */
add_filter( 'widget_text', 'do_shortcode' );