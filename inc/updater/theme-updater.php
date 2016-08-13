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
		'remote_api_url' => 'https://novelistplugin.com', // Site where EDD is hosted
		'item_name'      => 'Wordy Theme', // Name of theme
		'theme_slug'     => 'wordy', // Theme slug
		'version'        => $theme->get( 'Version' ), // The current version of this theme
		'author'         => 'Novelist', // The author of this theme
		'download_id'    => '', // Optional, used for generating a license renewal link
		'renew_url'      => '' // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license'             => __( 'Theme License', 'wordy' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'wordy' ),
		'license-key'               => __( 'License Key', 'wordy' ),
		'license-action'            => __( 'License Action', 'wordy' ),
		'deactivate-license'        => __( 'Deactivate License', 'wordy' ),
		'activate-license'          => __( 'Activate License', 'wordy' ),
		'status-unknown'            => __( 'License status is unknown.', 'wordy' ),
		'renew'                     => __( 'Renew?', 'wordy' ),
		'unlimited'                 => __( 'unlimited', 'wordy' ),
		'license-key-is-active'     => __( 'License key is active.', 'wordy' ),
		'expires%s'                 => __( 'Expires %s.', 'wordy' ),
		'expires-never'             => __( 'Lifetime license - never expires.', 'wordy' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'wordy' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'wordy' ),
		'license-key-expired'       => __( 'License key has expired.', 'wordy' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'wordy' ),
		'license-is-inactive'       => __( 'License is inactive.', 'wordy' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'wordy' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'wordy' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'wordy' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'wordy' ),
		'update-available'          => __( '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'wordy' )
	)

);