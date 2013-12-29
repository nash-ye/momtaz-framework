<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.
 * This is not shown on the front page and singular views.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */

if ( is_singular() ) {
	return;
}

if ( is_home() && ! is_front_page() ) : ?>

	<?php global $wp_query; ?>

	<header class="loop-header hidden">
		<h1 class="loop-title"><?php echo get_post_field( 'post_title', $wp_query->get_queried_object_id() ); ?></h1>
	</header> <!-- .loop-header -->

<?php elseif ( is_category() ) : ?>

	<header class="loop-header">

		<h1 class="loop-title"><?php single_cat_title(); ?></h1>

		<p class="loop-description">
		   <?php echo category_description(); ?>
		</p> <!-- .loop-description -->

	</header> <!-- .loop-header -->

<?php elseif ( is_tag() ) : ?>

	<header class="loop-header">

		<h1 class="loop-title"><?php single_tag_title(); ?></h1>

		<p class="loop-description">
		   <?php echo tag_description(); ?>
		</p> <!-- .loop-description -->

	</header> <!-- .loop-header -->

<?php elseif ( is_tax() ) : ?>

	<header class="loop-header">

		<h1 class="loop-title"><?php single_term_title(); ?></h1>

		<p class="loop-description">
			<?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?>
		</p> <!-- .loop-description -->

	</header> <!-- .loop-header -->

<?php elseif ( is_date() ) : ?>

	<header class="loop-header">

		<h1 class="loop-title">

			<time><?php

				if ( is_day() ) {
					the_time( __( 'j F, Y', 'momtaz' ) );

				} elseif ( is_month() ) {
					the_time( __( 'F Y', 'momtaz' ) );

				} elseif ( is_year() ) {
					the_time( __( 'Y', 'momtaz' ) );

				} // end if

			?></time>

		</h1>

		<p class="loop-description">
			<?php _e( 'You are browsing the site archives by date.', 'momtaz' ); ?>
		</p> <!-- .loop-description -->

	</header> <!-- .loop-header -->

<?php elseif ( is_author() ) : $id = get_query_var( 'author' ); ?>

	<header id="hcard-<?php echo esc_attr( $id ); ?>" class="vcard loop-header user-info author-info">

		<h1 class="loop-title fn n user-title author-title">
			<?php the_author_meta( 'display_name', $id ); ?>
		</h1>

		<?php if ( get_the_author_meta( 'description', $id ) ) : ?>

			<div class="user-bio author-bio">
				<?php echo get_avatar( get_the_author_meta( 'user_email', $id ), 100 ); ?>

				<div class="user-description author-description">
					<?php the_author_meta( 'description', $id ); ?>
				</div> <!-- .author-description -->
			</div> <!-- .author-bio -->

		<?php endif; ?>

	</header> <!-- .author-info -->

<?php elseif ( is_search() ) : ?>

	<header class="loop-header">

		<h1 class="loop-title"><?php echo esc_html( get_search_query() ); ?></h1>

		<p class="loop-description">
			<?php printf( __( 'You are browsing the search results for &quot;%1$s&quot;', 'momtaz' ), esc_attr( get_search_query() ) ); ?>
		</p> <!-- .loop-description -->

	</header> <!-- .loop-header -->

<?php elseif ( is_post_type_archive() ) :

	// Get current post-type.
	$post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>

	<header class="loop-header">

		<h1 class="loop-title"><?php post_type_archive_title(); ?></h1>

		<?php if ( ! empty( $post_type->description ) ) : ?>

			<p class="loop-description">
				<?php echo $post_type->description; ?>
			</p> <!-- .loop-description -->

		<?php endif; ?>

	</header> <!-- .loop-header -->

<?php elseif ( is_archive() ) : ?>

	<header class="loop-header">
		<h1 class="loop-title"><?php _e( 'Archives', 'momtaz' ); ?></h1>
	</header> <!-- .loop-header -->

<?php endif;