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
 * Return SEO options from the SEO options database.
 *
 * @since 1.6.0
 *
 * @uses exmachina_get_option() Return option from the options table and cache result.
 * @uses EXMACHINA_SEO_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the cache value or not. Defaults to true.
 *
 * @return mixed The value of this $key in the database.
 */
function exmachina_get_seo_option( $key, $use_cache = true ) {

  return exmachina_get_option( $key, EXMACHINA_SEO_SETTINGS_FIELD, $use_cache );

}

/**
 * Echo an SEO option from the SEO options database.
 *
 * @since 1.6.0
 *
 * @uses exmachina_option() Echo option from the options table and cache result.
 * @uses EXMACHINA_SEO_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the cache value or not. Defaults to true.
 */
function exmachina_seo_option( $key, $use_cache = true ) {

  exmachina_option( $key, EXMACHINA_SEO_SETTINGS_FIELD, $use_cache );

}

/**
 * Return hook options from the hook options database.
 *
 * @since 1.6.0
 *
 * @uses exmachina_get_option() Return option from the options table and cache result.
 * @uses EXMACHINA_HOOK_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the cache value or not. Defaults to true.
 *
 * @return mixed The value of this $key in the database.
 */
function exmachina_get_hook_option( $key, $use_cache = true ) {

  return exmachina_get_option( $key, EXMACHINA_HOOK_SETTINGS_FIELD, $use_cache );

}

/**
 * Echo an hook option from the hook options database.
 *
 * @since 1.6.0
 *
 * @uses exmachina_option() Echo option from the options table and cache result.
 * @uses EXMACHINA_HOOK_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the cache value or not. Defaults to true.
 */
function exmachina_hook_option( $key, $use_cache = true ) {

  exmachina_option( $key, EXMACHINA_HOOK_SETTINGS_FIELD, $use_cache );

}

/**
 * Return design options from the design options database.
 *
 * @since 1.6.0
 *
 * @uses exmachina_get_option() Return option from the options table and cache result.
 * @uses EXMACHINA_DESIGN_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the cache value or not. Defaults to true.
 *
 * @return mixed The value of this $key in the database.
 */
function exmachina_get_design_option( $key, $use_cache = true ) {

  return exmachina_get_option( $key, EXMACHINA_DESIGN_SETTINGS_FIELD, $use_cache );

}

/**
 * Echo an design option from the design options database.
 *
 * @since 1.6.0
 *
 * @uses exmachina_option() Echo option from the options table and cache result.
 * @uses EXMACHINA_DESIGN_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the cache value or not. Defaults to true.
 */
function exmachina_design_option( $key, $use_cache = true ) {

  exmachina_option( $key, EXMACHINA_DESIGN_SETTINGS_FIELD, $use_cache );

}

/**
 * Return content options from the content options database.
 *
 * @since 1.6.0
 *
 * @uses exmachina_get_option() Return option from the options table and cache result.
 * @uses EXMACHINA_CONTENT_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the cache value or not. Defaults to true.
 *
 * @return mixed The value of this $key in the database.
 */
function exmachina_get_content_option( $key, $use_cache = true ) {

  return exmachina_get_option( $key, EXMACHINA_CONTENT_SETTINGS_FIELD, $use_cache );

}

/**
 * Echo an content option from the content options database.
 *
 * @since 1.6.0
 *
 * @uses exmachina_option() Echo option from the options table and cache result.
 * @uses EXMACHINA_CONTENT_SETTINGS_FIELD
 *
 * @param string  $key       Option name.
 * @param boolean $use_cache Optional. Whether to use the cache value or not. Defaults to true.
 */
function exmachina_content_option( $key, $use_cache = true ) {

  exmachina_option( $key, EXMACHINA_CONTENT_SETTINGS_FIELD, $use_cache );

}

/*-------------------------------------------------------------------------*/
/* === Field Helper Functions === */
/*-------------------------------------------------------------------------*/

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

/*-------------------------------------------------------------------------*/
/* === Custom Field Functions === */
/*-------------------------------------------------------------------------*/

/**
 * Echo data from a post or page custom field.
 *
 * Echo only the first value of custom field.
 *
 * Pass in a `printf()` pattern as the second parameter and have that wrap around the value, if the value is not falsy.
 *
 * @since 0.1.3
 *
 * @uses exmachina_get_custom_field() Return custom field post meta data.
 *
 * @param string $field          Custom field key.
 * @param string $output_pattern printf() compatible output pattern.
 */
function exmachina_custom_field( $field, $output_pattern = '%s' ) {

  if ( $value = exmachina_get_custom_field( $field ) )
    printf( $output_pattern, $value );

}

/**
 * Return custom field post meta data.
 *
 * Return only the first value of custom field. Return false if field is blank or not set.
 *
 * @since 0.1.3
 *
 * @param string $field Custom field key.
 *
 * @return string|boolean Return value or false on failure.
 */
function exmachina_get_custom_field( $field ) {

  if ( null === get_the_ID() )
    return '';

  $custom_field = get_post_meta( get_the_ID(), $field, true );

  if ( ! $custom_field )
    return '';

  //* Return custom field, slashes stripped, sanitized if string
  return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

}

/**
 * Save post meta / custom field data for a post or page.
 *
 * It verifies the nonce, then checks we're not doing autosave, ajax or a future post request. It then checks the
 * current user's permissions, before finally* either updating the post meta, or deleting the field if the value was not
 * truthy.
 *
 * By passing an array of fields => values from the same metabox (and therefore same nonce) into the $data argument,
 * repeated checks against the nonce, request and permissions are avoided.
 *
 * @since 1.9.0
 *
 * @param array    $data         Key/Value pairs of data to save in '_field_name' => 'value' format.
 * @param string   $nonce_action Nonce action for use with wp_verify_nonce().
 * @param string   $nonce_name   Name of the nonce to check for permissions.
 * @param WP_Post|integer $post  Post object or ID.
 * @param integer  $deprecated   Deprecated (formerly accepted a post ID).
 *
 * @return mixed Return null if permissions incorrect, doing autosave, ajax or future post, false if update or delete
 *               failed, and true on success.
 */
function exmachina_save_custom_fields( array $data, $nonce_action, $nonce_name, $post, $deprecated = null ) {

  //* Verify the nonce
  if ( ! isset( $_POST[ $nonce_name ] ) || ! wp_verify_nonce( $_POST[ $nonce_name ], $nonce_action ) )
    return;

  //* Don't try to save the data under autosave, ajax, or future post.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return;
  if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
    return;
  if ( defined( 'DOING_CRON' ) && DOING_CRON )
    return;

  //* Grab the post object
  if ( ! is_null( $deprecated ) )
    $post = get_post( $deprecated );
  else
    $post = get_post( $post );

  //* Don't save if WP is creating a revision (same as DOING_AUTOSAVE?)
  if ( 'revision' === $post->post_type )
    return;

  //* Check that the user is allowed to edit the post
  if ( ! current_user_can( 'edit_post', $post->ID ) )
    return;

  //* Cycle through $data, insert value or delete field
  foreach ( (array) $data as $field => $value ) {
    //* Save $value, or delete if the $value is empty
    if ( $value )
      update_post_meta( $post->ID, $field, $value );
    else
      delete_post_meta( $post->ID, $field );
  }

}

/*-------------------------------------------------------------------------*/
/* === Term Filter Functions === */
/*-------------------------------------------------------------------------*/

add_filter( 'get_term', 'exmachina_get_term_filter', 10, 2 );
/**
 * Merge term meta data into options table.
 *
 * ExMachina is forced to create its own term-meta data structure in the options table, since it is not support in core WP.
 *
 * Applies `exmachina_term_meta_defaults`, `exmachina_term_meta_{field}` and `exmachina_term_meta` filters.
 *
 * @since 1.2.0
 *
 * @param object $term     Database row object.
 * @param string $taxonomy Taxonomy name that $term is part of.
 *
 * @return object $term Database row object.
 */
function exmachina_get_term_filter( $term, $taxonomy ) {

  //* Do nothing, if $term is not object
  if ( ! is_object( $term ) )
    return $term;

  $db = get_option( 'exmachina-term-meta' );
  $term_meta = isset( $db[$term->term_id] ) ? $db[$term->term_id] : array();

  $term->meta = wp_parse_args( $term_meta, apply_filters( 'exmachina_term_meta_defaults', array(
    'headline'            => '',
    'intro_text'          => '',
    'display_title'       => 0, //* vestigial
    'display_description' => 0, //* vestigial
    'doctitle'            => '',
    'description'         => '',
    'keywords'            => '',
    'layout'              => '',
    'noindex'             => 0,
    'nofollow'            => 0,
    'noarchive'           => 0,
  ) ) );

  //* Sanitize term meta
  foreach ( $term->meta as $field => $value )
    $term->meta[$field] = apply_filters( 'exmachina_term_meta_' . $field, stripslashes( wp_kses_decode_entities( $value ) ), $term, $taxonomy );

  $term->meta = apply_filters( 'exmachina_term_meta', $term->meta, $term, $taxonomy );

  return $term;

}

add_filter( 'get_terms', 'exmachina_get_terms_filter', 10, 2 );
/**
 * Add ExMachina term-meta data to functions that return multiple terms.
 *
 * @since 2.0.0
 *
 * @param array  $terms    Database row objects.
 * @param string $taxonomy Taxonomy name that $terms are part of.
 *
 * @return array $terms Database row objects.
 */
function exmachina_get_terms_filter( array $terms, $taxonomy ) {

  foreach( $terms as $term )
    $term = exmachina_get_term_filter( $term, $taxonomy );

  return $terms;

}

/*-------------------------------------------------------------------------*/
/* === Custom Post Type Archive Settings Functions === */
/*-------------------------------------------------------------------------*/

/**
 * Return a CPT Archive setting value from the options table.
 *
 * @since 2.0.0
 *
 * @uses exmachina_get_global_post_type_name()       Get the `post_type` from the global `$post` if supplied value is empty.
 * @uses exmachina_get_option()                      Return option from the options table and cache result.
 * @uses EXMACHINA_CPT_SETTINGS_FIELD
 *
 * @param string $key            Option name.
 * @param string $post_type_name Post type name.
 * @param bool   $use_cache      Optional. Whether to use the cache value or not. Defaults to true.
 *
 * @return mixed The option value.
 */
function exmachina_get_cpt_option( $key, $post_type_name = '', $use_cache = true ) {

  $post_type_name = exmachina_get_global_post_type_name( $post_type_name );

  return exmachina_get_option( $key, EXMACHINA_CPT_SETTINGS_FIELD . $post_type_name, $use_cache );

}

/**
 * Echo a CPT Archive option from the options table.
 *
 * @since 2.0.0
 *
 * @uses exmachina_get_cpt_option() Return a CPT Archive setting value from the options table.
 *
 * @param string $key            Option name.
 * @param string $post_type_name Post type name.
 * @param bool   $use_cache      Optional. Whether to use the cache value or not. Defaults to true.
 */
function exmachina_cpt_option( $key, $post_type_name, $use_cache = true ) {

  echo exmachina_get_cpt_option( $key, $post_type_name, $use_cache );

}

/*-------------------------------------------------------------------------*/
/* === Update Settings Functions === */
/*-------------------------------------------------------------------------*/

/**
 * Takes an array of new settings, merges them with the old settings, and pushes them into the database.
 *
 * @since 1.7.0
 *
 * @uses EXMACHINA_SETTINGS_FIELD
 *
 * @access private
 *
 * @param string|array $new     New settings. Can be a string, or an array.
 * @param string       $setting Optional. Settings field name. Default is EXMACHINA_SETTINGS_FIELD.
 */
function _exmachina_update_settings( $new = '', $setting = EXMACHINA_SETTINGS_FIELD ) {

  update_option( $setting, wp_parse_args( $new, get_option( $setting ) ) );

}
