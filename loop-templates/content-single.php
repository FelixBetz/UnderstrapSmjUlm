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
		<div class="row">
			<div class="col-md-auto" >
			<img style="width: 100px; border: 1px solid black; border-radius: 50px;"
			src="<?php echo get_the_post_thumbnail_url( $post->ID, 'thumbnail' ); ?>" />
				
			</div>
			<div class="col">
				<div class="row">
					<div class="col"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></div>
				</div>
				<div class="row">
					<div class="col-md-auto">
					<?php understrap_posted_on(); ?>
					</div>
					<div class="col-md-auto">
						<?php 
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								foreach($categories as $category) { 
									echo '<a style="text-decoration: none;" href="'. get_category_link( $category->term_id)  .'" <span class="ml-2 badge badge-pill">' . $category->name . ' </span></a>'; 
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
