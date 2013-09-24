<?php if ( get_option( 'page_comments' ) && 1 < get_comment_pages_count() ) : // are there comments to navigate through ?>

	<div class="comments-nav">
		<?php previous_comments_link( __( '&larr; Previous', 'beta' ) ); ?>
		<span class="page-numbers"><?php printf( __( 'Page %1$s of %2$s', 'beta' ), ( get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1 ), get_comment_pages_count() ); ?></span>
		<?php next_comments_link( __( 'Next &rarr;', 'beta' ) ); ?>
	</div><!-- .comments-nav -->

<?php endif; // check for comment navigation ?>