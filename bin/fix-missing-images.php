<?php
/**
 * Force-assign images for SKUs still missing thumbnails.
 *
 * @package Velora_Core
 */

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$fallbacks = array(
	'VEL-LINEN-SHIRT' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=900&h=1125&q=80',
	'VEL-SCARF'       => 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?auto=format&fit=crop&w=900&h=1125&q=80',
	'VEL-BELT'        => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&h=1125&q=65',
	'VEL-WIDELEG'     => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?auto=format&fit=crop&w=900&h=1125&q=80',
	'accessories'     => 'https://images.unsplash.com/photo-1611652022419-a9419f74343a?auto=format&fit=crop&w=900&h=1125&q=80',
);

foreach ( $fallbacks as $key => $url ) {
	if ( 0 === strpos( $key, 'VEL-' ) ) {
		$pid = wc_get_product_id_by_sku( $key );
		if ( ! $pid || has_post_thumbnail( $pid ) ) {
			echo "skip product {$key}\n";
			continue;
		}
		$id = velora_core_sideload_image( $url, $pid, get_the_title( $pid ) );
		if ( $id ) {
			set_post_thumbnail( $pid, $id );
			echo "product {$key} => {$id}\n";
		} else {
			echo "FAIL product {$key}\n";
		}
		continue;
	}

	$term = get_term_by( 'slug', $key, 'product_cat' );
	if ( ! $term || (int) get_term_meta( $term->term_id, 'thumbnail_id', true ) ) {
		echo "skip cat {$key}\n";
		continue;
	}
	$id = velora_core_sideload_image( $url, 0, $term->name );
	if ( $id ) {
		update_term_meta( $term->term_id, 'thumbnail_id', $id );
		echo "cat {$key} => {$id}\n";
	} else {
		echo "FAIL cat {$key}\n";
	}
}

$with = 0;
foreach ( wc_get_products( array( 'limit' => -1, 'return' => 'ids' ) ) as $id ) {
	if ( has_post_thumbnail( $id ) ) {
		++$with;
	}
}
echo "done products_with_images={$with}\n";
