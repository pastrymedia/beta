<?php
/**
 * ExMachina Framework
 *
 * WARNING: This file is part of the core ExMachina Framework DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package ExMachina\Header
 * @author  Machina Themes
 * @license GPL-2.0+
 * @link    http://machinathemes.com
 */


add_action( 'wp_head', 'exmachina_conditional_styles' );
add_action( 'wp_head', 'exmachina_custom_header_scripts' );

/* Header actions. */
add_action( exmachina_get_prefix() . '_header', 'exmachina_header_branding' );


/**
 * Insert conditional script / style for the theme used sitewide.
 */
function exmachina_conditional_styles() {
?>
  <!--[if lt IE 9]>
  <script src="<?php echo trailingslashit( EXMACHINA_JS ); ?>html5.js" type="text/javascript"></script>
  <![endif]-->
<?php
}


/**
 * Echo header scripts in to wp_head().
 */
function exmachina_custom_header_scripts() {

  echo exmachina_get_setting( 'header_scripts' );

}

/**
 * Dynamic element to wrap the site title and site description.
 */
function exmachina_header_branding() {

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