<?php

/**
 * Customizer Settings
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

if ( class_exists( 'Wordy_Customizer' ) ) {
	return;
}

/**
 * Class Wordy_Customizer
 *
 * @since 1.0
 */
class Wordy_Customizer {

	/**
	 * Theme object
	 *
	 * @var WP_Theme
	 * @access private
	 * @since  1.0
	 */
	private $theme;

	/**
	 * Catherine_Customizer constructor.
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public function __construct() {

		$theme       = wp_get_theme();
		$this->theme = $theme;

		add_action( 'customize_register', array( $this, 'register_customize_sections' ) );
		add_action( 'customize_register', array( $this, 'refresh' ) );
		add_action( 'customize_preview_init', array( $this, 'live_preview' ) );

	}

	/**
	 * Add all panels to the Customizer
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public function register_customize_sections( $wp_customize ) {

		// Typography
		$wp_customize->add_section( 'typography', array(
			'title'    => esc_html__( 'Typography', 'wordy' ),
			'priority' => 51
		) );

		// Social Media
		$wp_customize->add_section( 'social_media', array(
			'title'    => esc_html__( 'Social Media', 'wordy' ),
			'priority' => 209
		) );

		// Footer
		$wp_customize->add_section( 'footer', array(
			'title'    => esc_html__( 'Footer', 'wordy' ),
			'priority' => 210
		) );

		do_action( 'wordy/customizer/register-sections', $wp_customize );

		/*
		 * Populate Sections
		 */

		$this->colours_section( $wp_customize );
		$this->typography_section( $wp_customize );
		$this->social_media_section( $wp_customize );
		$this->footer_section( $wp_customize );

		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
		$wp_customize->get_control( 'header_textcolor' )->priority  = 71;
		$wp_customize->get_control( 'background_color' )->section   = 'background_image';
		$wp_customize->get_section( 'background_image' )->title     = esc_html__( 'Background', 'wordy' );

	}

	/**
	 * Selective Refresh
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access private
	 * @since  1.0
	 * @return void
	 */
	public function refresh( $wp_customize ) {

		// Abort if selective refresh is not available.
		if ( ! isset( $wp_customize->selective_refresh ) ) {
			return;
		}

		/* Social Media */
		foreach ( wordy_get_social_sites() as $key => $site ) {
			$wp_customize->selective_refresh->add_partial( $key, array(
				'selector'        => '#header .social-media-links',
				'settings'        => $key,
				'render_callback' => function () {
					return wordy_get_social_links();
				}
			) );
		}

	}

	/**
	 * Section: Colours
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access private
	 * @since  1.0
	 * @return void
	 */
	private function colours_section( $wp_customize ) {

	}

	/**
	 * Section: Typography
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access private
	 * @since  1.0
	 * @return void
	 */
	private function typography_section( $wp_customize ) {

		/* Typography Style */
		$wp_customize->add_setting( 'typography_style', array(
			'default'           => 'serif',
			'sanitize_callback' => array( $this, 'sanitize_typography_style' )
		) );

		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'typography_style', array(
			'label'    => esc_html__( 'Text Style', 'wordy' ),
			'type'     => 'select',
			'choices'  => array(
				'sans-serif' => esc_html__( 'Sans Serif', 'wordy' ),
				'serif'      => esc_html__( 'Serif', 'wordy' ),
				'slab-serif' => esc_html__( 'Slab Serif', 'wordy' )
			),
			'section'  => 'typography',
			'settings' => 'typography_style',
			'priority' => 10
		) ) );

	}

	/**
	 * Section: Social Media
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access private
	 * @since  1.0
	 * @return void
	 */
	private function social_media_section( $wp_customize ) {

		/* Icon Type */
		$wp_customize->add_setting( 'social_link_type', array(
			'default'           => 'square',
			'sanitize_callback' => array( $this, 'sanitize_social_link_type' )
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'social_link_type', array(
			'label'    => esc_html__( 'Icon Type', 'wordy' ),
			'type'     => 'select',
			'choices'  => array(
				'regular' => esc_html__( 'regular', 'wordy' ),
				'square'  => esc_html__( 'Square', 'wordy' )
			),
			'section'  => 'social_media',
			'settings' => 'social_link_type'
		) ) );

		foreach ( wordy_get_social_sites() as $key => $options ) {
			$label = $key != 'email' ? sprintf( __( '%s Profile URL', 'wordy' ), esc_html( $options['name'] ) ) : __( 'Email Address or Contact Page URL', 'wordy' );

			$wp_customize->add_setting( $key, array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => $wp_customize->selective_refresh ? 'postMessage' : 'refresh'
			) );
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $key, array(
				'label'    => $label,
				'type'     => 'text',
				'default'  => '',
				'section'  => 'social_media',
				'settings' => $key
			) ) );
		}

	}

	/**
	 * Section: Footer
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access private
	 * @since  1.0
	 * @return void
	 */
	private function footer_section( $wp_customize ) {

		/* Number of Widget Columns */
		$wp_customize->add_setting( 'footer_widget_columns', array(
			'default'           => 4,
			'sanitize_callback' => array( $this, 'sanitize_footer_widget_columns' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'footer_widget_columns', array(
			'label'       => esc_html__( 'Footer Widget Columns', 'wordy' ),
			'description' => esc_html__( 'The number of footer widgets to appear on each row (1-5).', 'wordy' ),
			'type'        => 'number',
			'section'     => 'footer',
			'settings'    => 'footer_widget_columns',
			'priority'    => 10
		) ) );

		/* Affiliate ID */
		$wp_customize->add_setting( 'affiliate_id', array(
			'default'           => '',
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'affiliate_id', array(
			'label'       => esc_html__( 'Affiliate ID', 'wordy' ),
			'description' => esc_html__( 'Enter your Nose Graze affiliate ID number to add it to the theme URL in the footer.', 'wordy' ),
			'type'        => 'text',
			'section'     => 'footer',
			'settings'    => 'affiliate_id',
			'priority'    => 20
		) ) );

		/* Text before credits */
		$wp_customize->add_setting( 'before_credits', array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'before_credits', array(
			'label'       => esc_html__( 'Footer Text', 'wordy' ),
			'description' => esc_html__( 'Any text you enter in here will appear directly before the copyright line (see below) in the footer.', 'wordy' ),
			'type'        => 'textarea',
			'default'     => '',
			'section'     => 'footer',
			'settings'    => 'before_credits',
			'priority'    => 30
		) ) );

		/* Copyright Message */
		$wp_customize->add_setting( 'copyright_message', array(
			'default'           => sprintf( __( 'Copyright %s %s. &hearts; All Rights Reserved.', 'wordy' ), date( 'Y' ), get_bloginfo( 'name' ) ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'copyright_message', array(
			'label'       => esc_html__( 'Copyright Message', 'wordy' ),
			'description' => esc_html__( 'Customize the copyright message. A link to the theme will be inserted after this.', 'wordy' ),
			'type'        => 'textarea',
			'default'     => '',
			'section'     => 'footer',
			'settings'    => 'copyright_message',
			'priority'    => 40
		) ) );

	}

	/**
	 * Sanitize: Typography Style
	 *
	 * Only `sans-serif`, `serif`, and `slab-serif` are allowed.
	 *
	 * @param string $input
	 *
	 * @access public
	 * @since  1.0
	 * @return string
	 */
	public function sanitize_typography_style( $input ) {

		$allowed = array( 'sans-serif', 'serif', 'slab-serif' );

		if ( in_array( $input, $allowed ) ) {
			return $input;
		}

		return 'serif';

	}

	/**
	 * Sanitize Social Link Type
	 *
	 * Must be one of our approved choices, otherwise it returns
	 * the default.
	 *
	 * @param mixed $input
	 *
	 * @access public
	 * @since  1.0
	 * @return string
	 */
	public function sanitize_social_link_type( $input ) {
		$valid = array(
			'regular',
			'square'
		);

		if ( in_array( $input, $valid ) ) {
			return $input;
		}

		return 'square';
	}

	/**
	 * Sanitize On/Off
	 *
	 * Must be one of our approved choices, otherwise it returns
	 * the default.
	 *
	 * @param mixed $input
	 *
	 * @access public
	 * @since  1.0
	 * @return string
	 */
	public function sanitize_on_off( $input ) {
		$valid = array(
			'on',
			'off'
		);

		if ( in_array( $input, $valid ) ) {
			return $input;
		}

		return 'on';
	}

	/**
	 * Sanitize Checkbox
	 *
	 * @param $input
	 *
	 * @access public
	 * @since  1.0
	 * @return bool
	 */
	public function sanitize_checkbox( $input ) {
		return ( $input === true ) ? true : false;
	}

	/**
	 * Sanitize Footer Widget Columns
	 *
	 * We only allow integers between 1 and 5.
	 *
	 * @param int $input
	 *
	 * @access public
	 * @since  1.0
	 * @return int
	 */
	public function sanitize_footer_widget_columns( $input ) {
		$sanitized_input = absint( $input );

		if ( ( 1 <= $sanitized_input ) && ( $sanitized_input <= 5 ) ) {
			return $sanitized_input;
		}

		return 4;
	}

	/**
	 * Add JavaScript
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public function live_preview() {

		// Use minified libraries if SCRIPT_DEBUG is turned off
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script(
			'wordy-customizer',
			get_template_directory_uri() . '/inc/customizer/js/customizer-preview' . $suffix . '.js',
			array( 'jquery', 'customize-preview' ),
			$this->theme->get( 'Version' ),
			true
		);

	}

}