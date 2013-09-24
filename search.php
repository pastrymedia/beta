<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Beta
 */

get_header(); ?>

	<main class="content" role="main" itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/SearchResultsPage">

		<?php do_atomic( 'before_content' ); // beta_before_content ?>

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="archive-title"><?php printf( __( 'Search Results for: %s', 'beta' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'partials/content', 'search' ); ?>

			<?php endwhile; ?>

			<?php beta_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>

		<?php do_atomic( 'after_content' ); // beta_after_content ?>

	</main><!-- .content -->
	
<?php get_footer(); ?>