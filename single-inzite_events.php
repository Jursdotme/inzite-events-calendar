<?php get_header(); ?>

	<main role="main">
		<section>
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
						<?php the_post_thumbnail(); // Fullsize image for the single post ?>
				<?php endif; ?>

				<h1 class="event-title">
					<?php the_title(); ?>
				</h1>

				<p class="date">
					<?php
						$inzite_event_start_date = get_post_meta( get_the_ID(), 'inzite_event_start_date', true );

						echo date_i18n(__('j. F - Y', 'github-Jursdotme-inzite-events-calendar'), $inzite_event_start_date);

						$inzite_event_start_time = get_post_meta( get_the_ID(), 'inzite_event_start_time', true );
						$inzite_event_end_time = get_post_meta( get_the_ID(), 'inzite_event_end_time', true );

						if ($inzite_event_start_time) {
							_e(" Fra $inzite_event_start_time", 'github-Jursdotme-inzite-events-calendar');
							if ($inzite_event_end_time) {
								_e(" til $inzite_event_end_time", 'github-Jursdotme-inzite-events-calendar');
							}
						}
					?>
				</p>

				<?php the_content(); ?>

			</article>

		<?php endwhile; ?>

		<?php endif; ?>

		</section>


	</main>
<?php get_footer(); ?>
