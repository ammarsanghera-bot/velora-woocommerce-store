<?php
/**
 * New arrivals.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$q = new WP_Query(
	array(
		'post_type'      => 'product',
		'posts_per_page' => 4,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);
?>
<section class="velora-section velora-section--muted">
	<div class="velora-container">
		<header class="velora-section__header">
			<p class="velora-eyebrow"><?php esc_html_e( 'Just in', 'velora' ); ?></p>
			<h2><?php esc_html_e( 'New Arrivals', 'velora' ); ?></h2>
		</header>
		<?php if ( $q->have_posts() ) : ?>
			<ul class="products columns-4">
				<?php
				while ( $q->have_posts() ) :
					$q->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php else : ?>
			<p class="velora-empty"><?php esc_html_e( 'No products yet. Import demo products from WooCommerce → Velora.', 'velora' ); ?></p>
		<?php endif; ?>
	</div>
</section>
