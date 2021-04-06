<?php
/**
 * Blog Sidebar
 *
 * @package   wordy
 * @copyright Copyright (c) 2021, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

if ( ! is_active_sidebar( 'sidebar' ) && ! is_customize_preview() ) {
	return;
}

if ( is_singular( 'book' ) && ! get_theme_mod( 'show_sidebar_single_book' ) ) {
	return;
}
?>

<button class="sidebar-toggle" aria-controls="sidebar" aria-expanded="false"><?php esc_html_e( 'Show Sidebar', 'wordy' ); ?></button>

<aside id="sidebar" class="widget-area" role="complementary">
	<?php
	if ( is_active_sidebar( 'sidebar' ) ) {
		dynamic_sidebar( 'sidebar' );
	} elseif ( is_customize_preview() ) {
		?>
		<p><?php _e( 'Add some widgets to the \'Sidebar\' widget area.', 'wordy' ); ?></p>
		<?php
	}
	?>
</aside>
