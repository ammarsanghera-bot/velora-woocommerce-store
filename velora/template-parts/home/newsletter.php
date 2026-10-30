<?php
/**
 * Newsletter (frontend validation + AJAX stub).
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="velora-newsletter">
	<div class="velora-container velora-newsletter__inner">
		<div>
			<h2><?php esc_html_e( 'Stay close to the collection.', 'velora' ); ?></h2>
			<p><?php esc_html_e( 'New arrivals and quiet updates — no noise, just the essentials.', 'velora' ); ?></p>
		</div>
		<form class="velora-newsletter__form" data-newsletter-form>
			<label class="screen-reader-text" for="velora-newsletter-email"><?php esc_html_e( 'Email', 'velora' ); ?></label>
			<input id="velora-newsletter-email" type="email" name="email" required placeholder="<?php esc_attr_e( 'Email address', 'velora' ); ?>" />
			<button class="velora-btn velora-btn--primary" type="submit"><?php esc_html_e( 'Subscribe', 'velora' ); ?></button>
			<p class="velora-newsletter__msg" role="status" aria-live="polite"></p>
		</form>
	</div>
</section>
