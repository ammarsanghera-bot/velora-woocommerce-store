<?php
/**
 * Optional product card meta (badge / subtitle).
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

global $product;
if ( ! $product instanceof WC_Product ) {
	return;
}
?>
