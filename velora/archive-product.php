<?php
/**
 * Product archive fallback — WooCommerce uses woocommerce/archive-product.php when present.
 * Kept for template hierarchy clarity; delegates to WooCommerce.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

wc_get_template( 'archive-product.php' );
