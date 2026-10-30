<?php
/**
 * Front page.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main">
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/categories' );
	get_template_part( 'template-parts/home/new-arrivals' );
	get_template_part( 'template-parts/home/featured' );
	get_template_part( 'template-parts/home/why' );
	get_template_part( 'template-parts/home/bestsellers' );
	get_template_part( 'template-parts/home/newsletter' );
	?>
</main>
<?php
get_footer();
