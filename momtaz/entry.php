<?php
/*
 * Default Entry Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

global $post_num; ++$post_num; ?>

<?php do_action( momtaz_format_hook( 'before_entry' ) ); ?>

<?php momtaz_struct_markup( 'entry', '<article%atts%>' ) ?>

	<?php momtaz_template_part( 'entry-header' ); ?>

	<div class="entry-body">

		<?php do_action( momtaz_format_hook( 'before_entry_content' ) ); ?>

			<?php

				if ( function_exists( 'get_the_image' ) ) {

					get_the_image( array(
						'before'		=> '<div class="entry-thumbnail">',
						'after'			=> '</div><!-- .entry-thumbnail -->',
						'image_class'	=> 'thumbnail',
						'scan'			=> true,
						'height'		=> 150,
						'width'			=> 150,
					) );

				} // end if

			?>

			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div> <!-- .entry-summary -->

		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'momtaz' ) . '</span>', 'after' => '</div>' ) ); ?>

		<?php do_action( momtaz_format_hook( 'after_entry_content' ) ); ?>

	</div> <!-- .entry-body -->

	<?php momtaz_template_part( 'entry-footer' ); ?>

<?php momtaz_struct_markup( 'entry', '</article> <!-- .hentry -->' ) ?>

<?php do_action( momtaz_format_hook( 'after_entry' ) );