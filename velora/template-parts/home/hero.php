<?php
/**
 * Hero.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

$heading = velora_option( 'velora_hero_heading', 'Everyday Essentials. Elevated.' );
$text    = velora_option( 'velora_hero_text', 'Discover timeless pieces designed for modern living. Thoughtful details, refined style, and everyday comfort.' );
$shop    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>
<section class="velora-hero">
	<div class="velora-container velora-hero__grid">
		<div class="velora-hero__copy">
			<p class="velora-eyebrow"><?php esc_html_e( 'New season', 'velora' ); ?></p>
			<h1><?php echo esc_html( $heading ); ?></h1>
			<p><?php echo esc_html( $text ); ?></p>
			<div class="velora-hero__actions">
				<a class="velora-btn velora-btn--primary" href="<?php echo esc_url( $shop ); ?>"><?php esc_html_e( 'Shop New Arrivals', 'velora' ); ?></a>
				<a class="velora-btn velora-btn--ghost" href="<?php echo esc_url( $shop ); ?>"><?php esc_html_e( 'Explore Collection', 'velora' ); ?></a>
			</div>
		</div>
		<div class="velora-hero__visual" aria-hidden="true"></div>
	</div>
</section>
