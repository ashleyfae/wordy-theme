<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Sample Theme
 */

// Includes the files needed for the theme updater
if ( ! class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

$theme = wp_get_theme();

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(

// Config settings
	$config = array(
		'remote_api_url' => 'https://shop.nosegraze.com', // Site where EDD is hosted
		'item_name'      => 'Bookstagram Theme', // Name of theme
		'theme_slug'     => 'catherine', // Theme slug
		'version'        => $theme->get( 'Version' ), // The current version of this theme
		'author'         => 'Nose Graze', // The author of this theme
		'download_id'    => '35470', // Optional, used for generating a license renewal link
		'renew_url'      => '' // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license'             => __( 'Theme License', 'catherine' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'catherine' ),
		'license-key'               => __( 'License Key', 'catherine' ),
		'license-action'            => __( 'License Action', 'catherine' ),
		'deactivate-license'        => __( 'Deactivate License', 'catherine' ),
		'activate-license'          => __( 'Activate License', 'catherine' ),
		'status-unknown'            => __( 'License status is unknown.', 'catherine' ),
		'renew'                     => __( 'Renew?', 'catherine' ),
		'unlimited'                 => __( 'unlimited', 'catherine' ),
		'license-key-is-active'     => __( 'License key is active.', 'catherine' ),
		'expires%s'                 => __( 'Expires %s.', 'catherine' ),
		'expires-never'             => __( 'Lifetime license - never expires.', 'catherine' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'catherine' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'catherine' ),
		'license-key-expired'       => __( 'License key has expired.', 'catherine' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'catherine' ),
		'license-is-inactive'       => __( 'License is inactive.', 'catherine' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'catherine' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'catherine' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'catherine' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'catherine' ),
		'update-available'          => __( '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'catherine' )
	)

);