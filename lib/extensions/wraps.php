<?php

function exmachina_wrap_open() {
  echo '<div class="wrap">';
}

function exmachina_wrap_close() {
  echo '</div><!-- .wrap -->';
}

add_action( exmachina_get_prefix() . '_header', 'exmachina_wrap_open', 7 );
add_action( exmachina_get_prefix() . '_header', 'exmachina_wrap_close' );

add_action( exmachina_get_prefix() . '_before_main', 'exmachina_wrap_open', 7 );
add_action( exmachina_get_prefix() . '_after_main', 'exmachina_wrap_close' );

add_action( exmachina_get_prefix() . '_before_primary_menu', 'exmachina_wrap_open', 7 );
add_action( exmachina_get_prefix() . '_after_primary_menu', 'exmachina_wrap_close' );

add_action( exmachina_get_prefix() . '_footer', 'exmachina_wrap_open', 7 );
add_action( exmachina_get_prefix() . '_footer', 'exmachina_wrap_close' );

?>