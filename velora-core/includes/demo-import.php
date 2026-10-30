<?php
/**
 * Idempotent fictional demo product importer + media enrichment.
 *
 * @package Velora_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handle demo import request.
 *
 * @return void
 */
function velora_core_handle_demo_import() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		wp_die( esc_html__( 'Unauthorized.', 'velora-core' ), 403 );
	}
	check_admin_referer( 'velora_import_demo' );

	$count = velora_core_import_demo_products();
	velora_core_enrich_store_media();
	wp_safe_redirect(
		add_query_arg(
			array(
				'page'          => 'velora-core',
				'velora_import' => $count,
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_velora_import_demo', 'velora_core_handle_demo_import' );

/**
 * Create categories, attributes, and demo products if missing.
 *
 * @return int Number of products created this run.
 */
function velora_core_import_demo_products() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return 0;
	}

	$cats = array(
		'women'       => __( "Women's Collection", 'velora-core' ),
		'men'         => __( "Men's Collection", 'velora-core' ),
		'bags'        => __( 'Bags & Accessories', 'velora-core' ),
		'lifestyle'   => __( 'Lifestyle Essentials', 'velora-core' ),
		'footwear'    => __( 'Footwear', 'velora-core' ),
		'accessories' => __( 'Accessories', 'velora-core' ),
	);

	$cat_ids = array();
	foreach ( $cats as $slug => $name ) {
		$term = term_exists( $slug, 'product_cat' );
		if ( ! $term ) {
			$term = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
		}
		$cat_ids[ $slug ] = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
		$term_obj         = get_term( $cat_ids[ $slug ], 'product_cat' );
		if ( $term_obj && ! is_wp_error( $term_obj ) && $term_obj->name !== $name ) {
			wp_update_term( $cat_ids[ $slug ], 'product_cat', array( 'name' => $name ) );
		}
	}

	velora_core_ensure_attribute( 'pa_size', 'Size', array( 'XS', 'S', 'M', 'L', 'XL' ) );
	velora_core_ensure_attribute( 'pa_color', 'Color', array( 'Ivory', 'Sand', 'Charcoal', 'Olive', 'Black' ) );

	$products = array(
		array( 'Essential Linen Shirt', 'VEL-LINEN-SHIRT', 78, array( 'men' ), true, false, 'Breathable linen shirt for elevated everyday wear. Fictional demo product.' ),
		array( 'Everyday Cotton T-Shirt', 'VEL-TEE-01', 32, array( 'men', 'women' ), false, true, 'Soft cotton tee with a clean silhouette. Fictional demo product.' ),
		array( 'Classic Tailored Trousers', 'VEL-TROUSER', 118, array( 'men' ), true, false, 'Tailored trousers with a refined drape. Fictional demo product.' ),
		array( 'Minimal Leather Tote', 'VEL-TOTE', 165, array( 'bags', 'women' ), true, false, 'Spacious leather tote for daily carry. Fictional demo product.' ),
		array( 'Everyday Crossbody Bag', 'VEL-CROSSBODY', 98, array( 'bags', 'accessories' ), false, false, 'Compact crossbody with adjustable strap. Fictional demo product.' ),
		array( 'Modern Leather Sneakers', 'VEL-SNEAKER', 142, array( 'footwear' ), true, true, 'Minimal sneakers in soft leather. Fictional demo product.' ),
		array( 'Premium Cotton Hoodie', 'VEL-HOODIE', 88, array( 'men', 'lifestyle' ), false, false, 'Relaxed hoodie in brushed cotton. Fictional demo product.' ),
		array( 'Signature Weekend Bag', 'VEL-WEEKEND', 210, array( 'bags', 'lifestyle' ), true, false, 'Structured weekend bag for short trips. Fictional demo product.' ),
		array( 'Minimalist Wallet', 'VEL-WALLET', 54, array( 'accessories' ), false, false, 'Slim bifold wallet. Fictional demo product.' ),
		array( 'Classic Summer Dress', 'VEL-DRESS', 126, array( 'women' ), true, true, 'Easy summer dress with a fluid line. Fictional demo product.' ),
		array( 'Essential Knit Sweater', 'VEL-SWEATER', 96, array( 'women', 'men' ), false, false, 'Fine-knit sweater for transitional weather. Fictional demo product.' ),
		array( 'Everyday Travel Pouch', 'VEL-POUCH', 42, array( 'lifestyle', 'accessories' ), false, false, 'Zip pouch for travel essentials. Fictional demo product.' ),
		array( 'Soft Merino Scarf', 'VEL-SCARF', 48, array( 'accessories', 'lifestyle' ), false, false, 'Lightweight merino scarf for cool mornings. Fictional demo product.' ),
		array( 'Structured Blazer', 'VEL-BLAZER', 198, array( 'women' ), true, false, 'Clean-line blazer for elevated daywear. Fictional demo product.' ),
		array( 'Relaxed Oxford Shirt', 'VEL-OXFORD', 72, array( 'men' ), false, false, 'Soft oxford shirt with a relaxed collar. Fictional demo product.' ),
		array( 'Leather Belt', 'VEL-BELT', 58, array( 'accessories', 'men' ), false, false, 'Minimal leather belt with brushed buckle. Fictional demo product.' ),
		array( 'Canvas Slip-Ons', 'VEL-SLIPON', 88, array( 'footwear', 'lifestyle' ), false, true, 'Easy canvas slip-ons for everyday comfort. Fictional demo product.' ),
		array( 'Wide-Leg Trousers', 'VEL-WIDELEG', 112, array( 'women' ), false, false, 'Fluid wide-leg trousers with a high rise. Fictional demo product.' ),
		array( 'Compact Card Holder', 'VEL-CARD', 36, array( 'accessories' ), false, false, 'Slim card holder for light carry. Fictional demo product.' ),
		array( 'City Cap', 'VEL-CAP', 34, array( 'accessories', 'lifestyle' ), false, false, 'Structured cotton cap for city days. Fictional demo product.' ),
	);

	$created = 0;
	foreach ( $products as $item ) {
		list( $title, $sku, $price, $categories, $featured, $on_sale, $desc ) = $item;

		$existing = wc_get_product_id_by_sku( $sku );
		if ( $existing ) {
			continue;
		}

		$product = new WC_Product_Simple();
		$product->set_name( $title );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_description( $desc );
		$product->set_short_description( 'Fictional Velora demonstration product.' );
		$product->set_sku( $sku );
		$product->set_regular_price( (string) $price );
		if ( $on_sale ) {
			$product->set_sale_price( (string) max( 10, (int) ( $price * 0.85 ) ) );
		}
		$product->set_manage_stock( true );
		$product->set_stock_quantity( 25 );
		$product->set_stock_status( 'instock' );
		$product->set_featured( $featured );
		$product->set_category_ids( array_values( array_intersect_key( $cat_ids, array_flip( $categories ) ) ) );
		$product_id = $product->save();

		velora_core_assign_product_attributes( $product_id );
		velora_core_assign_product_meta( $product_id );

		++$created;
	}

	if ( ! wc_get_product_id_by_sku( 'VEL-VAR-TEE' ) ) {
		$variable = new WC_Product_Variable();
		$variable->set_name( 'Essential Tee — Size & Color' );
		$variable->set_status( 'publish' );
		$variable->set_catalog_visibility( 'visible' );
		$variable->set_sku( 'VEL-VAR-TEE' );
		$variable->set_description( 'Variable demonstration product with size and color. Fictional demo product.' );
		$variable->set_short_description( 'Fictional Velora demonstration product.' );
		$variable->set_category_ids( array( $cat_ids['women'], $cat_ids['men'] ) );
		$parent_id = $variable->save();

		$attributes = array();
		foreach ( array( 'pa_size' => array( 'S', 'M', 'L' ), 'pa_color' => array( 'Ivory', 'Black' ) ) as $tax => $options ) {
			$attribute = new WC_Product_Attribute();
			$attribute->set_id( wc_attribute_taxonomy_id_by_name( $tax ) );
			$attribute->set_name( $tax );
			$attribute->set_options( $options );
			$attribute->set_visible( true );
			$attribute->set_variation( true );
			$attributes[] = $attribute;
		}
		$variable = wc_get_product( $parent_id );
		$variable->set_attributes( $attributes );
		$variable->save();

		foreach ( array( 'S', 'M', 'L' ) as $size ) {
			foreach ( array( 'Ivory', 'Black' ) as $color ) {
				$variation = new WC_Product_Variation();
				$variation->set_parent_id( $parent_id );
				$variation->set_attributes(
					array(
						'pa_size'  => sanitize_title( $size ),
						'pa_color' => sanitize_title( $color ),
					)
				);
				$variation->set_regular_price( '36' );
				$variation->set_manage_stock( true );
				$variation->set_stock_quantity( 10 );
				$variation->set_stock_status( 'instock' );
				$variation->save();
			}
		}
		velora_core_assign_product_meta( $parent_id );
		++$created;
	}

	velora_core_enrich_store_media();

	return $created;
}

/**
 * Assign size/color attributes to a simple product.
 *
 * @param int $product_id Product ID.
 * @return void
 */
function velora_core_assign_product_attributes( $product_id ) {
	wp_set_object_terms( $product_id, array( 's', 'm', 'l' ), 'pa_size' );
	wp_set_object_terms( $product_id, array( 'ivory', 'sand', 'charcoal' ), 'pa_color' );
	$attrs = array();
	foreach ( array( 'pa_size' => array( 'S', 'M', 'L' ), 'pa_color' => array( 'Ivory', 'Sand', 'Charcoal' ) ) as $tax => $options ) {
		$attribute = new WC_Product_Attribute();
		$attribute->set_id( wc_attribute_taxonomy_id_by_name( $tax ) );
		$attribute->set_name( $tax );
		$attribute->set_options( $options );
		$attribute->set_visible( true );
		$attribute->set_variation( false );
		$attrs[] = $attribute;
	}
	$saved = wc_get_product( $product_id );
	if ( $saved ) {
		$saved->set_attributes( $attrs );
		$saved->save();
	}
}

/**
 * Assign Velora custom meta.
 *
 * @param int $product_id Product ID.
 * @return void
 */
function velora_core_assign_product_meta( $product_id ) {
	update_post_meta( $product_id, '_velora_subtitle', __( 'Everyday elevated', 'velora-core' ) );
	update_post_meta( $product_id, '_velora_material', __( 'Premium textiles / materials (demo)', 'velora-core' ) );
	update_post_meta( $product_id, '_velora_highlight', __( 'Designed for modern daily wear', 'velora-core' ) );
	update_post_meta( $product_id, '_velora_dispatch', __( 'Ships within 2–4 business days', 'velora-core' ) );
	update_post_meta( $product_id, '_velora_care', __( 'Follow care label. Demo content only.', 'velora-core' ) );
}

/**
 * Sideload product + category images (Unsplash — free license; demo use).
 *
 * @return int Number of products that received an image this run.
 */
function velora_core_enrich_store_media() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// Curated Unsplash photo IDs (fashion/lifestyle). Demo only — not affiliated brands.
	$image_map = array(
		'VEL-LINEN-SHIRT' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-TEE-01'      => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-TROUSER'     => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-TOTE'        => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-CROSSBODY'   => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-SNEAKER'     => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-HOODIE'      => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-WEEKEND'     => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-WALLET'      => 'https://images.unsplash.com/photo-1627123424574-724758594e93?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-DRESS'       => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-SWEATER'     => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-POUCH'       => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&h=1125&q=70',
		'VEL-VAR-TEE'     => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-SCARF'       => 'https://images.unsplash.com/photo-1520903920245-00d872a80aa7?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-BLAZER'      => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-OXFORD'      => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-BELT'        => 'https://images.unsplash.com/photo-1624222247344-550fb60583fd?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-SLIPON'      => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-WIDELEG'     => 'https://images.unsplash.com/photo-1506629082955-511b1aa78430?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-CARD'        => 'https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?auto=format&fit=crop&w=900&h=1125&q=80',
		'VEL-CAP'         => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=900&h=1125&q=80',
	);

	$cat_images = array(
		'women'       => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&h=1125&q=80',
		'men'         => 'https://images.unsplash.com/photo-1490578474895-499c8edb2a67?auto=format&fit=crop&w=900&h=1125&q=80',
		'bags'        => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=900&h=1125&q=80',
		'lifestyle'   => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=900&h=1125&q=80',
		'footwear'    => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=900&h=1125&q=80',
		'accessories' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?auto=format&fit=crop&w=900&h=1125&q=80',
	);

	$updated = 0;
	foreach ( $image_map as $sku => $url ) {
		$product_id = wc_get_product_id_by_sku( $sku );
		if ( ! $product_id ) {
			continue;
		}
		if ( has_post_thumbnail( $product_id ) ) {
			continue;
		}
		$attach_id = velora_core_sideload_image( $url, $product_id, get_the_title( $product_id ) );
		if ( $attach_id ) {
			set_post_thumbnail( $product_id, $attach_id );
			++$updated;
		}
	}

	foreach ( $cat_images as $slug => $url ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( ! $term || is_wp_error( $term ) ) {
			continue;
		}
		$existing = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
		if ( $existing ) {
			continue;
		}
		$attach_id = velora_core_sideload_image( $url, 0, $term->name . ' category' );
		if ( $attach_id ) {
			update_term_meta( $term->term_id, 'thumbnail_id', $attach_id );
		}
	}

	return $updated;
}

/**
 * Sideload a remote image into the media library.
 *
 * @param string $url     Image URL.
 * @param int    $post_id Parent post ID.
 * @param string $desc    Description.
 * @return int Attachment ID or 0.
 */
function velora_core_sideload_image( $url, $post_id, $desc ) {
	$tmp = download_url( $url, 45 );
	if ( is_wp_error( $tmp ) ) {
		return 0;
	}

	$file_array = array(
		'name'     => sanitize_file_name( md5( $url ) . '.jpg' ),
		'tmp_name' => $tmp,
	);

	$attach_id = media_handle_sideload( $file_array, $post_id, $desc );
	if ( is_wp_error( $attach_id ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		return 0;
	}

	return (int) $attach_id;
}

/**
 * Ensure a global attribute taxonomy exists with terms.
 *
 * @param string   $taxonomy Attribute taxonomy.
 * @param string   $label    Label.
 * @param string[] $terms    Term names.
 * @return void
 */
function velora_core_ensure_attribute( $taxonomy, $label, array $terms ) {
	$slug = str_replace( 'pa_', '', $taxonomy );
	if ( ! taxonomy_exists( $taxonomy ) ) {
		$id = wc_create_attribute(
			array(
				'name'         => $label,
				'slug'         => $slug,
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
			)
		);
		if ( ! is_wp_error( $id ) ) {
			register_taxonomy(
				$taxonomy,
				array( 'product' ),
				array(
					'labels'       => array( 'name' => $label ),
					'hierarchical' => false,
					'show_ui'      => false,
					'query_var'    => true,
					'rewrite'      => false,
				)
			);
		}
	}

	foreach ( $terms as $term ) {
		if ( ! term_exists( $term, $taxonomy ) ) {
			wp_insert_term( $term, $taxonomy );
		}
	}
}
