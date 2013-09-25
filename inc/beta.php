<?php

function beta_theme_inc() {

	// Set template directory
    $beta_inc = get_template_directory();


	/* Custom functions that act independently of the theme templates. */
	require $beta_inc . '/inc/functions/extras.php';

	/* Customizer additions. */
	require $beta_inc . '/inc/functions/customizer.php';

	/* override exmachina code. */
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

	remove_action( 'wp_head', 'exmachina_meta_template', 4 );

}

add_action( 'after_setup_theme', 'beta_theme_inc', 20 );