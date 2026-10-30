<?php
/**
 * Assets.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue front-end assets.
 *
 * @return void
 */
function velora_enqueue_assets() {
	$ver_main = file_exists( VELORA_DIR . '/assets/css/main.css' ) ? (string) filemtime( VELORA_DIR . '/assets/css/main.css' ) : VELORA_VERSION;
	$ver_wc   = file_exists( VELORA_DIR . '/assets/css/woocommerce.css' ) ? (string) filemtime( VELORA_DIR . '/assets/css/woocommerce.css' ) : VELORA_VERSION;
	$ver_js   = file_exists( VELORA_DIR . '/assets/js/main.js' ) ? (string) filemtime( VELORA_DIR . '/assets/js/main.js' ) : VELORA_VERSION;

	wp_enqueue_style( 'velora-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'velora-style', get_stylesheet_uri(), array(), VELORA_VERSION );
	wp_enqueue_style( 'velora-main', VELORA_URI . '/assets/css/main.css', array( 'velora-fonts' ), $ver_main );
	wp_enqueue_style( 'velora-responsive', VELORA_URI . '/assets/css/responsive.css', array( 'velora-main' ), file_exists( VELORA_DIR . '/assets/css/responsive.css' ) ? (string) filemtime( VELORA_DIR . '/assets/css/responsive.css' ) : VELORA_VERSION );
	wp_enqueue_style( 'velora-woocommerce', VELORA_URI . '/assets/css/woocommerce.css', array( 'velora-main' ), $ver_wc );

	wp_enqueue_script( 'velora-main', VELORA_URI . '/assets/js/main.js', array(), $ver_js, true );
	wp_localize_script(
		'velora-main',
		'veloraData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'velora_core_ajax' ),
			'i18n'    => array(
				'added'   => __( 'Added to wishlist', 'velora' ),
				'removed' => __( 'Removed from wishlist', 'velora' ),
			),
		)
	);

	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		wp_enqueue_script( 'velora-filters', VELORA_URI . '/assets/js/shop-filters.js', array(), file_exists( VELORA_DIR . '/assets/js/shop-filters.js' ) ? (string) filemtime( VELORA_DIR . '/assets/js/shop-filters.js' ) : VELORA_VERSION, true );
	}

	wp_enqueue_script( 'velora-cart', VELORA_URI . '/assets/js/cart.js', array( 'jquery' ), file_exists( VELORA_DIR . '/assets/js/cart.js' ) ? (string) filemtime( VELORA_DIR . '/assets/js/cart.js' ) : VELORA_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'velora_enqueue_assets' );
