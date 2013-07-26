<?php
/**
 * Default Structure Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

momtaz_template_part( 'head' ); ?>

<body <?php body_class(); ?>>

    <?php do_action( momtaz_format_hook( 'before_wrapper' ) ); ?>

    <div id="wrapper" class="hfeed">

        <?php get_header(); ?>

        <?php do_action( momtaz_format_hook( 'before_container' ) ); ?>

        <div id="container">

            <div id="content" role="main">

                <?php do_action( momtaz_format_hook( 'before_primary_content' ) ); ?>

                <section id="primary-content">

                    <?php do_action( momtaz_format_hook( 'primary_content' ) ); ?>

                </section><!-- #primary-content -->

                <?php do_action( momtaz_format_hook( 'after_primary_content' ) ); ?>

            </div> <!-- #content -->

            <?php get_sidebar(); ?>

         </div> <!-- #container -->

         <?php do_action( momtaz_format_hook( 'after_container' ) ); ?>

         <?php get_footer(); ?>

    </div> <!-- #wrapper -->

    <?php do_action( momtaz_format_hook( 'after_wrapper' ) ); ?>

<?php wp_footer(); ?>
</body> <!-- body -->
</html> <!-- html -->