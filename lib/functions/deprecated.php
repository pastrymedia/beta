<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Deprecated Functions
 *
 * deprecated.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Deprecated functions that should be avoided in favor of newer functions.
 * Also handles removed functions to avoid errors. Developers should not use
 * these functions in their parent themes and users should not use these
 * functions in their child themes.
 *
 * The functions below will all be removed at some point in a future release.
 * If your theme is using one of these, you should use the listed alternative
 * or remove it from your theme if necessary.
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
 * Deprecated. Post Format Tools URL Grabber
 *
 * Should use 'exmachina_get_the_post_format_url()'.
 *
 * @since 1.5.0
 * @deprecated 1.6.0
 */
function post_format_tools_url_grabber() {
  _deprecated_function( __FUNCTION__, '1.6.0', 'exmachina_get_the_post_format_url()' );
  exmachina_get_the_post_format_url();
}

/* === Removed Functions === */

/* Functions removed in the 0.8 branch. */

function post_format_tools_clean_post_format_slug() {
  exmachina_function_removed( __FUNCTION__ );
}

/**
 * Removed Function Message
 *
 * Message to display to the user for removed functions.
 *
 * @since 1.0.1
 * @access public
 *
 * @param  string $func The deprecated function name.
 * @return string       The error message.
 */
function exmachina_function_removed( $func = '' ) {

  die( sprintf( __( '<code>%1$s</code> &mdash; This function has been removed or replaced by another function.', 'exmachina-core' ), $func ) );

} // end function exmachina_function_removed()