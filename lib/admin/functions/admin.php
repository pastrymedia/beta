<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Main Admin Functions
 *
 * admin.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Theme administration functions used with other components of the framework
 * admin. This file is for setting up any basic features and holding additional
 * admin helper functions.
 *
 * @package     ExMachina
 * @subpackage  Admin Functions
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright (c) 2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com
 */
###############################################################################
# Begin functions
###############################################################################

/* Add the admin setup function to the 'exmachina_setup' hook. */
add_action( 'exmachina_setup', 'exmachina_admin_setup' );

/**
 * Admin Setup
 *
 * Sets up the administration functionality for the framework and themes. This
 * files serves as an action hook loader for other functions used in the theme
 * settings and on the backend of the site.
 *
 * @uses exmachina_admin_load_post_meta_boxes() Loads the post metaboxes.
 * @uses exmachina_admin_register_styles()      Register admin styles.
 * @uses exmachina_admin_enqueue_styles()       Enqueue admin styles.
 *
 * @since 1.5.3
 * @access public
 *
 * @return void
 */
function exmachina_admin_setup() {

  /* Adds custom icons to the admin menu. */
  add_action( 'admin_head', 'exmachina_admin_custom_icons' );

  /* Load the post meta boxes on the new post and edit post screens. */
  add_action( 'load-post.php', 'exmachina_admin_load_post_meta_boxes' );
  add_action( 'load-post-new.php', 'exmachina_admin_load_post_meta_boxes' );

  /* Registers vendor stylesheets and javascripts for the framework. */
  add_action( 'admin_enqueue_scripts', 'exmachina_admin_vendor_assets', 1 );

  /* Registers admin stylesheets and javascripts for the framework. */
  add_action( 'admin_enqueue_scripts', 'exmachina_admin_register_scripts', 1 );
  add_action( 'admin_enqueue_scripts', 'exmachina_admin_register_styles', 1 );

  /* Loads admin stylesheets for the framework. */
  add_action( 'admin_enqueue_scripts', 'exmachina_admin_enqueue_styles' );

} // end function exmachina_admin_setup()

/**
 * Custom Admin Icons
 *
 * Adds custom icons to the top level admin menus. Admin menu icons are located
 * in the 'EXMACHINA_ADMIN_IMAGES' directory under 'icons.'
 *
 * @link http://randyjensenonline.com/thoughts/wordpress-custom-post-type-fugue-icons/
 *
 * @since 1.5.3
 * @return void
 */
function exmachina_admin_custom_icons() {
  ?>
    <style type="text/css" media="screen">
        #toplevel_page_theme-settings .wp-menu-image {
          background: url(<?php echo trailingslashit( EXMACHINA_ADMIN_IMAGES ) . 'icons/control-power.png'; ?>) no-repeat 6px -17px !important;
        }
        #toplevel_page_theme-settings:hover .wp-menu-image,
        #toplevel_page_theme-settings.current .wp-menu-image,
        #toplevel_page_theme-settings.wp-has-current-submenu .wp-menu-image {
          background-position:6px 7px!important;
        }
    </style>
  <?php

} // end function exmachina_admin_custom_icons()

/**
 * Load Post Metaboxes
 *
 * Loads the core post meta box files on the 'load-post.php' action hook. Each
 * meta box file is only loaded if the theme declares support for the feature.
 *
 * @link http://codex.wordpress.org/Function_Reference/require_if_theme_supports
 *
 * @since 1.5.3
 * @access public
 *
 * @return void
 */
function exmachina_admin_load_post_meta_boxes() {

  /* Load the SEO post meta box. */
  require_if_theme_supports( 'exmachina-core-seo', trailingslashit( EXMACHINA_ADMIN ) . 'meta-box-post-seo.php' );

  /* Load the post template meta box. */
  require_if_theme_supports( 'exmachina-core-template-hierarchy', trailingslashit( EXMACHINA_ADMIN ) . 'meta-box-post-template.php' );

} // end function exmachina_admin_load_post_meta_boxes()

/**
 * Register Vendor Admin Assets
 *
 * Registers external assets, plugins, and packages that were developed outside
 * of the frameword (vendor assets). This function does not load the JavaScript
 * files or CSS Stylesheets, it merely registers them for use within WordPress.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_register_script
 * @link http://codex.wordpress.org/Function_Reference/wp_register_style
 *
 * @since 1.5.3
 * @access public
 *
 * @return void
 */
function exmachina_admin_vendor_assets() {

  /* Use the .min version if SCRIPT_DEBUG is turned off. */
  $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

  /* Register the uikit framework CSS and JS. */
  wp_register_script( 'exmachina-uikit-admin-js', esc_url( trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "uikit/js/uikit{$suffix}.js" ), array( 'jquery' ), '1.1.0', true );
  wp_register_style( 'exmachina-uikit-admin-css', trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "uikit/css/uikit.gradient{$suffix}.css", false, '1.1.0', 'screen' );

  /* Register the bootstrap framework CSS and JS. */
  wp_register_script( 'exmachina-bootstrap-admin-js', esc_url( trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "bootstrap/js/bootstrap{$suffix}.js" ), array( 'jquery' ), '3.0.0', true );
  wp_register_style( 'exmachina-bootstrap-admin-css', trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "bootstrap/css/bootstrap{$suffix}.css", false, '3.0.0', 'screen' );

  /* Register the minicolors jQuery plugin CSS and JS. */
  wp_register_script( 'exmachina-minicolors-js', esc_url( trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "minicolors/js/jquery.minicolors{$suffix}.js" ), array( 'jquery' ), '2.1.0', true );
  wp_register_style( 'exmachina-minicolors-css', trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "minicolors/css/jquery.minicolors{$suffix}.css", false, '2.1.0', 'screen' );

  /* Register the select2 jQuery plugin CSS and JS. */
  wp_register_script( 'exmachina-select-js', esc_url( trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "select/js/select{$suffix}.js" ), array( 'jquery' ), '3.4.2', true );
  wp_register_style( 'exmachina-select-css', trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "select/css/select{$suffix}.css", false, '3.4.2', 'screen' );

  /* Register codemirror plugin CSS and JS. */
  wp_register_script( 'exmachina-codemirror-js', esc_url( trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "codemirror/js/codemirror{$suffix}.js" ), array(), '3.1.6', true );
  wp_register_script( 'exmachina-codemirror-mode-js', esc_url( trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "codemirror/js/codemirror.mode{$suffix}.js" ), array( 'jquery' ), '3.1.6', true );
  wp_register_style( 'exmachina-codemirror-css', trailingslashit( EXMACHINA_ADMIN_VENDOR ) . "codemirror/css/codemirror{$suffix}.css", false, '3.1.6', 'screen' );

} // end function exmachina_admin_vendor_assets()

/**
 * Register Admin JavaScript
 *
 * Registers the framework's core javascript files. The function does not load
 * the JavaScript files, it merely registers them for use within WordPress.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_register_script
 *
 * @since 1.5.3
 * @access public
 *
 * @return void
 */
function exmachina_admin_register_scripts() {

  /* Use the .min stylesheet if SCRIPT_DEBUG is turned off. */
  $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

  /* Register the admin typography preview JavaScript file. */
  wp_register_script( 'exmachina-core-admin-typography-js', trailingslashit( EXMACHINA_ADMIN_JS ) . "font-preview{$suffix}.js", array( 'jquery' ), '1.0.0', true );

  /* Register the admin setup JavaScript file. */
  wp_register_script( 'exmachina-core-admin-setup-js', trailingslashit( EXMACHINA_ADMIN_JS ) . "setup{$suffix}.js", array( 'jquery' ), '1.0.0', true );

  /* Register the core admin JavaScript file. */
  wp_register_script( 'exmachina-core-admin-js', trailingslashit( EXMACHINA_ADMIN_JS ) . "admin{$suffix}.js", array( 'jquery' ), '1.0.0', false );

} // end function exmachina_admin_register_scripts()

/**
 * Register Admin CSS Stylesheets
 *
 * Registers the framework's core stylesheet files. The function does not load
 * the stylesheets, it merely registers them for use within WordPress.
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_register_style
 *
 * @since 1.5.3
 * @return void
 */
function exmachina_admin_register_styles() {

  /* Use the .min stylesheet if SCRIPT_DEBUG is turned off. */
  $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

  /* Register the admin normalize CSS stylesheet file. */
  wp_register_style( 'exmachina-core-admin-reset-css', trailingslashit( EXMACHINA_ADMIN_CSS ) . "reset{$suffix}.css", false, '1.0.0', 'screen' );

  /* Register the admin editor CSS stylesheet file. */
  wp_register_style( 'exmachina-core-admin-editor-css', trailingslashit( EXMACHINA_ADMIN_CSS ) . "editor{$suffix}.css", false, '1.0.0', 'screen' );

  /* Register the admin widgets CSS stylesheet file. */
  wp_register_style( 'exmachina-core-admin-widgets-css', trailingslashit( EXMACHINA_ADMIN_CSS ) . "widgets{$suffix}.css", false, '1.0.0', 'screen' );

  /* Register the admin colorpicker CSS stylesheet file. */
  wp_register_style( 'exmachina-core-admin-minicolors-css', trailingslashit( EXMACHINA_ADMIN_CSS ) . "minicolors{$suffix}.css", false, '1.0.0', 'screen' );

  /* Register the core admin CSS stylesheet file. */
  wp_register_style( 'exmachina-core-admin-css', trailingslashit( EXMACHINA_ADMIN_CSS ) . "admin{$suffix}.css", false, '1.0.0', 'screen' );

} // end function exmachina_admin_register_styles()

/**
 * Enqueue Admin CSS Stylesheets
 *
 * Loads the admin CSS stylesheets for admin-related features.
 *
 * @link http://codex.wordpress.org/Function_Reference/current_theme_supports
 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 *
 * @since 1.5.3
 * @access public
 *
 * @return void
 */
function exmachina_admin_enqueue_styles( $hook_suffix ) {

  /* Load admin styles if on the widgets screen and the current theme supports 'exmachina-core-widgets'. */
  if ( current_theme_supports( 'exmachina-core-widgets' ) && 'widgets.php' == $hook_suffix )
    wp_enqueue_style( 'exmachina-core-admin-widgets-css' );

} // end function exmachina_admin_enqueue_styles()

/**
 * Get Post Templates
 *
 * Function for getting an array of available custom templates with a specific
 * header. Ideally, this function would be used to grab custom singular post
 * (any post type) templates. It is a recreation of the WordPress page templates
 * function because it doesn't allow for other types of templates.
 *
 * @link http://codex.wordpress.org/Function_Reference/get_post_type_object
 * @link http://codex.wordpress.org/Function_Reference/wp_get_theme
 * @link http://codex.wordpress.org/Function_Reference/get_template
 * @link http://codex.wordpress.org/Function_Reference/is_child_theme
 *
 * @since 1.5.3
 * @access public
 *
 * @global object $exmachina      The global ExMachina object.
 * @param  string $post_type      The name of the post type to get templates for.
 * @return array  $post_templates The array of templates.
 */
function exmachina_get_post_templates( $post_type = 'post' ) {
  global $exmachina;

  /* If templates have already been called, just return them. */
  if ( !empty( $exmachina->post_templates ) && isset( $exmachina->post_templates[ $post_type ] ) )
    return $exmachina->post_templates[ $post_type ];

  /* Else, set up an empty array to house the templates. */
  else
    $exmachina->post_templates = array();

  /* Set up an empty post templates array. */
  $post_templates = array();

  /* Get the post type object. */
  $post_type_object = get_post_type_object( $post_type );

  /* Get the theme (parent theme if using a child theme) object. */
  $theme = wp_get_theme( get_template() );

  /* Get the theme PHP files one level deep. */
  $files = (array) $theme->get_files( 'php', 1 );

  /* If a child theme is active, get its files and merge with the parent theme files. */
  if ( is_child_theme() ) {
    $child = wp_get_theme();
    $child_files = (array) $child->get_files( 'php', 1 );
    $files = array_merge( $files, $child_files );
  }

  /* Loop through each of the PHP files and check if they are post templates. */
  foreach ( $files as $file => $path ) {

    /* Get file data based on the post type singular name (e.g., "Post Template", "Book Template", etc.). */
    $headers = get_file_data(
      $path,
      array(
        "{$post_type_object->name} Template" => "{$post_type_object->name} Template",
      )
    );

    /* Continue loop if the header is empty. */
    if ( empty( $headers["{$post_type_object->name} Template"] ) )
      continue;

    /* Add the PHP filename and template name to the array. */
    $post_templates[ $file ] = $headers["{$post_type_object->name} Template"];
  }

  /* Add the templates to the global $exmachina object. */
  $exmachina->post_templates[ $post_type ] = array_flip( $post_templates );

  /* Return array of post templates. */
  return $exmachina->post_templates[ $post_type ];

} // end function exmachina_get_post_templates()