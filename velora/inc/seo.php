<?php
/**
 * Lightweight SEO (skips if Yoast / Rank Math active).
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Detect SEO plugins.
 *
 * @return bool
 */
function velora_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' );
}

/**
 * Basic meta tags.
 *
 * @return void
 */
function velora_seo_meta() {
	if ( velora_seo_plugin_active() ) {
		return;
	}
	$description = get_bloginfo( 'description' );
	if ( function_exists( 'is_product' ) && is_product() ) {
		global $product;
		if ( $product instanceof WC_Product ) {
			$description = wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_name() );
		}
	}
	echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( wp_get_document_title() ) . '" />' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
	echo '<meta property="og:type" content="website" />' . "\n";
}
add_action( 'wp_head', 'velora_seo_meta', 1 );
