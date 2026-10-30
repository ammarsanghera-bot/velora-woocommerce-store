<?php
/**
 * Header.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="velora-skip" href="#main"><?php esc_html_e( 'Skip to content', 'velora' ); ?></a>
<header class="velora-header" id="site-header">
	<div class="velora-container velora-header__inner">
		<button class="velora-nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="<?php esc_attr_e( 'Open menu', 'velora' ); ?>">
			<span></span><span></span><span></span>
		</button>
		<div class="velora-brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="velora-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">VELORA</a>
			<?php endif; ?>
		</div>
		<nav id="site-nav" class="velora-nav" aria-label="<?php esc_attr_e( 'Primary', 'velora' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'velora-nav__list',
					'fallback_cb'    => 'velora_fallback_menu',
				)
			);
			?>
		</nav>
		<div class="velora-header__actions">
			<a class="velora-icon-link" href="<?php echo esc_url( home_url( '/?s=&post_type=product' ) ); ?>" aria-label="<?php esc_attr_e( 'Search', 'velora' ); ?>">
				<span aria-hidden="true">⌕</span>
			</a>
			<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
				<a class="velora-icon-link" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="<?php esc_attr_e( 'Account', 'velora' ); ?>">
					<span aria-hidden="true">◎</span>
				</a>
			<?php endif; ?>
			<a class="velora-icon-link" href="<?php echo esc_url( velora_wishlist_url() ); ?>" aria-label="<?php esc_attr_e( 'Wishlist', 'velora' ); ?>">
				<span aria-hidden="true">♡</span>
			</a>
			<button type="button" class="velora-icon-link velora-cart-toggle" data-cart-open aria-label="<?php esc_attr_e( 'Open cart', 'velora' ); ?>">
				<span aria-hidden="true">👜</span>
				<span class="velora-cart-count" data-cart-count><?php echo esc_html( (string) ( function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ) ); ?></span>
			</button>
		</div>
	</div>
</header>
<div class="velora-minicart" id="velora-minicart" hidden>
	<div class="velora-minicart__panel" role="dialog" aria-label="<?php esc_attr_e( 'Mini cart', 'velora' ); ?>">
		<button type="button" class="velora-minicart__close" data-cart-close aria-label="<?php esc_attr_e( 'Close cart', 'velora' ); ?>">×</button>
		<h2><?php esc_html_e( 'Your bag', 'velora' ); ?></h2>
		<div class="widget_shopping_cart_content">
			<?php
			if ( function_exists( 'woocommerce_mini_cart' ) ) {
				woocommerce_mini_cart();
			}
			?>
		</div>
	</div>
</div>
