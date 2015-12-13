<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

Momtaz_Zones::add_callback( 'content', 'momtaz_template_content' );

/**
 * Outputs the current template content.
 *
 * @return void
 * @since 1.3
 */
function momtaz_template_content() { ?>

	<section id="primary-content">

		<article<?php momtaz_atts( 'entry', array( 'id' => 'post-0', 'class' => 'hentry not-found error-404' ) ) ?>>

			<header<?php momtaz_atts( 'entry-header', array( 'class' => 'entry-header' ) ) ?>>
				<h1<?php momtaz_atts( 'entry-title', array( 'class' => 'entry-title' ) ) ?>><?php esc_html_e( 'Not found', 'momtaz' ) ?></h1>
			</header>

			<div<?php momtaz_atts( 'entry-content', array( 'class' => 'entry-content' ) ) ?>>
				<p><?php esc_html_e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'momtaz' ) ?></p>
				<?php get_search_form() ?>
			</div>

		</article>

	</section> <!-- #primary-content --><?php

}

// Load the structure template.
momtaz_context_template( 'structure' );
