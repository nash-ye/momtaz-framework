<?php
/*
 * Default Entry Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

global $post_num; ++$post_num;

 Momtaz_Zones::call( 'entry:before' ) ?>

<article<?php momtaz_atts( 'entry', array( 'class' => 'loop-entry' ) ) ?>>

	<?php momtaz_template_part( 'entry-header' ) ?>

	<div class="entry-body">

		<?php Momtaz_Zones::call( 'entry_content:before' ) ?>

		<?php

			if ( function_exists( 'get_the_image' ) ) {

				get_the_image( array(
					'before'        => '<div class="entry-thumbnail">',
					'after'         => '</div><!-- .entry-thumbnail -->',
					'image_class'   => 'thumbnail',
					'scan'          => true,
					'height'        => 150,
					'width'         => 150,
				) );

			} // end if

		?>

		<div<?php momtaz_atts( 'entry-summary', array( 'class' => 'entry-summary' ) ) ?>>
			<?php the_excerpt() ?>
		</div> <!-- .entry-summary -->

		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'momtaz' ) . '</span>', 'after' => '</div>' ) ) ?>

		<?php Momtaz_Zones::call( 'entry_content:after' ) ?>

	</div> <!-- .entry-body -->

	<?php momtaz_template_part( 'entry-footer' ) ?>

</article><?php

Momtaz_Zones::call( 'entry:after' );
