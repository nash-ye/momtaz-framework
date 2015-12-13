<?php
/**
 * Primary Sidebar Template
 *
 * This template houses the HTML used for the 'Primary' sidebar.
 * It will first check if the sidebar is active before displaying anything.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

if ( is_active_sidebar( 'primary' ) ) { ?>

	<div<?php momtaz_atts( 'sidebar', array( 'id' => 'sidebar' ) ) ?>>

		<?php Momtaz_Zones::call( 'primary_sidebar:before' ) ?>

			<div id="primary-sidebar" class="widget-area">
				<?php dynamic_sidebar( 'primary' ) ?>
			</div> <!-- #primary-sidebar -->

		<?php Momtaz_Zones::call( 'primary_sidebar:after' ) ?>

	</div><?php

}
