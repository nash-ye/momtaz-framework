<?php
/**
 * Main Header Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.3
 */

Momtaz_Zones::call( 'footer:before' ) ?>

<footer<?php momtaz_atts( 'footer', array( 'id' => 'footer' ) ) ?>>
	<p class="site-credits"><?php printf( __( 'Proudly powered by %1$s and %2$s Theme', 'momtaz' ), momtaz_get_wp_link(), momtaz_get_theme_link( get_stylesheet() ) ) ?></p>
</footer>

<?php Momtaz_Zones::call( 'footer:after' );
