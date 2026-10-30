<?php
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$term = get_term_by( 'slug', 'men', 'product_cat' );
if ( ! $term ) {
	echo "no men term\n";
	return;
}
$existing = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
if ( $existing && wp_get_attachment_url( $existing ) ) {
	echo "already set {$existing}\n";
	return;
}
$url = 'https://images.unsplash.com/photo-1617137968427-85924c800a22?auto=format&fit=crop&w=900&h=1125&q=80';
$id  = velora_core_sideload_image( $url, 0, 'Men category' );
if ( ! $id ) {
	$url = 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=900&h=1125&q=80';
	$id  = velora_core_sideload_image( $url, 0, 'Men category' );
}
if ( $id ) {
	update_term_meta( $term->term_id, 'thumbnail_id', $id );
	echo "men={$id}\n";
} else {
	echo "fail\n";
}
