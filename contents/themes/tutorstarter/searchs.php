<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Tutor_Starter
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container">
	<div class="row">
		<div class="col-xl-12 col-lg-12 col-sm-12">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

					<?php
					// Lấy từ khóa tìm kiếm từ URL
					$term = get_query_var('term');
					$post_types = ['digitalsat', 'ieltswritingtest', 'ieltsreadingtest']; // Các post type cần tìm

					// Thiết lập tham số cho truy vấn
					$args = [
						'post_type' => $post_types,
						's' => $term,
					];

					$query = new WP_Query($args);

					if ($query->have_posts()) : ?>

						<header>
							<h1 class="page-title">
								<?php
									printf(
										/* translators: %s: Search Term. */
										esc_html__( 'Advanced Search Results for: %s', 'tutorstarter' ),
										'<span>' . esc_html($term) . '</span>'
									);
								?>
							</h1>
						</header><!-- .page-header -->

						<?php
						/* Start the Loop */
						while ($query->have_posts()) :
							$query->the_post();

							// Hiển thị nội dung cho từng post type
							get_template_part( 'views/content', get_post_type() ); // Sử dụng loại post hiện tại để lấy template
						endwhile;

						the_posts_navigation();

					else :

						get_template_part( 'views/content', 'none' );

					endif;

					// Reset post data
					wp_reset_postdata();
					?>

				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .col- -->
	</div><!-- .row -->
</div><!-- .container -->
<div style="padding-bottom:30px"></div>

<?php
get_footer();
