<?php
/**
 * Template helpers.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme mod helper.
 *
 * @param string $key     Key.
 * @param mixed  $default Default.
 * @return mixed
 */
function velora_option( $key, $default = '' ) {
	$value = get_theme_mod( $key, $default );
	return ( '' === $value || null === $value ) ? $default : $value;
}

/**
 * Fallback primary menu.
 *
 * @return void
 */
function velora_fallback_menu() {
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$links = array(
		home_url( '/' ) => __( 'Home', 'velora' ),
		$shop           => __( 'Shop', 'velora' ),
	);
	echo '<ul class="velora-nav__list">';
	foreach ( $links as $url => $label ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Wishlist page URL.
 *
 * @return string
 */
function velora_wishlist_url() {
	$page_id = (int) get_option( 'velora_core_wishlist_page' );
	return $page_id ? get_permalink( $page_id ) : home_url( '/wishlist/' );
}
