<?php

/* add meta viewport for responsive layout */
function exmachina_viewport () {
	echo '<meta name="viewport" content="width=device-width">';
}

add_action('wp_head', 'exmachina_viewport', 1 );
?>