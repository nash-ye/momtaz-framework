<?php
/*
 * Singular Entry Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.1
 */

global $post; ?>

<?php do_action( momtaz_format_hook( 'before_entry' ) ); ?>

<?php momtaz_struct_markup( 'entry', '<article%atts%>' ) ?>

	<?php momtaz_template_part( 'entry-header' ); ?>

	<div class="entry-body">

		<?php do_action( momtaz_format_hook( 'before_entry_content' ) ); ?>

			<div class="entry-content">

				<?php if ( momtaz_is_single( get_the_ID(), 'attachment' ) ) : ?>

						<?php if ( wp_attachment_is_image() ) { ?>

								<div class="entry-attachment">

									<div class="attachment attachment-image">

										<figure class="wp-caption">

											<a href="<?php echo esc_url( wp_get_attachment_url() ); ?>" rel="attachment">
												<?php echo wp_get_attachment_image( get_the_ID(), apply_filters( 'momtaz_attachment_image_size', array( momtaz_get_content_width(), 1024 ) ), false, array( 'class' => 'aligncenter' ) ); ?>
											</a>

											<figcaption class="wp-caption-text">
												<?php if ( ! empty( $post->post_excerpt ) ) : ?>
													<?php the_excerpt(); ?>
												<?php else: ?>
													<?php the_title(); ?>
												<?php endif; ?>
											</figcaption>

										</figure>

									</div> <!-- .attachment -->

								</div> <!-- .entry-attachment -->

								<?php if ( ! empty( $post->post_content ) ) : ?>

										<div class="entry-description">
											<?php echo $post->post_content; ?>
										</div><!-- .entry-description -->

								<?php endif; ?>

						<?php } else { ?>

								<?php the_content();  ?>

								<div class="download">
									<a href="<?php echo esc_url( wp_get_attachment_url() ); ?>" type="<?php echo esc_attr( get_post_mime_type() ); ?>"><?php printf( __( 'Download &quot;%s&quot;', 'momtaz' ), the_title( '<span class="fn">', '</span>', false ) ); ?></a>
								</div> <!-- .download -->

						<?php } ?>

				<?php else: ?>

						<?php the_content();  ?>

				<?php endif; ?>

			</div> <!-- .entry-content -->

		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'momtaz' ) . '</span>', 'after' => '</div>' ) ); ?>

		<?php do_action( momtaz_format_hook( 'after_entry_content' ) ); ?>

	</div> <!-- .entry-body -->

	<?php momtaz_template_part( 'entry-footer' ); ?>

<?php momtaz_struct_markup( 'entry', '</article> <!-- .hentry -->' ) ?>

<?php do_action( momtaz_format_hook( 'after_entry' ) ); ?>

<?php comments_template( '/comments.php', true );