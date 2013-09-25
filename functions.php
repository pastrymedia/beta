<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Beta WordPress Theme
 * Main Theme Functions
 *
 * functions.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Please do all modifications in the form
 * of a child theme.
 *
 * The functions file is used to initialize everything in the theme. It controls
 * how the theme is loaded and sets up the supported features, default actions,
 * and default filters. If making customizations, users should create a child
 * theme and make changes to its functions.php file (not this one).
 *
 * Child themes should do their setup on the 'exmachina_init' action hook with
 * a priority of 11 if they want to override parent theme features. Use a priority
 * of 9 or lower if wanted to run before the parent theme.
 *
 * @package     Beta
 * @subpackage  Functions
 * @author      Machina Themes | @machinathemes
 * @copyright   Copyright(c) 2012-2013, Machina Themes
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL-2.0+
 * @link        http://www.machinathemes.com/
 */
###############################################################################
# begin functions
###############################################################################

/* Load the core theme framework. */
require ( trailingslashit( get_template_directory() ) . 'lib/engine.php' );
new ExMachina();

/* Load beta functions */
require get_template_directory() . '/inc/beta.php';

if ( ! function_exists( 'beta_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function beta_theme_setup() {

  /* Get the theme prefix. */
  $prefix = exmachina_get_prefix();

  /* The best thumbnail/image script ever. */
  add_theme_support( 'get-the-image' );

  /* Register menus. */
  add_theme_support(
    'exmachina-core-menus',
    array( 'primary')
  );

  /* Register sidebars. */
  add_theme_support(
    'exmachina-core-sidebars',
    array( 'primary' )
  );

  /* Load scripts. */
  add_theme_support(
    'exmachina-core-scripts',
    array( 'comment-reply' )
  );

  /* Load shortcodes. */
  add_theme_support( 'exmachina-core-shortcodes' );

  add_theme_support( 'exmachina-core-theme-settings', array( 'about' ) );

  /* Enable custom template hierarchy. */
  add_theme_support( 'exmachina-core-template-hierarchy' );


  /* Enable theme layouts (need to add stylesheet support). */
  add_theme_support(
    'theme-layouts',
    array( '1c', '2c-l', '2c-r' ),
    array( 'default' => '2c-l', 'customizer' => true )
  );

  /* Add default theme settings */
  add_filter( "{$prefix}_default_theme_settings", 'beta_default_theme_settings');

  /* implement editor styling, so as to make the editor content match the resulting post output in the theme. */
  add_editor_style();

  /* Support pagination instead of prev/next links. */
  add_theme_support( 'loop-pagination' );

  /* Better captions for themes to style. */
  add_theme_support( 'cleaner-caption' );

  /* Add default posts and comments RSS feed links to <head>.  */
  add_theme_support( 'automatic-feed-links' );

  /* Enable footer widgets. */
  add_theme_support( 'footer-widgets', 3 );

  /* Enable wraps */
  add_theme_support( 'structural-wraps' );

  /* Enable custom css */
  add_theme_support( 'custom-css' );

  /* Enable custom footer */
  add_theme_support( 'custom-footer' );

  /* Enable custom logo */
  add_theme_support( 'custom-logo' );

  /* Enable responsive support */
  add_theme_support( 'responsive' );

  /* Handle content width for embeds and images. */
  exmachina_set_content_width( 640 );

  add_action( 'wp_enqueue_scripts', 'beta_scripts' );
  add_action( 'wp_head', 'beta_styles' );
  add_action( 'wp_head', 'beta_header_scripts' );
  add_action( 'wp_footer', 'beta_footer_scripts' );

  /* Header actions. */
  add_action( "{$prefix}_header", 'beta_branding' );

  /* footer insert to the footer. */
  add_action( "{$prefix}_footer", 'beta_footer_insert' );

  /* Load the primary menu. */
  add_action( "{$prefix}_before_header", 'beta_get_primary_menu' );

  /* Add the title, byline, and entry meta before and after the entry.*/
  add_action( "{$prefix}_before_entry", 'beta_entry_header' );
  add_action( "{$prefix}_entry", 'beta_entry' );
  add_action( "{$prefix}_singular_entry", 'beta_singular_entry' );
  add_action( "{$prefix}_after_entry", 'beta_entry_footer' );
  add_action( "{$prefix}_singular-page_after_entry", 'beta_page_entry_meta' );

  /* Add the primary sidebars after the main content. */
  add_action( "{$prefix}_after_main", 'beta_after_main' );

  /* Filter the sidebar widgets. */
  add_filter( 'sidebars_widgets', 'beta_disable_sidebars' );
  add_action( 'template_redirect', 'beta_one_column' );

  /* Allow developers to filter the default sidebar arguments. */
  add_filter( "{$prefix}_sidebar_defaults", 'beta_sidebar_defaults' );

  add_filter( 'beta_footer_insert', 'beta_default_footer_insert' );

  // add disqus compatibility
  if (function_exists('dsq_comments_template')) {
    remove_filter( 'comments_template', 'dsq_comments_template' );
    add_filter( 'comments_template', 'dsq_comments_template', 12 ); // You can use any priority higher than '10'
  }
}
endif; // beta_theme_setup

add_action( 'after_setup_theme', 'beta_theme_setup' );


function beta_sidebar_defaults($defaults) {
  /* Set up some default sidebar arguments. */
  $defaults = array(
    'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap">',
    'after_widget'  => '</div></section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>'
  );

  return $defaults;
}

/**
 * Adds custom default theme settings.
 *
 * @since 0.3.0
 * @access public
 * @param array $settings The default theme settings.
 * @return array $settings
 */

function beta_default_theme_settings( $settings ) {

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

  return $settings;

}

/**
 * Dynamic element to wrap the site title and site description.
 */
function beta_branding() {

  echo '<div class="title-area">';

  /* Get the site title.  If it's not empty, wrap it with the appropriate HTML. */
  if ( $title = get_bloginfo( 'name' ) ) {
    if ( $logo = get_theme_mod( 'custom_logo' ) )
      $title = sprintf( '<h1 class="site-title"><a href="%1$s" title="%2$s" rel="home"><span><img src="%3$s"/></span></a></h1>', home_url(), esc_attr( $title ), $logo );
    else
      $title = sprintf( '<h1 class="site-title"><a href="%1$s" title="%2$s" rel="home"><span>%3$s</span></a></h1>', home_url(), esc_attr( $title ), $title );
  }

  /* Display the site title and apply filters for developers to overwrite. */
  echo apply_atomic( 'site_title', $title );

  /* Get the site description.  If it's not empty, wrap it with the appropriate HTML. */
  if ( $desc = get_bloginfo( 'description' ) )
    $desc = sprintf( '<h2 class="site-description"><span>%1$s</span></h2>', $desc );

  /* Display the site description and apply filters for developers to overwrite. */
  echo apply_atomic( 'site_description', $desc );

  echo '</div>';
}


function beta_default_footer_insert( $settings ) {

  $settings = '<p class="copyright">' . __( 'Copyright &#169; [the-year] [site-link].', 'beta' ) . '</p>' . "\n\n" . '<p class="credit">' . __( 'Powered by [wp-link] and [theme-link].', 'beta' ) . '</p>';

  return $settings;

}

function beta_footer_insert() {

  echo '<div class="footer-content footer-insert">';

  if ( $footer_insert = get_theme_mod( 'custom_footer' ) ) {
    echo apply_atomic_shortcode( 'footer_content', $footer_insert );
  } else {
    echo apply_atomic_shortcode( 'footer_content', apply_filters( 'beta_footer_insert','') );
  }

  echo '</div>';
}

/**
 * Loads the menu-primary.php template.
 */
function beta_get_primary_menu() {
  get_template_part( 'partials/menu', 'primary' );
}


/**
 * Display the default page edit link
 */
function beta_page_entry_meta() {

  echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">[entry-edit-link]</div>' );
}

/**
 * Display sidebar
 */
function beta_after_main() {
  get_sidebar();
}


/**
 * Display the default entry header.
 */
function beta_entry_header() {

  echo '<header class="entry-header">';

  if ( is_home() || is_archive() || is_search() ) {
  ?>
    <h1 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
  <?php
  } else {
  ?>
    <h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
  <?php
  }
  if ( 'post' == get_post_type() ) :
    get_template_part( 'partials/entry', 'byline' );
  endif;
  echo '</header><!-- .entry-header -->';

}

/**
 * Display the default entry metadata.
 */
function beta_entry() {

  if ( is_home() || is_archive() || is_search() ) {
    if(exmachina_get_setting( 'content_archive_thumbnail' )) {
      get_the_image( array( 'meta_key' => 'Thumbnail', 'default_size' => exmachina_get_setting( 'image_size' ) ) );
    }


    if ( 'excerpts' === exmachina_get_setting( 'content_archive' ) ) {
      if ( exmachina_get_setting( 'content_archive_limit' ) )
        the_content_limit( (int) exmachina_get_setting( 'content_archive_limit' ), exmachina_get_setting( 'content_archive_more' ) );
      else
        the_excerpt();
    }
    else {
      the_content( exmachina_get_setting( 'content_archive_more' ) );
    }
  }

}


function beta_excerpt_more( $more ) {
  return ' ... <a class="more-link" href="'. get_permalink( get_the_ID() ) . '">' . exmachina_get_setting( 'content_archive_more' ) . '</a>';
}
add_filter('excerpt_more', 'beta_excerpt_more');


/**
 * Display the default singular entry metadata.
 */
function beta_singular_entry() {

  the_content();

  wp_link_pages( array( 'before' => '<p class="page-links">' . '<span class="before">' . __( 'Pages:', 'beta' ) . '</span>', 'after' => '</p>' ) );

}


/**
 * Display the default entry footer.
 */
function beta_entry_footer() {

  if ( 'post' == get_post_type() ) {
    get_template_part( 'partials/entry', 'footer' );
  }

}

/**
 * Enqueue scripts and styles
 */
function beta_scripts() {
  wp_enqueue_style( 'beta-style', get_stylesheet_uri() );
}

/**
 * Insert conditional script / style for the theme used sitewide.
 */
function beta_styles() {
?>
  <!--[if lt IE 9]>
  <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
  <![endif]-->
<?php
}


/**
 * Echo header scripts in to wp_head().
 */
function beta_header_scripts() {

  echo exmachina_get_setting( 'header_scripts' );

}

/**
 * Echo the footer scripts, defined in Theme Settings.
 */
function beta_footer_scripts() {

  echo exmachina_get_setting( 'footer_scripts' );

}

/**
 * Function for deciding which pages should have a one-column layout.
 */
function beta_one_column() {

  if ( !is_active_sidebar( 'primary' ) )
    add_filter( 'theme_mod_theme_layout', 'beta_theme_layout_one_column' );

  elseif ( is_attachment() && wp_attachment_is_image() && 'default' == get_post_layout( get_queried_object_id() ) )
    add_filter( 'theme_mod_theme_layout', 'beta_theme_layout_one_column' );

}


/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 */
function beta_theme_layout_one_column( $layout ) {
  return '1c';
}


/**
 * Disables sidebars if viewing a one-column page.
 */

function beta_disable_sidebars( $sidebars_widgets ) {
  global $wp_customize;

  $customize = ( is_object( $wp_customize ) && $wp_customize->is_preview() ) ? true : false;

  if ( !is_admin() && !$customize && '1c' == get_theme_mod( 'theme_layout' ) )
    $sidebars_widgets['primary'] = false;

  return $sidebars_widgets;
}