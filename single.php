<?php
/**
 * Single Post Template
 *
 * @package   wordy
 * @copyright Copyright (c) 2021, Nose Graze Ltd.
 * @license   GPL2+
 */

get_header(); ?>

	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post();

				/**
				 * Post Content
				 * Pull in template-parts/content-single.php, which displays the post content.
				 */
				get_template_part( 'template-parts/content', 'single' );

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

<?php
get_sidebar();
get_footer();
