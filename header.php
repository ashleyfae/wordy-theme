<?php
/**
 * Theme Header
 *
 * Displays the <head> section, navigation, logo, and opening content tags.
 *
 * @package   wordy
 * @copyright Copyright (c) 2021, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'wordy' ); ?></a>

	<header id="header" role="banner">

		<div class="container">

			<div class="site-branding">
				<?php if ( get_header_image() ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php header_image(); ?>" alt="<?php echo esc_attr( strip_tags( get_bloginfo( 'name' ) ) ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>">
					</a>
				<?php endif; ?>

				<h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</h1>

				<?php
				// Description / Tagline.
				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) {
					echo '<p class="site-description">' . $description . '</p>';
				}
				?>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle" aria-controls="main-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'wordy' ); ?></button>
				<?php wordy_main_menu(); ?>
			</nav>

		</div>

	</header>

	<?php do_action( 'wordy/after-header' ); ?>

	<div class="container">

		<div id="content">

<?php do_action( 'wordy/inside-content/top' ); ?>
