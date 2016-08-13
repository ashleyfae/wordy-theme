<?php
/**
 * Front Page Template
 *
 * Used for displaying a static homepage.
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

get_header();

/**
 * Display the featured book.
 */
if ( function_exists( 'wordy_featured_book' ) ) {
	wordy_featured_book();
}

get_footer();