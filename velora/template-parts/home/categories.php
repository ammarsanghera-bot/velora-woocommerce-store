<?php
/**
 * Category collections.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

$slugs    = array( 'women', 'men', 'bags', 'lifestyle' );
$fallback = VELORA_URI . '/assets/images/cat-fallback.jpg';
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
				$thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
				$img      = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'velora-category' ) : $fallback;
				?>
				<a class="velora-cat-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<span class="velora-cat-card__media">
						<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy" decoding="async" width="600" height="800" />
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
