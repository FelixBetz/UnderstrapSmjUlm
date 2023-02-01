<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

	//set default col size to 6
	$col_size = 6;
	if ( $args['col_size'] ) {
		$col_size =  $args['col_size'];
	}
?>

<article class="col-lg-<?php echo $col_size ?>" <?php post_class(); ?> id="post-<?php the_ID(); ?>">


	<div class="card rounded">
		<a class="img-card" href= "<?php echo esc_url( get_permalink() ) ; ?>">
			<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
		</a>
		<div class="card-content">
			<!-- card-title start -->
			<h4 class="card-title">
				<a href= "<?php echo esc_url( get_permalink() ) ; ?>">
					<?php the_title(sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),'</a></h2>');?>
				</a>
			</h4>
			<!-- card-title end -->

			<!-- meta start -->
			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="d-flex justify-content-between align-items-center">
					<?php understrap_posted_on(); ?> 
					<?php 
						$categories = get_the_category();
						if ( ! empty( $categories ) ) {
							foreach($categories as $category) { 
								echo '<a style="text-decoration: none;" href="'. get_category_link( $category->term_id)  .'" <span class="ml-2 badge badge-pill bg-secondary">' . $category->name . ' </span></a>'; 
							}
						}

					?>
				</div>
			<?php endif; ?>
			<!-- .meta end-->

			<!-- content start-->
			<p class="">
				<?php
					the_excerpt();
					understrap_link_pages();
				?>
			</p>
			<!-- content end-->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
