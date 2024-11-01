<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type SupaPress_Book $supapress */ ?>

<?php if($supapress->has_books()) :

	if($supapress->has_filters()) : ?>

		<?php $supapress->the_filters(); ?>

	<?php endif; ?>

	<div class="search-wrapper<?php echo $supapress->has_filters() ? ' has-filters' : ''; ?>">

		<div class="pagination-wrapper">
			<?php

			$supapress->the_sort_by();

			$supapress->the_per_page();

			$supapress->the_pagination();

            $supapress->the_search_term();

			$supapress->the_result_count();

			?>
		</div>

		<div class="books-wrapper">

			<?php while ( $supapress->has_books() ) : $supapress->the_book(); ?>

				<div class="book-wrapper">
					<div class="image-wrapper" data-baseline-images="wrapper">
						<?php $supapress->the_cover(); ?>
					</div>
					<?php

					$supapress->the_title();

					$supapress->the_subtitle();

					$supapress->the_edition();

					$supapress->the_price();

					$supapress->the_format();

					$supapress->the_author();

					$supapress->the_author_bio();

					$supapress->the_publisher();

					$supapress->the_series();

					$supapress->the_imprint();

					$supapress->the_publication_date();

					$supapress->the_sales_date();

					$supapress->the_summary();

					$supapress->the_description();

					$supapress->the_isbn13();

					$supapress->the_pages();

                    $supapress->the_retailer_links();

					?>
				</div>

			<?php endwhile; ?>

		</div>

	</div>

<?php else :

	$supapress->no_books();

endif;