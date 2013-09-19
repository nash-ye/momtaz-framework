<?php
/**
 * Default Comment Template
 *
 * This template displays an individual comment. This can be overwritten by templates specific
 * to the comment type (comment.php, comment-{$comment_type}.php, comment-pingback.php,
 * comment-trackback.php) in a child theme.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

global $comment, $comment_depth, $max_depth; ?>

<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>

	<?php do_action( momtaz_format_hook( 'before_comment' ) ); ?>

	<article id="comment-container-<?php comment_ID(); ?>" class="comment-container">

			<header class="comment-header">

				<div class="vcard comment-author">

					<div class="comment-author-avatar">
						<?php echo get_avatar( $comment, 38 ); ?>
					</div> <!-- .comment-author-avatar -->

					<div class="comment-author-data">
						<span class="fn comment-author-link"><?php comment_author_link(); ?></span>
					</div> <!-- .comment-author-data -->

				</div> <!-- .comment-author -->

				<div class="comment-datetime">
				   <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><time datetime="<?php echo esc_attr( get_comment_date('c') ); ?>"><?php printf( _x( '%1s @ %2s', 'comment-datetime', 'momtaz' ), get_comment_date(), get_comment_time() );  ?></time></a>
				</div> <!-- .comment-datetime -->

			</header> <!-- .comment-header -->

			<div class="comment-utility">
				<?php edit_comment_link( __( 'Edit', 'momtaz' ), '<span class="edit-link">', '</span>' ); ?>
				<?php comment_reply_link( array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'momtaz' ), 'depth' => $comment_depth, 'max_depth' => $max_depth, 'before' => '<span class="reply-link">', 'after' => '</span>' ) ); ?>
			</div> <!-- .comment-utility -->

			<div class="comment-content">

				<?php if ( $comment->comment_approved == 0 ) : ?>
					 <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'momtaz' ); ?></em>
				<?php endif; ?>

				<?php comment_text(); ?>

			</div> <!-- .comment-content -->

	</article> <!-- .comment-container -->

	<?php do_action( momtaz_format_hook( 'after_comment' ) ); ?>

<?php /* No closing. WordPress will know where to add it. */ ?>