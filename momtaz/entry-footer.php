<?php
/**
 * Entry Footer Template
 *
 * This template must be included in <article> tag .
 *
 * @package Momtaz
 * @subpackage Template
 */


if ( in_array( get_post_type(), array( 'page', 'attachment' ) ) )
    return;

?>

<footer class="entry-meta entry-footer">

    <?php

        /*** Post Categories List *********************************************/

        $post_category = get_the_category_list( _x( ' , ', 'categories-list', THEME_TEXTDOMAIN ) );

        if ( $post_category ) { ?>

            <span class="category-links">

                <?php

                    printf( __( '<span class="prep entry-utility-prep">Categories:</span> %s', THEME_TEXTDOMAIN ),
                            $post_category
                         );

                    $show_sep = true;

                ?>

            </span> <!-- .category-links -->

        <?php } // end if


        /*** Post Tags List ***************************************************/

        $post_tags = get_the_tag_list( '', _x( ' , ', 'tags-list', THEME_TEXTDOMAIN ) );

        if ( $post_tags ) {

            if ( isset( $show_sep ) && $show_sep )
                echo '<span class="sep">' . _x( ' | ', 'entry-meta-sep', THEME_TEXTDOMAIN ) . '</span>'; ?>

            <span class="tag-links">

                <?php

                    printf( __( '<span class="prep entry-utility-prep">Tags:</span> %s', THEME_TEXTDOMAIN ),
                            $post_tags
                         );

                    $show_sep = true;

                ?>

            </span> <!-- .tag-links -->

        <?php } // end if

    ?>

</footer> <!-- .entry-footer -->