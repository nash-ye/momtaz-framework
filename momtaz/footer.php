<?php
/**
 * The template for displaying the footer.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */
?>
<div id="footer-container">

    <?php do_action( momtaz_format_hook( 'before_footer' ) ); ?>

    <footer id="footer" role="contentinfo">
        <?php do_action( momtaz_format_hook( 'footer' ) ); ?>
    </footer> <!-- #footer -->

    <?php do_action( momtaz_format_hook( 'after_footer' ) ); ?>

</div> <!-- #footer-container -->