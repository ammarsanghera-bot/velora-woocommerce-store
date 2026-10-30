<?php
/**
 * Featured collection promo — full editorial band.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

$shop  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$promo = VELORA_URI . '/assets/images/promo.jpg';
?>
<section class="velora-promo">
	<div class="velora-promo__band" style="--velora-promo-image: url('<?php echo esc_url( $promo ); ?>')">
		<img class="velora-promo__img" src="<?php echo esc_url( $promo ); ?>" alt="" width="1400" height="1000" loading="lazy" decoding="async" />
		<div class="velora-promo__shade" aria-hidden="true"></div>
		<div class="velora-container velora-promo__copy">
			<p class="velora-eyebrow velora-eyebrow--light"><?php esc_html_e( 'Featured collection', 'velora' ); ?></p>
			<h2><?php esc_html_e( 'Refined pieces for everyday ritual.', 'velora' ); ?></h2>
			<p><?php esc_html_e( 'A curated edit of essentials — clean lines, considered materials, and lasting style.', 'velora' ); ?></p>
			<a class="velora-btn velora-btn--light" href="<?php echo esc_url( $shop ); ?>"><?php esc_html_e( 'Explore featured', 'velora' ); ?></a>
		</div>
	</div>
</section>
