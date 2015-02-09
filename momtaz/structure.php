<?php
/**
 * Default Structure Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

momtaz_template_header();

	Momtaz_Zones::call( 'wrapper:before' ) ?>

	<div<?php momtaz_atts( 'wrapper', array( 'id' => 'wrapper', 'class' => 'hfeed' ) ) ?>>

		<?php momtaz_template_header( 'main' ) ?>

		<?php Momtaz_Zones::call( 'container:before' ) ?>

		<div<?php momtaz_atts( 'container', array( 'id' => 'container' ) ) ?>>

			<main<?php momtaz_atts( 'content', array( 'id' => 'content' ) ) ?>>
				<?php Momtaz_Zones::call( 'content' ) ?>
			</main> <!-- #content -->

			<?php momtaz_template_sidebar() ?>

		</div> <!-- #container -->

		<?php Momtaz_Zones::call( 'container:after' ) ?>

		<?php momtaz_template_footer( 'main' ) ?>

	</div> <!-- #wrapper --><?php

	Momtaz_Zones::call( 'wrapper:after' );

momtaz_template_footer();