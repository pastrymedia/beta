<?php

//* Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * ExMachina WordPress Theme Framework Engine
 * Theme Settings
 *
 * theme-settings.php
 *
 * WARNING: This file is part of the ExMachina Framework Engine. DO NOT edit
 * this file under any circumstances. Bad things will happen. Please do all
 * modifications in the form of a child theme.
 *
 * Handles the display and functionality of the theme settings page. This provides
 * the needed hooks and meta box calls to create any number of theme settings needed.
 * This file is only loaded if the theme supports the 'exmachina-core-theme-settings'
 * feature.
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

/**
 * Theme Settings Admin Subclass
 *
 * Registers a new admin page, providing content and corresponding menu item for
 * the Theme Settings page.
 *
 * @since 1.5.5
 */
class ExMachina_Admin_Theme_Settings extends ExMachina_Admin_Metaboxes {

  /**
   * Theme Settings Class Constructor
   *
   * Creates an admin menu item and settings page. This constructor method defines
   * the page id, page title, menu position, default settings, and sanitization
   * hooks.
   *
   * @link http://codex.wordpress.org/Function_Reference/wp_get_theme
   * @link http://codex.wordpress.org/Function_Reference/get_template
   * @link http://codex.wordpress.org/Function_Reference/get_theme_root
   * @link http://codex.wordpress.org/Function_Reference/get_template_directory
   *
   * @uses exmachina_get_prefix()
   * @uses \ExMachina_Admin::create()
   * @uses \ExMachina_Admin_Theme_Settings::sanitizer_filters()
   *
   * @todo prefix settings filters.
   * @todo possibly split out this method.
   * @todo create function to control capability.
   * @todo add filter to settings field (???)
   * @todo add filters to page/menu titles
   * @todo maybe remove page_ops for defaults
   *
   * @since 1.5.5
   */
  function __construct() {

    /* Get the theme prefix. */
    $prefix = exmachina_get_prefix();

    /* Get theme information. */
    $theme = wp_get_theme( get_template(), get_theme_root( get_template_directory() ) );

    /* Get menu titles. */
    $menu_title = __( 'Theme Settings', 'exmachina-core' );
    $page_title = sprintf( esc_html__( '%1s %2s', 'exmachina-core' ), $theme->get( 'Name' ), $menu_title );

    /* Specify the unique page id. */
    $page_id = 'theme-settings';

    /* Define page titles and menu position. Can be filtered using 'exmachina_theme_settings_menu_ops'. */
    $menu_ops = apply_filters(
      'exmachina_theme_settings_menu_ops',
      array(
        'main_menu' => array(
          'sep' => array(
            'sep_position'   => '58.995',
            'sep_capability' => 'edit_theme_options',
          ),
          'page_title' => $page_title,
          'menu_title' => $theme->get( 'Name' ),
          'capability' => 'edit_theme_options',
          'icon_url'   => 'div',
          'position'   => '58.996',
        ),
        'first_submenu' => array( //* Do not use without 'main_menu'
          'page_title' => $page_title,
          'menu_title' => $menu_title,
          'capability' => 'edit_theme_options',
        ),
        'theme_submenu' => array( //* Do not use without 'main_menu'
          'page_title' => $page_title,
          'menu_title' => $menu_title,
          'capability' => 'edit_theme_options',
        ),
      )
    ); // end $menu_ops

    /* Define page options (notice text and screen icon). Can be filtered using 'exmachina_theme_settings_page_ops'. */
    $page_ops = apply_filters(
      'exmachina_theme_settings_page_ops',
      array(
        'screen_icon'       => 'options-general',
        'save_button_text'  => __( 'Save Settings', 'exmachina-core' ),
        'reset_button_text' => __( 'Reset Settings', 'exmachina-core' ),
        'saved_notice_text' => __( 'Settings saved.', 'exmachina-core' ),
        'reset_notice_text' => __( 'Settings reset.', 'exmachina-core' ),
        'error_notice_text' => __( 'Error saving settings.', 'exmachina-core' ),
      )
    ); // end $page_ops

    /* Set the unique settings field id. */
    $settings_field = EXMACHINA_SETTINGS_FIELD;

    /* Define the default setting values. Can be filtered using 'exmachina_theme_settings_defaults'. */
    $default_settings = apply_filters(
      'exmachina_theme_settings_defaults',
      array(
        'theme_version'             => EXMACHINA_VERSION,
        'db_version'                => EXMACHINA_DB_VERSION,
        'release_date'              => EXMACHINA_RELEASE_DATE,
        'license_key'               => '',
        'update'                    => 1,
        'update_email'              => 0,
        'update_email_address'      => '',
        'blog_title'                => 'text',
        'style_selection'           => '',
        'site_layout'               => '',
        'nav_extras'                => '',
        'nav_extras_twitter_id'     => '',
        'nav_extras_twitter_text'   => __( 'Follow me on Twitter', 'exmachina-core' ),
        'feed_uri'                  => '',
        'redirect_feed'             => 0,
        'comments_feed_uri'         => '',
        'redirect_comments_feed'    => 0,
        'comments_pages'            => 0,
        'comments_posts'            => 1,
        'trackbacks_pages'          => 0,
        'trackbacks_posts'          => 1,
        'breadcrumb_home'           => 0,
        'breadcrumb_front_page'     => 0,
        'breadcrumb_posts_page'     => 0,
        'breadcrumb_single'         => 0,
        'breadcrumb_page'           => 0,
        'breadcrumb_archive'        => 0,
        'breadcrumb_404'            => 0,
        'breadcrumb_attachment'     => 0,
        'content_archive'           => 'full',
        'content_archive_limit'     => 0,
        'content_archive_thumbnail' => 0,
        'content_archive_more'      => '[Read more...]',
        'image_size'                => 'thumbnail',
        'posts_nav'                 => 'numeric',
        'single_nav'                => 0,
        'header_scripts'            => '',
        'footer_scripts'            => '',
        'footer_insert'             => exmachina_default_footer_insert(),
      )
    ); // end $default_settings

    /* Create the admin page. */
    $this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

    /* Initialize the sanitization filter. */
    add_action( 'exmachina_settings_sanitizer_init', array( $this, 'sanitizer_filters' ) );

  } // end function __construct()

  /**
   * Theme Settings Sanitizer Filters
   *
   * Register each of the settings with a sanitization filter type. This method
   * takes each defined setting and runs it through the appropiate type in the
   * sanitization class.
   *
   * @uses exmachina_add_option_filter() Assign a sanitization filter.
   *
   * @since 1.5.5
   */
  public function sanitizer_filters() {

    /* Apply the truthy/falsy sanitization filter. */
    exmachina_add_option_filter( 'one_zero', $this->settings_field,
      array(
        'breadcrumb_front_page',
        'breadcrumb_home',
        'breadcrumb_single',
        'breadcrumb_page',
        'breadcrumb_posts_page',
        'breadcrumb_archive',
        'breadcrumb_404',
        'breadcrumb_attachment',
        'comments_posts',
        'comments_pages',
        'content_archive_thumbnail',
        'superfish',
        'redirect_feed',
        'redirect_comments_feed',
        'trackbacks_posts',
        'trackbacks_pages',
        'update',
        'update_email',
        'single_nav',
    ) );

    /* Apply the positive integer sanitization filter. */
    exmachina_add_option_filter( 'absint', $this->settings_field,
      array(
        'blog_cat',
        'blog_cat_num',
        'content_archive_limit',
        'db_version',
    ) );

    /* Apply the URL sanitization filter. */
    exmachina_add_option_filter( 'url', $this->settings_field,
      array(
        'feed_uri',
        'comments_feed_uri',
    ) );

    /* Apply the no HTML sanitization filter. */
    exmachina_add_option_filter( 'no_html', $this->settings_field,
      array(
        'blog_cat_exclude',
        'blog_title',
        'content_archive',
        'nav_extras',
        'nav_extras_twitter_id',
        'posts_nav',
        'site_layout',
        'style_selection',
        'theme_version',
        'image_size',
        'license_key',
        'release_date',
    ) );

    /* Apply the safe HTML sanitization filter. */
    exmachina_add_option_filter( 'safe_html', $this->settings_field,
      array(
        'nav_extras_twitter_text',
        'content_archive_more',
    ) );

    /* Apply the unfiltered HTML sanitiation filter. */
    exmachina_add_option_filter( 'requires_unfiltered_html', $this->settings_field,
      array(
        'update_email_address',
        'header_scripts',
        'footer_scripts',
        'footer_insert',
    ) );

  } // end function sanitizer_filters()

  /**
   * Theme Settings Help Tabs
   *
   * Setup contextual help tabs content. This method adds the appropiate help
   * tabs based on the metaboxes/settings the theme supports.
   *
   * @link  http://codex.wordpress.org/Class_Reference/WP_Screen
   * @link  http://codex.wordpress.org/Function_Reference/get_current_screen
   * @link  http://codex.wordpress.org/Class_Reference/WP_Screen/add_help_tab
   * @link  http://codex.wordpress.org/Class_Reference/WP_Screen/set_help_sidebar
   *
   * @uses exmachina_get_help_sidebar() Gets the help sidebar content.
   *
   * @since 1.5.5
   */
  public function settings_page_help() {

    /* Get the current screen. */
    $screen = get_current_screen();

    /* Get the sidebar content. */
    $template_help = exmachina_get_help_sidebar();

    /* Get theme-supported meta boxes for the settings page. */
    $supports = get_theme_support( 'exmachina-core-theme-settings' );

    $theme_settings_help =
    '<h3>' . __( 'Theme Settings', 'exmachina-core' ) . '</h3>' .
    '<p>'  . __( 'Your Theme Settings provides control over how the theme works. You will be able to control a lot of common and even advanced features from this menu. Each of the boxes can be collapsed by clicking the box header and expanded by doing the same. They can also be dragged into any order you desire or even hidden by clicking on "Screen Options" in the top right of the screen and "unchecking" the boxes you do not want to see.', 'exmachina-core' ) . '</p>';

    $screen->add_help_tab( array(
    'id'      => $this->pagehook . '-theme-settings',
    'title'   => __( 'Theme Settings', 'exmachina-core' ),
    'content' => $theme_settings_help,
    ) );

    $customize_help =
    '<h3>' . __( 'Customize', 'exmachina-core' ) . '</h3>' .
    '<p>'  . __( 'The theme customizer is available for a real time editing environment where theme options can be tried before being applied to the live site. Click \'Customize\' button below to personalize your theme', 'exmachina-core' ) . '</p>';

    $screen->add_help_tab( array(
    'id'      => $this->pagehook . '-customize',
    'title'   => __( 'Customize', 'exmachina-core' ),
    'content' => $customize_help,
    ) );

    $updates_help =
      '<h3>' . __( 'Theme Updates', 'exmachina-core' ) . '</h3>' .
      '<p>'  . __( 'The information box allows you to see the current ExMachina theme information and display if desired.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'Normally, this should be unchecked. You can also set to enable automatic updates.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'This does not mean the updates happen automatically without your permission; it will just notify you that an update is available. You must select it to perform the update.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'If you provide an email address and select to notify that email address when the update is available, your site will email you when the update can be performed. No, updates only affect files being updated.', 'exmachina-core' ) . '</p>';

    $feeds_help =
      '<h3>' . __( 'Custom Feeds', 'exmachina-core' ) . '</h3>' .
      '<p>'  . __( 'If you use Feedburner to handle your rss feed(s) you can use this function to set your site\'s native feed to redirect to your Feedburner feed.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'By filling in the feed links calling for the main site feed, it will display as a link to Feedburner.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'By checking the "Redirect Feed" box, all traffic to default feed links will be redirected to the Feedburner link instead.', 'exmachina-core' ) . '</p>';

    $breadcrumbs_help =
      '<h3>' . __( 'Breadcrumbs', 'exmachina-core' ) . '</h3>' .
      '<p>'  . __( 'This box lets you define where the "Breadcrumbs" display. The Breadcrumb is the navigation tool that displays where a visitor is on the site at any given moment.', 'exmachina-core' ) . '</p>';

    $comments_help =
      '<h3>' . __( 'Comments <span class="amp">&amp;</span> Trackbacks', 'exmachina-core' ) . '</h3>' .
      '<p>'  . __( 'This allows a site wide decision on whether comments and trackbacks (notifications when someone links to your page) are enabled for posts and pages.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'If you enable comments or trackbacks here, it can be disabled on an individual post or page. If you disable here, they cannot be enabled on an individual post or page.', 'exmachina-core' ) . '</p>';

    $archives_help =
      '<h3>' . __( 'Content Archives', 'exmachina-core' ) . '</h3>' .
      '<p>'  . __( 'You may change the site wide Content Archives options to control what displays in the site\'s Archives.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'Archives include any pages using the blog template, category pages, tag pages, date archive, author archives, and the latest posts if there is no custom home page.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'The first option allows you to display the full post or the post excerpt. The Display full post setting will display the entire post including HTML code up to the <!--more--> tag if used (this is HTML for the comment tag that is not displayed in the browser).', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'The Display post excerpt setting will display the first 55 words of the post after also stripping any included HTML or the manual/custom excerpt added in the post edit screen.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'It may also be coupled with the second field "Limit content to [___] characters" to limit the content to a specific number of letters or spaces.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'The \'Include post image?\' setting allows you to show a thumbnail of the first attached image or currently set featured image.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'This option should not be used with the post content unless the content is limited to avoid duplicate images.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'The \'Image Size\' list is populated by the available image sizes defined in the theme.', 'exmachina-core' ) . '</p>' .
      '<p>'  . __( 'Post Navigation format allows you to select one of two navigation methods.', 'exmachina-core' ) . '</p>';
      '<p>'  . __( 'There is also a checkbox to disable previous & next navigation links on single post', 'exmachina-core' ) . '</p>';

    $scripts_help =
      '<h3>' . __( 'Header <span class="amp">&amp;</span> Footer Scripts', 'exmachina-core' ) . '</h3>' .
      '<p>'  . __( 'This provides you with two fields that will output to the head section of your site and just before the closing body tag. These will appear on every page of the site and are a great way to add analytic code, Google Font and other scripts. You cannot use PHP in these fields.', 'exmachina-core' ) . '</p>';

    $footer_help =
      '<h3>' . __( 'Footer Settings', 'exmachina-core' ) . '</h3>' .
      '<p>'  . __( 'Help content goes here', 'exmachina-core' ) . '</p>';

    /* Load the help tabs that are supported by the theme. */
    if ( is_array( $supports[0] ) ) {

      /* Load the 'Updates' meta box if it is supported. */
      if ( in_array( 'updates', $supports[0] ) )
        $screen->add_help_tab( array(
          'id'      => $this->pagehook . '-updates',
          'title'   => __( 'Theme Updates', 'exmachina-core' ),
          'content' => $updates_help,
        ) );

      /* Load the 'Feeds' meta box if it is supported. */
      if ( in_array( 'feeds', $supports[0] ) )
        $screen->add_help_tab( array(
          'id'      => $this->pagehook . '-feeds',
          'title'   => __( 'Custom Feeds', 'exmachina-core' ),
          'content' => $feeds_help,
        ) );

      /* Load the 'Breadcrumbs' meta box if it is supported. */
      if ( in_array( 'breadcrumbs', $supports[0] ) )
        $screen->add_help_tab( array(
          'id'      => $this->pagehook . '-breadcrumbs',
          'title'   => __( 'Breadcrumbs', 'exmachina-core' ),
          'content' => $breadcrumbs_help,
        ) );

      /* Load the 'Comments' meta box if it is supported. */
      if ( in_array( 'comments', $supports[0] ) )
        $screen->add_help_tab( array(
          'id'      => $this->pagehook . '-comments',
          'title'   => __( 'Comments & Trackbacks', 'exmachina-core' ),
          'content' => $comments_help,
        ) );

      /* Load the 'Archives' meta box if it is supported. */
      if ( in_array( 'archives', $supports[0] ) )
        $screen->add_help_tab( array(
          'id'      => $this->pagehook . '-archives',
          'title'   => __( 'Content Archives', 'exmachina-core' ),
          'content' => $archives_help,
        ) );

      /* Load the 'Header & Footer Scripts' meta box if it is supported. */
      if ( in_array( 'scripts', $supports[0] ) )
        $screen->add_help_tab( array(
          'id'      => $this->pagehook . '-scripts',
          'title'   => __( 'Header & Footer Scripts', 'exmachina-core' ),
          'content' => $scripts_help,
        ) );

      /* Load the 'Footer' meta box if it is supported. */
      if ( in_array( 'footer', $supports[0] ) )
        $screen->add_help_tab( array(
          'id'      => $this->pagehook . '-footer',
          'title'   => __( 'Footer Settings', 'exmachina-core' ),
          'content' => $footer_help,
        ) );



    } // end if (is_array($supports[0]))

    /* Add help sidebar. */
    $screen->set_help_sidebar(
      $template_help
    );

    /* Trigger the help content action hook. */
    do_action( 'exmachina_theme_settings_help', $this->pagehook );

  } // end function settings_page_help()

  /**
   * Theme Settings Load Metaboxes
   *
   * Registers metaboxes for the settings page. Metaboxes are only registered if
   * supported by the theme and the user capabilitiy allows it.
   *
   * @link http://codex.wordpress.org/Function_Reference/add_meta_box
   * @link http://codex.wordpress.org/Function_Reference/wp_get_theme
   * @link http://codex.wordpress.org/Function_Reference/get_theme_support
   *
   * @uses exmachina_get_prefix() Gets the theme prefix.
   * @uses \ExMachina_Admin_Theme_Settings::hidden_fields()
   * @uses \ExMachina_Admin_Theme_Settings::exmachina_metabox_theme_display_save()
   *
   * @todo prefix/add action hooks.
   *
   * @since 1.5.5
   */
  public function settings_page_load_metaboxes() {

    /* Get theme information. */
    $prefix = exmachina_get_prefix();
    $theme = wp_get_theme( get_template() );

    /* Adds hidden fields before the theme settings metabox display. */
    add_action( $this->pagehook . '_admin_before_metaboxes', array( $this, 'hidden_fields' ) );

    /* Register the 'Save Settings' metabox to the 'side' priority. */
    add_meta_box( 'exmachina-core-theme-settings-save', __( '<i class="uk-icon-save"></i> Save Settings', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_save' ), $this->pagehook, 'side', 'high' );

    /* Get theme-supported meta boxes for the settings page. */
    $supports = get_theme_support( 'exmachina-core-theme-settings' );

    /* If there are any supported meta boxes, load them. */
    if ( is_array( $supports[0] ) ) {

      /* Load the 'Theme Updates' meta box if it is supported. */
      if ( in_array( 'updates', $supports[0] ) )
      add_meta_box( 'exmachina-core-updates', __( '<i class="uk-icon-download"></i> Theme Updates', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_updates' ), $this->pagehook, 'normal', 'high' );

      /* Load the 'Style Selector' meta box if it is supported. */
      if ( in_array( 'style', $supports[0] ) )
      add_meta_box( 'exmachina-core-style', __( '<i class="uk-icon-adjust"></i> Style Selector', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_style' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Branding' meta box if it is supported. */
      if ( in_array( 'brand', $supports[0] ) )
      add_meta_box( 'exmachina-core-branding', __( '<i class="uk-icon-bullseye"></i> Brand Settings', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_brand' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Feeds' meta box if it is supported. */
      if ( in_array( 'feeds', $supports[0] ) )
      add_meta_box( 'exmachina-core-feeds', __( '<i class="uk-icon-rss"></i> Feed Settings', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_feeds' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Layout' meta box if it is supported. */
      if ( in_array( 'layout', $supports[0] ) )
      add_meta_box( 'exmachina-core-layout', __( '<i class="uk-icon-columns"></i> Global Layout', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_layout' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Header' meta box if it is supported. */
      if ( in_array( 'header', $supports[0] ) )
      add_meta_box( 'exmachina-core-header', __( '<i class="uk-icon-cog"></i> Custom Header', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_header' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Menus' meta box if it is supported. */
      if ( in_array( 'menus', $supports[0] ) )
      add_meta_box( 'exmachina-core-menus', __( '<i class="uk-icon-compass"></i> Menu Settings', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_menus' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Breadcrumbs' meta box if it is supported. */
      if ( in_array( 'breadcrumbs', $supports[0] ) )
      add_meta_box( 'exmachina-core-breadcrumbs', __( '<i class="uk-icon-chevron-right"></i> Breadcrumbs', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_breadcrumbs' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Comments' meta box if it is supported. */
      if ( in_array( 'comments', $supports[0] ) )
      add_meta_box( 'exmachina-core-comments', __( '<i class="uk-icon-comments-alt"></i> Comments <span class="amp">&amp;</span> Trackbacks', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_comments' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Archives' meta box if it is supported. */
      if ( in_array( 'archives', $supports[0] ) )
      add_meta_box( 'exmachina-core-archives', __( '<i class="uk-icon-archive"></i> Content Archives', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_archives' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Header & Footer Scripts' meta box if it is supported. */
      if ( in_array( 'scripts', $supports[0] ) )
      add_meta_box( 'exmachina-core-scripts', __( '<i class="uk-icon-code"></i> Header <span class="amp">&amp;</span> Footer Scripts', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_scripts' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'Footer' meta box if it is supported. */
      if ( in_array( 'footer', $supports[0] ) )
      add_meta_box( 'exmachina-core-footer', __( '<i class="uk-icon-reorder"></i>  Footer settings', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_footer' ), $this->pagehook, 'normal', 'default' );

      /* Load the 'About' metabox if it is supported. */
      if ( in_array( 'about', $supports[0] ) ) {

        /* Adds the About box for the parent theme. */
        add_meta_box( 'exmachina-core-about-theme', sprintf( __( '<i class="uk-icon-info-sign"></i> About %s', 'exmachina-core' ), $theme->get( 'Name' ) ), array( $this, 'exmachina_metabox_theme_display_about' ), $this->pagehook, 'side', 'default' );

        /* If the user is using a child theme, add an About box for it. */
        if ( is_child_theme() ) {
          $child = wp_get_theme();
          add_meta_box( 'exmachina-core-about-child', sprintf( __( '<i class="uk-icon-info-sign"></i> About %s', 'exmachina-core' ), $child->get( 'Name' ) ), array( $this, 'exmachina_metabox_theme_display_about' ), $this->pagehook, 'side', 'default' );
        }
      } // end if  in_array('about', $supports[0]))

      /* Load the 'Help' meta box if it is supported. */
      if ( in_array( 'help', $supports[0] ) )
      add_meta_box( 'exmachina-core-help', __( '<i class="uk-icon-question-sign"></i> Need Help', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_help' ), $this->pagehook, 'side', 'default' );

    } // end if (is_array($supports[0]))

    /* Trigger the theme settings metabox action hook. */
    do_action( 'exmachina_theme_settings_metaboxes', $this->pagehook );

  } // end function settings_page_load_metaboxes()

  /**
   * Theme Settings Hidden Fields
   *
   * Echo hidden form fields before the metaboxes. This method adds the theme
   * and database version to the settings form.
   *
   * @since 1.5.5
   *
   * @uses \ExMachina_Admin::get_field_name()  Construct field name.
   * @uses \ExMachina_Admin::get_field_value() Retrieve value of key under $this->settings_field.
   *
   * @param  string $pagehook Current page hook.
   * @return null             Returns early if not set to the correct admin page.
   */
  function hidden_fields( $pagehook ) {

    if ( $pagehook !== $this->pagehook )
      return;

    printf( '<input type="hidden" name="%s" value="%s" />', $this->get_field_name( 'theme_version' ), esc_attr( $this->get_field_value( 'theme_version' ) ) );
    printf( '<input type="hidden" name="%s" value="%s" />', $this->get_field_name( 'db_version' ), esc_attr( $this->get_field_value( 'db_version' ) ) );

  } // end function hidden_fields()

  /*-------------------------------------------------------------------------*/
  /* Begin the metabox callbacks. */
  /*-------------------------------------------------------------------------*/

  /**
   * Save Settings Metabox Display
   *
   * Callback to display the 'Save Settings' metabox.
   *
   * @since 1.5.5
   */
  function exmachina_metabox_theme_display_save() {
    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table">
        <!-- Begin Table Body -->
        <tbody>
          <tr>
            <td class="uk-width-1-1 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <?php submit_button( $this->page_ops['save_button_text'], 'primary button-hero update-button uk-button-expand', 'submit', false, array( 'id' => '' ) ); ?>
                      <?php submit_button( $this->page_ops['reset_button_text'], 'secondary reset-button uk-button-expand uk-text-bold exmachina-js-confirm-reset', $this->get_field_name( 'reset' ), false, array( 'id' => '' ) ); ?>
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>
        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_save()

  /**
   * Theme Updates Metabox Display
   *
   * Callback to display the 'Theme Updates' metabox. Creates a metabox for the
   * theme settings page, which allows theme updates.
   *
   * Fields:
   * ~~~~~~~
   * 'theme_version'
   * 'license_key'
   *
   * To use this feature, the theme must support the 'updates' argument for the
   * 'exmachina-core-theme-settings' feature.
   *
   * @uses \ExMachina_Admin::get_field_id()    Construct field ID.
   * @uses \ExMachina_Admin::get_field_name()  Construct field name.
   * @uses \ExMachina_Admin::get_field_value() Retrieve value of key under $this->settings_field.
   *
   * @since 1.5.5
   */
  function exmachina_metabox_theme_display_updates() {

    /* Get theme information. */
    $theme = wp_get_theme( get_template() );
    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table postbox-bordered">
        <!-- Begin Table Header -->
        <thead>
          <tr>
            <td class="postbox-header info" colspan="2">
              <p><?php _e( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'exmachina-core' ); ?></p>
            </td><!-- .postbox-header -->
          </tr>
        </thead>
        <!-- End Table Header -->
        <!-- Begin Table Body -->
        <tbody>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label class="uk-text-bold"><?php _e( 'Theme Version:', 'exmachina-core' ); ?></label>
            </td>
            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls uk-form-controls-condensed">
                      <!-- Begin Form Inputs -->
                      <strong><?php _e( 'Version:', 'exmachina-core' ); ?></strong> <?php echo $this->get_field_value( 'theme_version' ); ?> &#x000B7; <strong><?php _e( 'Released:', 'exmachina-core' ); ?></strong> <?php echo $this->get_field_value( 'release_date' ); ?>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'license_key' ); ?>" class="uk-text-bold"><?php _e( 'License Key:', 'exmachina-core' ); ?></label>
            </td>
            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <input type="text" name="<?php echo $this->get_field_name( 'license_key' ); ?>" id="<?php echo $this->get_field_id( 'license_key' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'license_key' ) ); ?>" placeholder="Enter your license key" size="50" />
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'update' ); ?>" class="uk-text-bold"><?php _e( 'Automatic Updates:', 'exmachina-core' ); ?></label>
            </td>
            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <label for="<?php echo $this->get_field_id( 'update' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'update' ); ?>" id="<?php echo $this->get_field_id( 'update' ); ?>" value="1"<?php checked( $this->get_field_value( 'update' ) ) . disabled( is_super_admin(), 0 ); ?> />
                      <?php _e( 'Enable Automatic Updates', 'exmachina-core' ); ?></label>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'update_email' ); ?>" class="uk-text-bold"><?php _e( 'Update Email:', 'exmachina-core' ); ?></label>
            </td>
            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <label for="<?php echo $this->get_field_id( 'update_email' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'update_email' ); ?>" id="<?php echo $this->get_field_id( 'update_email' ); ?>" value="1"<?php checked( $this->get_field_value( 'update_email' ) ) . disabled( is_super_admin(), 0 ); ?> />
                      <?php _e( 'Notify', 'exmachina-core' ); ?></label>
                      <input type="text" name="<?php echo $this->get_field_name( 'update_email_address' ); ?>" id="<?php echo $this->get_field_id( 'update_email_address' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'update_email_address' ) ); ?>" placeholder="enter your e-mail address" size="30"<?php disabled( 0, is_super_admin() ); ?> />
                      <label for="<?php echo $this->get_field_id( 'update_email_address' ); ?>"><?php _e( 'when updates are available', 'exmachina-core' ); ?></label>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->


                  <p class="uk-text-muted"><?php printf( __( 'If you provide an email address above, you will be notified via email when a new version of %1s is available.', 'exmachina-core' ), $theme->get( 'Name' ) ); ?></p>

                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>

        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_updates()

  /**
   * Feed Settings Metabox Display
   *
   * Callback to display the 'Feed Settings' metabox. Creates a metabox for the
   * theme settings page, which allows a custom redirect of the default WordPress
   * feeds.
   *
   * Fields:
   * ~~~~~~~
   * 'feed_uri'
   * 'redirect_feed'
   * 'comments_feed_uri'
   * 'redirect_comments_feed'
   *
   * To use this feature, the theme must support the 'feeds' argument for the
   * 'exmachina-core-theme-settings' feature.
   *
   * @todo write header info text
   * @todo add different placeholder text
   *
   * @link http://codex.wordpress.org/WordPress_Feeds
   * @link http://codex.wordpress.org/Customizing_Feeds
   *
   * @uses \ExMachina_Admin::get_field_id()    Construct field ID.
   * @uses \ExMachina_Admin::get_field_name()  Construct field name.
   * @uses \ExMachina_Admin::get_field_value() Retrieve value of key under $this->settings_field.
   *
   * @since 1.5.5
   */
  function exmachina_metabox_theme_display_feeds() {
    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table postbox-bordered">
        <!-- Begin Table Header -->
        <thead>
          <tr>
            <td class="postbox-header info" colspan="2">
              <p><?php _e( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'exmachina-core' ); ?></p>
            </td><!-- .postbox-header -->
          </tr>
        </thead>
        <!-- End Table Header -->
        <!-- Begin Table Body -->
        <tbody>
          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'feed_uri' ); ?>" class="uk-text-bold"><?php _e( 'Enter your custom feed URL:', 'exmachina-core' ); ?></label>
            </td>
            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <input type="text" name="<?php echo $this->get_field_name( 'feed_uri' ); ?>" id="<?php echo $this->get_field_id( 'feed_uri' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'feed_uri' ) ); ?>" placeholder="http://customfeedurl.com" size="50" />
                      <label for="<?php echo $this->get_field_id( 'redirect_feed' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'redirect_feed' ); ?>" id="<?php echo $this->get_field_id( 'redirect_feed' ); ?>" value="1"<?php checked( $this->get_field_value( 'redirect_feed' ) ); ?> />
                      <?php _e( 'Redirect Feed?', 'exmachina-core' ); ?></label>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>
          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'comments_feed_uri' ); ?>" class="uk-text-bold"><?php _e( 'Enter your custom comments feed URL:', 'exmachina-core' ); ?></label>
            </td>
            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <input type="text" name="<?php echo $this->get_field_name( 'comments_feed_uri' ); ?>" id="<?php echo $this->get_field_id( 'comments_feed_uri' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'comments_feed_uri' ) ); ?>" placeholder="http://customfeedurl.com" size="50" />
                      <label for="<?php echo $this->get_field_id( 'redirect_comments_feed' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'redirect_comments_feed' ); ?>" id="<?php echo $this->get_field_id( 'redirect_comments_feed' ); ?>" value="1"<?php checked( $this->get_field_value( 'redirect_comments_feed' ) ); ?> />
                      <?php _e( 'Redirect Feed?', 'exmachina-core' ); ?></label>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>
        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_feeds()

  /**
   * Breadcrumb Settings Metabox Display
   *
   * Callback to display the 'Breadcrumb Settings' metabox. Creates a metabox
   * for the theme settings page, which allows customization of the breadcrumb
   * trail.
   *
   * Fields:
   * ~~~~~~~
   * 'breadcrumb_front_page'
   * 'breadcrumb_posts_page'
   * 'breadcrumb_home'
   * 'breadcrumb_single'
   * 'breadcrumb_page'
   * 'breadcrumb_archive'
   * 'breadcrumb_404'
   * 'breadcrumb_attachment'
   *
   * To use this feature, the theme must support the 'breadcrumbs' argument for
   * the 'exmachina-core-theme-settings' feature.
   *
   * @uses \ExMachina_Admin::get_field_id()    Construct field ID.
   * @uses \ExMachina_Admin::get_field_name()  Construct field name.
   * @uses \ExMachina_Admin::get_field_value() Retrieve value of key under $this->settings_field.
   *
   * @since 1.5.5
   */
  function exmachina_metabox_theme_display_breadcrumbs() {
    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table postbox-bordered">
        <!-- Begin Table Header -->
        <thead>
          <tr>
            <td class="postbox-header info" colspan="2">
              <p><?php _e( 'Breadcrumbs are a great way of letting your visitors find out where they are on your site with just a glance. You can enable/disable them on certain areas of your site.', 'exmachina-core' ); ?></p>
            </td><!-- .postbox-header -->
          </tr>
        </thead>
        <!-- End Table Header -->
        <!-- Begin Table Body -->
        <tbody>
          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label class="uk-text-bold"><?php _e( 'Enable Breadcrumbs on:', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <ul class="checkbox-list horizontal">

                        <?php if ( 'page' === get_option( 'show_on_front' ) ) : ?>

                          <li><label for="<?php echo $this->get_field_id( 'breadcrumb_front_page' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'breadcrumb_front_page' ); ?>" id="<?php echo $this->get_field_id( 'breadcrumb_front_page' ); ?>" value="1"<?php checked( $this->get_field_value( 'breadcrumb_front_page' ) ); ?> />
                          <?php _e( 'Front Page', 'exmachina-core' ); ?></label></li>

                          <li><label for="<?php echo $this->get_field_id( 'breadcrumb_posts_page' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'breadcrumb_posts_page' ); ?>" id="<?php echo $this->get_field_id( 'breadcrumb_posts_page' ); ?>" value="1"<?php checked( $this->get_field_value( 'breadcrumb_posts_page' ) ); ?> />
                          <?php _e( 'Posts Page', 'exmachina-core' ); ?></label></li>

                        <?php else : ?>

                          <li><label for="<?php echo $this->get_field_id( 'breadcrumb_home' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'breadcrumb_home' ); ?>" id="<?php echo $this->get_field_id( 'breadcrumb_home' ); ?>" value="1"<?php checked( $this->get_field_value( 'breadcrumb_home' ) ); ?> />
                          <?php _e( 'Homepage', 'exmachina-core' ); ?></label></li>

                        <?php endif; ?>

                          <li><label for="<?php echo $this->get_field_id( 'breadcrumb_single' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'breadcrumb_single' ); ?>" id="<?php echo $this->get_field_id( 'breadcrumb_single' ); ?>" value="1"<?php checked( $this->get_field_value( 'breadcrumb_single' ) ); ?> />
                          <?php _e( 'Posts', 'exmachina-core' ); ?></label></li>

                          <li><label for="<?php echo $this->get_field_id( 'breadcrumb_page' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'breadcrumb_page' ); ?>" id="<?php echo $this->get_field_id( 'breadcrumb_page' ); ?>" value="1"<?php checked( $this->get_field_value( 'breadcrumb_page' ) ); ?> />
                          <?php _e( 'Pages', 'exmachina-core' ); ?></label></li>

                          <li><label for="<?php echo $this->get_field_id( 'breadcrumb_archive' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'breadcrumb_archive' ); ?>" id="<?php echo $this->get_field_id( 'breadcrumb_archive' ); ?>" value="1"<?php checked( $this->get_field_value( 'breadcrumb_archive' ) ); ?> />
                          <?php _e( 'Archives', 'exmachina-core' ); ?></label></li>

                          <li><label for="<?php echo $this->get_field_id( 'breadcrumb_404' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'breadcrumb_404' ); ?>" id="<?php echo $this->get_field_id( 'breadcrumb_404' ); ?>" value="1"<?php checked( $this->get_field_value( 'breadcrumb_404' ) ); ?> />
                          <?php _e( '404 Page', 'exmachina-core' ); ?></label></li>

                          <li><label for="<?php echo $this->get_field_id( 'breadcrumb_attachment' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'breadcrumb_attachment' ); ?>" id="<?php echo $this->get_field_id( 'breadcrumb_attachment' ); ?>" value="1"<?php checked( $this->get_field_value( 'breadcrumb_attachment' ) ); ?> />
                          <?php _e( 'Attachment Page', 'exmachina-core' ); ?></label></li>

                      </ul>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>


        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_breadcrumbs()

  /**
   * Comments & Trackbacks Metabox Display
   *
   * Callback to display the 'Comment & Trackbacks' metabox. Creates a metabox
   * for the theme settings page, which allows customization of the comment
   * and trackback settings on pages and posts.
   *
   * Fields:
   * ~~~~~~~
   * 'comments_posts'
   * 'comments_pages'
   * 'trackback_posts'
   * 'trackback_pages'
   *
   * To use this feature, the theme must support the 'comments' argument for
   * the 'exmachina-core-theme-settings' feature.
   *
   * @todo write header info text
   * @todo write description text
   * @todo cleanup form layout
   *
   * @uses \ExMachina_Admin::get_field_id()    Construct field ID.
   * @uses \ExMachina_Admin::get_field_name()  Construct field name.
   * @uses \ExMachina_Admin::get_field_value() Retrieve value of key under $this->settings_field.
   *
   * @since 1.5.5
   */
  function exmachina_metabox_theme_display_comments() {
    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table postbox-bordered">
        <!-- Begin Table Header -->
        <thead>
          <tr>
            <td class="postbox-header info" colspan="2">
              <p><?php _e( 'Comments and Trackbacks can also be disabled on a per post/page basis.', 'exmachina-core' ); ?></p>
            </td><!-- .postbox-header -->
          </tr>
        </thead>
        <!-- End Table Header -->
        <!-- Begin Table Body -->
        <tbody>
          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label class="uk-text-bold"><?php _e( 'Enable Comments:', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <ul class="checkbox-list horizontal">

                        <li><label for="<?php echo $this->get_field_id( 'comments_posts' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'comments_posts' ); ?>" id="<?php echo $this->get_field_id( 'comments_posts' ); ?>" value="1"<?php checked( $this->get_field_value( 'comments_posts' ) ); ?> />
                        <?php _e( 'on posts?', 'exmachina-core' ); ?></label></li>

                        <li><label for="<?php echo $this->get_field_id( 'comments_pages' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'comments_pages' ); ?>" id="<?php echo $this->get_field_id( 'comments_pages' ); ?>" value="1"<?php checked( $this->get_field_value( 'comments_pages' ) ); ?> />
                        <?php _e( 'on pages?', 'exmachina-core' ); ?></label></li>

                      </ul>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label class="uk-text-bold"><?php _e( 'Enable Trackbacks:', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <ul class="checkbox-list horizontal">

                        <li><label for="<?php echo $this->get_field_id( 'trackbacks_posts' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'trackbacks_posts' ); ?>" id="<?php echo $this->get_field_id( 'trackbacks_posts' ); ?>" value="1"<?php checked( $this->get_field_value( 'trackbacks_posts' ) ); ?> />
                        <?php _e( 'on posts?', 'exmachina-core' ); ?></label></li>

                        <li><label for="<?php echo $this->get_field_id( 'trackbacks_pages' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'trackbacks_pages' ); ?>" id="<?php echo $this->get_field_id( 'trackbacks_pages' ); ?>" value="1"<?php checked( $this->get_field_value( 'trackbacks_pages' ) ); ?> />
                        <?php _e( 'on pages?', 'exmachina-core' ); ?></label></li>

                      </ul>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>
        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_comments()

  /**
   * Content Archives Metabox Display
   *
   * Callback to display the 'Content Archives' metabox. Creates a metabox
   * for the theme settings page, which allows customization of the content
   * archives.
   *
   * Fields:
   * ~~~~~~~
   * 'content_archive'
   * 'content_archive_limit'
   * 'content_archive_more'
   * 'content_archive_thumbnail'
   * 'image_size'
   * 'posts_nav'
   * 'single_nav'
   *
   * To use this feature, the theme must support the 'archives' argument for
   * the 'exmachina-core-theme-settings' feature.
   *
   * @uses \ExMachina_Admin::get_field_id()    Construct field ID.
   * @uses \ExMachina_Admin::get_field_name()  Construct field name.
   * @uses \ExMachina_Admin::get_field_value() Retrieve value of key under $this->settings_field.
   *
   * @since 1.5.5
   */
  function exmachina_metabox_theme_display_archives() {
    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table postbox-bordered">
        <!-- Begin Table Header -->
        <thead>
          <tr>
            <td class="postbox-header info" colspan="2">
              <p><?php _e( 'These options will affect any blog listings page, including archive, author, blog, category, search, and tag pages.', 'exmachina-core' ); ?></p>
            </td><!-- .postbox-header -->
          </tr>
        </thead>
        <!-- End Table Header -->
        <!-- Begin Table Body -->
        <tbody>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'content_archive' ); ?>" class="uk-text-bold"><?php _e( 'Select one of the following:', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <select name="<?php echo $this->get_field_name( 'content_archive' ); ?>" id="<?php echo $this->get_field_id( 'content_archive' ); ?>">
                      <?php
                      $archive_display = apply_filters(
                        'exmachina_archive_display_options',
                        array(
                          'full'     => __( 'Display full post', 'exmachina-core' ),
                          'excerpts' => __( 'Display post excerpts', 'exmachina-core' ),
                        )
                      );
                      foreach ( (array) $archive_display as $value => $name )
                        echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->get_field_value( 'content_archive' ), esc_attr( $value ), false ) . '>' . esc_html( $name ) . '</option>' . "\n";
                      ?>
                      </select>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'content_archive_limit' ); ?>" class="uk-text-bold"><?php _e( 'Content limit:', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <label for="<?php echo $this->get_field_id( 'content_archive_limit' ); ?>"><?php _e( 'Limit content to', 'exmachina-core' ); ?>
                      <input type="text" name="<?php echo $this->get_field_name( 'content_archive_limit' ); ?>" id="<?php echo $this->get_field_id( 'content_archive_limit' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'content_archive_limit' ) ); ?>" size="3" />
                      <?php _e( 'characters', 'exmachina-core' ); ?></label>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                  <p class="uk-text-muted"><?php _e( 'Select "Display post excerpts" will limit the text and strip all formatting from the text displayed. Set 0 characters will display the first 55 words (default).', 'exmachina-core' ); ?></p>
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label class="uk-text-bold"><?php _e( 'More Text (if applicable):', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <input type="text" name="<?php echo $this->get_field_name( 'content_archive_more' ); ?>" id="<?php echo $this->get_field_id( 'content_archive_more' ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'content_archive_more' ) ); ?>" size="25" />
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label class="uk-text-bold"><?php _e( 'Include the Featured Image?', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <label for="<?php echo $this->get_field_id( 'content_archive_thumbnail' ); ?>">
                      <input type="checkbox" name="<?php echo $this->get_field_name( 'content_archive_thumbnail' ); ?>" id="<?php echo $this->get_field_id( 'content_archive_thumbnail' ); ?>" value="1" <?php checked( $this->get_field_value( 'content_archive_thumbnail' ) ); ?> />
                      <?php _e( 'Include the Featured Image?', 'exmachina-core' ); ?></label>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'image_size' ); ?>" class="uk-text-bold"><?php _e( 'Image Size:', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <select name="<?php echo $this->get_field_name( 'image_size' ); ?>" id="<?php echo $this->get_field_id( 'image_size' ); ?>">
                      <?php
                      $sizes = exmachina_get_image_sizes();
                      foreach ( (array) $sizes as $name => $size )
                        echo '<option value="' . esc_attr( $name ) . '"' . selected( $this->get_field_value( 'image_size' ), $name, FALSE ) . '>' . esc_html( $name ) . ' (' . absint( $size['width'] ) . ' &#x000D7; ' . absint( $size['height'] ) . ')</option>' . "\n";
                      ?>
                      </select>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'posts_nav' ); ?>" class="uk-text-bold"><?php _e( 'Select Post Navigation Format:', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <select name="<?php echo $this->get_field_name( 'posts_nav' ); ?>" id="<?php echo $this->get_field_id( 'posts_nav' ); ?>">
                        <option value="prev-next"<?php selected( 'prev-next', $this->get_field_value( 'posts_nav' ) ); ?>><?php _e( 'Previous / Next', 'exmachina-core' ); ?></option>
                        <option value="numeric"<?php selected( 'numeric', $this->get_field_value( 'posts_nav' ) ); ?>><?php _e( 'Numeric', 'exmachina-core' ); ?></option>
                      </select>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>

          <tr class="uk-table-middle">
            <td class="uk-width-3-10 postbox-label">
              <label for="<?php echo $this->get_field_id( 'single_nav' ); ?>" class="uk-text-bold"><?php _e( 'Disable single post navigation link?', 'exmachina-core' ); ?></label>
            </td>

            <td class="uk-width-7-10 postbox-fieldset">
              <div class="fieldset-wrap uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <label for="<?php echo $this->get_field_id( 'single_nav' ); ?>">
                      <input type="checkbox" name="<?php echo $this->get_field_name( 'single_nav' ); ?>" id="<?php echo $this->get_field_id( 'single_nav' ); ?>" value="1" <?php checked( $this->get_field_value( 'single_nav' ) ); ?> />
                      <?php _e( 'Disable single post navigation link?', 'exmachina-core' ); ?></label>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td>
          </tr>


        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_archives()

  /**
   * Header & Footer Scripts Metabox Display
   *
   * Callback to display the 'Header & Footer Scripts' metabox. Creates a metabox
   * for the theme settings page, which holds textareas to add custom scripts
   * (CSS or JS) within the header or the footer of the theme.
   *
   * Fields:
   * ~~~~~~~
   * 'header_scripts'
   * 'footer_scripts'
   *
   * To use this feature, the theme must support the 'scripts' argument for
   * the 'exmachina-core-theme-settings' feature.
   *
   * @todo write header info text
   * @todo CSS a no-lined legend and/or a non-lined table row
   *
   * @uses \ExMachina_Admin::get_field_id()    Construct field ID.
   * @uses \ExMachina_Admin::get_field_name()  Construct field name.
   * @uses \ExMachina_Admin::get_field_value() Retrieve value of key under $this->settings_field.
   *
   * @since 1.5.5
   */
  function exmachina_metabox_theme_display_scripts() {
    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table">
        <!-- Begin Table Header -->
        <thead>
          <tr>
            <td class="postbox-header info" colspan="2">
              <p><?php _e( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'exmachina-core' ); ?></p>
            </td><!-- .postbox-header -->
          </tr>
        </thead>
        <!-- End Table Header -->
        <!-- Begin Table Body -->
        <tbody>
          <tr>
            <td class="uk-width-1-1 postbox-fieldset">
              <div class="fieldset-wrap uk-margin uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <legend><?php _e( 'Header Scripts', 'exmachina-core' ); ?></legend>
                  <p class="uk-margin-top-remove"><label for="<?php echo $this->get_field_id( 'header_scripts' ); ?>"><?php printf( __( 'Enter scripts or code you would like output to %s:', 'exmachina-core' ), exmachina_code( 'wp_head()' ) ); ?></label></p>
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <textarea class="input-block-level vertical-resize code exmachina-code-area" name="<?php echo $this->get_field_name( 'header_scripts' ); ?>" id="<?php echo $this->get_field_id( 'header_scripts' ); ?>" cols="78" rows="8"><?php echo esc_textarea( $this->get_field_value( 'header_scripts' ) ); ?></textarea>
                      <link rel="stylesheet" href="<?php echo EXMACHINA_ADMIN_VENDOR . '/codemirror/css/theme/monokai.min.css'; ?>">
                      <script>
                        jQuery(document).ready(function($){
                            var editor_header_scripts = CodeMirror.fromTextArea(document.getElementById('<?= $this->get_field_id( 'header_scripts' );?>'), {
                                lineNumbers: true,
                                tabmode: 'indent',
                                mode: 'htmlmixed',
                                theme: 'monokai'
                            });
                        });
                      </script>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                  <p class="uk-text-muted"><?php printf( __( 'The %1$s hook executes immediately before the closing %2$s tag in the document source.', 'exmachina-core' ), exmachina_code( 'wp_head()' ), exmachina_code( '</head>' ) ); ?></p>
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>
          <tr>
            <td class="uk-width-1-1 postbox-fieldset">
              <div class="fieldset-wrap uk-margin uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <legend><?php _e( 'Footer Scripts', 'exmachina-core' ); ?></legend>
                  <p class="uk-margin-top-remove"><label for="<?php echo $this->get_field_id( 'footer_scripts' ); ?>"><?php printf( __( 'Enter scripts or code you would like output to %s:', 'exmachina-core' ), exmachina_code( 'wp_footer()' ) ); ?></label></p>
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <textarea class="input-block-level vertical-resize code exmachina-code-area" name="<?php echo $this->get_field_name( 'footer_scripts' ); ?>" id="<?php echo $this->get_field_id( 'footer_scripts' ); ?>" cols="78" rows="8"><?php echo esc_textarea( $this->get_field_value( 'footer_scripts' ) ); ?></textarea>
                      <link rel="stylesheet" href="<?php echo EXMACHINA_ADMIN_VENDOR . '/codemirror/css/theme/monokai.min.css'; ?>">
                      <script>
                        jQuery(document).ready(function($){
                            var editor_header_scripts = CodeMirror.fromTextArea(document.getElementById('<?= $this->get_field_id( 'footer_scripts' );?>'), {
                                lineNumbers: true,
                                tabmode: 'indent',
                                mode: 'htmlmixed',
                                theme: 'monokai'
                            });
                        });
                      </script>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                  <p class="uk-text-muted"><?php printf( __( 'The %1$s hook executes immediately before the closing %2$s tag in the document source.', 'exmachina-core' ), exmachina_code( 'wp_footer()' ), exmachina_code( '</body>' ) ); ?></p>
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>
        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_scripts()

  /**
   * Footer Settings Metabox Display
   *
   * Callback to display the 'Footer Settings' metabox. Creates a metabox for
   * the theme settings page, which holds a textarea for custom footer text within
   * the theme.
   *
   * Settings:
   * ~~~~~~~~~
   * 'footer_insert'
   *
   * To use this feature, the theme must support the 'footer' argument
   * for the 'exmachina-core-theme-settings' feature.
   *
   * @todo Add header info content
   * @todo Add default footer insert content function
   *
   * @link http://codex.wordpress.org/Function_Reference/wp_editor
   * @link http://codex.wordpress.org/Function_Reference/esc_textarea
   *
   * @uses \ExMachina_Admin::get_field_id()    Construct field ID.
   * @uses \ExMachina_Admin::get_field_name()  Construct field name.
   * @uses \ExMachina_Admin::get_field_value() Retrieve value of key under $this->settings_field.
   *
   * @since 1.5.5
   */
  function exmachina_metabox_theme_display_footer() {
    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table">
        <!-- Begin Table Header -->
        <thead>
          <tr>
            <td class="postbox-header info" colspan="2">
              <p><?php _e( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'exmachina-core' ); ?></p>
            </td><!-- .postbox-header -->
          </tr>
        </thead>
        <!-- End Table Header -->
        <!-- Begin Table Body -->
        <tbody>
          <tr>
            <td class="uk-width-1-1 postbox-fieldset">
              <div class="fieldset-wrap uk-margin uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <p class="uk-margin-top-remove"><?php _e( 'You can add custom <abbr title="Hypertext Markup Language">HTML</abbr> and/or shortcodes, which will be automatically inserted into your theme.', 'exmachina-core' ); ?></p>
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <?php
                      /* Add a textarea using the wp_editor() function to make it easier on users to add custom content. */
                      wp_editor(
                        $this->get_field_value( 'footer_insert' ), // Editor content.
                        $this->get_field_id( 'footer_insert' ),    // Editor ID.
                        array(
                          'tinymce'       => false, // Don't use TinyMCE in a meta box.
                          'textarea_rows' => 8,     // Set the number of textarea rows.
                          'media_buttons' => false,
                        ) );
                      ?>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                  <p class="uk-text-muted"><?php _e( 'Shortcodes and some <abbr title="Hypertext Markup Language">HTML</abbr> is allowed.', 'exmachina-core' ); ?></p>
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>
        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_footer()

  /*-------------------------------------------------------------------------*/
  /* Begin side priority metabox callbacks. */
  /*-------------------------------------------------------------------------*/

  /**
   * About Theme Metabox Display
   *
   * Callback to display the 'About Theme' metabox. Creates a meta box for the
   * theme settings page, which displays information about the theme. If a child
   * theme is in use, an additional meta box will be added with its information.
   *
   * Fields:
   * ~~~~~~~
   * none
   *
   * To use this feature, the theme must support the 'about' argument in the
   * 'exmachina-core-theme-settings' feature.
   *
   * @link http://codex.wordpress.org/Function_Reference/wp_get_theme
   * @link http://codex.wordpress.org/Function_Reference/get_template
   *
   * @since 1.5.5
   * @access public
   *
   * @param  object $object Variable passed through the do_meta_boxes() call.
   * @param  array  $box    Specific information about the meta box being loaded.
   * @return void
   */
  function exmachina_metabox_theme_display_about( $object, $box ) {

    /* Grab theme information for the parent/child theme. */
    $theme = ( 'exmachina-core-about-child' == $box['id'] ) ? wp_get_theme() : wp_get_theme( get_template() );

    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table">
        <!-- Begin Table Body -->
        <tbody>
          <tr>
            <td class="uk-width-1-1 postbox-fieldset">
              <div class="fieldset-wrap uk-margin uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <img class="uk-align-center uk-thumbnail uk-thumbnail-medium" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/screenshot.png' ); ?>" alt="<?php echo esc_attr( $theme->get( 'Name' ) ); ?>">
                      <dl class="uk-description-list uk-description-list-horizontal">
                        <dt class="uk-text-bold"><?php _e( 'Theme:', 'exmachina-core' ); ?></dt>
                        <dd><a href="<?php echo esc_url( $theme->get( 'ThemeURI' ) ); ?>" title="<?php echo esc_attr( $theme->get( 'Name' ) ); ?>"><?php echo $theme->get( 'Name' ); ?></a></dd>
                        <dt class="uk-text-bold"><?php _e( 'Author:', 'exmachina-core' ); ?></dt>
                        <dd><a href="<?php echo esc_url( $theme->get( 'AuthorURI' ) ); ?>" title="<?php echo esc_attr( $theme->get( 'Author' ) ); ?>"><?php echo $theme->get( 'Author' ); ?></a></dd>
                      </dl>
                      <dl class="uk-description-list">
                        <dt class="uk-text-bold"><?php _e( 'Description:', 'exmachina-core' ); ?></dt>
                        <dd><?php echo $theme->get( 'Description' ); ?></dd>
                      </dl>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>
        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_about()

  /**
   * Help Settings Metabox Display
   *
   * Callback to display the 'Help Settings' metabox. Creates a metabox on the
   * theme settings page which directs the user to a contextual help tabs.
   *
   * Fields:
   * ~~~~~~~
   * none
   *
   * To use this feature, the theme must support the 'help' argument in the
   * 'exmachina-core-theme-settings' feature.
   *
   * @link http://codex.wordpress.org/Function_Reference/wp_get_theme
   *
   * @todo  create variable/function to get theme support answers.
   * @todo  add programatic link to help: http://wordpress.stackexchange.com/questions/10810/how-to-control-contextual-help-section-by-code
   *
   * @since 1.5.5
   * @access public
   *
   * @return void
   */
  function exmachina_metabox_theme_display_help() {

    /* Get theme information. */
    $theme = wp_get_theme();

    ?>
    <!-- Begin Markup -->
    <div class="postbox-inner-wrap">
      <table class="uk-table postbox-table">
        <!-- Begin Table Body -->
        <tbody>
          <tr>
            <td class="uk-width-1-1 postbox-fieldset">
              <div class="fieldset-wrap uk-margin uk-grid">
                <!-- Begin Fieldset -->
                <fieldset class="uk-form uk-width-1-1">
                  <div class="uk-form-row">
                    <div class="uk-form-controls">
                      <!-- Begin Form Inputs -->
                      <p class="help-block"><?php _e( 'Struggling with some of the theme options or settings? Click on the "Help" tab above.', 'exmachina-core' ); ?></p>
                      <p class="help-block"><?php echo sprintf( __( 'You can also visit the %s <a href="%s" target="_blank">support forum</a>', 'exmachina-core' ), $theme->{'Name'}, 'http://www.machinathemes.com/support/' ); ?></p>
                      <!-- End Form Inputs -->
                    </div><!-- .uk-form-controls -->
                  </div><!-- .uk-form-row -->
                </fieldset>
                <!-- End Fieldset -->
              </div><!-- .fieldset-wrap -->
            </td><!-- .postbox-fieldset -->
          </tr>
        </tbody>
        <!-- End Table Body -->
      </table>
    </div><!-- .postbox-inner-wrap -->
    <!-- End Markup -->
    <?php
  } // end function exmachina_metabox_theme_display_help()

  /*-------------------------------------------------------------------------*/
  /* End the metabox callbacks. */
  /*-------------------------------------------------------------------------*/

} // end class ExMachina_Admin_Theme_Settings

add_action( 'exmachina_setup', 'exmachina_add_theme_settings_page' );
/**
 * Add Theme Settings Page
 *
 * Initializes a new instance of the ExMachina_Admin_Theme_Settings and adds
 * the Theme Settings Page.
 *
 * @since 1.5.5
 */
function exmachina_add_theme_settings_page() {

  /* Globalize the $_exmachina_admin_theme_settings variable. */
  global $_exmachina_admin_theme_settings;

  /* Create a new instance of the ExMachina_Admin_Theme_Settings class. */
  $_exmachina_admin_theme_settings = new ExMachina_Admin_Theme_Settings;

  //* Set the old global pagehook var for backward compatibility (May not need this)
  global $_exmachina_admin_theme_settings_pagehook;
  $_exmachina_admin_theme_settings_pagehook = $_exmachina_admin_theme_settings->pagehook;

  do_action( 'exmachina_admin_menu' );

} // end function exmachina_add_theme_settings_page()