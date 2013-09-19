<?php
/**
 * Header Template
 *
 * The header template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the top of the file.
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */
?>
<div id="header-container">

	<?php do_action( momtaz_format_hook( 'before_header' ) ); ?>

	<header id="header" role="banner">

		<hgroup id="branding">

			<h1 id="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</h1> <!-- #site-title -->

			<h2 id="site-description">
				<?php bloginfo( 'description' ); ?>
			</h2> <!-- #site-description -->

		</hgroup> <!-- #branding -->

	</header> <!-- #header -->

	<?php do_action( momtaz_format_hook( 'after_header' ) ); ?>

	<?php momtaz_template_part( 'menu', 'primary' ); ?>

</div> <!-- #header-container -->