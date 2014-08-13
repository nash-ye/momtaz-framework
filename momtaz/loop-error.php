<?php
/**
 * Posts Loop Error Template
 *
 * Displays an error message when no posts are found.
 *
 * @package Momtaz
 * @subpackage Template
 */

 if ( ! is_search() ) : ?>

	<article<?php momtaz_atts( 'entry', array( 'id' => 'post-0', 'class' => 'hentry loop-error no-results not-found no-archive-data' ) ) ?>>

		<header<?php momtaz_atts( 'entry-header', array( 'class' => 'entry-header' ) ) ?>>
			<h1<?php momtaz_atts( 'entry-title', array( 'class' => 'entry-title' ) ) ?>><?php _e( 'Nothing Found', 'momtaz' ) ?></h1>
		</header>

		<div<?php momtaz_atts( 'entry-content', array( 'class' => 'entry-content' ) ) ?>>
			<p class='no-data'><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'momtaz')  ?></p>
			<?php get_search_form() ?>
		</div>

	</article><?php

else : ?>

	<article<?php momtaz_atts( 'entry', array( 'id' => 'post-0', 'class' => 'hentry loop-error no-results not-found no-search-data' ) ) ?>>

		<header<?php momtaz_atts( 'entry-header', array( 'class' => 'entry-header' ) ) ?>>
			<h1<?php momtaz_atts( 'entry-title', array( 'class' => 'entry-title' ) ) ?>><?php _e( 'Nothing Found', 'momtaz' ) ?></h1>
		</header>

		<div<?php momtaz_atts( 'entry-content', array( 'class' => 'entry-content' ) ) ?>>
			<p class='no-data'><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'momtaz') ?></p>
			<?php get_search_form() ?>
		</div><!-- .entry-content -->

	</article><?php

endif;