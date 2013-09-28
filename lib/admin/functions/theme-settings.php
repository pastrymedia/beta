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
      add_meta_box( 'exmachina-core-breadcrumbs', __( '<i class="uk-icon-cog"></i> Breadcrumbs', 'exmachina-core' ), array( $this, 'exmachina_metabox_theme_display_breadcrumbs' ), $this->pagehook, 'normal', 'default' );

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