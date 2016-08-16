<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php
		/*
		 * Page Title
		 */
		the_title( '<h1 class="entry-title">', '</h1>' );

		do_action( 'wordy/after-page-title', get_post() );
		?>
	</header>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wordy' ),
			'after'  => '</div>',
		) );
		?>
	</div>

	<?php
	do_action( 'wordy/after-page-content', get_post() );
	?>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					esc_html__( 'Edit %s', 'wordy' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer>
	<?php endif; ?>

</article>