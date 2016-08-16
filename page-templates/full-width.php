<?php
/**
 * Template Name: Full Width Page
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

get_header(); ?>

	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post();

				/**
				 * Page Content
				 * Pull in template-parts/content-page.php, which displays the page content.
				 */
				get_template_part( 'template-parts/content', 'page' );

				/**
				 * Comments Template
				 * Pull in the comments template if comments are open or if we have at least one comment.
				 */
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

	</main>

<?php get_footer();