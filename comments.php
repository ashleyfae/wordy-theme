<?php
/**
 * Commente Template
 *
 * Displays the list of comments and the reply form.
 *
 * @package   wordy
 * @copyright Copyright (c) 2021, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	/**
	 * Display the comment form.
	 */
	comment_form();

	if ( have_comments() ) : ?>

		<h2 class="comments-title">
			<?php printf(
				esc_html( _nx( 'One comment', '%1$s comments', get_comments_number(), 'comments title', 'wordy' ) ),
				number_format_i18n( get_comments_number() )
			); ?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'wordy' ); ?></h2>
				<div class="nav-links">
					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'wordy' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'wordy' ) ); ?></div>
				</div>
			</nav>
		<?php endif; ?>

		<ol class="comment-list">
			<?php
			/**
			 * List of Comments
			 */
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
			) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'wordy' ); ?></h2>
				<div class="nav-links">
					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'wordy' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'wordy' ) ); ?></div>
				</div>
			</nav>
			<?php
		endif;

	endif; ?>

</div>
