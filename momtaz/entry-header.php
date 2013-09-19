<?php
/**
 * Entry Header Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */
?>
<header class="entry-header">

	<?php if ( momtaz_is_single( get_the_ID() ) ) : ?>

		<h1 class="entry-title"><?php the_title(); ?></h1>

	<?php else: ?>

		<h1 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>

	<?php endif; ?>

	<div class="entry-meta">

			<span class="entry-date">

				<?php

					printf( __( '<span class="prep entry-utility-prep">On:</span> %s', 'momtaz' ),
						sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="published" datetime="%3$s">%4$s</time></a>',
							esc_url( get_permalink() ),
							esc_attr( get_the_time() ),
							esc_attr( get_the_date( 'c' ) ),
							esc_html( get_the_date() )
						) // sprintf()
					);

					$show_sep = true;

				?>

			</span> <!-- .entry-date -->

			<?php if ( 'post' === get_post_type() && is_multi_author() ) : ?>

				<span class="sep"><?php _ex( ' | ', 'entry-meta-sep', 'momtaz' ); ?></span>

				<span class="by-author">

					<?php

						printf( __( '<span class="prep entry-utility-prep">By:</span> %s', 'momtaz' ),
							sprintf( '<span class="vcard author"><a class="url fn n" href="%1$s" rel="author">%2$s</a></span>',
								esc_url( get_author_posts_url ( get_the_author_meta( 'ID' ) ) ) ,
								esc_html( get_the_author() )
							) // sprintf()
						);

						$show_sep = true;

					?>

				</span> <!-- .by-author -->

			<?php endif; ?>

			<?php if ( ! momtaz_is_single( get_the_ID() ) && post_type_supports( get_post_type(), 'comments' ) ) : ?>

					<?php if ( $show_sep ) { echo '<span class="sep">'. _x( ' | ', 'entry-meta-sep', 'momtaz' ) .'</span>'; } ?>

					<span class="comments-link">
						<?php comments_popup_link( '<span class="leave-reply">'. __( 'Leave a comment', 'momtaz' ) .'</span>' , __( '1 Comment', 'momtaz' ), __( '<b>%</b> Comments', 'momtaz' ) ); ?>
					</span> <!-- .comments-links -->

			<?php endif; ?>

			<?php edit_post_link( __( 'Edit', 'momtaz' ), '<span class="edit-link">', '</span>' ); ?>

	</div> <!-- .entry-meta -->

</header> <!-- .entry-header -->