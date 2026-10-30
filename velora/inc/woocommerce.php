<?php
/**
 * WooCommerce theme integration via hooks (prefer hooks over overrides).
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Products per page / columns.
 *
 * @return void
 */
function velora_wc_loop_config() {
	add_filter( 'loop_shop_per_page', static fn() => 12 );
	add_filter( 'loop_shop_columns', static fn() => 3 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	add_action( 'woocommerce_before_main_content', 'velora_wc_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'velora_wc_wrapper_end', 10 );
	add_action( 'woocommerce_before_shop_loop', 'velora_shop_toolbar_open', 15 );
	add_action( 'woocommerce_before_shop_loop', 'velora_shop_filters_toggle', 25 );
	add_action( 'woocommerce_before_shop_loop', 'velora_shop_toolbar_close', 40 );
}
add_action( 'after_setup_theme', 'velora_wc_loop_config' );

/**
 * Wrapper start.
 *
 * @return void
 */
function velora_wc_wrapper_start() {
	echo '<main id="main" class="velora-main"><div class="velora-container velora-shop-layout">';
	if ( is_shop() || is_product_taxonomy() ) {
		get_template_part( 'template-parts/shop/filters' );
		echo '<div class="velora-shop-results">';
	}
}

/**
 * Wrapper end.
 *
 * @return void
 */
function velora_wc_wrapper_end() {
	if ( is_shop() || is_product_taxonomy() ) {
		echo '</div>';
	}
	echo '</div></main>';
}

/**
 * Toolbar open.
 *
 * @return void
 */
function velora_shop_toolbar_open() {
	echo '<div class="velora-shop-toolbar">';
}

/**
 * Toolbar close.
 *
 * @return void
 */
function velora_shop_toolbar_close() {
	echo '</div>';
}

/**
 * Mobile filters button.
 *
 * @return void
 */
function velora_shop_filters_toggle() {
	echo '<button type="button" class="velora-btn velora-btn--ghost velora-filter-open" data-filter-open>' . esc_html__( 'Filters', 'velora' ) . '</button>';
}

/**
 * Cart fragments for header count.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function velora_cart_count_fragment( $fragments ) {
	ob_start();
	?>
	<span class="velora-cart-count" data-cart-count><?php echo esc_html( (string) WC()->cart->get_cart_contents_count() ); ?></span>
	<?php
	$fragments['span.velora-cart-count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'velora_cart_count_fragment' );

/**
 * Related products count.
 *
 * @param array $args Args.
 * @return array
 */
function velora_related_products_args( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns']        = 3;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'velora_related_products_args' );

/**
 * Apply shop filters via pre_get_posts.
 *
 * @param WP_Query $query Query.
 * @return void
 */
function velora_apply_product_filters( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( ! function_exists( 'is_shop' ) || ! ( is_shop() || is_product_taxonomy() ) ) {
		return;
	}

	$meta_query = array_filter( (array) $query->get( 'meta_query' ) );
	$tax_query  = array_filter( (array) $query->get( 'tax_query' ) );

	$min = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$max = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( $min > 0 ) {
		$meta_query[] = array(
			'key'     => '_price',
			'value'   => $min,
			'compare' => '>=',
			'type'    => 'NUMERIC',
		);
	}
	if ( $max > 0 ) {
		$meta_query[] = array(
			'key'     => '_price',
			'value'   => $max,
			'compare' => '<=',
			'type'    => 'NUMERIC',
		);
	}
	if ( isset( $_GET['stock'] ) && 'instock' === sanitize_text_field( wp_unslash( $_GET['stock'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$meta_query[] = array(
			'key'   => '_stock_status',
			'value' => 'instock',
		);
	}

	$rating = isset( $_GET['rating_filter'] ) ? absint( $_GET['rating_filter'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( $rating >= 1 && $rating <= 5 ) {
		$meta_query[] = array(
			'key'     => '_wc_average_rating',
			'value'   => $rating,
			'compare' => '>=',
			'type'    => 'DECIMAL',
		);
	}

	if ( ! empty( $_GET['product_cat'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => sanitize_title( wp_unslash( $_GET['product_cat'] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		);
	}

	foreach ( array( 'size' => 'pa_size', 'color' => 'pa_color' ) as $key => $taxonomy ) {
		$param = 'filter_' . $key;
		if ( empty( $_GET[ $param ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			continue;
		}
		$terms       = array_map( 'sanitize_title', (array) wp_unslash( $_GET[ $param ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$tax_query[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $terms,
			'operator' => 'IN',
		);
	}

	if ( count( $meta_query ) > 1 ) {
		$meta_query['relation'] = 'AND';
	}
	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	if ( ! empty( $meta_query ) ) {
		$query->set( 'meta_query', $meta_query );
	}
	if ( ! empty( $tax_query ) ) {
		$query->set( 'tax_query', $tax_query );
	}
}
add_action( 'pre_get_posts', 'velora_apply_product_filters' );
