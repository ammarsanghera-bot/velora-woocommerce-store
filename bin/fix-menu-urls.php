<?php
/**
 * Replace localhost menu URLs with current home_url().
 */

$menus = wp_get_nav_menus();
foreach ( $menus as $menu ) {
	$items = wp_get_nav_menu_items( $menu->term_id );
	if ( ! $items ) {
		continue;
	}
	foreach ( $items as $item ) {
		if ( 'custom' !== $item->type || false === strpos( $item->url, 'localhost' ) ) {
			continue;
		}
		$new = preg_replace( '#https?://localhost:\d+#', untrailingslashit( home_url() ), $item->url );
		wp_update_nav_menu_item(
			$menu->term_id,
			$item->db_id,
			array(
				'menu-item-title'  => $item->title,
				'menu-item-url'    => $new,
				'menu-item-status' => 'publish',
				'menu-item-type'   => 'custom',
			)
		);
		echo "updated {$item->title} -> {$new}\n";
	}
}
echo 'home=' . home_url() . "\n";
