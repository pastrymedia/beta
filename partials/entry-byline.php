<div class="entry-meta">
	<?php
	if (is_multi_author()) {
		echo apply_atomic_shortcode( 'entry_author', __( 'Posted by [entry-author] ', 'beta' ) );
	} else {
		echo apply_atomic_shortcode( 'entry_author', __( 'Posted ', 'beta' ) );
	}?>
	<?php
	if (  exmachina_get_option( 'trackbacks_posts' ) || exmachina_get_option( 'comments_posts' ) ) {
		echo apply_atomic_shortcode( 'entry_byline', __( 'on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'beta' ) );
	} else {
		echo apply_atomic_shortcode( 'entry_byline', __( 'on [entry-published] [entry-edit-link before=" | "]', 'beta' ) );
	}

	?>
</div><!-- .entry-meta -->