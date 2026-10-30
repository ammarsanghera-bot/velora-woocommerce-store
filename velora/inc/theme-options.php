<?php
/**
 * Customizer options.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 * @return void
 */
function velora_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'velora_store',
		array(
			'title'    => __( 'Velora Store', 'velora' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'velora_hero_heading' => array( 'Everyday Essentials. Elevated.', __( 'Hero heading', 'velora' ) ),
		'velora_hero_text'    => array( 'Discover timeless pieces designed for modern living. Thoughtful details, refined style, and everyday comfort.', __( 'Hero text', 'velora' ) ),
		'velora_footer_about' => array( 'VELORA crafts elevated everyday essentials for modern living. This is a portfolio demonstration store with fictional products.', __( 'Footer about', 'velora' ) ),
		'velora_support'      => array( 'support@velora.example', __( 'Support email', 'velora' ) ),
	);

	foreach ( $fields as $id => $args ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $args[0],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $args[1],
				'section' => 'velora_store',
				'type'    => 'textarea',
			)
		);
	}
}
add_action( 'customize_register', 'velora_customize_register' );
