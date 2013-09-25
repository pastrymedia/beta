<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Core Functions
 *
 * core.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * The core functions for the ExMachina framework. Functions located here define
 * the global ExMachina prefix, contextual atomic functions, and the content width.
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

/**
 * Get Theme Prefix
 *
 * Defines the theme prefix. This allows infinite changes to the theme. Based on
 * the template directory.
 *
 * @link http://codex.wordpress.org/Function_Reference/sanitize_key
 * @link http://codex.wordpress.org/Function_Reference/get_template
 *
 * @since 0.1.1
 * @access public
 *
 * @global object   $exmachina          The global ExMachina object.
 * @return string   $exmachina->prefix  The prefix of the theme.
 */
function exmachina_get_prefix() {
  global $exmachina;

  /* If the global prefix isn't set, define it. Plugin/theme authors may also define a custom prefix. */
  if ( empty( $exmachina->prefix ) )
    $exmachina->prefix = sanitize_key( apply_filters( 'exmachina_prefix', get_template() ) );

  return $exmachina->prefix;

} // end function exmachina_get_prefix()

/**
 * Atomic Action Hooks
 *
 * Adds contextual action hooks to the theme. This allows users to easily add
 * context-based content without having to know how to use WordPress conditional
 * tags.
 *
 * @link http://codex.wordpress.org/Function_Reference/do_action_ref_array
 *
 * @uses exmachina_get_prefix()   Gets the theme prefix.
 * @uses exmachina_get_context()  Gets the context of the current page.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $tag   Defines the base hook (Usually the location).
 * @param  mixed  $arg   Optional. Additional arguments passed to the function.
 * @return void          Returns false if tag is empty.
 */
function do_atomic( $tag = '', $arg = '' ) {

  if ( empty( $tag ) )
    return false;

  /* Get the theme prefix. */
  $pre = exmachina_get_prefix();

  /* Get the args passed into the function and remove $tag. */
  $args = func_get_args();
  array_splice( $args, 0, 1 );

  /* Do actions on the basic hook. */
  do_action_ref_array( "{$pre}_{$tag}", $args );

  /* Loop through context array and fire actions on a contextual scale. */
  foreach ( (array) exmachina_get_context() as $context )
    do_action_ref_array( "{$pre}_{$context}_{$tag}", $args );

} // end function do_atomic()

/**
 * Atomic Filter Hooks
 *
 * Adds contextual filter hooks to the theme. This allows users to easily filter
 * context-based content without having to know how to use WordPress conditional
 * tags.
 *
 * @link http://codex.wordpress.org/Function_Reference/apply_filters_ref_array
 *
 * @uses exmachina_get_prefix()   Gets the theme prefix.
 * @uses exmachina_get_context()  Gets the context of the current page.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $tag   Defines the base hook (Usually the location).
 * @param  mixed  $value The value to filter.
 * @return mixed         The value after it has been filtered.
 */
function apply_atomic( $tag = '', $value = '' ) {

  if ( empty( $tag ) )
    return false;

  /* Get theme prefix. */
  $pre = exmachina_get_prefix();

  /* Get the args passed into the function and remove $tag. */
  $args = func_get_args();
  array_splice( $args, 0, 1 );

  /* Apply filters on the basic hook. */
  $value = $args[0] = apply_filters_ref_array( "{$pre}_{$tag}", $args );

  /* Loop through context array and apply filters on a contextual scale. */
  foreach ( (array) exmachina_get_context() as $context )
    $value = $args[0] = apply_filters_ref_array( "{$pre}_{$context}_{$tag}", $args );

  /* Return the final value once all filters have been applied. */
  return $value;

} // end function apply_atomic()

/**
 * Atomic Shortcodes
 *
 * Wraps the output of apply_atomic() in a callback to do_shortcode(). This allows
 * for context-aware functionality alongside shortcodes.
 *
 * @link  http://codex.wordpress.org/Function_Reference/do_shortcode
 *
 * @uses apply_atomic() Applies the contextual filter.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $tag   Defines the base hook (Usually the location).
 * @param  mixed  $value The value to filter.
 * @return mixed         The value after it has been filtered.
 */
function apply_atomic_shortcode( $tag = '', $value = '' ) {
  return do_shortcode( apply_atomic( $tag, $value ) );

} // end function apply_atomic_shortcode()

/**
 * Get Transient Expiration
 *
 * The theme can save multiple things in a transient to help speed up page load
 * times. The default transient is set to 12 hours or 43,000 seconds.
 *
 * @link  http://codex.wordpress.org/Transients_API
 *
 * @uses exmachina_get_prefix()  Gets the theme prefix.
 *
 * @since 0.1.1
 * @access public
 *
 * @return int The transient expiration time in seconds (60 * 60 * 12).
 */
function exmachina_get_transient_expiration() {
  return apply_filters( exmachina_get_prefix() . '_transient_expiration', 43200 );

} // end function exmachina_get_transient_expiration()

/**
 * Format Hook Name
 *
 * Function for formatting a hook name if needed. This function automatically
 * adds the theme's prefix to the hook, and it will add the context (or any
 * variable) if it's given.
 *
 * @uses exmachina_get_prefix() Gets the theme prefix.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string $tag     The base hook (Usually the location).
 * @param  string $context A specific context/value to be added to the hook.
 * @return string          Returns the properly formatted hook.
 */
function exmachina_format_hook( $tag, $context = '' ) {
  return exmachina_get_prefix() . ( ( !empty( $context ) ) ? "_{$context}" : "" ). "_{$tag}";

} // end function exmachina_format_hook()

/**
 * Set Content Width
 *
 * Function for setting the content width of a theme. This does not check if a
 * content width has been set, it simply overwrites with any value set.
 *
 * @link  http://codex.wordpress.org/Content_Width
 *
 * @since 0.1.1
 * @access public
 *
 * @global int $content_width The width for the theme's content area.
 * @param  int $width         Numeric value of the theme width.
 * @return void
 */
function exmachina_set_content_width( $width = '' ) {
  global $content_width;

  $content_width = absint( $width );
} // end function exmachina_set_content_width()

/**
 * Get Content Width
 *
 * Function for getting the theme's content width.
 *
 * @link  http://codex.wordpress.org/Content_Width
 *
 * @since 0.1.1
 * @access public
 *
 * @global int $content_width The width for the theme's content area.
 * @return int $content_width Numeric value of the theme's width.
 */
function exmachina_get_content_width() {
  global $content_width;

  return $content_width;
} // end function exmachina_get_content_width()