<?php

function beta_theme_inc() {

	// Set template directory
    $beta_inc = get_template_directory();

	/* Custom template tags for this theme. */
	require $beta_inc . '/inc/functions/template-tags.php';

	/* Custom functions that act independently of the theme templates. */
	require $beta_inc . '/inc/functions/extras.php';

	/* Customizer additions. */
	require $beta_inc . '/inc/functions/customizer.php';

	/* override hybrid code. */
	require $beta_inc . '/inc/functions/override.php';

	/* image function */
	require $beta_inc . '/inc/functions/image.php';

	if ( is_admin() ) {
		/* Load  theme settings page */		
		require  $beta_inc  . '/inc/admin/meta-box-theme-options.php';		
		require  $beta_inc  . '/inc/admin/meta-box-theme-comments.php';
		require  $beta_inc  . '/inc/admin/meta-box-theme-archives.php';
		require  $beta_inc  . '/inc/admin/meta-box-theme-general.php';		
	}

	/* Load  child themes page if supported. */
	require_if_theme_supports( 'beta-child-themes-page', $beta_inc . '/inc/extensions/child-themes-page.php' );

	/* Load wraps extension if supported. */
	require_if_theme_supports( 'beta-wraps', $beta_inc . '/inc/extensions/wraps.php' );

	/* Load custom footer extension if supported. */
	require_if_theme_supports( 'beta-custom-footer', $beta_inc . '/inc/extensions/custom-footer.php' );

	/* Load custom css extension if supported. */
	require_if_theme_supports( 'beta-custom-css', $beta_inc . '/inc/extensions/custom-css.php' );

	/* Load custom logo extension if supported. */
	require_if_theme_supports( 'beta-custom-logo', $beta_inc . '/inc/extensions/custom-logo.php' );

	/* Load reponsive support. */
	require_if_theme_supports( 'beta-responsive', $beta_inc . '/inc/extensions/responsive.php' );

	/* Load  footer widgets extension if supported. */
	require_if_theme_supports( 'beta-footer-widgets', $beta_inc . '/inc/extensions/footer-widgets.php' );

	remove_action( 'wp_head', 'hybrid_meta_template', 4 );

}

add_action( 'after_setup_theme', 'beta_theme_inc', 20 );