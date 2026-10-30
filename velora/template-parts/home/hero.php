<?php
/**
 * Full-bleed editorial hero.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

$heading = velora_option( 'velora_hero_heading', 'Everyday Essentials. Elevated.' );
$text    = velora_option( 'velora_hero_text', 'Discover timeless pieces designed for modern living. Thoughtful details, refined style, and everyday comfort.' );
$shop    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$hero    = VELORA_URI . '/assets/images/hero.jpg';
$women   = get_term_by( 'slug', 'women', 'product_cat' );
$explore = ( $women && ! is_wp_error( $women ) ) ? get_term_link( $women ) : $shop;
?>
<section class="velora-hero" style="--velora-hero-image: url('<?php echo esc_url( $hero ); ?>')">
	<div class="velora-hero__media" aria-hidden="true">
		<img
			src="<?php echo esc_url( $hero ); ?>"
			alt=""
			width="2000"
			height="1200"
			fetchpriority="high"
			decoding="async"
			class="velora-hero__img"
		/>
	</div>
	<div class="velora-hero__shade" aria-hidden="true"></div>
	<div class="velora-container velora-hero__content">
		<p class="velora-logo velora-hero__brand">VELORA</p>
		<p class="velora-eyebrow velora-eyebrow--light"><?php esc_html_e( 'New season', 'velora' ); ?></p>
		<h1><?php echo esc_html( $heading ); ?></h1>
		<p class="velora-hero__lead"><?php echo esc_html( $text ); ?></p>
		<div class="velora-hero__actions">
			<a class="velora-btn velora-btn--light" href="<?php echo esc_url( $shop ); ?>"><?php esc_html_e( 'Shop New Arrivals', 'velora' ); ?></a>
			<a class="velora-btn velora-btn--ghost-light" href="<?php echo esc_url( $explore ); ?>"><?php esc_html_e( 'Explore Collection', 'velora' ); ?></a>
		</div>
	</div>
</section>
