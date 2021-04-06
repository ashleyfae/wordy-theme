<?php
/**
 * Template part for showing full blog posts in single.php.
 *
 * @package   wordy
 * @copyright Copyright (c) 2021, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	do_action( 'wordy/before-featured-image', get_post() );

	/*
	 * Featured Image
	 */
	if ( has_post_thumbnail() && ! get_theme_mod( 'hide_featured_image' ) ) {
		the_post_thumbnail( 'full', array( 'class' => 'aligncenter featured-image' ) );
	}
	?>

	<header class="entry-header">
		<?php
		/*
		 * Maybe show entry meta
		 */
		if ( 'post' === get_post_type()) {
			wordy_entry_meta( 'meta_config_single' );
		}

		/*
		 * Post Title
		 */
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}

		do_action( 'wordy/after-post-title', get_post() );
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
	do_action( 'wordy/after-post-content', get_post() );

	/*
	 * Post footer information (tags list).
	 */
	wordy_entry_footer();
	?>

</article>
