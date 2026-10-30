<?php
/**
 * Search results.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="velora-main">
	<div class="velora-container velora-section">
		<h1>
			<?php
			printf(
				/* translators: %s search query */
				esc_html__( 'Search: %s', 'velora' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>
		<?php get_product_search_form(); ?>
		<?php if ( have_posts() ) : ?>
			<ul class="products columns-3">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( 'product' === get_post_type() ) {
						wc_get_template_part( 'content', 'product' );
					}
				endwhile;
				?>
			</ul>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="velora-empty"><?php esc_html_e( 'No products matched your search.', 'velora' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
