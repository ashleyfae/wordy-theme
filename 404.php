<?php
/**
 * 404 (Not Found) Template
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

$widget_args = $archive_widget_args = array(
	'before_title' => '<h2 class="widget-title"><span>',
	'after_title'  => '</span></h2>'
);

get_header(); ?>


	// Include header.php.
	get_header(); ?>

	<main id="main" class="site-main" role="main">

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="entry-title page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'wordy' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'wordy' ); ?></p>

				<div id="error-404-widgets">
					<?php
					the_widget( 'WP_Widget_Search', array( 'title' => esc_html__( 'Search', 'wordy' ) ), $widget_args );

					the_widget( 'WP_Widget_Recent_Posts', array(), $widget_args );
					?>

					<div class="widget widget_categories">
						<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'wordy' ); ?></h2>
						<ul>
							<?php
							wp_list_categories( array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							) );
							?>
						</ul>
					</div><!-- .widget -->

					<?php
					/* translators: %1$s: smiley */
					$archive_content                    = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'wordy' ), convert_smilies( ':)' ) ) . '</p>';
					$archive_widget_args['after_title'] = $archive_widget_args['after_title'] . $archive_content;

					the_widget( 'WP_Widget_Archives', 'dropdown=1', $archive_widget_args );

					the_widget( 'WP_Widget_Tag_Cloud', array(), $widget_args );
					?>
				</div>

			</div>
		</section>

	</main>

<?php get_footer();