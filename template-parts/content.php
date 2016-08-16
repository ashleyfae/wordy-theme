<?php
/**
 * Template part for displaying posts.
 *
 * This is loaded into the archive pages (index.php) to display post excerpts
 * and thumbnails.
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	do_action( 'wordy/before-post-thumbnail', get_post() );

	/**
	 * Display the post thumbnail.
	 */
	echo wordy_get_post_thumbnail( 200, 175, true, 'alignleft post-thumbnail' );

	do_action( 'wordy/after-post-thumbnail', get_post() )
	?>

	<header class="entry-header">
		<?php
		/*
		 * Maybe show entry meta
		 */
		if ( 'post' === get_post_type() ) {
			wordy_entry_meta();
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
		the_excerpt();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wordy' ),
			'after'  => '</div>',
		) );
		?>
	</div>
	<?php do_action( 'wordy/after-post-content', get_post() ); ?>

	<footer class="entry-footer">

	</footer>

</article>