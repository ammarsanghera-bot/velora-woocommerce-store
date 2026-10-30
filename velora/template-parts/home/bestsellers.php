<?php
/**
 * Featured / best sellers from WooCommerce featured flag (no invented sales figures).
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
		'tax_query'      => array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
			),
		),
	)
);
?>
<section class="velora-section velora-section--muted">
	<div class="velora-container">
		<header class="velora-section__header">
			<p class="velora-eyebrow"><?php esc_html_e( 'Staff picks', 'velora' ); ?></p>
			<h2><?php esc_html_e( 'Featured products', 'velora' ); ?></h2>
			<p><?php esc_html_e( 'A focused selection of pieces we return to again and again.', 'velora' ); ?></p>
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
			<p class="velora-empty"><?php esc_html_e( 'Mark products as Featured in WooCommerce to populate this section.', 'velora' ); ?></p>
		<?php endif; ?>
	</div>
</section>
