<?php
/**
 * Trackback Comment Template
 *
 * This template displays an individual trackback comment.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

momtaz_struct_markup( 'comment', '<li%atts%>' ) ?>

	<small><?php _e( 'Trackback:', 'momtaz' ); ?></small> <?php comment_author_link(); ?>

	<?php edit_comment_link( __( 'Edit', 'momtaz' ), '<span class="edit-link">', '</span>' ); ?>

<?php /* No closing. WordPress will know where to add it. */