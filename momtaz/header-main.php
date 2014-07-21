<?php
/**
 * Main Header Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.3
 */
?>
<div id="header-container">

	<?php do_action( momtaz_format_hook( 'before_header' ) ); ?>

	<?php momtaz_struct_markup( 'header', '<header%atts%>' ); ?>

		<hgroup id="branding">

			<h1 id="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</h1> <!-- #site-title -->

			<h2 id="site-description">
				<?php bloginfo( 'description' ); ?>
			</h2> <!-- #site-description -->

		</hgroup> <!-- #branding -->

	<?php momtaz_struct_markup( 'header', '</header> <!-- #header -->' ); ?>

	<?php do_action( momtaz_format_hook( 'after_header' ) ); ?>

	<?php momtaz_template_part( 'menu', 'primary' ); ?>

</div> <!-- #header-container -->