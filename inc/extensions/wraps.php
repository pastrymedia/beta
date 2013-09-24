<?php

function beta_wrap_open() {
  echo '<div class="wrap">';
}

function beta_wrap_close() {
  echo '</div><!-- .wrap -->';
}

add_action('beta_header', 'beta_wrap_open', 7 );
add_action('beta_header', 'beta_wrap_close' );

add_action('beta_before_main', 'beta_wrap_open', 7 );
add_action('beta_after_main', 'beta_wrap_close' );

add_action('beta_before_primary_menu', 'beta_wrap_open', 7 );
add_action('beta_after_primary_menu', 'beta_wrap_close' );

add_action('beta_footer', 'beta_wrap_open', 7 );
add_action('beta_footer', 'beta_wrap_close' );

?>