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

if ( is_active_sidebar( 'primary' ) ) : ?>

    <div id="sidebar" role="complementary">

        <?php do_action( momtaz_format_hook( 'before_primary_sidebar' ) ); ?>

        <div id="primary-sidebar" class="widget-area">
                <?php dynamic_sidebar( 'primary' ); ?>
        </div> <!-- #primary-sidebar -->

        <?php do_action( momtaz_format_hook( 'after_primary_sidebar' ) ); ?>

    </div> <!-- #sidebar -->

<?php endif;