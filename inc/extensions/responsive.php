<?php

/* add meta viewport for responsive layout */
function beta_viewport () {
	echo '<meta name="viewport" content="width=device-width">';
}

add_action('wp_head', 'beta_viewport', 1 );
?>