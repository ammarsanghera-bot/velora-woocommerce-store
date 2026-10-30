<?php
/**
 * 404.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="velora-main">
	<div class="velora-container velora-section">
		<h1><?php esc_html_e( 'Page not found', 'velora' ); ?></h1>
		<p><a class="velora-btn velora-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back home', 'velora' ); ?></a></p>
	</div>
</main>
<?php
get_footer();
