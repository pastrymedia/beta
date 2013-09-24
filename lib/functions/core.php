<?php
/**
 * The core functions file for the ExMachina framework. Functions defined here are generally
 * used across the entire framework to make various tasks faster. This file should be loaded
 * prior to any other files because its functions are needed to run the framework.
 *
 * @package    ExMachinaCore
 * @subpackage Functions
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, Justin Tadlock
 * @link       http://themeexmachina.com/exmachina-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Defines the theme prefix. This allows developers to infinitely change the theme. In theory,
 * one could use the ExMachina core to create their own theme or filter 'exmachina_prefix' with a 
 * plugin to make it easier to use hooks across multiple themes without having to figure out
 * each theme's hooks (assuming other themes used the same system).
 *
 * @since 0.7.0
 * @access public
 * @uses get_template() Defines the theme prefix based on the theme directory.
 * @global object $exmachina The global ExMachina object.
 * @return string $exmachina->prefix The prefix of the theme.
 */
function exmachina_get_prefix() {
	global $exmachina;

	/* If the global prefix isn't set, define it. Plugin/theme authors may also define a custom prefix. */
	if ( empty( $exmachina->prefix ) )
		$exmachina->prefix = sanitize_key( apply_filters( 'exmachina_prefix', get_template() ) );

	return $exmachina->prefix;
}

/**
 * Adds contextual action hooks to the theme.  This allows users to easily add context-based content 
 * without having to know how to use WordPress conditional tags.  The theme handles the logic.
 *
 * An example of a basic hook would be 'exmachina_header'.  The do_atomic() function extends that to 
 * give extra hooks such as 'exmachina_singular_header', 'exmachina_singular-post_header', and 
 * 'exmachina_singular-post-ID_header'.
 *
 * @author Justin Tadlock <justin@justintadlock.com>
 * @author Ptah Dunbar <pt@ptahd.com>
 * @link http://ptahdunbar.com/wordpress/smarter-hooks-context-sensitive-hooks
 *
 * @since 0.7.0
 * @access public
 * @uses exmachina_get_prefix() Gets the theme prefix.
 * @uses exmachina_get_context() Gets the context of the current page.
 * @param string $tag Usually the location of the hook but defines what the base hook is.
 * @param mixed $arg,... Optional additional arguments which are passed on to the functions hooked to the action.
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
}

/**
 * Adds contextual filter hooks to the theme.  This allows users to easily filter context-based content 
 * without having to know how to use WordPress conditional tags.  The theme handles the logic.
 *
 * An example of a basic hook would be 'exmachina_entry_meta'.  The apply_atomic() function extends 
 * that to give extra hooks such as 'exmachina_singular_entry_meta', 'exmachina_singular-post_entry_meta', 
 * and 'exmachina_singular-post-ID_entry_meta'.
 *
 * @since 0.7.0
 * @access public
 * @uses exmachina_get_prefix() Gets the theme prefix.
 * @uses exmachina_get_context() Gets the context of the current page.
 * @param string $tag Usually the location of the hook but defines what the base hook is.
 * @param mixed $value The value on which the filters hooked to $tag are applied on.
 * @param mixed $var,... Additional variables passed to the functions hooked to $tag.
 * @return mixed $value The value after it has been filtered.
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
}

/**
 * Wraps the output of apply_atomic() in a call to do_shortcode(). This allows developers to use 
 * context-aware functionality alongside shortcodes. Rather than adding a lot of code to the 
 * function itself, developers can create individual functions to handle shortcodes.
 *
 * @since 0.7.0
 * @access public
 * @param string $tag Usually the location of the hook but defines what the base hook is.
 * @param mixed $value The value to be filtered.
 * @return mixed $value The value after it has been filtered.
 */
function apply_atomic_shortcode( $tag = '', $value = '' ) {
	return do_shortcode( apply_atomic( $tag, $value ) );
}

/**
 * The theme can save multiple things in a transient to help speed up page load times. We're
 * setting a default of 12 hours or 43,200 seconds (60 * 60 * 12).
 *
 * @since 0.8.0
 * @access public
 * @return int Transient expiration time in seconds.
 */
function exmachina_get_transient_expiration() {
	return apply_filters( exmachina_get_prefix() . '_transient_expiration', 43200 );
}

/**
 * Function for formatting a hook name if needed. It automatically adds the theme's prefix to 
 * the hook, and it will add a context (or any variable) if it's given.
 *
 * @since 0.7.0
 * @access public
 * @param string $tag The basic name of the hook (e.g., 'before_header').
 * @param string $context A specific context/value to be added to the hook.
 */
function exmachina_format_hook( $tag, $context = '' ) {
	return exmachina_get_prefix() . ( ( !empty( $context ) ) ? "_{$context}" : "" ). "_{$tag}";
}

/**
 * Function for setting the content width of a theme.  This does not check if a content width has been set; it 
 * simply overwrites whatever the content width is.
 *
 * @since 1.2.0
 * @access public
 * @global int $content_width The width for the theme's content area.
 * @param int $width Numeric value of the width to set.
 */
function exmachina_set_content_width( $width = '' ) {
	global $content_width;

	$content_width = absint( $width );
}

/**
 * Function for getting the theme's content width.
 *
 * @since 1.2.0
 * @access public
 * @global int $content_width The width for the theme's content area.
 * @return int $content_width
 */
function exmachina_get_content_width() {
	global $content_width;

	return $content_width;
}

?>