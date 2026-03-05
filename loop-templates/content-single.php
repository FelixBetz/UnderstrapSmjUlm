<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">
		<div class="d-flex  align-items-center gap-2">
			<div class="mr-1">
				<img style="width: 100px;" class="border border-secondary border-2 rounded-circle"
				src="<?php echo get_the_post_thumbnail_url( $post->ID, 'thumbnail' ); ?>" />
			</div>
			<div >
				<div class="flex-column ">	
					<div>
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
					</div>

					<div class="d-flex gap-2">
						<div>
							<?php understrap_posted_on(); ?>
						</div>
			
						<?php 
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								foreach($categories as $category) {  
								
									echo '<a href='. get_category_link( $category->term_id)  .' class="ml-1 mr-1 pt-0 pb-0 btn btn-secondary btn-sm" role="button" aria-pressed="true">'. $category->name . '</a>'; 
								}
							}
						?>
						
					</div>
				</div>

			</div>
		</div>

		

	</header><!-- .entry-header -->
	<hr class="my-3">
	<div class="entry-content">

		<?php
		the_content();
		understrap_link_pages();
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php /*todoFB understrap_entry_footer(); */?>

	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
