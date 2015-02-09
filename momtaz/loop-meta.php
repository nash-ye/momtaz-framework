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

	<header class="loop-header hidden">
		<h1 class="loop-title"><?php echo get_post_field( 'post_title', get_queried_object_id() ) ?></h1>
	</header> <!-- .loop-header -->

<?php elseif ( is_category() ) : ?>

	<header class="loop-header">

		<h1 class="loop-title"><?php single_cat_title() ?></h1>

		<p class="loop-description">
		   <?php echo term_description() ?>
		</p> <!-- .loop-description -->

	</header> <!-- .loop-header -->

<?php elseif ( is_tag() ) : ?>

	<header class="loop-header">

		<h1 class="loop-title"><?php single_tag_title() ?></h1>

		<p class="loop-description">
		   <?php echo term_description() ?>
		</p> <!-- .loop-description -->

	</header> <!-- .loop-header -->

<?php elseif ( is_tax() ) : ?>

	<header class="loop-header">

		<h1 class="loop-title"><?php single_term_title() ?></h1>

		<p class="loop-description">
			<?php echo term_description() ?>
		</p> <!-- .loop-description -->

	</header> <!-- .loop-header -->

<?php elseif ( is_day() ) : ?>

	<header class="loop-header">
		<h1 class="loop-title"><?php the_date( __( 'j F, Y', 'momtaz' ) ) ?></h1>
	</header> <!-- .loop-header -->

<?php elseif ( is_month() ) : ?>

	<header class="loop-header">
		<h1 class="loop-title"><?php the_date( __( 'F Y', 'momtaz' ) ) ?></h1>
	</header> <!-- .loop-header -->

<?php elseif ( is_year() ) : ?>

	<header class="loop-header">
		<h1 class="loop-title"><?php the_date( __( 'Y', 'momtaz' ) ) ?></h1>
	</header> <!-- .loop-header -->

<?php elseif ( is_author() ) : ?>

	<?php

		$author_id = (int) get_query_var( 'author' );

	?>

	<header id="hcard-<?php echo esc_attr( $author_id ) ?>" class="vcard loop-header user-info author-info">

		<h1 class="loop-title fn n user-name author-name"><?php the_author_meta( 'display_name', $author_id ) ?></h1>

		<?php if ( get_the_author_meta( 'description', $author_id ) ) : ?>

			<div class="user-bio author-bio">
				<?php echo get_avatar( get_the_author_meta( 'user_email', $author_id ), 100 ) ?>

				<div class="user-description author-description">
					<?php the_author_meta( 'description', $author_id ) ?>
				</div> <!-- .author-description -->
			</div> <!-- .author-bio -->

		<?php endif ?>

	</header> <!-- .author-info -->

<?php elseif ( is_search() ) : ?>

	<header class="loop-header">
		<h1 class="loop-title"><?php printf( __( 'Search Results for: %s', 'momtaz' ), get_search_query() ) ?></h1>
	</header> <!-- .loop-header -->

<?php elseif ( is_post_type_archive() ) : ?>

	<header class="loop-header">
		<h1 class="loop-title"><?php post_type_archive_title() ?></h1>
	</header> <!-- .loop-header -->

<?php elseif ( is_archive() ) : ?>

	<header class="loop-header">
		<h1 class="loop-title"><?php _e( 'Archives', 'momtaz' ) ?></h1>
	</header> <!-- .loop-header -->

<?php endif;