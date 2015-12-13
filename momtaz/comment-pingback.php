<?php
/**
 * Pingback Comment Template
 *
 * This template displays an individual pingback comment.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */
?>
<li<?php momtaz_atts( 'comment' ) ?>>

	<small><?php _e( 'Pingback:', 'momtaz' ) ?></small> <?php comment_author_link() ?>
	<?php edit_comment_link( __( 'Edit', 'momtaz' ), '<span class="edit-link">', '</span>' ) ?>

<?php /* No closing. WordPress will know where to add it. */
