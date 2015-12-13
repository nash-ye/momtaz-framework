<?php
/**
 * Primary Menu Template
 *
 * Displays the primary menu if it has active menu items.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

if ( has_nav_menu( 'primary' ) ) {

	Momtaz_Zones::call( 'primary_menu:before' ) ?>

	<nav<?php momtaz_atts( 'nav-primary', array( 'id' => 'primary-menu', 'class' => 'menu-container' ) ) ?>>

		<?php

			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_class' => 'nav-menu dropdown'
			) )

		?>

	</nav><?php

    Momtaz_Zones::call( 'primary_menu:after' );

}
