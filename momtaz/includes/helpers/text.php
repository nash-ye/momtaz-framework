<?php

/**
 * Function for formatting a hook name if needed. It automatically adds the
 * theme's prefix to begining of the hook name.
 *
 * @param string $tag The basic name of the hook.
 * @return string
 * @since 1.0
 */
function momtaz_format_hook( $tag ) {
	return THEME_PREFIX . "_{$tag}";
}

/**
 * Limit the text characters.
 *
 * @param mixed Text to be evaluated
 * @param int Maximum number of characters to be viewed
 * @return string
 * @since 1.3
 */
function momtaz_limit_chars( $text, $limit ) {

	if ( mb_strlen( $text ) > $limit ) {
		$text = mb_substr( $text, 0, $limit );
	}

	return $text;

}