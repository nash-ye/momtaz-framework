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
</footer>

<?php Momtaz_Zones::call( 'footer:after' );