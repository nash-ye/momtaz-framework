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

if ( has_nav_menu( 'primary' ) ) : ?>

    <?php do_action( momtaz_format_hook( 'before_primary_menu' ) ); ?>

    <nav id="primary-menu" class="menu-container" role="navigation">
        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu dropdown' ) ); ?>
    </nav> <!-- #primary-menu -->

    <?php do_action( momtaz_format_hook( 'after_primary_menu' ) ); ?>

<?php endif;