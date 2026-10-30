<?php
/**
 * Category collections.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

$slugs = array( 'women', 'men', 'bags', 'lifestyle', 'footwear', 'accessories' );
?>
<section class="velora-section">
	<div class="velora-container">
		<header class="velora-section__header">
			<p class="velora-eyebrow"><?php esc_html_e( 'Collections', 'velora' ); ?></p>
			<h2><?php esc_html_e( 'Shop by category', 'velora' ); ?></h2>
		</header>
		<div class="velora-cat-grid">
			<?php foreach ( $slugs as $slug ) : ?>
				<?php
				$term = get_term_by( 'slug', $slug, 'product_cat' );
				if ( ! $term || is_wp_error( $term ) ) {
					continue;
				}
				$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
				?>
				<a class="velora-cat-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<span class="velora-cat-card__media">
						<?php
						if ( $thumb_id ) {
							echo wp_get_attachment_image( (int) $thumb_id, 'velora-category', false, array( 'loading' => 'lazy' ) );
						} else {
							echo '<span class="velora-placeholder"></span>';
						}
						?>
					</span>
					<span class="velora-cat-card__body">
						<strong><?php echo esc_html( $term->name ); ?></strong>
						<em><?php esc_html_e( 'Shop Now', 'velora' ); ?></em>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
