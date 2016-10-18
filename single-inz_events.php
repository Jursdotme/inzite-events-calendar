<?php get_header(); ?>

	<main role="main">
		<section>
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
						<?php the_post_thumbnail(); // Fullsize image for the single post ?>
				<?php endif; ?>

				<h2>
					<?php the_title(); ?>
				</h2>

				<p class="date">
					<?php
						$inz_event_start_date = get_post_meta( get_the_ID(), 'inz_event_start_date', true );
						echo date('d-m-Y', $inz_event_start_date);
						$inz_event_start_time = get_post_meta( get_the_ID(), 'inz_event_start_time', true );
						$inz_event_end_time = get_post_meta( get_the_ID(), 'inz_event_end_time', true );
						if ($inz_event_start_time) {
							echo ' (Kl. ' . $inz_event_start_time;
							if ($inz_event_end_time) {
								echo ' - ' . $inz_event_end_time;
							}
							echo ')';
						}
					?>
				</p>

				<?php the_content(); ?>

				<?php // comments_template(); ?>

			</article>

		<?php endwhile; ?>

		<?php endif; ?>

		</section>


	</main>
<h1>PLUGIN</h1>
<?php get_footer(); ?>
