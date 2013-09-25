<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Internationalization Functions
 *
 * i18n.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Internationalization and translation functions. Because ExMachina Core is a
 * framework made up of various extensions with different textdomains, it must
 * filter 'gettext' so that a single translation file can handle all translations.
 *
 * @package     ExMachina
 * @subpackage  Framework
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com
 */
###############################################################################
# Begin functions
###############################################################################

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
 * Textdomain Check
 *
 * Checks if a textdomain's translation files have been loaded. This function
 * behaves differently from WordPress core's is_textdomain_loaded(), which will
 * return true after any translation function is run over a text string with the
 * given domain.  The purpose of this function is to simply check if the translation
 * files are loaded.
 *
 * @since 0.1.1
 * @access private
 *
 * @global $exmachina The global ExMachina object.
 * @param  string $domain The textdomain to check translations for.
 * @return true|false     If textdomain is loaded.
 */
function exmachina_is_textdomain_loaded( $domain ) {
  global $exmachina;

  return ( isset( $exmachina->textdomain_loaded[ $domain ] ) && true === $exmachina->textdomain_loaded[ $domain ] ) ? true : false;

} // end function exmachina_is_textdomain_loaded()

/**
 * Load Framework Textdomain
 *
 * Loads the framework's translation files. The function first checks if the
 * parent theme or child theme has the translation files housed in their
 * '/languages' folder. If not, it sets the translation file the the framework
 * '/languages' folder.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_locale
 * @link http://codex.wordpress.org/Function_Reference/locate_template
 * @link http://codex.wordpress.org/Function_Reference/load_textdomain
 *
 * @uses EXMACHINA_LANGUAGES Path to the framework '/languages' folder.
 *
 * @since 0.1.1
 * @access private
 *
 * @param  string $domain The framework's textdomain.
 * @return true|false     If the MO file is loaded.
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

} // end function exmachina_load_framework_textdomain()

/**
 * Get Parent Textdomain
 *
 * Gets the parent theme textdomain. This allows for the framework to recognize
 * the proper textdomain for the parent theme.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_get_theme
 * @link http://codex.wordpress.org/Function_Reference/get_template
 *
 * @uses exmachina_get_prefix() Gets the theme prefix.
 *
 * @since 0.1.1
 * @access private
 *
 * @global object $exmachina             The global ExMachina object.
 * @return string $exmachina->textdomain The textdomain of the theme.
 */
function exmachina_get_parent_textdomain() {
  global $exmachina;

  /* If the global textdomain isn't set, define it. Plugin/theme authors may also define a custom textdomain. */
  if ( empty( $exmachina->parent_textdomain ) ) {

    $exmachina->parent_textdomain = sanitize_key( apply_filters( exmachina_get_prefix() . '_parent_textdomain', get_template() ) );
  }

  /* Return the expected textdomain of the parent theme. */
  return $exmachina->parent_textdomain;

} // end function exmachina_get_parent_textdomain()

/**
 * Get Child Textdomain
 *
 * Gets the child theme textdomain. This allows the framework to recognize the
 * proper textdomain of the child theme.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_get_theme
 * @link http://codex.wordpress.org/Function_Reference/get_stylesheet
 *
 * @uses exmachina_get_prefix() Gets the theme prefix.
 *
 * @since 0.1.1
 * @access private
 *
 * @global object $exmachina                    The global ExMachina object.
 * @return string $exmachina->child_textdomain  The textdomain of the child theme.
 */
function exmachina_get_child_textdomain() {
  global $exmachina;

  /* If a child theme isn't active, return an empty string. */
  if ( !is_child_theme() )
    return '';

  /* If the global textdomain isn't set, define it. Plugin/theme authors may also define a custom textdomain. */
  if ( empty( $exmachina->child_textdomain ) ) {

    $exmachina->child_textdomain = sanitize_key( apply_filters( exmachina_get_prefix() . '_child_textdomain', get_stylesheet() ) );
  }

  /* Return the expected textdomain of the child theme. */
  return $exmachina->child_textdomain;

} // end function exmachina_get_child_textdomain()

/**
 * Load Textdomain MO File
 *
 * Filters the 'load_textdomain_mofile' filter hook so that we can change the
 * directory and file name of the mofile for translations. This allows child
 * themes to have a folder called /languages with translations of their parent
 * theme so that the translations aren't lost on a parent theme upgrade.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_locale
 * @link http://codex.wordpress.org/Function_Reference/locate_template
 *
 * @uses exmachina_get_parent_textdomain() Returns the parent textdomain.
 * @uses exmachina_get_child_textdomain()  Returns the child textdomain.
 *
 * @since 0.1.1
 * @access private
 *
 * @param  string $mofile File name of the .mo file.
 * @param  string $domain The textdomain currently being filtered.
 * @return string         Returns the $mofile string.
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

} // end function exmachina_load_textdomain_mofile()

/**
 * Translation Helper
 *
 * Helper function for allowing the theme to translate the text strings for
 * both ExMachina Core and the available framework extensions.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $domain  The textdomain to be translated.
 * @param  string $text    The text to translate.
 * @param  string $context Optional. The context for the translation.
 * @return string          The translated string.
 */
function exmachina_translate( $domain, $text, $context = null ) {

  $translations = get_translations_for_domain( $domain );

  return $translations->translate( $text, $context );

} // end function exmachina_translate()

/**
 * Plural Translation Helper
 *
 * Helper function for allowing the theme to translate the plural text strings
 * for both ExMachina Core and the available framework extensions.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $domain  The textdomain to be translated.
 * @param  string $single  The singlular form of the translation.
 * @param  string $plural  The plural form of the translation.
 * @param  int    $number  The number to base whether smething is singular or plural.
 * @param  string $context Optional. The context for the translation.
 * @return string          The translated string.
 */
function exmachina_translate_plural( $domain, $single, $plural, $number, $context = null ) {

  $translations = get_translations_for_domain( $domain );

  return $translations->translate_plural( $single, $plural, $number, $context );

} // end function exmachina_translate_plural()

/**
 * Gettext Filter
 *
 * Filters 'gettext' to change the translations used for the 'exmachina-core'
 * textdomain.
 *
 * @uses exmachina_is_textdomain_loaded()  Checks if textdomain is loaded.
 * @uses exmachina_get_parent_textdomain() Gets the parent textdomain.
 * @uses exmachina_translate()             Translation helper function.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $translated The translated text.
 * @param  string $text       The original, untranslated text.
 * @param  string $domain     The textdomain for the text.
 * @return string             The translated text.
 */
function exmachina_gettext( $translated, $text, $domain ) {

  /* Check if 'exmachina-core' is the current textdomain, there's no mofile for it, and the theme has a mofile. */
  if ( 'exmachina-core' == $domain && !exmachina_is_textdomain_loaded( 'exmachina-core' ) && exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
    $translated = exmachina_translate( exmachina_get_parent_textdomain(), $text );

  return $translated;

} // end function exmachina_gettext()

/**
 * Contextual Gettext Filter
 *
 * Filters 'gettext_with_context' to change the translations used for the
 * 'exmachina-core' textdomain.
 *
 * @uses exmachina_is_textdomain_loaded()  Checks if textdomain is loaded.
 * @uses exmachina_get_parent_textdomain() Gets the parent textdomain.
 * @uses exmachina_translate()             Translation helper function.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $translated The translated text.
 * @param  string $text       The original, untranslated text.
 * @param  string $context    The context of the text.
 * @param  string $domain     The textdomain for the text.
 * @return string             The translated text.
 */
function exmachina_gettext_with_context( $translated, $text, $context, $domain ) {

  /* Check if 'exmachina-core' is the current textdomain, there's no mofile for it, and the theme has a mofile. */
  if ( 'exmachina-core' == $domain && !exmachina_is_textdomain_loaded( 'exmachina-core' ) && exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
    $translated = exmachina_translate( exmachina_get_parent_textdomain(), $text, $context );

  return $translated;

} // end function exmachina_gettext_with_context()

/**
 * Ngettext Filter
 *
 * Filters 'ngettext' to change the translations used for the 'exmachina-core' textdomain.
 *
 * @uses exmachina_is_textdomain_loaded()  Checks if textdomain is loaded.
 * @uses exmachina_get_parent_textdomain() Gets the parent textdomain.
 * @uses exmachina_translate_plural()      Plural Translation helper function.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $translated The translated text.
 * @param  string $single     The singular form of the translated text.
 * @param  string $plural     The plural form of the translated text.
 * @param  int    $number     The number to use to base whether something is singular or plural.
 * @param  string $domain     The textdomain for the text.
 * @return string             The translated text.
 */
function exmachina_ngettext( $translated, $single, $plural, $number, $domain ) {

  /* Check if 'exmachina-core' is the current textdomain, there's no mofile for it, and the theme has a mofile. */
  if ( 'exmachina-core' == $domain && !exmachina_is_textdomain_loaded( 'exmachina-core' ) && exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
    $translated = exmachina_translate_plural( exmachina_get_parent_textdomain(), $single, $plural, $number );

  return $translated;

} // end function exmachina_ngettext()

/**
 * Contextual Ngettext Filter
 *
 * Filters 'ngettext_with_context' to change the translations used for the
 * 'exmachina-core' textdomain.
 *
 * @uses exmachina_is_textdomain_loaded()  Checks if textdomain is loaded.
 * @uses exmachina_get_parent_textdomain() Gets the parent textdomain.
 * @uses exmachina_translate_plural()      Plural Translation helper function.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $translated The translated text.
 * @param  string $single     The singular form of the untranslated text.
 * @param  string $plural     The plural form of the untranslated text.
 * @param  int    $number     The number to use to base whether something is singular or plural.
 * @param  string $context    The context of the text.
 * @param  string $domain     The textdomain for the text.
 * @return string             The translated text.
 */
function exmachina_ngettext_with_context( $translated, $single, $plural, $number, $context, $domain ) {

  /* Check if 'exmachina-core' is the current textdomain, there's no mofile for it, and the theme has a mofile. */
  if ( 'exmachina-core' == $domain && !exmachina_is_textdomain_loaded( 'exmachina-core' ) && exmachina_is_textdomain_loaded( exmachina_get_parent_textdomain() ) )
    $translated = exmachina_translate_plural( exmachina_get_parent_textdomain(), $single, $plural, $number, $context );

  return $translated;

} // end function exmachina_ngettext_with_context()

/**
 * Extensions Gettext Filter
 *
 * Filters 'gettext_with_context' to change the translations used for each of
 * the extensions' textdomains.
 *
 * @uses exmachina_is_textdomain_loaded()  Checks if textdomain is loaded.
 * @uses exmachina_translate()             Translation helper function.
 * @uses exmachina_get_parent_textdomain() Gets the parent textdomain.
 *
 * @todo Make sure to include additional extensions and/or create extension list function.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $translated The translated text.
 * @param  string $text       The untranslated text.
 * @param  string $domain     The textdomain for the text.
 * @return string             The translated text.
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

} // end function exmachina_extensions_gettext()

/**
 * Contextual Extensions Gettext Filter
 *
 * Filters 'gettext_with_context' to change the translations used for each of the extensions' textdomains.
 *
 * @uses exmachina_is_textdomain_loaded()  Checks if textdomain is loaded.
 * @uses exmachina_translate()             Translation helper function.
 * @uses exmachina_get_parent_textdomain() Gets the parent textdomain.
 *
 * @todo Make sure to include additional extensions and/or create extension list function.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $translated The translated text.
 * @param  string $text       The untranslated text.
 * @param  string $context    The context of the text.
 * @param  string $domain     The textdomain for the text.
 * @return string             The translated text.
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

} // end function exmachina_extensions_gettext_with_context()

/**
 * Extensions Ngettext Filter
 *
 * Filters 'ngettext' to change the translations used for each of the extensions' textdomains.
 *
 * @uses exmachina_is_textdomain_loaded()  Checks if textdomain is loaded.
 * @uses exmachina_translate_plural()      Plural Translation helper function.
 * @uses exmachina_get_parent_textdomain() Gets the parent textdomain.
 *
 * @todo Make sure to include additional extensions and/or create extension list function.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $translated The translated text.
 * @param  string $single     The singular form of the untranslated text.
 * @param  string $plural     The plural form of the untranslated text.
 * @param  int    $number     The number to use to base whether something is singular or plural.
 * @param  string $domain     The textdomain for the text.
 * @return string             The translated text.
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

} // end function exmachina_extensions_ngettext()

/**
 * Contextual Extensions Ngettext Filter
 *
 * Filters 'ngettext_with_context' to change the translations used for each of the extensions' textdomains.
 *
 * @uses exmachina_is_textdomain_loaded()  Checks if textdomain is loaded.
 * @uses exmachina_translate_plural()      Plural Translation helper function.
 * @uses exmachina_get_parent_textdomain() Gets the parent textdomain.
 *
 * @todo Make sure to include additional extensions and/or create extension list function.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $translated The translated text.
 * @param  string $single     The singular form of the untranslated text.
 * @param  string $plural     The plural form of the untranslated text.
 * @param  int    $number     The number to use to base whether something is singular or plural.
 * @param  string $context    The context of the text.
 * @param  string $domain     The textdomain for the text.
 * @return string             The translated text.
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

} // end function exmachina_extensions_ngettext_with_context()