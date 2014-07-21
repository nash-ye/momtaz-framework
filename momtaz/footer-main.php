<?php
/**
 * Main Header Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.3
 */
?>
<div id="footer-container">

	<?php do_action( momtaz_format_hook( 'before_footer' ) ); ?>

	<footer id="footer" role="contentinfo">
		<?php do_action( momtaz_format_hook( 'footer' ) ); ?>
	</footer> <!-- #footer -->

	<?php do_action( momtaz_format_hook( 'after_footer' ) ); ?>

</div> <!-- #footer-container -->