<?php
/**
 * Admin settings for Velora Core.
 *
 * @package Velora_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register settings page under WooCommerce.
 *
 * @return void
 */
function velora_core_admin_menu() {
	add_submenu_page(
		'woocommerce',
		__( 'Velora Settings', 'velora-core' ),
		__( 'Velora', 'velora-core' ),
		'manage_woocommerce',
		'velora-core',
		'velora_core_render_settings_page'
	);
}
add_action( 'admin_menu', 'velora_core_admin_menu' );

/**
 * Register settings.
 *
 * @return void
 */
function velora_core_register_settings() {
	register_setting(
		'velora_core_settings',
		'velora_core_support_email',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_email',
			'default'           => '',
		)
	);
}
add_action( 'admin_init', 'velora_core_register_settings' );

/**
 * Settings page markup.
 *
 * @return void
 */
function velora_core_render_settings_page() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Velora Core', 'velora-core' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'velora_core_settings' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="velora_core_support_email"><?php esc_html_e( 'Support email', 'velora-core' ); ?></label></th>
					<td><input type="email" class="regular-text" id="velora_core_support_email" name="velora_core_support_email" value="<?php echo esc_attr( get_option( 'velora_core_support_email', '' ) ); ?>" /></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<hr />
		<h2><?php esc_html_e( 'Demo products', 'velora-core' ); ?></h2>
		<p><?php esc_html_e( 'Import fictional demonstration products (safe to re-run; skips existing SKUs).', 'velora-core' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="velora_import_demo" />
			<?php wp_nonce_field( 'velora_import_demo' ); ?>
			<?php submit_button( __( 'Import demo products + images', 'velora-core' ), 'secondary' ); ?>
		</form>
		<p><?php esc_html_e( 'Stripe: install the official “WooCommerce Stripe Payment Gateway” plugin and configure test keys in WooCommerce → Settings → Payments. Never commit API keys.', 'velora-core' ); ?></p>
	</div>
	<?php
}
