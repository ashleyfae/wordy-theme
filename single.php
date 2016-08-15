<?php
/**
 * Single Post Template
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

get_header(); ?>

	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post();

				/**
				 * Allow child themes to modify the template part. By default it's template-parts/content-single.php.
				 *
				 * @param string  $slug Template slug to use.
				 * @param WP_Post $post Current post.
				 *
				 * @since 1.0
				 */
				$slug          = 'single';
				$post          = get_post();
				$template_slug = apply_filters( 'wordy/single/template-slug', $slug, $post );

				/**
				 * Include the template part.
				 */
				get_template_part( 'template-parts/content', $template_slug );

			endwhile; ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

	</main>

<?php
get_sidebar();
get_footer();