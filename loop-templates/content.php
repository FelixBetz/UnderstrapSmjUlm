<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article class="col-lg-4" <?php post_class(); ?> id="post-<?php the_ID(); ?>">


	<div class="card">
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
				<div class="entry-meta">
					<?php understrap_posted_on(); ?>
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
