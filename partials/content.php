<?php
/**
 * @package Beta
 */
?>

<article id="post-<?php the_ID(); ?>" class="<?php exmachina_entry_class(); ?>" itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">	

	<div class="entry-wrap">
		
		<?php do_atomic( 'before_entry' ); // beta_before_entry ?>

		<div class="entry-content">		

			<?php do_atomic( 'entry' ); // beta_entry ?>
			
		</div><!-- .entry-content -->

		<?php do_atomic( 'after_entry' ); // beta_after_entry ?>

	</div><!-- .entry-wrap -->
	
</article><!-- #post-## -->