<?php
/**
 * Template part for displaying books in `archive-book.php`
 *
 * @package   wordy
 * @copyright Copyright (c) 2021, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

$book     = new Novelist_Book( get_the_ID() );
$synopsis = $book->get_synopsis();
?>

<article id="book-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="book-cover-wrap">
		<?php
		do_action( 'wordy/book/before-book-cover', get_post() );

		/**
		 * Display the post thumbnail.
		 */
		echo '<a href="' . esc_url( get_permalink() ) . '">' . $book->get_cover_image( 'large', 'aligncenter' ) . '</a>';

		do_action( 'wordy/book/after-book-thumbnail', get_post() )
		?>
	</div>

	<div class="book-content-wrap">
		<header class="entry-header">
			<?php
			/*
			 * Post Title
			 */
			if ( is_single() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}

			do_action( 'wordy/book/after-book-title', get_post() );

			/*
			 * Book Genre
			 */
			?>
			<div class="entry-meta">
				<?php echo $book->get_genre(); ?>
			</div>
		</header>

		<?php if ( $synopsis ) : ?>
			<div class="entry-content">
				<blockquote class="book-synopsis">
					<?php echo wpautop( wordy_excerpt( $synopsis, 550 ) ); ?>
				</blockquote>
			</div>
			<?php do_action( 'wordy/book/after-book-synopsis', get_post() ); ?>
		<?php endif; ?>

		<footer class="entry-footer">
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="button"><?php _e( 'More Info &raquo;', 'wordy' ); ?></a>
		</footer>
	</div>

</article>
