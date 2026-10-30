<?php
/**
 * Footer.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="velora-footer">
	<div class="velora-container velora-footer__grid">
		<div>
			<a class="velora-logo velora-logo--light" href="<?php echo esc_url( home_url( '/' ) ); ?>">VELORA</a>
			<p><?php echo esc_html( velora_option( 'velora_footer_about', 'VELORA crafts elevated everyday essentials for modern living. Portfolio demonstration store with fictional products.' ) ); ?></p>
		</div>
		<div>
			<h2><?php esc_html_e( 'Shop', 'velora' ); ?></h2>
			<ul>
				<li><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'All products', 'velora' ); ?></a></li>
				<?php
				foreach ( array( 'women' => __( 'Women', 'velora' ), 'men' => __( 'Men', 'velora' ) ) as $slug => $label ) {
					$term = get_term_by( 'slug', $slug, 'product_cat' );
					if ( $term && ! is_wp_error( $term ) ) {
						echo '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $label ) . '</a></li>';
					}
				}
				?>
			</ul>
		</div>
		<div>
			<h2><?php esc_html_e( 'Customer care', 'velora' ); ?></h2>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>"><?php esc_html_e( 'Shipping & Returns', 'velora' ); ?></a></li>
				<li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy Policy', 'velora' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'velora' ); ?></a></li>
				<li><a href="mailto:<?php echo esc_attr( velora_option( 'velora_support', 'support@velora.example' ) ); ?>"><?php echo esc_html( velora_option( 'velora_support', 'support@velora.example' ) ); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="velora-container velora-footer__bar">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> VELORA. <?php esc_html_e( 'Demo store — fictional products.', 'velora' ); ?></p>
		<p><?php esc_html_e( 'Payments via WooCommerce gateways (Stripe test mode when configured).', 'velora' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
