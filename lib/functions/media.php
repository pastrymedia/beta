<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Media Functions
 *
 * media.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Functions for handling media (i.e., attachments & images) within themes.
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

/* Add all image sizes to the image editor to insert into post. */
add_filter( 'image_size_names_choose', 'exmachina_image_size_names_choose' );

/**
 * Image Size Name Chooser
 *
 * Adds theme/plugin custom images sizes added with add_image_size() to the
 * image uploader/editor. This allows users to insert these images within
 * their post content editor.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
 *
 * @since 1.0.6
 * @access public
 *
 * @param  array $sizes Selectable image sizes.
 * @return array        Array of image sizes.
 */
function exmachina_image_size_names_choose( $sizes ) {

  /* Get all intermediate image sizes. */
  $intermediate_sizes = get_intermediate_image_sizes();
  $add_sizes = array();

  /* Loop through each of the intermediate sizes, adding them to the $add_sizes array. */
  foreach ( $intermediate_sizes as $size )
    $add_sizes[$size] = $size;

  /* Merge the original array, keeping it intact, with the new array of image sizes. */
  $sizes = array_merge( $add_sizes, $sizes );

  /* Return the new sizes plus the old sizes back. */
  return $sizes;

} // end function exmachina_image_size_names_choose()

/* === Attachments === */

/**
 * Attachment Loader
 *
 * Loads the correct function for handling attachments. Checks the attachment
 * mime type to call correct function. Image attachments are not loaded with
 * this function. The functionality for them should be handled by the theme's
 * attachment or image attachment file.
 *
 * Ideally, all attachments would be appropriately handled within their templates.
 * However, this could lead to messy template files.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_get_attachment_url
 * @link http://codex.wordpress.org/Function_Reference/get_post_mime_type
 *
 * @uses apply_atomic() Applies the contextual filter hook.
 *
 * @since 1.0.6
 * @access public
 *
 * @return void
 */
function exmachina_attachment() {
  $file = wp_get_attachment_url();
  $mime = get_post_mime_type();
  $mime_type = explode( '/', $mime );

  /* Loop through each mime type. If a function exists for it, call it. Allow users to filter the display. */
  foreach ( $mime_type as $type ) {
    if ( function_exists( "exmachina_{$type}_attachment" ) )
      $attachment = call_user_func( "exmachina_{$type}_attachment", $mime, $file );

    $attachment = apply_atomic( "{$type}_attachment", $attachment );
  }

  echo apply_atomic( 'attachment', $attachment );

} // end function exmachina_attachment()

/**
 * Application Attachments
 *
 * Handles application attachments on their attachment pages. Uses the <object>
 * tag to embed media on those pages.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_embed_defaults
 * @link http://codex.wordpress.org/Function_Reference/esc_attr
 * @link http://codex.wordpress.org/Function_Reference/esc_url
 *
 * @since 1.0.6
 * @access public
 *
 * @param  string $mime Attachment mime type.
 * @param  string $file Attachment file URL.
 * @return string       The application attachment markup.
 */
function exmachina_application_attachment( $mime = '', $file = '' ) {

  $embed_defaults = wp_embed_defaults();
  $application = '<object class="text" type="' . esc_attr( $mime ) . '" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
  $application .= '<param name="src" value="' . esc_url( $file ) . '" />';
  $application .= '</object>';

  return $application;

} // end function exmachina_application_attachment()

/**
 * Text Attachments
 *
 * Handles text attachments on their attachment pages. Uses the <object> element
 * to embed media in the pages.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_embed_defaults
 * @link http://codex.wordpress.org/Function_Reference/esc_attr
 * @link http://codex.wordpress.org/Function_Reference/esc_url
 *
 * @since 1.0.6
 * @access public
 *
 * @param  string $mime Attachment mime type.
 * @param  string $file Attachment file URL.
 * @return string       The text attachment markup.
 */
function exmachina_text_attachment( $mime = '', $file = '' ) {

  $embed_defaults = wp_embed_defaults();
  $text = '<object class="text" type="' . esc_attr( $mime ) . '" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
  $text .= '<param name="src" value="' . esc_url( $file ) . '" />';
  $text .= '</object>';

  return $text;

} // end function exmachina_text_attachment()

/**
 * Audio Attachments
 *
 * Handles audio attachments on their attachment pages. Puts audio/mpeg and
 * audio/wma files into an <object> element.
 *
 * @link http://codex.wordpress.org/Function_Reference/do_shortcode
 * @link http://codex.wordpress.org/Function_Reference/esc_url
 *
 * @todo Test out and support more audio types.
 *
 * @since 1.0.6
 * @access public
 *
 * @param  string $mime Attachment mime type.
 * @param  string $file Attachment file URL.
 * @return string       The audio attachment markup.
 */
function exmachina_audio_attachment( $mime = '', $file = '' ) {

  return do_shortcode( '[audio src="' . esc_url( $file ) . '"]' );

} // end function exmachina_audio_attachment()

/**
 * Video Attachments
 *
 * Handles video attachments on attachment pages. Add other video types to the
 * <object> element.
 *
 * @link http://codex.wordpress.org/Function_Reference/do_shortcode
 * @link http://codex.wordpress.org/Function_Reference/esc_url
 *
 * @since 1.0.6
 * @access public
 *
 * @param  string $mime Attachment mime type.
 * @param  string $file Attachment file URL.
 * @return string       The video attachment markup.
 */
function exmachina_video_attachment( $mime = false, $file = false ) {

  return do_shortcode( '[video src="' . esc_url( $file ) . '"]' );

} // end function exmachina_video_attachment()

/* === Images === */

/**
 * Get Image ID
 *
 * Pulls an attachment ID from a post if one exists.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_children
 *
 * @since 1.0.6
 * @access public
 *
 * @global object  $post  The WP_Post post object.
 * @param  integer $index Optional. Index of which image to return from a post.
 * @return integer        Returns image ID, or false.
 */
function exmachina_get_image_id( $index = 0 ) {
  global $post;

  $image_ids = array_keys(
    get_children(
      array(
        'post_parent'    => $post->ID,
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
      )
    )
  );

  if ( isset( $image_ids[$index] ) )
    return $image_ids[$index];

  return false;

} // end function exmachina_get_image_id()

/**
 * Get Image
 *
 * Returns an image pulled from the media gallery.
 *
 * Supported $args keys are:
 * - format   - string, default is 'html'
 * - size     - string, default is 'full'
 * - num      - integer, default is 0
 * - attr     - string, default is ''
 * - fallback - mixed, default is 'first-attached'
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_parse_args
 * @link http://codex.wordpress.org/Function_Reference/has_post_thumbnail
 * @link http://codex.wordpress.org/Function_Reference/get_post_thumbnail_id
 * @link http://codex.wordpress.org/Function_Reference/wp_get_attachment_image
 * @link http://codex.wordpress.org/Function_Reference/wp_get_attachment_image_src
 * @link http://codex.wordpress.org/Function_Reference/home_url
 *
 * @uses exmachina_get_prefix()   Gets the theme prefix.
 * @uses exmachina_get_image_id() Gets the image ID from the post.
 *
 * @since 1.0.6
 * @access public
 *
 * @global object $post The WP_Post post object.
 * @param  array  $args Optional. Image query arguments.
 * @return string       Returns img element HTML.
 */
function exmachina_get_image( $args = array() ) {
  global $post;

  $defaults = apply_filters( exmachina_get_prefix() . '_get_image_default_args', array(
    'format'   => 'html',
    'size'     => 'full',
    'num'      => 0,
    'attr'     => '',
    'fallback' => 'first-attached',
    'context'  => '',
  ) );

  $args = wp_parse_args( $args, $defaults );

  //* Allow child theme to short-circuit this function
  $pre = apply_filters( exmachina_get_prefix() . '_pre_get_image', false, $args, $post );
  if ( false !== $pre )
    return $pre;

  //* Check for post image (native WP)
  if ( has_post_thumbnail() && ( 0 === $args['num'] ) ) {
    $id = get_post_thumbnail_id();
    $html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
    list( $url ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
  }
  //* Else if first-attached, pull the first (default) image attachment
  elseif ( 'first-attached' == $args['fallback'] ) {
    $id = exmachina_get_image_id( $args['num'] );
    $html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
    list( $url ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
  }
  //* Else if fallback array exists
  elseif ( is_array( $args['fallback'] ) ) {
    $id   = 0;
    $html = $args['fallback']['html'];
    $url  = $args['fallback']['url'];
  }
  //* Else, return false (no image)
  else {
    return false;
  }

  //* Source path, relative to the root
  $src = str_replace( home_url(), '', $url );

  //* Determine output
  if ( 'html' === strtolower( $args['format'] ) )
    $output = $html;
  elseif ( 'url' === strtolower( $args['format'] ) )
    $output = $url;
  else
    $output = $src;

  // Return FALSE if $url is blank
  if ( empty( $url ) ) $output = false;

  //* Return FALSE if $src is invalid (file doesn't exist)
//  if ( ! file_exists( ABSPATH . $src ) )
//    $output = false;

  //* Return data, filtered
  return apply_filters( exmachina_get_prefix() . '_get_image', $output, $args, $id, $html, $url, $src );

} // end function exmachina_get_image()

/**
 * Echo Image
 *
 * Echoes an image pulled from media gallery.
 *
 * Supported $args keys are:
 * - format - string, default is 'html', may be 'url'
 * - size   - string, default is 'full'
 * - num    - integer, default is 0
 * - attr   - string, default is ''
 *
 * @uses exmachina_get_image() Gets the image from the media gallery.
 *
 * @since 1.0.6
 * @access public
 *
 * @param  array  $args Optional. Image query arguments.
 * @return string       Returns the image HTML or URL.
 */
function exmachina_image( $args = array() ) {

  $image = exmachina_get_image( $args );

  if ( $image )
    echo $image;
  else
    return false;

} // end function exmachina_image()

/**
 * Get Additional Image Sizes
 *
 * Returns registered image sizes. Returns a two-dimensional array of just the
 * additionally registered image sizes, with width, height and crop sub-keys.
 *
 * @since 1.0.6
 * @access public
 *
 * @global array $_wp_additional_image_sizes Additionally registered image sizes.
 * @return array                             Returns array of image sizes.
 */
function exmachina_get_additional_image_sizes() {
  global $_wp_additional_image_sizes;

  if ( $_wp_additional_image_sizes )
    return $_wp_additional_image_sizes;

  return array();

} // end function exmachina_get_additional_image_sizes()

/**
 * Get Image Sizes
 *
 * Returns all registered image sizes arrays, including the standard sizes.
 * Returns a two-dimensional array of standard and additionally registered
 * image sizes, with width, height and crop sub-keys.
 *
 * Here, the standard sizes have their sub-keys populated by pulling from the
 * options saved in the database.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_option
 *
 * @uses exmachina_get_additional_image_sizes() Gets image size array.
 *
 * @since 1.0.6
 * @access public
 *
 * @return array Returns array of image sizes.
 */
function exmachina_get_image_sizes() {

  $builtin_sizes = array(
    'large'   => array(
      'width'  => get_option( 'large_size_w' ),
      'height' => get_option( 'large_size_h' ),
    ),
    'medium'  => array(
      'width'  => get_option( 'medium_size_w' ),
      'height' => get_option( 'medium_size_h' ),
    ),
    'thumbnail' => array(
      'width'  => get_option( 'thumbnail_size_w' ),
      'height' => get_option( 'thumbnail_size_h' ),
      'crop'   => get_option( 'thumbnail_crop' ),
    ),
  );

  $additional_sizes = exmachina_get_additional_image_sizes();

  return array_merge( $builtin_sizes, $additional_sizes );

} // end function exmachina_get_image_sizes()