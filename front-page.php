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
	?>
	<section id="featured-book">
		<?php wordy_featured_book(); ?>
	</section>
	<?php
}

/**
 * Display the call to action boxes.
 */
?>
	<section id="cta-boxes">

		<?php foreach ( range( 1, 3 ) as $number ) : ?>
			<?php
			$defaults = wordy_get_default_cta_values( $number );
			$text     = get_theme_mod( 'cta_text_' . $number, $defaults['text'] );
			$url      = get_theme_mod( 'cta_url_' . $number, $defaults['url'] );

			if ( empty( $text ) || empty( $url ) ) {
				continue;
			}
			?>
			<div id="cta-box-<?php echo absint( $number ); ?>" class="box">
				<h2><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $text ); ?></a></h2>
			</div>
		<?php endforeach; ?>

	</section>
<?php

get_footer();