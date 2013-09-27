<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Settings Functions
 *
 * settings.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Functions for dealing with theme settings on both the front end of the site
 * and the admin. This allows us to set some default settings and make it easy
 * for theme developers to quickly grab theme settings from the database. This
 * file is only loaded if the theme supports the 'exmachina-core-theme-settings'
 * feature.
 *
 * @package     ExMachina
 * @subpackage  Functions
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com
 */
###############################################################################
# Begin functions
###############################################################################

/**
 * Get Option
 *
 * Returns an option from the options table and caches the result. Applies the
 * 'exmachina_pre_get_option_$key' filter to allow child themes to short-circuit
 * the function and 'exmachina_options' filter to override a specific option.
 *
 * Values pulled from the database are cached on each request, so a second request
 * for the same value won't cause a second DB interaction.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_option
 *
 * @since 1.5.4
 *
 * @param  string  $key       The option name.
 * @param  string  $setting   Optional. The settings field name.
 * @param  boolean $use_cache Optional. Whether to use the cache value.
 * @return mixed              The value of $key in the database.
 */
function exmachina_get_option( $key, $setting = null, $use_cache = true ) {

  /* Defines the default settings field so it doesn't need to be repeated. */
  $setting = $setting ? $setting : EXMACHINA_SETTINGS_FIELD;

  /* Bypasses the cache if needed. */
  if ( ! $use_cache ) {
    $options = get_option( $setting );

    if ( ! is_array( $options ) || ! array_key_exists( $key, $options ) )
      return '';

    return is_array( $options[$key] ) ? stripslashes_deep( $options[$key] ) : stripslashes( wp_kses_decode_entities( $options[$key] ) );
  } // end if (!$use_cache)

  /* Setup the caches. */
  static $settings_cache = array();
  static $options_cache  = array();

  /* Allow child themes to short-circuit this function. */
  $pre = apply_filters( 'exmachina_pre_get_option_' . $key, null, $setting );
  if ( null !== $pre )
    return $pre;

  /* Check the options cache. */
  if ( isset( $options_cache[$setting][$key] ) )
    /* Option has been cached. */
    return $options_cache[$setting][$key];

  /* Check the settings cache. */
  if ( isset( $settings_cache[$setting] ) )
    /* Setting has been cached. */
    $options = apply_filters( 'exmachina_options', $settings_cache[$setting], $setting );
  else
    /* Set value and cache setting. */
    $options = $settings_cache[$setting] = apply_filters( 'exmachina_options', get_option( $setting ), $setting );

  /* Check for non-existent option. */
  if ( ! is_array( $options ) || ! array_key_exists( $key, (array) $options ) )
    /* Cache non-existent option. */
    $options_cache[$setting][$key] = '';
  else
    /* Option has not been previously been cached, so cache now. */
    $options_cache[$setting][$key] = is_array( $options[$key] ) ? stripslashes_deep( $options[$key] ) : stripslashes( wp_kses_decode_entities( $options[$key] ) );

  /* Return the $options_cache. */
  return $options_cache[$setting][$key];

} // end exmachina_get_option()

/**
 * Echo Option
 *
 * Echoes out options from the options table.
 *
 * @uses exmachina_get_option() Returns option from the database and cache result.
 *
 * @since 1.5.4
 *
 * @param  string  $key       The option name.
 * @param  string  $setting   Optional. The settings field name.
 * @param  boolean $use_cache Optional. Whether to use the cache value.
 */
function exmachina_option( $key, $setting = null, $use_cache = true ) {

  /* Echo out the option value from exmachina_get_option(). */
  echo exmachina_get_option( $key, $setting, $use_cache );

} // end function exmachina_option()

/**
 * Get Field Name
 *
 * Creates a settings field name attribute for use on the theme settings pages.
 * This is a helper function for use with the WordPress settings API.
 *
 * @since 1.5.4
 *
 * @param  string $name    Field name base.
 * @param  string $setting Optional. The settings field name.
 * @return string          Full field name.
 */
function exmachina_get_field_name( $name, $setting = null ) {

  /* Defines the default settings field so it doesn't need to be repeated. */
  $setting = $setting ? $setting : EXMACHINA_SETTINGS_FIELD;

  return sprintf( '%s[%s]', $setting, $name );

} // end function exmachina_get_field_name()

/**
 * Get Field ID
 *
 * Creates a settings field id attribute for use on the theme settings pages.
 * This is a helper function for use with the WordPress settings API.
 *
 * @since 1.5.4
 *
 * @param  string $id      Field id base.
 * @param  string $setting Optional. The settings field name.
 * @return string          Full field id.
 */
function exmachina_get_field_id( $id, $setting = null ) {

  /* Defines the default settings field so it doesn't need to be repeated. */
  $setting = $setting ? $setting : EXMACHINA_SETTINGS_FIELD;

  return sprintf( '%s[%s]', $setting, $id );
} // end function exmachina_get_field_id()

/**
 * Get Field Value
 *
 * Creates a settings field value attribute for use on the theme settings pages.
 * This is a helper function for use with the WordPress settings API.
 *
 * @uses exmachina_get_option() Returns an option from the options table.
 *
 * @since 1.5.4
 *
 * @param  string $key     Field key.
 * @param  string $setting Optional. The settings field name.
 * @return string          Full field value.
 */
function exmachina_get_field_value( $key, $setting = null ) {

  /* Defines the default settings field so it doesn't need to be repeated. */
  $setting = $setting ? $setting : EXMACHINA_SETTINGS_FIELD;

  return exmachina_get_option( $key, $setting );
} // end function exmachina_get_field_value()

/**
 * Loads the ExMachina theme settings once and allows the input of the specific field the user would
 * like to show.  ExMachina theme settings are added with 'autoload' set to 'yes', so the settings are
 * only loaded once on each page load.
 *
 * @since 0.7.0
 * @access public
 * @uses get_option() Gets an option from the database.
 * @uses exmachina_get_prefix() Gets the prefix of the theme.
 * @global object $exmachina The global ExMachina object.
 * @param string $option The specific theme setting the user wants.
 * @return mixed $settings[$option] Specific setting asked for.
 */
function exmachina_get_setting( $option = '' ) {
  global $exmachina;

  /* If no specific option was requested, return false. */
  if ( !$option )
    return false;

  /* Get the default settings. */
  $defaults = exmachina_get_default_theme_settings();

  /* If the settings array hasn't been set, call get_option() to get an array of theme settings. */
  if ( !isset( $exmachina->settings ) || !is_array( $exmachina->settings ) )
    $exmachina->settings = get_option( exmachina_get_prefix() . '_theme_settings', $defaults );

  /* If the option isn't set but the default is, set the option to the default. */
  if ( !isset( $exmachina->settings[ $option ] ) && isset( $defaults[ $option ] ) )
    $exmachina->settings[ $option ] = $defaults[ $option ];

  /* If no option is found at this point, return false. */
  if ( !isset( $exmachina->settings[ $option ] ) )
    return false;

  /* If the specific option is an array, return it. */
  if ( is_array( $exmachina->settings[ $option ] ) )
    return $exmachina->settings[ $option ];

  /* Strip slashes from the setting and return. */
  else
    return wp_kses_stripslashes( $exmachina->settings[ $option ] );
}

/**
 * Sets up a default array of theme settings for use with the theme.  Theme developers should filter the
 * "{$prefix}_default_theme_settings" hook to define any default theme settings.  WordPress does not
 * provide a hook for default settings at this time.
 *
 * @since 1.0.0
 * @access public
 * @return array $settings The default theme settings.
 */
function exmachina_get_default_theme_settings() {

  /* Set up some default variables. */
  $settings = array();
  $prefix = exmachina_get_prefix();

  /* Get theme-supported meta boxes for the settings page. */
  $supports = get_theme_support( 'exmachina-core-theme-settings' );

  $settings = array(
    'comments_pages'            => 0,
    'comments_posts'            => 1,
    'trackbacks_pages'          => 0,
    'trackbacks_posts'          => 1,
    'content_archive'           => 'full',
    'content_archive_limit'   => 0,
    'content_archive_thumbnail' => 0,
    'content_archive_more'      => '[Read more...]',
    'image_size'                => 'thumbnail',
    'posts_nav'                 => 'numeric',
    'single_nav'                 => 0,
    'header_scripts'            => '',
    'footer_scripts'            => '',
  );

  /* If the current theme supports the footer meta box and shortcodes, add default footer settings. */
  if ( is_array( $supports[0] ) && in_array( 'footer', $supports[0] ) && current_theme_supports( 'exmachina-core-shortcodes' ) ) {

    /* If there is a child theme active, add the [child-link] shortcode to the $footer_insert. */
    if ( is_child_theme() )
      $settings['footer_insert'] = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'exmachina-core' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link], [theme-link], and [child-link].', 'exmachina-core' ) . '</p>';

    /* If no child theme is active, leave out the [child-link] shortcode. */
    else
      $settings['footer_insert'] = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'exmachina-core' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link] and [theme-link].', 'exmachina-core' ) . '</p>';
  }

  /* Return the $settings array and provide a hook for overwriting the default settings. */
  return apply_filters( "{$prefix}_default_theme_settings", $settings );
}