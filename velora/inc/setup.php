<?php
/**
 * Theme supports and menus.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

/**
 * After setup theme.
 *
 * @return void
 */
function velora_setup() {
	load_theme_textdomain( 'velora', VELORA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 72,
			'width'       => 220,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'velora' ),
			'footer'  => __( 'Footer Menu', 'velora' ),
		)
	);

	add_image_size( 'velora-product', 800, 1000, true );
	add_image_size( 'velora-hero', 1600, 1000, true );
	add_image_size( 'velora-category', 900, 1100, true );
}
add_action( 'after_setup_theme', 'velora_setup' );
