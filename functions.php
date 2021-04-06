<?php
/**
 * Theme Functions
 *
 * Sets up the theme and includes any other required files.
 *
 * @package   wordy
 * @copyright Copyright (c) 2021, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

if ( ! function_exists( 'wordy_setup' ) ) :

	/**
	 * Setup
	 *
	 * Sets up theme definitions and registers support for WordPress features.
	 *
	 * @since 1.0
	 * @return void
	 */
	function wordy_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'wordy', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 * Also specify the size.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 200, 175, true );

		/*
		 * Register Menus
		 */
		register_nav_menus( array(
			'main' => esc_html__( 'Main Menu', 'wordy' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'wordy/custom-background-args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

	}

endif;

add_action( 'after_setup_theme', 'wordy_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since 1.0
 * @return void
 */
function wordy_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wordy/content-width', 700 );
}

add_action( 'after_setup_theme', 'wordy_content_width', 0 );

/**
 * Register widget areas.
 *
 * @link  https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @since 1.0
 * @return void
 */
function wordy_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wordy' ),
		'id'            => 'sidebar',
		'description'   => esc_html__( 'Sidebar on the left-hand side.', 'wordy' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer', 'wordy' ),
		'id'            => 'footer',
		'description'   => esc_html__( 'Footer widgets.', 'wordy' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}

add_action( 'widgets_init', 'wordy_widgets_init' );

/**
 * Enqueue scripts and styles.
 *
 * @uses  wp_get_theme()
 * @uses  wordy_get_google_fonts_url()
 * @uses  wordy_custom_css()
 *
 * @since 1.0
 * @return void
 */
function wordy_assets() {
	$wordy   = wp_get_theme();
	$version = $wordy->get( 'Version' );

	// Remove Expanding Archives CSS
	wp_deregister_style( 'expanding-archives' );

	// Google Fonts
	wp_enqueue_style( 'wordy-google-fonts', wordy_get_google_fonts_url() );

	// Add styles
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '4.6.1' );
	wp_enqueue_style( 'wordy', get_stylesheet_uri(), array(), $version );
	wp_add_inline_style( 'wordy', wordy_custom_css() );

	// JavaScript
	wp_enqueue_script( 'wordy', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), $version, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'wordy_assets' );

/**
 * Aqua Resizer script.
 */
require get_template_directory() . '/inc/aq_resizer.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Custom header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Novelist integration.
 */
require get_template_directory() . '/inc/novelist.php';

/**
 * Customizer.
 */
require get_template_directory() . '/inc/customizer/class-wordy-customizer.php';
new Wordy_Customizer();
