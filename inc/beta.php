<?php

function beta_theme_inc() {

	// Set template directory
    $beta_inc = get_template_directory();

	remove_action( 'wp_head', 'exmachina_meta_template', 4 );

}

add_action( 'after_setup_theme', 'beta_theme_inc', 20 );