<?php
/**
 * Novelist Book Archive
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

if ( ! class_exists( 'Novelist' ) ) {
	get_template_part( 'archive' );

	return;
}

get_header(); ?>

	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php if ( ! get_theme_mod( 'suppress_archive_headings', false ) ) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Books', 'wordy' ); ?></h1>
				</header>
			<?php endif; ?>

			<div id="book-feed">

				<?php while ( have_posts() ) : the_post();

					/**
					 * Allow child themes to modify the template part. By default it's template-parts/book.php.
					 *
					 * @param string  $slug Template slug to use.
					 * @param WP_Post $post Current post.
					 *
					 * @since 1.0
					 */
					$slug          = '';
					$post          = get_post();
					$template_slug = apply_filters( 'wordy/archive-book/template-slug', $slug, $post );

					/**
					 * Include the template part.
					 */
					get_template_part( 'template-parts/book', $template_slug );

				endwhile; ?>

				<nav class="pagination" role="navigation">
					<?php echo paginate_links(); ?>
				</nav>

			</div>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

	</main>

<?php get_footer();