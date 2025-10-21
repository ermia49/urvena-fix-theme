<?php
/**
 * Main Template File
 *
 * @package URVENA_Fix
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="content-area">
	<div class="container">
		<div class="content-wrapper">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<?php
							if ( is_singular() ) :
								the_title( '<h1 class="entry-title">', '</h1>' );
							else :
								the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
							endif;
							?>
						</header>

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="entry-thumbnail">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>
						<?php endif; ?>

						<div class="entry-content">
							<?php
							if ( is_singular() ) {
								the_content();
							} else {
								the_excerpt();
							}
							?>
						</div>
					</article>
					<?php
				endwhile;

				the_posts_navigation();
			else :
				?>
				<div class="no-results">
					<h1><?php esc_html_e( 'Nichts gefunden', 'urvena-fix' ); ?></h1>
					<p><?php esc_html_e( 'Es wurden keine Inhalte gefunden. Versuchen Sie es mit einer Suche.', 'urvena-fix' ); ?></p>
					<?php get_search_form(); ?>
				</div>
				<?php
			endif;
			?>
		</div>
	</div>
</div>

<?php
get_footer();

