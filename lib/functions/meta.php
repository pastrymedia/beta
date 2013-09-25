<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Metadata Functions
 *
 * meta.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Metadata functions used in the core framework. This file registers meta
 * keys for use in WordPress in a safe manner by setting up a custom sanitize
 * callback.
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

/* Register meta on the 'init' hook. */
add_action( 'init', 'exmachina_register_meta' );

/**
 * Register Metadata
 *
 * Registers the framework's custom metadata keys and sets up the sanitize
 * callback function.
 *
 * @uses exmachina_sanitize_meta() Sanitization callback function.
 *
 * @since 1.0.2
 *
 * @return void
 */
function exmachina_register_meta() {

  /* Register meta if the theme supports the 'exmachina-core-seo' feature. */
  if ( current_theme_supports( 'exmachina-core-seo' ) ) {

    /* Register 'Title', 'Description', and 'Keywords' meta for posts. */
    register_meta( 'post', 'Title', 'exmachina_sanitize_meta' );
    register_meta( 'post', 'Description', 'exmachina_sanitize_meta' );
    register_meta( 'post', 'Keywords', 'exmachina_sanitize_meta' );

    /* Register 'Title', 'Description', and 'Keywords' meta for users. */
    register_meta( 'user', 'Title', 'exmachina_sanitize_meta' );
    register_meta( 'user', 'Description', 'exmachina_sanitize_meta' );
    register_meta( 'user', 'Keywords', 'exmachina_sanitize_meta' );
  }

  /* Register meta if the theme supports the 'exmachina-core-template-hierarchy' feature. */
  if ( current_theme_supports( 'exmachina-core-template-hierarchy' ) ) {

    $post_types = get_post_types( array( 'public' => true ) );

    foreach ( $post_types as $post_type ) {
      if ( 'page' !== $post_type )
        register_meta( 'post', "_wp_{$post_type}_template", 'exmachina_sanitize_meta' );
    }
  }

} // end function exmachina_register_meta()

/**
 * Sanitize Metadata
 *
 * Callback function for sanitizing meta when add_metadata() or update_metadata()
 * is called by WordPress. To set up a custom method for sanitizing the data, use
 * the "sanitize_{$meta_type}_meta_{$meta_key}" filter hook to do so.
 *
 * @since 1.0.2
 *
 * @param  mixed  $meta_value The value of the data.
 * @param  string $meta_key   The meta key name.
 * @param  string $meta_type  The type of metadata (post, comment, user, etc.)
 * @return mixed              The sanitized meta value.
 */
function exmachina_sanitize_meta( $meta_value, $meta_key, $meta_type ) {

  return strip_tags( $meta_value );

} // end function exmachina_sanitize_meta()