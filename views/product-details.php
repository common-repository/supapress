<?php

if ( ! defined( 'ABSPATH' ) ) exit;




/** @type SupaPress_Book $supapress */ ?>

<?php if($supapress->has_book()) : $supapress->the_book(false);
?>

		<div class="book-wrapper">
			<div class="title-wrapper">
				<?php

				$supapress->the_title('<h1>', '</h1>');

				?>
			</div>
			<div class="image-wrapper" data-baseline-images="wrapper">
				<?php

				$supapress->the_cover();

				$supapress->the_subtitle();

				$supapress->the_edition();

				$supapress->the_author();

				$supapress->the_author_bio();

				$supapress->the_publisher();

				$supapress->the_imprint();

				$supapress->the_series();

				$supapress->the_isbn13();

				$supapress->the_price();

				$supapress->the_format();

				$supapress->the_publication_date();

				$supapress->the_sales_date();

				?>
			</div>
			<div class="information-wrapper">
				<?php

				$supapress->the_summary();

				$supapress->the_description();

				$supapress->the_trim_size();

				$supapress->the_weight();

				$supapress->the_awards();

				$supapress->the_reviews();

				$supapress->the_pages();

				$supapress->the_retailers();

//                $supapress->the_retailer_links();
				?>
			</div>
		</div>

<?php else :

	$supapress->no_book();

endif;