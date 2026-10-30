<?php
/**
 * Featured collection promo.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;
$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>
<section class="velora-promo">
	<div class="velora-container velora-promo__inner">
		<div>
			<p class="velora-eyebrow"><?php esc_html_e( 'Featured collection', 'velora' ); ?></p>
			<h2><?php esc_html_e( 'Refined pieces for everyday ritual.', 'velora' ); ?></h2>
			<p><?php esc_html_e( 'A curated edit of demonstration products showcasing Velora’s editorial product presentation.', 'velora' ); ?></p>
			<a class="velora-btn velora-btn--primary" href="<?php echo esc_url( $shop ); ?>"><?php esc_html_e( 'Explore featured', 'velora' ); ?></a>
		</div>
		<div class="velora-promo__visual" aria-hidden="true"></div>
	</div>
</section>
