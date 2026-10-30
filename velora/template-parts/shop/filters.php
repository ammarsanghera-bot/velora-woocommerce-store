<?php
/**
 * Shop filters — GET form works without JS; drawer enhanced by JS.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

$min   = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$max   = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$stock = isset( $_GET['stock'] ) ? sanitize_text_field( wp_unslash( $_GET['stock'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$action = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>
<aside class="velora-filters" id="velora-filters">
	<button type="button" class="velora-filters__close" data-filter-close aria-label="<?php esc_attr_e( 'Close filters', 'velora' ); ?>">×</button>
	<h2><?php esc_html_e( 'Filters', 'velora' ); ?></h2>
	<form method="get" action="<?php echo esc_url( $action ); ?>">
		<?php
		$cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true ) );
		if ( ! is_wp_error( $cats ) && $cats ) :
			?>
			<fieldset class="velora-filter-group">
				<legend><?php esc_html_e( 'Category', 'velora' ); ?></legend>
				<?php foreach ( $cats as $cat ) : ?>
					<label>
						<input type="radio" name="product_cat" value="<?php echo esc_attr( $cat->slug ); ?>" <?php checked( isset( $_GET['product_cat'] ) ? sanitize_title( wp_unslash( $_GET['product_cat'] ) ) : '', $cat->slug ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?> />
						<?php echo esc_html( $cat->name ); ?>
					</label>
				<?php endforeach; ?>
			</fieldset>
		<?php endif; ?>

		<fieldset class="velora-filter-group">
			<legend><?php esc_html_e( 'Price', 'velora' ); ?></legend>
			<label><?php esc_html_e( 'Min', 'velora' ); ?><input type="number" name="min_price" min="0" value="<?php echo esc_attr( (string) $min ); ?>" /></label>
			<label><?php esc_html_e( 'Max', 'velora' ); ?><input type="number" name="max_price" min="0" value="<?php echo esc_attr( (string) $max ); ?>" /></label>
		</fieldset>

		<?php
		foreach ( array( 'pa_size' => __( 'Size', 'velora' ), 'pa_color' => __( 'Color', 'velora' ) ) as $tax => $label ) :
			if ( ! taxonomy_exists( $tax ) ) {
				continue;
			}
			$terms = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => true ) );
			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				continue;
			}
			$param = 'filter_' . str_replace( 'pa_', '', $tax );
			?>
			<fieldset class="velora-filter-group">
				<legend><?php echo esc_html( $label ); ?></legend>
				<?php foreach ( $terms as $term ) : ?>
					<label>
						<input type="checkbox" name="<?php echo esc_attr( $param ); ?>[]" value="<?php echo esc_attr( $term->slug ); ?>"
							<?php
							$selected = isset( $_GET[ $param ] ) ? (array) wp_unslash( $_GET[ $param ] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
							checked( in_array( $term->slug, array_map( 'sanitize_title', $selected ), true ) );
							?>
						/>
						<?php echo esc_html( $term->name ); ?>
					</label>
				<?php endforeach; ?>
			</fieldset>
		<?php endforeach; ?>

		<fieldset class="velora-filter-group">
			<legend><?php esc_html_e( 'Rating', 'velora' ); ?></legend>
			<?php
			$rating = isset( $_GET['rating_filter'] ) ? absint( $_GET['rating_filter'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			foreach ( array( 4, 3, 2 ) as $stars ) :
				?>
				<label>
					<input type="radio" name="rating_filter" value="<?php echo esc_attr( (string) $stars ); ?>" <?php checked( $rating, $stars ); ?> />
					<?php
					/* translators: %d: star rating threshold */
					printf( esc_html__( '%d stars & up', 'velora' ), (int) $stars );
					?>
				</label>
			<?php endforeach; ?>
		</fieldset>

		<fieldset class="velora-filter-group">
			<legend><?php esc_html_e( 'Availability', 'velora' ); ?></legend>
			<label><input type="checkbox" name="stock" value="instock" <?php checked( $stock, 'instock' ); ?> /> <?php esc_html_e( 'In stock', 'velora' ); ?></label>
		</fieldset>

		<button class="velora-btn velora-btn--primary" type="submit"><?php esc_html_e( 'Apply', 'velora' ); ?></button>
		<a class="velora-text-link" href="<?php echo esc_url( $action ); ?>"><?php esc_html_e( 'Reset', 'velora' ); ?></a>
	</form>
</aside>
