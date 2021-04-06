<?php
/**
 * Theme Footer
 *
 * Includes footer widgets, copyright info, theme credits, and footer scripts.
 *
 * @package   wordy
 * @copyright Copyright (c) 2021, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

do_action( 'wordy/inside-content/bottom' ); ?>

</div> <!-- #content -->

<?php do_action( 'wordy/before-footer' ); ?>

</div> <!-- .container -->

<footer id="footer">
	<div class="container">
		<?php
		/**
		 * Display the footer widgets.
		 */
		$widget_columns = get_theme_mod( 'footer_widget_columns', 4 );

		if ( is_active_sidebar( 'footer' ) ) : ?>
			<div id="footer-widgets" class="widget-area widget-columns-<?php echo esc_attr( $widget_columns ); ?>">
				<?php dynamic_sidebar( 'footer' ); ?>
			</div>
		<?php endif; ?>

		<div id="site-info">
			<?php
			/**
			 * Copyright information.
			 */
			do_action( 'wordy/before-copyright' );

			printf(
				__( '%s %s by Novelist.', 'wordy' ),
				wordy_get_copyright(),
				wordy_get_theme_url()
			);

			do_action( 'wordy/after-copyright' );
			?>
		</div>
	</div>
</footer>

<?php do_action( 'wordy/after-footer' ); ?>

</div> <!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
