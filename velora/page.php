<?php
/**
 * Page template.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="velora-main">
	<div class="velora-container velora-section velora-prose">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
