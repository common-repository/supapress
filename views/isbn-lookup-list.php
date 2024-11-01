<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/** @type SupaPress_Book $supapress */ ?>

<?php if($supapress->has_books()) : ?>

	<?php while ( $supapress->has_books() ) : $supapress->the_book(); ?>

		<div class="book-wrapper <?php $supapress->cover_position(); ?>">
			<div class="image-wrapper" data-baseline-images="wrapper">
				<?php $supapress->the_cover(); ?>
			</div>
			<div class="information-wrapper">
				<?php

                $supapress->the_retailer_links();

                $supapress->the_title();

				$supapress->the_subtitle();

				$supapress->the_edition();

				$supapress->the_price();

				$supapress->the_format();

				$supapress->the_author();

				$supapress->the_author_bio();

				$supapress->the_publisher();

				$supapress->the_imprint();

				$supapress->the_publication_date();

				$supapress->the_sales_date();

				$supapress->the_summary();

				$supapress->the_description();

				$supapress->the_isbn13();

				$supapress->the_pages();
				?>
			</div>
		</div>

	<?php endwhile;

else :

	$supapress->no_books();

endif;