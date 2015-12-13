<?php
/**
 * Main Header Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.3
 */

Momtaz_Zones::call( 'header:before' ) ?>

<header<?php momtaz_atts( 'header', array( 'id' => 'header' ) ) ?>>

	<div<?php momtaz_atts( 'branding', array( 'id' => 'branding' ) ) ?>>

		<h1<?php momtaz_atts( 'site-title', array( 'id' => 'site-title' ) ) ?>>
			<a href="<?php echo esc_url( home_url( '/' ) ) ?>" rel="home"><?php bloginfo( 'name' ) ?></a>
		</h1> <!-- #site-title -->

		<h2<?php momtaz_atts( 'site-description', array( 'id' => 'site-description' ) ) ?>>
			<?php bloginfo( 'description' ) ?>
		</h2> <!-- #site-description -->

	</div> <!-- #branding -->

</header><?php

Momtaz_Zones::call( 'header:after' );

momtaz_template_part( 'menu', 'primary' );
