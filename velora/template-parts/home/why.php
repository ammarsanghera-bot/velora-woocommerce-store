<?php
/**
 * Why Velora.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

$items = array(
	array( __( 'Thoughtfully Selected Products', 'velora' ), __( 'Each piece is chosen for clarity of form and everyday utility.', 'velora' ) ),
	array( __( 'Quality-Focused Design', 'velora' ), __( 'Materials and construction prioritized over seasonal noise.', 'velora' ) ),
	array( __( 'Secure Shopping', 'velora' ), __( 'Protected checkout with trusted payment gateways.', 'velora' ) ),
	array( __( 'Reliable Customer Support', 'velora' ), __( 'Clear policies and attentive care when you need it.', 'velora' ) ),
);
?>
<section class="velora-section">
	<div class="velora-container">
		<header class="velora-section__header">
			<p class="velora-eyebrow"><?php esc_html_e( 'Why Velora', 'velora' ); ?></p>
			<h2><?php esc_html_e( 'Elevated essentials, honestly presented.', 'velora' ); ?></h2>
		</header>
		<div class="velora-why-grid">
			<?php foreach ( $items as $i => $item ) : ?>
				<article>
					<span>0<?php echo esc_html( (string) ( $i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $item[0] ); ?></h3>
					<p><?php echo esc_html( $item[1] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
