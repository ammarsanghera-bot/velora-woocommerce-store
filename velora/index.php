<?php
/**
 * Fallback index.
 *
 * @package Velora
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="velora-main">
	<div class="velora-container velora-section">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class(); ?>>
					<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing found.', 'velora' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
