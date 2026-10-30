<?php
/**
 * WP-CLI: Force-assign any missing demo product/category images.
 *
 * Usage: wp eval-file /scripts/enrich-media.php --allow-root
 */

if ( ! function_exists( 'velora_core_enrich_store_media' ) ) {
	echo "Velora Core not loaded\n";
	return;
}

// Clear broken empty thumbnails then re-enrich.
foreach ( wc_get_products( array( 'limit' => -1, 'return' => 'ids' ) ) as $id ) {
	$thumb = get_post_thumbnail_id( $id );
	if ( $thumb && ! wp_get_attachment_url( $thumb ) ) {
		delete_post_thumbnail( $id );
	}
}

$created = velora_core_import_demo_products();
$media   = velora_core_enrich_store_media();

$missing = array();
foreach ( wc_get_products( array( 'limit' => -1, 'return' => 'ids' ) ) as $id ) {
	$product = wc_get_product( $id );
	if ( ! has_post_thumbnail( $id ) ) {
		$missing[] = $product ? $product->get_sku() : (string) $id;
	}
}

$cats_ok = 0;
foreach ( array( 'women', 'men', 'bags', 'lifestyle', 'footwear', 'accessories' ) as $slug ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term && (int) get_term_meta( $term->term_id, 'thumbnail_id', true ) ) {
		++$cats_ok;
	}
}

echo 'created=' . $created . ' media_new=' . $media . ' missing_images=' . implode( ',', $missing ) . ' cats_with_thumbs=' . $cats_ok . "/6 total_products=" . count( wc_get_products( array( 'limit' => -1, 'return' => 'ids' ) ) ) . "\n";
