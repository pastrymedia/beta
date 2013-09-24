<?php
/**
 * Internationalization and translation functions.  Because ExMachina Core is a framework made up of various 
 * extensions with different textdomains, it must filter 'gettext' so that a single translation file can 
 * handle all translations.
 *
 * @package    ExMachinaCore
 * @subpackage Functions
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, Justin Tadlock
 * @link       http://themeexmachina.com/exmachina-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Filter the textdomain mofile to allow child themes to load the parent theme translation. */
add_filter( 'load_textdomain_mofile', 'exmachina_load_textdomain_mofile', 10, 2 );

/* Filter text strings for ExMachina Core and extensions so themes can serve up translations. */
add_filter( 'gettext',               'exmachina_gettext',                          1, 3 );
add_filter( 'gettext',               'exmachina_extensions_gettext',               1, 3 );
add_filter( 'gettext_with_context',  'exmachina_gettext_with_context',             1, 4 );
add_filter( 'gettext_with_context',  'exmachina_extensions_gettext_with_context',  1, 4 );
add_filter( 'ngettext',              'exmachina_ngettext',                         1, 5 );
add_filter( 'ngettext',              'exmachina_extensions_ngettext',              1, 5 );
add_filter( 'ngettext_with_context', 'exmachina_ngettext_with_context',            1, 6 );
add_filter( 'ngettext_with_context', 'exmachina_extensions_ngettext_with_context', 1, 6 );

/**
 * Checks if a textdomain's translation files have been loaded.  This function behaves differently from 
 * WordPress core's is_textdomain_loaded(), which will return true after any translation function is run over 
 * a text string with the given domain.  The purpose of this function is to simply check if the translation files 
 * are loaded.
 *
 * @since 1.3.0
 * @access private This is only used internally by the framework for checking translations.
 * @param string $domain The textdomain to check translations for.
 */
function exmachina_is_textdomain_loaded( $domain ) {
	global $exmachina;

	return ( isset( $exmachina->textdomain_loaded[ $domain ] ) && true === $exmachina->textdomain_loaded[ $domain ] ) ? true : false;
}

/**
 * Loads the framework's translation files.  The function first checks if the parent theme or child theme 
 * has the translation files housed in their '/languages' folder.  If not, it sets the translation file the the 
 * framework '/languages' folder.
 *
 * @since 1.3.0
 * @access private
 * @uses load_textdomain() Loads an MO file into the domain for the framework.
 * @param string $domain The name of the framework's textdomain.
 * @return true|false Whether the MO file was loaded.
 */
function exmachina_load_framework_textdomain( $domain ) {

	/* Get the WordPress installation's locale set by the user. */
	$locale = get_locale();

	/* Check if the mofile is located in parent/child theme /languages folder. */
	$mofile = locate_template( array( "languages/{$domain}-{$locale}.mo" ) );

	/* If no mofile was found in the parent/child theme, set it to the framework's mofile. */
	if ( empty( $mofile ) )
		$mofile = trailingslashit( EXMACHINA_LANGUAGES ) . "{$domain}-{$locale}.mo";

	return load_textdomain( $domain, $mofile );
}

/**
 * @since 0.7.0
 * @deprecated 1.3.0
 */
function exmachina_get_textdomain() {
	_deprecated_function( __FUNCTION__, '1.3.0', 'exmachina_get_parent_textdomain' );
	return exmachina_get_parent_textdomain();
}

/**
 * Gets the parent theme textdomain. This allows the framework to recognize the proper textdomain of the 
 * parent theme.
 *
 * Important! Do not use this for translation functions in your theme.  Hardcode your textdomain string.  Your 
 * theme's textdomain should match your theme's folder name.
 *
 * @since 1.3.0
 * @access private
 * @uses get_template() Defines the theme textdomain based on the template directory.
 * @global object $exmachina The global ExMachina object.
 * @return string $exmachina->textdomain The textdomain of the theme.
 */
function exmachina_get_parent_textdomain() {
	global $exmachina;

	/* If the global textdomain isn't set, define it. Plugin/theme authors may also define a custom textdomain. */
	if ( empty( $exmachina->parent_textdomain ) ) {

		$theme = wp_get_theme( get_template() );

		$textdomain = $theme->get( 'TextDomain' ) ? $theme->get( 'TextDomain' ) : get_template();

		$exmachina->parent_textdomain = sanitize_key( apply_filters( exmachina_get_prefix() . '_parent_textdomain', $textdomain ) );
	}

	/* Return the expected textdomain of the parent theme. */
	return $exmachina->parent_textdomain;
}

/**
 * Gets the child theme textdomain. This allows the framework to recognize the proper textdomain of the 
 * child theme.
 *
 * Important! Do not use this for translation functions in your theme.  Hardcode your textdomain string.  Your 
 * theme's textdomain should match your theme's folder name.
 *
 * @since 1.2.0
 * @access private
 * @uses get_stylesheet() Defines the child theme textdomain based on the stylesheet directory.
 * @global object $exmachina The global ExMachina object.
 * @return string $exmachina->child_theme_textdomain The textdomain of the child theme.
 */
function exmachina_get_child_textdomain() {
	global $exmachina;

	/* If a child theme isn't active, return an empty string. */
	if ( !is_child_theme() )
		return '';

	/* If the global textdomain isn't set, define it. Plugin/theme authors may also define a custom textdomain. */
	if ( empty( $exmachina->child_textdomain ) ) {

		$theme = wp_get_theme();

		$textdomain = $theme->get( 'TextDomain' ) ? $theme->get( 'TextDomain' ) : get_stylesheet();

		$exmachina->child_textdomain = sanitize_key( apply_filters( exmachina_get_prefix() . '_child_textdomain', $textdomain ) );
	}

	/* Return the expected textdomain of the child theme. */
	return $exmachina->child_textdomain;
}

/**
 * Filters the 'load_textdomain_mofile' filter hook so that we can change the directory and file name 
 * of the mofile for translations.  This allows child themes to have a folder called /languages with translations
 * of their parent theme so that the translations aren't lost on a parent theme upgrade.
 *
 * @since 1.3.0
 * @access private
 * @param string $mofile File name of the .mo file.
 * @param string $domain The textdomain currently being filtered.
 * @return $mofile
 */
function exmachina_load_textdomain_mofile( $mofile, $domain ) {

	/* If the $domain is for the parent or child theme, search for a $domain-$locale.mo file. */
	if ( $domain == exmachina_get_parent_textdomain() || $domain == exmachina_get_child_textdomain() ) {

		/* Check for a $domain-$locale.mo file in the parent and child theme root and /languages folder. */
		$locale = get_locale();
		$locate_mofile = locate_template( array( "languages/{$domain}-{$locale}.mo", "{$domain}-{$locale}.mo" ) );

		/* If a mofile was found based on the given format, set $mofile to that file name. */
		if ( !empty( $locate_mofile ) )
			$mofile = $locate_mofile;
	}

	/* Return the $mofile string. */
	return $mofile;
}

/**
 * Helper function for allowing the theme to translate the text strings for both ExMachina Core and the 
 * available framework extensions.
 *
 * @since  1.6.0
 * @access public
 * @param  string  $domain
 * @param  string  $text
 * @param  string  $context
 * @return string
 */
function exmachina_translate( $domain, $text, $context = null ) {

	$translations = get_translations_for_domain( $domain );

	return $translations->translate( $text, $context );
}

/**
 * Helper function for allowing the theme to translate the plural text strings for both ExMachina Core and 
 * the available framework extensions.
 *
 * @since  1.6.0
 * @access public
 * @param  string  $domain
 * @param  string  $single
 * @param  string  $plural
 * @param  int     $number
 * @param  string  $context
 * @return string
 */
function exmachina_translate_plural( $domain, $single, $plural, $number, $context = null ) {

	$translations = get_translations_for_domain( $domain );

	return $translations->translate_plural( $single, $plural, $number, $context );
}

/**
 * Filters 'gettext' to change the translations used for the 'exmachina-core' textdomain.
 *
 * @since  1.3.0
 * @access public
 * @param  string $translated The translated text.
 * @param  string $text       The original, untranslated text.
 * @param  string $domain     The textdomain for the text.
 * @return string
 */
function exmachina_gettext( $translated, $text, $domain ) {

	/* Check if 'exmachina-core' is the current textdomain, there's no mofile for it, and the theme has a mofile. */
	if ( 'exmachina-core' == $domain && !exmachina_is_textdomain_loaded( 'exmachina-core' ) && exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
		$translated = exmachina_translate( exmachina_get_parent_textdomain(), $text );

	return $translated;
}

/**
 * Filters 'gettext_with_context' to change the translations used for the 'exmachina-core' textdomain.
 *
 * @since  1.6.0
 * @access public
 * @param  string $translated The translated text.
 * @param  string $text       The original, untranslated text.
 * @param  string $context    The context of the text.
 * @param  string $domain     The textdomain for the text.
 * @return string
 */
function exmachina_gettext_with_context( $translated, $text, $context, $domain ) {

	/* Check if 'exmachina-core' is the current textdomain, there's no mofile for it, and the theme has a mofile. */
	if ( 'exmachina-core' == $domain && !exmachina_is_textdomain_loaded( 'exmachina-core' ) && exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
		$translated = exmachina_translate( exmachina_get_parent_textdomain(), $text, $context );

	return $translated;
}

/**
 * Filters 'ngettext' to change the translations used for the 'exmachina-core' textdomain.
 *
 * @since  1.6.0
 * @access public
 * @param  string $translated The translated text.
 * @param  string $single     The singular form of the untranslated text.
 * @param  string $plural     The plural form of the untranslated text.
 * @param  int    $number     The number to use to base whether something is singular or plural.
 * @param  string $domain     The textdomain for the text.
 * @return string
 */
function exmachina_ngettext( $translated, $single, $plural, $number, $domain ) {

	/* Check if 'exmachina-core' is the current textdomain, there's no mofile for it, and the theme has a mofile. */
	if ( 'exmachina-core' == $domain && !exmachina_is_textdomain_loaded( 'exmachina-core' ) && exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
		$translated = exmachina_translate_plural( exmachina_get_parent_textdomain(), $single, $plural, $number );

	return $translated;
}

/**
 * Filters 'ngettext_with_context' to change the translations used for the 'exmachina-core' textdomain.
 *
 * @since  1.6.0
 * @access public
 * @param  string $translated The translated text.
 * @param  string $single     The singular form of the untranslated text.
 * @param  string $plural     The plural form of the untranslated text.
 * @param  int    $number     The number to use to base whether something is singular or plural.
 * @param  string $context    The context of the text.
 * @param  string $domain     The textdomain for the text.
 * @return string
 */
function exmachina_ngettext_with_context( $translated, $single, $plural, $number, $context, $domain ) {

	/* Check if 'exmachina-core' is the current textdomain, there's no mofile for it, and the theme has a mofile. */
	if ( 'exmachina-core' == $domain && !exmachina_is_textdomain_loaded( 'exmachina-core' ) && exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
		$translated = exmachina_translate_plural( exmachina_get_parent_textdomain(), $single, $plural, $number, $context );

	return $translated;
}

/**
 * Filters 'gettext_with_context' to change the translations used for each of the extensions' textdomains.
 *
 * @since  1.3.0
 * @access public
 * @param  string $translated The translated text.
 * @param  string $text       The untranslated text.
 * @param  string $domain     The textdomain for the text.
 * @return string
 */
function exmachina_extensions_gettext( $translated, $text, $domain ) {

	$extensions = array( 'breadcrumb-trail', 'custom-field-series', 'featured-header', 'post-stylesheets', 'theme-fonts', 'theme-layouts' );

	/* Check if the current textdomain matches one of the framework extensions. */
	if ( in_array( $domain, $extensions ) && current_theme_supports( $domain ) ) {

		/* If the framework mofile is loaded, use its translations. */
		if ( exmachina_is_textdomain_loaded( 'exmachina-core' ) )
			$translated = exmachina_translate( 'exmachina-core', $text );

		/* If the theme mofile is loaded, use its translations. */
		elseif ( exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
			$translated = exmachina_translate( exmachina_get_parent_textdomain(), $text );
	}

	return $translated;
}

/**
 * Filters 'gettext_with_context' to change the translations used for each of the extensions' textdomains.
 *
 * @since  1.6.0
 * @access public
 * @param  string $translated The translated text.
 * @param  string $text       The untranslated text.
 * @param  string $context    The context of the text.
 * @param  string $domain     The textdomain for the text.
 * @return string
 */
function exmachina_extensions_gettext_with_context( $translated, $text, $context, $domain ) {

	$extensions = array( 'breadcrumb-trail', 'custom-field-series', 'featured-header', 'post-stylesheets', 'theme-fonts', 'theme-layouts' );

	/* Check if the current textdomain matches one of the framework extensions. */
	if ( in_array( $domain, $extensions ) && current_theme_supports( $domain ) ) {

		/* If the framework mofile is loaded, use its translations. */
		if ( exmachina_is_textdomain_loaded( 'exmachina-core' ) )
			$translated = exmachina_translate( 'exmachina-core', $text, $context );

		/* If the theme mofile is loaded, use its translations. */
		elseif ( exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
			$translated = exmachina_translate( exmachina_get_parent_textdomain(), $text, $context );
	}

	return $translated;
}

/**
 * Filters 'ngettext' to change the translations used for each of the extensions' textdomains.
 *
 * @since  1.6.0
 * @access public
 * @param  string $translated The translated text.
 * @param  string $single     The singular form of the untranslated text.
 * @param  string $plural     The plural form of the untranslated text.
 * @param  int    $number     The number to use to base whether something is singular or plural.
 * @param  string $domain     The textdomain for the text.
 * @return string
 */
function exmachina_extensions_ngettext( $translated, $single, $plural, $number, $domain ) {

	$extensions = array( 'breadcrumb-trail', 'custom-field-series', 'featured-header', 'post-stylesheets', 'theme-fonts', 'theme-layouts' );

	/* Check if the current textdomain matches one of the framework extensions. */
	if ( in_array( $domain, $extensions ) && current_theme_supports( $domain ) ) {

		/* If the framework mofile is loaded, use its translations. */
		if ( exmachina_is_textdomain_loaded( 'exmachina-core' ) )
			$translated = exmachina_translate_plural( 'exmachina-core', $single, $plural, $number );

		/* If the theme mofile is loaded, use its translations. */
		elseif ( exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
			$translated = exmachina_translate_plural( exmachina_get_parent_textdomain(), $single, $plural, $number );
	}

	return $translated;
}

/**
 * Filters 'ngettext_with_context' to change the translations used for each of the extensions' textdomains.
 *
 * @since  1.6.0
 * @access public
 * @param  string $translated The translated text.
 * @param  string $single     The singular form of the untranslated text.
 * @param  string $plural     The plural form of the untranslated text.
 * @param  int    $number     The number to use to base whether something is singular or plural.
 * @param  string $context    The context of the text.
 * @param  string $domain     The textdomain for the text.
 * @return string
 */
function exmachina_extensions_ngettext_with_context( $translated, $single, $plural, $number, $context, $domain ) {

	$extensions = array( 'breadcrumb-trail', 'custom-field-series', 'featured-header', 'post-stylesheets', 'theme-fonts', 'theme-layouts' );

	/* Check if the current textdomain matches one of the framework extensions. */
	if ( in_array( $domain, $extensions ) && current_theme_supports( $domain ) ) {

		/* If the framework mofile is loaded, use its translations. */
		if ( exmachina_is_textdomain_loaded( 'exmachina-core' ) )
			$translated = exmachina_translate_plural( 'exmachina-core', $single, $plural, $number, $context );

		/* If the theme mofile is loaded, use its translations. */
		elseif ( exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
			$translated = exmachina_translate_plural( exmachina_get_parent_textdomain(), $single, $plural, $number, $context );
	}

	return $translated;
}

?>