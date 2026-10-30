<?php
/**
 * Single product template.
 * Customization prefers hooks (velora-core / inc/woocommerce.php)
 * over copying WooCommerce templates.
 *
 * @package Velora
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

while ( have_posts() ) {
	the_post();
	wc_get_template_part( 'content', 'single-product' );
}

get_footer( 'shop' );
