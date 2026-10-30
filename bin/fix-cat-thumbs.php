<?php
/**
 * Ensure all storefront categories have thumbnails.
 */

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$cat_images = array(
	'women'       => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&h=1125&q=80',
	'men'         => 'https://images.unsplash.com/photo-1490578474895-499c8edb2a67?auto=format&fit=crop&w=900&h=1125&q=80',
	'bags'        => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=900&h=1125&q=80',
	'lifestyle'   => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=900&h=1125&q=80',
	'footwear'    => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=900&h=1125&q=80',
	'accessories' => 'https://images.unsplash.com/photo-1611652022419-a9419f74343a?auto=format&fit=crop&w=900&h=1125&q=80',
);

foreach ( $cat_images as $slug => $url ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( ! $term || is_wp_error( $term ) ) {
		echo "missing term {$slug}\n";
		continue;
	}
	$existing = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
	if ( $existing && wp_get_attachment_url( $existing ) ) {
		echo "ok {$slug}\n";
		continue;
	}
	$id = velora_core_sideload_image( $url, 0, $term->name . ' category' );
	if ( $id ) {
		update_term_meta( $term->term_id, 'thumbnail_id', $id );
		echo "set {$slug} => {$id}\n";
	} else {
		echo "FAIL {$slug}\n";
	}
}
