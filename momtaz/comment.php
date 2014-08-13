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

global $comment, $comment_depth, $max_depth ?>

<li<?php momtaz_atts( 'comment' ) ?>>

	<?php Momtaz_Zones::call( 'comment:before' ) ?>

	<article id="comment-container-<?php comment_ID() ?>" class="comment-container">

			<header<?php momtaz_atts( 'comment-header', array( 'class' => 'comment-header' ) ) ?>>

				<div<?php momtaz_atts( 'comment-author', array( 'class' => 'vcard comment-author' ) ) ?>>

					<?php echo get_avatar( $comment, 38 ) ?>
					<span class="fn"><?php comment_author_link() ?></span>

				</div> <!-- .comment-author -->

				<div class="comment-datetime">
					<a<?php momtaz_atts( 'comment-permalink' ) ?>><time<?php momtaz_atts( 'comment-time' ) ?>><?php printf( _x( '%1s @ %2s', 'comment-datetime', 'momtaz' ), get_comment_date(), get_comment_time() ) ?></time></a>
				</div> <!-- .comment-datetime -->

			</header>

			<div class="comment-utility">
				<?php edit_comment_link( __( 'Edit', 'momtaz' ), '<span class="edit-link">', '</span>' ) ?>
				<?php comment_reply_link( array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'momtaz' ), 'depth' => $comment_depth, 'max_depth' => $max_depth, 'before' => '<span class="reply-link">', 'after' => '</span>' ) ) ?>
			</div> <!-- .comment-utility -->

			<div<?php momtaz_atts( 'comment-content', array( 'class' => 'comment-content' ) ) ?>>

				<?php if ( $comment->comment_approved == 0 ) : ?>
					 <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'momtaz' ) ?></em>
				<?php endif ?>

				<?php comment_text() ?>

			</div>

	</article> <!-- .comment-container -->

	<?php Momtaz_Zones::call( 'comment:after' ) ?>

<?php /* No closing. WordPress will know where to add it. */