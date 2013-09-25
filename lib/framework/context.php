<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Contextual Functions
 *
 * context.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Functions for making various theme elements context-aware. Controls things
 * such as the smart and logical body, post, and comment CSS classes as well
 * as context-based action and filter hooks. The functions also integrate with
 * WordPress' implementations of body_class, post_class, and comment_class, so
 * your theme won't have any trouble with plugin integration.
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
 * ExMachina Get Context
 *
 * Main contextual function. This allows code to be used more than once without
 * hundreds of conditional checks within the theme. It returns an array of
 * contexts based on what page a visitor is currently viewing on the site.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_queried_object
 * @link http://codex.wordpress.org/Function_Reference/get_post_type_object
 * @link http://codex.wordpress.org/Function_Reference/get_query_var
 * @link http://codex.wordpress.org/Function_Reference/sanitize_html_class
 * @link http://codex.wordpress.org/Function_Reference/get_the_author_meta
 * @link http://codex.wordpress.org/Conditional_Tags
 *
 * @todo Add page template context - https://github.com/pmarkun/stagium/blob/master/functions/admin-hooks.php#L180
 *
 * @since 0.1.1
 * @access public
 *
 * @global object $wp_query           The current page query object.
 * @global object $exmachina          The global ExMachina object.
 * @return array  $exmachina->context Several contexts based on the current page.
 */
function exmachina_get_context() {
  global $exmachina;

  /* If $exmachina->context has been set, don't run through the conditionals again. Just return the variable. */
  if ( isset( $exmachina->context ) )
    return apply_filters( 'exmachina_context', $exmachina->context );

  /* Set some variables for use within the function. */
  $exmachina->context = array();
  $object = get_queried_object();
  $object_id = get_queried_object_id();

  /* Front page of the site. */
  if ( is_front_page() )
    $exmachina->context[] = 'home';

  /* Blog page. */
  if ( is_home() ) {
    $exmachina->context[] = 'blog';
  }

  /* Singular views. */
  elseif ( is_singular() ) {
    $exmachina->context[] = 'singular';
    $exmachina->context[] = "singular-{$object->post_type}";
    $exmachina->context[] = "singular-{$object->post_type}-{$object_id}";
  }

  /* Archive views. */
  elseif ( is_archive() ) {
    $exmachina->context[] = 'archive';

    /* Post type archives. */
    if ( is_post_type_archive() ) {
      $post_type = get_post_type_object( get_query_var( 'post_type' ) );
      $exmachina->context[] = "archive-{$post_type->name}";
    }

    /* Taxonomy archives. */
    if ( is_tax() || is_category() || is_tag() ) {
      $exmachina->context[] = 'taxonomy';
      $exmachina->context[] = "taxonomy-{$object->taxonomy}";

      $slug = ( ( 'post_format' == $object->taxonomy ) ? str_replace( 'post-format-', '', $object->slug ) : $object->slug );
      $exmachina->context[] = "taxonomy-{$object->taxonomy}-" . sanitize_html_class( $slug, $object->term_id );
    }

    /* User/author archives. */
    if ( is_author() ) {
      $user_id = get_query_var( 'author' );
      $exmachina->context[] = 'user';
      $exmachina->context[] = 'user-' . sanitize_html_class( get_the_author_meta( 'user_nicename', $user_id ), $user_id );
    }

    /* Date archives. */
    if ( is_date() ) {
      $exmachina->context[] = 'date';

      if ( is_year() )
        $exmachina->context[] = 'year';

      if ( is_month() )
        $exmachina->context[] = 'month';

      if ( get_query_var( 'w' ) )
        $exmachina->context[] = 'week';

      if ( is_day() )
        $exmachina->context[] = 'day';
    }

    /* Time archives. */
    if ( is_time() ) {
      $exmachina->context[] = 'time';

      if ( get_query_var( 'hour' ) )
        $exmachina->context[] = 'hour';

      if ( get_query_var( 'minute' ) )
        $exmachina->context[] = 'minute';
    }
  }

  /* Search results. */
  elseif ( is_search() ) {
    $exmachina->context[] = 'search';
  }

  /* Error 404 pages. */
  elseif ( is_404() ) {
    $exmachina->context[] = 'error-404';
  }

  return array_map( 'esc_attr', apply_filters( 'exmachina_context', array_unique( $exmachina->context ) ) );

} // end function exmachina_get_context()

/**
 * Contextual Body Attributes
 *
 * Outputs the attributes for the <body> element. By default, this is just the
 * 'class' attribute, but developers can filter this to add other attributes.
 *
 * @uses exmachina_get_body_class() Returns the contextual body class.
 * @uses apply_atomic()             Applies the contextual atomic filters.
 *
 * @since 0.1.1
 * @access public
 *
 * @return void
 */
function exmachina_body_attributes() {

  $attributes = array();
  $output     = '';

  $attributes['class'] = join( ' ', exmachina_get_body_class() );

  $attributes = apply_atomic( 'body_attributes', $attributes );

  foreach( $attributes as $attr => $value )
    $output .= " {$attr}='{$value}'";

  echo $output;

} // end function exmachina_body_attributes()

/**
 * Contextual Body Class Output
 *
 * Outputs classes for the <body> element depending on the page context.
 *
 * @uses exmachina_get_body_class() Returns the contextual body class.
 * @uses apply_atomic()             Applies the contextual atomic filters.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string|array $class Additional classes for more control.
 * @return void
 */
function exmachina_body_class( $class = '' ) {

  /* Get the body class. */
  $classes = exmachina_get_body_class( $class );

  /* Print the body class. */
  echo apply_atomic( 'body_class', join( ' ', $classes ) );

} // end function exmachina_body_class()

/**
 * Returns Contextual Body Class
 *
 * Returns the classes for the <body> element depending on the page context.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_bloginfo
 * @link http://codex.wordpress.org/Function_Reference/get_locale
 * @link http://codex.wordpress.org/Function_Reference/is_child_theme
 * @link http://codex.wordpress.org/Function_Reference/is_multisite
 * @link http://codex.wordpress.org/Function_Reference/get_current_blog_id
 * @link http://codex.wordpress.org/Function_Reference/get_option
 * @link http://codex.wordpress.org/Function_Reference/is_user_logged_in
 * @link http://codex.wordpress.org/Function_Reference/is_admin_bar_showing
 * @link http://codex.wordpress.org/Function_Reference/get_background_image
 * @link http://codex.wordpress.org/Function_Reference/get_background_color
 * @link http://codex.wordpress.org/Function_Reference/get_header_image
 * @link http://codex.wordpress.org/Function_Reference/is_singular
 * @link http://codex.wordpress.org/Function_Reference/get_queried_object
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta
 * @link http://codex.wordpress.org/Function_Reference/current_theme_supports
 * @link http://codex.wordpress.org/Function_Reference/post_type_supports
 * @link http://codex.wordpress.org/Function_Reference/get_post_format
 * @link http://codex.wordpress.org/Function_Reference/is_wp_error
 * @link http://codex.wordpress.org/Function_Reference/is_attachment
 * @link http://codex.wordpress.org/Function_Reference/get_post_mime_type
 *
 * @uses exmachina_get_context() Main contextual function.
 *
 * @since 0.1.1
 * @access public
 *
 * @global object       $wp_query The current page query object.
 * @param  string|array $class    Additional classes for more control.
 * @return array
 */
function exmachina_get_body_class( $class = '' ) {
  global $wp_query;

  /* Text direction (which direction does the text flow). */
  $classes = array( 'wordpress', get_bloginfo( 'text_direction' ), get_locale() );

  /* Check if the current theme is a parent or child theme. */
  $classes[] = ( is_child_theme() ? 'child-theme' : 'parent-theme' );

  /* Multisite check adds the 'multisite' class and the blog ID. */
  if ( is_multisite() ) {
    $classes[] = 'multisite';
    $classes[] = 'blog-' . get_current_blog_id();
  }

  /* Date classes. */
  $time = time() + ( get_option( 'gmt_offset' ) * 3600 );
  $classes[] = strtolower( gmdate( '\yY \mm \dd \hH l', $time ) );

  /* Is the current user logged in. */
  $classes[] = ( is_user_logged_in() ) ? 'logged-in' : 'logged-out';

  /* WP admin bar. */
  if ( is_admin_bar_showing() )
    $classes[] = 'admin-bar';

  /* Use the '.custom-background' class to integrate with the WP background feature. */
  if ( get_background_image() || get_background_color() )
    $classes[] = 'custom-background';

  /* Add the '.custom-header' class if the user is using a custom header. */
  if ( get_header_image() )
    $classes[] = 'custom-header';

  /* Merge base contextual classes with $classes. */
  $classes = array_merge( $classes, exmachina_get_context() );

  /* Singular post (post_type) classes. */
  if ( is_singular() ) {

    /* Get the queried post object. */
    $post = get_queried_object();

    /* Checks for custom template. */
    $template = str_replace( array ( "{$post->post_type}-template-", "{$post->post_type}-" ), '', basename( get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true ), '.php' ) );
    if ( !empty( $template ) )
      $classes[] = "{$post->post_type}-template-{$template}";

    /* Post format. */
    if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) ) {
      $post_format = get_post_format( get_queried_object_id() );
      $classes[] = ( ( empty( $post_format ) || is_wp_error( $post_format ) ) ? "{$post->post_type}-format-standard" : "{$post->post_type}-format-{$post_format}" );
    }

    /* Attachment mime types. */
    if ( is_attachment() ) {
      foreach ( explode( '/', get_post_mime_type() ) as $type )
        $classes[] = "attachment-{$type}";
    }
  }

  /* Paged views. */
  if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 )
    $classes[] = 'paged paged-' . intval( $page );

  /* Input class. */
  if ( !empty( $class ) ) {
    if ( !is_array( $class ) )
      $class = preg_split( '#\s+#', $class );
    $classes = array_merge( $classes, $class );
  }

  /* Apply the filters for WP's 'body_class'. */
  return array_unique( apply_filters( 'body_class', $classes, $class ) );

} // end function exmachina_get_body_class()

/**
 * Contextual Post Attributes
 *
 * Outputs the attributes for the post wrapper. By default, this is the 'class'
 * and 'id' attributes, but developers can filter this to add other attributes.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID
 *
 * @uses exmachina_get_post_class() Returns the contextual post class.
 * @uses apply_atomic()             Applies the contextual atomic filters.
 *
 * @since 0.1.1
 * @access public
 * @return void
 */
function exmachina_post_attributes() {

  $attributes = array();
  $output     = '';

  $attributes['id']    = 'post-' . get_the_ID();
  $attributes['class'] = join( ' ', exmachina_get_post_class() );

  $attributes = apply_atomic( 'post_attributes', $attributes );

  foreach( $attributes as $attr => $value )
    $output .= " {$attr}='{$value}'";

  echo $output;

} // end function exmachina_post_attributes()

/**
 * Contextual Post Class Output
 *
 * Outputs the class for the post wrapper.
 *
 * @uses exmachina_get_post_class() Returns the contextual post class.
 * @uses apply_atomic()             Applies the contextual atomic filters.
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string|array $class   Additional classes for more control.
 * @param  int          $post_id ID of a specific post to get the class for.
 * @return void
 */
function exmachina_post_class( $class = '', $post_id = null ) {

  /* Get the post class. */
  $classes = exmachina_get_post_class( $class, $post_id );

  /* Print the body class. */
  echo apply_atomic( 'post_class', join( ' ', $classes ) );

} // end function exmachina_post_class()

/**
 * Returns Contextual Post Class
 *
 * Creates a set of classes for each site entry upon display. Each entry is
 * given the class of 'hentry'. Posts are given category, tag, and author
 * classes. Alternate post classes of odd, even, and alt are added.
 *
 * @todo Add WP codex function references to exmachina_get_post_class()
 *
 * @since 0.1.1
 * @access public
 *
 * @param  string|array $class   Additional classes for more control.
 * @param  int          $post_id ID of a specific post to get the class for.
 * @return array
 */
function exmachina_get_post_class( $class = '', $post_id = null ) {
  static $post_alt;

  $post = get_post( $post_id );

  /* Make sure we have a real post first. */
  if ( !empty( $post ) ) {

    $post_id = $post->ID;

    /* Add hentry for microformats compliance, the post type, and post status. */
    $classes = array( 'hentry', $post->post_type, $post->post_status );

    /* Post alt class. */
    $classes[] = 'post-' . ++$post_alt;
    $classes[] = ( $post_alt % 2 ) ? 'odd' : 'even alt';

    /* Author class. */
    $classes[] = 'author-' . sanitize_html_class( get_the_author_meta( 'user_nicename' ), get_the_author_meta( 'ID' ) );

    /* Sticky class (only on home/blog page). */
    if ( is_home() && is_sticky() && !is_paged() )
      $classes[] = 'sticky';

    /* Password-protected posts. */
    if ( post_password_required() )
      $classes[] = 'protected';

    /* Has excerpt. */
    if ( post_type_supports( $post->post_type, 'excerpt' ) && has_excerpt() )
      $classes[] = 'has-excerpt';

    /* Has <!--more--> link. */
    if ( !is_singular() && false !== strpos( $post->post_content, '<!--more-->' ) )
      $classes[] = 'has-more-link';

    /* Post format. */
    if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) ) {
      $post_format = get_post_format( $post_id );
      $classes[] = ( ( empty( $post_format ) || is_wp_error( $post_format ) ) ? 'format-standard' : "format-{$post_format}" );
    }

    /* Add category and post tag terms as classes. */
    if ( 'post' == $post->post_type ) {

      foreach ( array( 'category', 'post_tag' ) as $tax ) {

        foreach ( (array)get_the_terms( $post->ID, $tax ) as $term ) {
          if ( !empty( $term->slug ) )
            $classes[] = $tax . '-' . sanitize_html_class( $term->slug, $term->term_id );
        }
      }
    }
  }

  /* If not a post. */
  else {
    $classes = array( 'hentry', 'error' );
  }

  /* User-created classes. */
  if ( !empty( $class ) ) {
    if ( !is_array( $class ) )
      $class = preg_split( '#\s+#', $class );
    $classes = array_merge( $classes, $class );
  }

  /* Apply the filters for WP's 'post_class'. */
  return array_unique( apply_filters( 'post_class', $classes, $class, $post_id ) );
} // end function exmachina_get_post_class()

/**
 * Contextual Comment Attributes
 *
 * Outputs the class for the current comment wrapper element.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_comment_ID
 *
 * @uses exmachina_get_comment_class() Returns the contextual comment class.
 * @uses apply_atomic()                Applies the contextual atomic filters.
 *
 * @since 0.1.1
 * @access public
 *
 * @return void
 */
function exmachina_comment_attributes() {

  $attributes = array();
  $output     = '';

  $attributes['id']    = 'comment-' . get_comment_ID();
  $attributes['class'] = join( ' ', exmachina_get_comment_class() );

  $attributes = apply_atomic( 'comment_attributes', $attributes );

  foreach( $attributes as $attr => $value )
    $output .= " {$attr}='{$value}'";

  echo $output;

} // end function exmachina_comment_attributes()

/**
 * Contextual Comment Class Output
 *
 * Outputs the class for the current comment wrapper element.
 *
 * @uses exmachina_get_comment_class() Returns the contextual comment class.
 *
 * @since 0.1.1
 * @access public
 *
 * @global object       $exmachina  The global ExMachina object.
 * @param  string|array $class      Additional classes for more control.
 * @return void
 */
function exmachina_comment_class( $class = '' ) {
  global $exmachina;

  /* Join all the classes into one string and echo them. */
  $class = join( ' ', exmachina_get_comment_class( $class ) );

  echo apply_filters( "{$exmachina->prefix}_comment_class", $class );

} // end function exmachina_comment_class()

/**
 * Returns Contextual Comment Class
 *
 * Sets a class for each comment. Sets alt, odd/even, and author/user classes.
 * Adds author, user, and reader classes.
 *
 * @todo Add WP codex function references to exmachina_get_comment_class()
 *
 * @since 0.1.1
 * @access public
 *
 * @global object       $comment  The current comment's DB object.
 * @param  string|array $class    Additional classes for more control.
 * @return void
 */
function exmachina_get_comment_class( $class = '' ) {
  global $comment;

  /* Gets default WP comment classes. */
  $classes = get_comment_class( $class );

  /* Get the comment type. */
  $comment_type = get_comment_type();

  /* If the comment type is 'pingback' or 'trackback', add the 'ping' comment class. */
  if ( 'pingback' == $comment_type || 'trackback' == $comment_type )
    $classes[] = 'ping';

  /* User classes to match user role and user. */
  if ( $comment->user_id > 0 ) {

    /* Create new user object. */
    $user = new WP_User( $comment->user_id );

    /* Set a class with the user's role(s). */
    if ( is_array( $user->roles ) ) {
      foreach ( $user->roles as $role )
        $classes[] = sanitize_html_class( "role-{$role}" );
    }

    /* Set a class with the user's name. */
    $classes[] = sanitize_html_class( "user-{$user->user_nicename}", "user-{$user->ID}" );
  }

  /* If not a registered user */
  else {
    $classes[] = 'reader';
  }

  /* Comment by the entry/post author. */
  if ( $post = get_post( get_the_ID() ) ) {
    if ( $comment->user_id == $post->post_author )
      $classes[] = 'entry-author';
  }

  /* Get comment types that are allowed to have an avatar. */
  $avatar_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );

  /* If avatars are enabled and the comment types can display avatars, add the 'has-avatar' class. */
  if ( get_option( 'show_avatars' ) && in_array( $comment->comment_type, $avatar_comment_types ) )
    $classes[] = 'has-avatar';

  /* Make sure comment classes doesn't have any duplicates. */
  return array_unique( $classes );

} // end function exmachina_get_comment_class()
