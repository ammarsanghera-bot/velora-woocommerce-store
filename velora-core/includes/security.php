<?php
/**
 * Shared security helpers.
 *
 * @package Velora_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Verify AJAX nonce or die.
 *
 * @param string $action Nonce action.
 * @return void
 */
function velora_core_verify_ajax( $action = 'velora_core_ajax' ) {
	check_ajax_referer( $action, 'nonce' );
}
