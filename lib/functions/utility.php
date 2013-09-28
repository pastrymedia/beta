<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Utility Functions
 *
 * utility.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Additional helper functions that the framework or themes may use. The functions
 * in this file are functions that don't really have a home within any other parts
 * of the framework.
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

/* Add extra support for post types. */
add_action( 'init', 'exmachina_add_post_type_support' );

/**
 * Add Post Type Support
 *
 * This function is for adding extra support for features not default to the core
 * post types. Excerpts are added to the 'page' post type.  Comments and trackbacks
 * are added for the 'attachment' post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/add_post_type_support
 *
 * @since 1.0.10
 * @access public
 *
 * @return void
 */
function exmachina_add_post_type_support() {

  /* Add support for excerpts to the 'page' post type. */
  add_post_type_support( 'page', array( 'excerpt' ) );

  /* Add thumbnail support for audio and video attachments. */
  add_post_type_support( 'attachment:audio', 'thumbnail' );
  add_post_type_support( 'attachment:video', 'thumbnail' );

} // end function exmachina_add_post_type_support()

/* Add extra file headers for themes. */
add_filter( 'extra_theme_headers', 'exmachina_extra_theme_headers' );

/**
 * Extra Theme Headers
 *
 * Creates custom theme headers. This is the information shown in the header
 * block of a theme's 'style.css' file. Themes are not required to use this
 * information, but the framework does make use of the data for displaying
 * additional information to the theme user.
 *
 * @since 1.0.10
 * @access public
 *
 * @param  array $headers Array of extra headers.
 * @return array          Array of custom headers.
 */
function exmachina_extra_theme_headers( $headers ) {

  /* Add support for 'Template Version'. This is for use in child themes to note the version of the parent theme. */
  if ( !in_array( 'Template Version', $headers ) )
    $headers[] = 'Template Version';

  /* Add support for 'License'.  Proposed in the guidelines for the WordPress.org theme review. */
  if ( !in_array( 'License', $headers ) )
    $headers[] = 'License';

  /* Add support for 'License URI'. Proposed in the guidelines for the WordPress.org theme review. */
  if ( !in_array( 'License URI', $headers ) )
    $headers[] = 'License URI';

  /* Add support for 'Support URI'.  This should be a link to the theme's support forums. */
  if ( !in_array( 'Support URI', $headers ) )
    $headers[] = 'Support URI';

  /* Add support for 'Documentation URI'.  This should be a link to the theme's documentation. */
  if ( !in_array( 'Documentation URI', $headers ) )
    $headers[] = 'Documentation URI';

  /* Return the array of custom theme headers. */
  return $headers;

} // end function exmachina_extra_theme_headers()

/**
 * Meta Template
 *
 * Generates the relevant template info. Adds template meta with theme version.
 * Uses the theme name and version from style.css.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_get_theme
 * @link http://codex.wordpress.org/Function_Reference/esc_attr
 *
 * @uses apply_atomic() Applies the contextual filter hook.
 *
 * @since 1.0.10
 * @access public
 *
 * @return void
 */
function exmachina_meta_template() {

  /* Get the theme template info. */
  $theme = wp_get_theme( get_template() );

  /* Set the meta template markup. */
  $template = '<meta name="template" content="' . esc_attr( $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ) ) . '" />' . "\n";

  /* Echo with filters. */
  echo apply_atomic( 'meta_template', $template );

} // end function exmachina_meta_template()

/**
 * Site Title Element
 *
 * Dynamic element to wrap the site title in. If it is the front page, wrap it
 * in an <h1> element. On other pages, wrap it in a <div> element.
 *
 * @link http://codex.wordpress.org/Function_Reference/is_front_page
 * @link http://codex.wordpress.org/Function_Reference/get_bloginfo
 * @link http://codex.wordpress.org/Function_Reference/tag_escape
 * @link http://codex.wordpress.org/Function_Reference/home_url
 * @link http://codex.wordpress.org/Function_Reference/esc_attr
 *
 * @uses apply_atomic() Applies the contextual filter hook.
 *
 * @since 1.0.10
 * @access public
 *
 * @return void
 */
function exmachina_site_title() {

  /* If viewing the front page of the site, use an <h1> tag.  Otherwise, use a <div> tag. */
  $tag = ( is_front_page() ) ? 'h1' : 'div';

  /* Get the site title.  If it's not empty, wrap it with the appropriate HTML. */
  if ( $title = get_bloginfo( 'name' ) )
    $title = sprintf( '<%1$s id="site-title"><a href="%2$s" title="%3$s" rel="home"><span>%4$s</span></a></%1$s>', tag_escape( $tag ), home_url(), esc_attr( $title ), $title );

  /* Display the site title and apply filters for developers to overwrite. */
  echo apply_atomic( 'site_title', $title );

} // end function exmachina_site_title()

/**
 * Site Description Element
 *
 * Dynamic element to wrap the site description in. If it is the front page,
 * wrap it in an <h2> element. On other pages, wrap it in a <div> element.
 *
 * @link http://codex.wordpress.org/Function_Reference/is_front_page
 * @link http://codex.wordpress.org/Function_Reference/get_bloginfo
 * @link http://codex.wordpress.org/Function_Reference/tag_escape
 *
 * @uses apply_atomic() Applies the contextual filter hook.
 *
 * @since 1.0.10
 * @access public
 *
 * @return void
 */
function exmachina_site_description() {

  /* If viewing the front page of the site, use an <h2> tag.  Otherwise, use a <div> tag. */
  $tag = ( is_front_page() ) ? 'h2' : 'div';

  /* Get the site description.  If it's not empty, wrap it with the appropriate HTML. */
  if ( $desc = get_bloginfo( 'description' ) )
    $desc = sprintf( '<%1$s id="site-description"><span>%2$s</span></%1$s>', tag_escape( $tag ), $desc );

  /* Display the site description and apply filters for developers to overwrite. */
  echo apply_atomic( 'site_description', $desc );

} // end function exmachina_site_description()

/**
 * Footer Content
 *
 * Standardized function for outputting the footer content.
 *
 * @link http://codex.wordpress.org/Function_Reference/current_theme_supports
 *
 * @uses apply_atomic_shortcode() Applies the contextual shortcode.
 * @uses exmachina_get_setting()  Gets the setting from the options db.
 *
 * @since 1.0.10
 * @access public
 *
 * @return void
 */
function exmachina_footer_content() {

  /* Only run the code if the theme supports the ExMachina Core theme settings. */
  if ( current_theme_supports( 'exmachina-core-theme-settings' ) )
    echo apply_atomic_shortcode( 'footer_content', exmachina_get_option( 'footer_insert' ) );

} // end function exmachina_footer_content()

/**
 * Has Post Template
 *
 * Checks if a post of any post type has a custom template. This is the equivalent
 * of WordPress' is_page_template() function with the exception that it works for
 * all post types.
 *
 * @link http://codex.wordpress.org/Function_Reference/is_singular
 * @link http://codex.wordpress.org/Function_Reference/get_queried_object
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta
 *
 * @since 1.0.10
 * @access public
 *
 * @param  string $template The name of the template to check for.
 * @return bool             Whether the post has a template.
 */
function exmachina_has_post_template( $template = '' ) {

  /* Assume we're viewing a singular post. */
  if ( is_singular() ) {

    /* Get the queried object. */
    $post = get_queried_object();

    /* Get the post template, which is saved as metadata. */
    $post_template = get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true );

    /* If a specific template was input, check that the post template matches. */
    if ( !empty( $template) && ( $template == $post_template ) )
      return true;

    /* If no specific template was input, check if the post has a template. */
    elseif ( empty( $template) && !empty( $post_template ) )
      return true;
  }

  /* Return false for everything else. */
  return false;

} // end function exmachina_has_post_template()

/* Filters the title for untitled posts. */
add_filter( 'the_title', 'exmachina_untitled_post' );

/**
 * Untitled Post Filter
 *
 * The WordPress.org theme review requires that a link be provided to the single
 * post page for untitled posts. This is a filter on 'the_title' so that an
 * '(Untitled)' title appears in that scenario, allowing for the normal method
 * to work.
 *
 * @link http://codex.wordpress.org/Function_Reference/is_singular
 * @link http://codex.wordpress.org/Function_Reference/in_the_loop
 * @link http://codex.wordpress.org/Function_Reference/is_admin
 *
 * @since 1.0.10
 * @access public
 *
 * @param  string $title The title string.
 * @return string        The title.
 */
function exmachina_untitled_post( $title ) {

  /* If the title is empty, set to '(Untitled)'. */
  if ( empty( $title ) && !is_singular() && in_the_loop() && !is_admin() )
    $title = __( '(Untitled)', 'exmachina-core' );

  /* Return the title. */
  return $title;

} // end function exmachina_untitled_post()

/**
 * Locate Theme File
 *
 * Retrieves the file with the highest priority that exists. The function
 * searches both the stylesheet and template directories. This function is
 * similar to the locate_template() function in WordPress but returns the file
 * name with the URI path instead of the directory path.
 *
 * @link http://codex.wordpress.org/Function_Reference/is_child_theme
 * @link http://codex.wordpress.org/Function_Reference/get_stylesheet_directory
 * @link http://codex.wordpress.org/Function_Reference/get_stylesheet_directory_uri
 * @link http://codex.wordpress.org/Function_Reference/get_template_directory
 * @link http://codex.wordpress.org/Function_Reference/get_template_directory_uri
 *
 * @since 1.0.10
 * @access public
 *
 * @param  array  $file_name The files to search for.
 * @return string            The located file.
 */
function exmachina_locate_theme_file( $file_name ) {

  $located = '';

  /* Loops through each of the given file names. */
  foreach ( (array) $file_names as $file ) {

    /* If the file exists in the stylesheet (child theme) directory. */
    if ( is_child_theme() && file_exists( trailingslashit( get_stylesheet_directory() ) . $file ) ) {
      $located = trailingslashit( get_stylesheet_directory_uri() ) . $file;
      break;
    }

    /* If the file exists in the template (parent theme) directory. */
    elseif ( file_exists( trailingslashit( get_template_directory() ) . $file ) ) {
      $located = trailingslashit( get_template_directory_uri() ) . $file;
      break;
    }
  }

  return $located;

} // end function exmachina_locate_theme_file()

/**
 * Post Has Content
 *
 * Checks if a post has any content. Useful if you need to check if the user has
 * written any content before performing any actions.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_post
 *
 * @since 1.0.10
 * @access public
 *
 * @param  integer $id The ID of the post.
 * @return boolean
 */
function exmachina_post_has_content( $id = 0 ) {

  /* Get the post via post ID. */
  $post = get_post( $id );

  /* Returns true if post content is empty, false if not. */
  return !empty( $post->post_content ) ? true : false;

} // end function exmachina_post_has_content()

/**
 * Audio Attachment Check
 *
 * Checks if the current post has a mime type of 'audio'.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID
 * @link http://codex.wordpress.org/Function_Reference/get_post_mime_type
 *
 * @since 1.0.10
 * @access public
 *
 * @param  integer $post_id The ID of the post.
 * @return boolean
 */
function exmachina_attachment_is_audio( $post_id = 0 ) {

  $post_id = empty( $post_id ) ? get_the_ID() : $post_id;

  $mime = get_post_mime_type( $post_id );
  $mime_type = explode( '/', $mime );

  return 'audio' == array_shift( $mime_type ) ? true : false;

} // end function exmachina_attachment_is_audio()

/**
 * Video Attachment Check
 *
 * Checks if the current post has a mime type of 'video'.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID
 * @link http://codex.wordpress.org/Function_Reference/get_post_mime_type
 *
 * @since 1.0.10
 * @access public
 *
 * @param  integer $post_id The ID of the post.
 * @return boolean
 */
function exmachina_attachment_is_video( $post_id = 0 ) {

  $post_id = empty( $post_id ) ? get_the_ID() : $post_id;

  $mime = get_post_mime_type( $post_id );
  $mime_type = explode( '/', $mime );

  return 'video' == array_shift( $mime_type ) ? true : false;

} // end function exmachina_attachment_is_video()

/**
 * Categorized Blog Check
 *
 * Returns true if a blog has more than one category.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_transient
 * @link http://codex.wordpress.org/Function_Reference/get_categories
 * @link http://codex.wordpress.org/Function_Reference/set_transient
 *
 * @since 1.0.10
 * @access public
 *
 * @return boolean
 */
function exmachina_categorized_blog() {

  if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
    // Create an array of all the categories that are attached to posts
    $all_the_cool_cats = get_categories( array(
      'hide_empty' => 1,
    ) );

    // Count the number of categories that are attached to the posts
    $all_the_cool_cats = count( $all_the_cool_cats );

    set_transient( 'all_the_cool_cats', $all_the_cool_cats );
  }

  if ( '1' != $all_the_cool_cats ) {
    // This blog has more than 1 category so exmachina_categorized_blog should return true
    return true;
  } else {
    // This blog has only 1 category so exmachina_categorized_blog should return false
    return false;
  }

} // end function exmachina_categorized_blog()

add_action( 'edit_category', 'exmachina_category_transient_flush' );
add_action( 'save_post',     'exmachina_category_transient_flush' );

/**
 * Category Transient Flush
 *
 * Flush out the transients used in exmachina_categorized_blog().
 *
 * @link http://codex.wordpress.org/Function_Reference/delete_transient
 *
 * @since 1.0.10
 * @access public
 *
 * @return void
 */
function exmachina_category_transient_flush() {

  /* Delete the transient. */
  delete_transient( 'all_the_cool_cats' );

} // end function exmachina_category_transient_flush()

/* Add custom menu args. */
add_filter( 'wp_page_menu_args', 'exmachina_custom_page_menu_args' );

/**
 * Custom Page Menu Arguments
 *
 * Filters the wp_nav_menu() fallback to show a link to the homepage.
 *
 * @since 1.0.10
 * @access public
 *
 * @param  array $args The menu arguments.
 * @return array       The menu arguments.
 */
function exmachina_custom_page_menu_args( $args ) {

  /* Set the 'show_home' $args to true. */
  $args['show_home'] = true;

  /* Return the $args. */
  return $args;

} // end function exmachina_custom_page_menu_args()

/* Add custom body classes. */
add_filter( 'body_class', 'exmachina_custom_body_classes' );

/**
 * Custom Body Classes
 *
 * Adds custom classes to the array of body classes.
 *
 * @link http://codex.wordpress.org/Function_Reference/is_multi_author
 *
 * @since 1.0.10
 * @access public
 *
 * @param  array $classes Array of body classes.
 * @return array          Custom body class array.
 */
function exmachina_custom_body_classes( $classes ) {

  /* Adds a 'group-blog' class to multi-author blogs. */
  if ( is_multi_author() ) {
    $classes[] = 'group-blog';
  }

  /* Return the custom class array. */
  return $classes;

} // end function exmachina_custom_body_classes()

/* Render a custom admn bar. */
add_action( 'wp_before_admin_bar_render', 'exmachina_adminbar' );

/**
 * Custom Admin Bar
 *
 * Adds the theme settings menu item to the WordPress admin bar.
 *
 * @link http://codex.wordpress.org/Class_Reference/WP_Admin_Bar
 * @link http://codex.wordpress.org/Function_Reference/add_menu
 * @link http://codex.wordpress.org/Function_Reference/admin_url
 *
 * @since 1.0.10
 * @access public
 *
 * @global object $wp_admin_bar  The admin bar object.
 * @return void
 */
function exmachina_adminbar() {
  global $wp_admin_bar;

  $wp_admin_bar->add_menu( array(
      'parent' => 'appearance',
      'id' => 'theme-settings',
      'title' => __( 'Theme Settings', 'exmachina-core' ),
      'href' => admin_url( 'themes.php?page=theme-settings' )
    ));

} // end function exmachina_adminbar()

/**
 * Default Menu Display
 *
 * Display page list when no menu is assigned. Based on the wp_list_pages()
 * function from the WordPress core.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_parse_args
 * @link http://codex.wordpress.org/Function_Reference/is_front_page
 * @link http://codex.wordpress.org/Function_Reference/is_paged
 * @link http://codex.wordpress.org/Function_Reference/home_url
 * @link http://codex.wordpress.org/Function_Reference/esc_attr
 * @link http://codex.wordpress.org/Function_Reference/get_option
 * @link http://codex.wordpress.org/Function_Reference/wp_list_pages
 *
 * @uses exmachina_get_prefix() Gets the theme prefix.
 *
 * @since 1.0.10
 * @access public
 *
 * @param  array  $args Array of menu arguments.
 * @return string       Returns HTML menu.
 */
function exmachina_default_menu( $args = array() ) {

  $defaults = array('sort_column' => 'menu_order, post_title', 'menu_class' => 'menu', 'echo' => true, 'link_before' => '', 'link_after' => '');
  $args = wp_parse_args( $args, $defaults );
  $args = apply_filters( exmachina_get_prefix() . '_default_menu_args', $args );

  $menu = '';

  $list_args = $args;

  // Show Home in the menu
  if ( ! empty($args['show_home']) ) {
    if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
      $text = __('Home', 'exmachina-core');
    else
      $text = $args['show_home'];
    $class = '';
    if ( is_front_page() && !is_paged() )
      $class = 'class="current_page_item"';
    $menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '" title="' . esc_attr($text) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
    // If the front page is a page, add it to the exclude list
    if (get_option('show_on_front') == 'page') {
      if ( !empty( $list_args['exclude'] ) ) {
        $list_args['exclude'] .= ',';
      } else {
        $list_args['exclude'] = '';
      }
      $list_args['exclude'] .= get_option('page_on_front');
    }
  }

  $list_args['echo'] = false;
  $list_args['title_li'] = '';
  $menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages($list_args) );

  if ( $menu )
    $menu = '<ul class="' . esc_attr($args['menu_class']) . '">' . $menu . '</ul>';

  $menu = apply_filters( exmachina_get_prefix() . '_default_menu', $menu, $args );
  if ( $args['echo'] )
    echo $menu;
  else
    return $menu;
} // end function exmachina_default_menu()

/**
 * Truncate Phrase
 *
 * Return a phrase shortened in length to a maximum number of characters.
 *
 * Result will be truncated at the last white space in the original string.
 * In this function the word separator is a single space. Other white space
 * characters (like newlines and tabs) are ignored.
 *
 * If the first `$max_characters` of the string does not contain a space
 * character, an empty string will be returned.
 *
 * @since 1.0.10
 * @access public
 *
 * @param  string $text           The string to be shortened.
 * @param  int    $max_characters The maximum number of characters to return.
 * @return string                 The truncated string.
 */
function exmachina_truncate_phrase( $text, $max_characters ) {

  $text = trim( $text );

  if ( mb_strlen( $text ) > $max_characters ) {
    /* Truncate $text to $max_characters + 1. */
    $text = mb_substr( $text, 0, $max_characters + 1 );

    /* Truncate to the last space in the truncated string. */
    $text = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
  }

  return $text;

} // end function exmachina_truncate_phrase()

/**
 * Get the Content Limit
 *
 * Return content stripped down and limited content. Strips out tags and
 * shortcodes, limits the output to `$max_char` characters, and appends an
 * ellipsis and more link to the end.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_the_content
 * @link http://codex.wordpress.org/Function_Reference/strip_shortcodes
 * @link http://codex.wordpress.org/Function_Reference/get_permalink
 *
 * @uses exmachina_truncate_phrase() [description]
 *
 * @since 1.0.10
 * @access public
 *
 * @param  int     $max_characters The maximum number of characters to return.
 * @param  string  $more_link_text Optional. Text of the more link. Default is "(more...)".
 * @param  boolean $stripteaser    Optional. Strip teaser content before the more text. Default is false.
 * @return string                  The limited content.
 */
function get_the_content_limit( $max_characters, $more_link_text = '(more...)', $stripteaser = false ) {

  $content = get_the_content( '', $stripteaser );

  //* Strip tags and shortcodes so the content truncation count is done correctly
  $content = strip_tags( strip_shortcodes( $content ), apply_filters( 'get_the_content_limit_allowedtags', '<script>,<style>' ) );

  //* Remove inline styles / scripts
  $content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

  //* Truncate $content to $max_char
  if ($max_characters < strlen( $content )) {
    $content = exmachina_truncate_phrase( $content, $max_characters );
    $no_more = false;
  } else {
    $no_more = true;
  }

  //* More link?
  if ( $more_link_text && !$no_more )  {
    $link   = apply_filters( 'get_the_content_more_link', sprintf( '&#x02026; <a href="%s" class="more-link">%s</a>', get_permalink(), $more_link_text ), $more_link_text );
    $output = sprintf( '<p>%s %s</p>', $content, $link );
  } else {
    $output = sprintf( '<p>%s</p>', $content );
    $link = '';
  }

  return apply_filters( 'get_the_content_limit', $output, $content, $link, $max_characters );

} // end function get_the_content_limit()

/**
 * Echo the Content Limit
 *
 * Echos the limited content.
 *
 * @uses get_the_content_limit() Gets the truncated content.
 *
 * @since 1.0.10
 * @access public
 *
 * @param  int     $max_characters The maximum number of characters to return.
 * @param  string  $more_link_text Optional. Text of the more link. Default is "(more...)".
 * @param  boolean $stripteaser    Optional. Strip teaser content before the more text. Default is false.
 * @return string                  The limited content.
 */
function the_content_limit( $max_characters, $more_link_text = '(more...)', $stripteaser = false ) {

  /* Gets the content limit. */
  $content = get_the_content_limit( $max_characters, $more_link_text, $stripteaser );

  /* Apply the filter and echo the content. */
  echo apply_filters( 'the_content_limit', $content );

} // end function the_content_limit()

/**
 * Admin Redirect
 *
 * Redirect the user to an admin page and add query args to the URL string for
 * alerts, etc.
 *
 * @link http://codex.wordpress.org/Function_Reference/menu_page_url
 * @link http://codex.wordpress.org/Function_Reference/add_query_arg
 * @link http://codex.wordpress.org/Function_Reference/wp_redirect
 *
 * @since 1.0.10
 * @access public
 *
 * @param  string $page       Menu slug.
 * @param  array  $query_args Optional. Associative array of query string arguments.
 * @return null               Return early if not on a page.
 */
function exmachina_admin_redirect( $page, array $query_args = array() ) {

  /* If not a page, return. */
  if ( ! $page )
    return;

  /* Define the menu page url. */
  $url = html_entity_decode( menu_page_url( $page, 0 ) );

  /* Loop through and unset the $query_args. */
  foreach ( (array) $query_args as $key => $value ) {
    if ( empty( $key ) && empty( $value ) ) {
      unset( $query_args[$key] );
    } // end if (empty($key) && empty($value))
  } // end foreach ((array) $query_args as $key => $value)

  /* Add the $query_args to the url. */
  $url = add_query_arg( $query_args, $url );

  /* Redirect to the admin page. */
  wp_redirect( esc_url_raw( $url ) );

} // end function exmachina_admin_redirect()

/**
 * Menu Page Check
 *
 * Check to see that the theme is targetting a specific admin page.
 *
 * @since 1.0.10
 * @access public
 *
 * @global string   $page_hook  Page hook of the current page.
 * @param  string   $pagehook   Page hook string to check.
 * @return boolean              Returns true if the global $page_hook matches the given $pagehook.
 */
function exmachina_is_menu_page( $pagehook = '' ) {

  /* Globalize the $page_hook variable. */
  global $page_hook;

  /* Return true if on the define $pagehook. */
  if ( isset( $page_hook ) && $page_hook === $pagehook )
    return true;

  /* May be too early for $page_hook. */
  if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === $pagehook )
    return true;

  /* Otherwise, return false. */
  return false;

} // end function exmachina_is_menu_page()

/**
 * Settings Page Capability
 *
 * Returns the required capability for viewing and saving theme settings.
 *
 * @link http://codex.wordpress.org/Roles_and_Capabilities
 *
 * @uses exmachina_get_prefix() Gets the theme prefix.
 *
 * @since 1.0.10
 * @access public
 *
 * @return string   The filtered page capability.
 */
function exmachina_settings_page_capability() {
  return apply_filters( exmachina_get_prefix() . '_settings_capability', 'edit_theme_options' );

} // end function exmachina_settings_page_capability()

/**
 * Get Help Sidebar
 *
 * Adds a help tab to the theme settings screen if the theme has provided a
 * 'Documentation URI' and/or 'Support URI'. Theme developers can add custom help
 * tabs using get_current_screen()->add_help_tab().
 *
 * @since 1.5.6
 * @access public
 *
 * @return void
 */
function exmachina_get_help_sidebar() {

  /* Get the parent theme data. */
  $theme = wp_get_theme( get_template() );
  $theme_uri = $theme->get( 'ThemeURI' );
  $author_uri = $theme->get( 'AuthorURI' );
  $doc_uri = $theme->get( 'Documentation URI' );
  $support_uri = $theme->get( 'Support URI' );

  /* If the theme has provided a theme or author URI, add them to the help text. */
  if ( !empty( $theme_uri ) || !empty( $author_uri ) ) {

    /* Open an unordered list for the help text. */
    $help = '<p><strong>' . sprintf( esc_html__( '%1s %2s:', 'exmachina-core' ), __( 'About', 'exmachina-core' ), $theme->get( 'Name' ) ) . '</strong></p>';
    //$help = '<p><strong>' . __( 'For more information:', 'exmachina-core' ) . '</strong></p>';
    $help .= '<ul>';

    /* Add the Documentation URI. */
    if ( !empty( $theme_uri ) )
      $help .= '<li><a href="' . esc_url( $theme_uri ) . '" target="_blank" title="' . __( 'Theme Homepage', 'exmachina-core' ) . '">' . __( 'Theme Homepage', 'exmachina-core' ) . '</a></li>';

    /* Add the Support URI. */
    if ( !empty( $author_uri ) )
      $help .= '<li><a href="' . esc_url( $author_uri ) . '" target="_blank" title="' . __( 'Author Homepage', 'exmachina-core' ) . '">' . __( 'Author Homepage', 'exmachina-core' ) . '</a></li>';

    /* Close the unordered list for the help text. */
    $help .= '</ul>';

  }


  /* If the theme has provided a documentation or support URI, add them to the help text. */
  if ( !empty( $doc_uri ) || !empty( $support_uri ) ) {

    /* Open an unordered list for the help text. */
    $help .= '<p><strong>' . __( 'For more information:', 'exmachina-core' ) . '</strong></p>';
    $help .= '<ul>';

    /* Add the Documentation URI. */
    if ( !empty( $doc_uri ) )
      $help .= '<li><a href="' . esc_url( $doc_uri ) . '" target="_blank" title="' . __( 'Documentation', 'exmachina-core' ) . '">' . __( 'Documentation', 'exmachina-core' ) . '</a></li>';

    /* Add the Support URI. */
    if ( !empty( $support_uri ) )
      $help .= '<li><a href="' . esc_url( $support_uri ) . '" target="_blank" title="' . __( 'Support', 'exmachina-core' ) . '">' . __( 'Support', 'exmachina-core' ) . '</a></li>';

    /* Close the unordered list for the help text. */
    $help .= '</ul>';

  }

  /* Return the help content. */
  return $help;

} // end function exmachina_get_help_sidebar()