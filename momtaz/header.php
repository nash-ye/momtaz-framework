<?php
/**
 * Header Template
 *
 * @package Momtaz
 * @subpackage Template
 * @since Momtaz Theme 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes( 'html' ) ?>>
<head>
<!-- Momtaz Head -->
<meta charset="<?php bloginfo( 'charset' ) ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?php momtaz_title() ?></title>

<meta name="viewport" content="width=device-width,initial-scale=1" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ) ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />

<!--[if lt IE 9]>
	<script src="<?php echo esc_url( momtaz_theme_uri( 'content/scripts/html5shiv.js' ) ) ?>"></script>
<![endif]-->

<?php Momtaz_Zones::call( 'head' ) ?>
<!-- end Momtaz Head -->

<!-- WP Head -->
<?php wp_head() ?>
<!-- end WP Head -->
</head>

<body<?php momtaz_atts( 'body' ) ?>>