<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the class=site-inner div and all content after
 *
 * @package Beta
 */
?>
		<?php do_atomic( 'after_main' ); // beta_after_main ?>

	</div><!-- .site-inner -->

	<?php get_template_part( 'partials/footer' ); ?>

</div><!-- .site-container -->

<?php do_atomic( 'after' ); // beta_after ?>

<?php wp_footer(); ?>

</body>
</html>