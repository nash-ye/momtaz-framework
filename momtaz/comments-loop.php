<?php
/**
 * Comments Loop Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

global $wp_query;

if ( have_comments() ) :

	if ( ! empty( $wp_query->comments ) ) : ?>

		<section id="comments-container" class="comments-container">

			<header id="comments-header" class="comments-header">
				<h2 id="comments-number" class="comments-title">
					<?php printf( __( 'Comments (%d)', 'momtaz' ), get_comments_number() ) ?>
				</h2> <!-- #comments-number -->
			</header> <!-- .comments-header -->

			 <?php Momtaz_Zones::call( 'comments_list:before' ) ?>

			 <ol id="comment-list" class="comment-list">
				 <?php wp_list_comments( array( 'callback' => 'momtaz_comments_callback', 'type' => ( isset( $wp_query->comments_by_type ) ) ? 'comment' : 'all', 'avatar_size' => 80 ) ) ?>
			 </ol> <!-- #comment-list -->

			 <?php Momtaz_Zones::call( 'comments_list:after' ) ?>

			 <?php momtaz_template_part( 'comments-nav' ) ?>

		</section> <!-- #comments-container -->

	<?php endif;

	if ( ! empty( $wp_query->comments_by_type['pings'] ) ) :

		// Get the pings count.
		$pings_count = count( $wp_query->comments_by_type['pings'] ) ?>

		<section id="pings-container" class="comments-container">

			<header id="pings-header" class="comments-header">
				<h2 id="pings-number" class="comments-title">
					<?php printf( __( 'Pingbacks and Trackbacks (%d)', 'momtaz' ), number_format_i18n( $pings_count ) ) ?>
				</h2> <!-- #pings-number -->
			</header> <!-- #pings-header -->

			 <?php Momtaz_Zones::call( 'pings_list:before' ) ?>

			 <ol id="ping-list" class="comment-list">
				<?php wp_list_comments( array( 'callback' => 'momtaz_comments_callback', 'type' => 'pings' ) ) ?>
			 </ol> <!-- #ping-list -->

			 <?php Momtaz_Zones::call( 'pings_list:after' ) ?>

		</section> <!-- #pings-container -->

	<?php endif;

endif; // end have_comments()

if ( ! comments_open() && get_comments_number() > 0 ) : ?>

	<p class="comments-closed">
		<?php esc_html_e( 'Comments are closed.', 'momtaz' ) ?>
	</p> <!-- .comments-closed -->

<?php endif;
