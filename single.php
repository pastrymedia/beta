<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Beta
 */

get_header(); ?>

	<main class="<?php echo apply_atomic( 'beta_main_class', 'content' );?>" role="main" itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/Blog">

		<?php do_atomic( 'before_content' ); // beta_before_content ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'partials/content', 'single' ); ?>

			<?php exmachina_content_nav( 'nav-below' ); ?>

			<?php comments_template(); // Loads the comments.php template. ?>

		<?php endwhile; // end of the loop. ?>

		<?php do_atomic( 'after_content' ); // beta_after_content ?>

	</main><!-- .content -->

<?php get_footer(); ?>