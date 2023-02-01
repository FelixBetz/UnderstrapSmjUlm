<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<footer class="footer mt-auto">

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>



<nav class="d-flex justify-content-center  navbar navbar-expand-lg navbar-dark bg-dark">

    <ul class="navbar-nav">
	<?php


$menuLocations = get_nav_menu_locations(); 
$footerMenuID = $menuLocations['footer-menu'];
$menuitems = wp_get_nav_menu_items($footerMenuID, array( 'order' => 'DESC' ) );
foreach($menuitems as $item)
{
      echo '<li class="nav-item active">';
	  echo '<a class="nav-link" href="'. $item->url  . '">'. $item->title  . '<span class="sr-only">(current)</span></a>';
      echo '</li>';
	}
	?>
    </ul>
</nav>


<!-- todoFB
<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">

			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<div class="site-info">

						<?php understrap_site_info(); ?>

					</div>

				</footer>

			</div>

		</div>

	</div>

</div>

<?php // Closing div#page from header.php. ?>
</div> -->

<?php wp_footer(); ?>

</footer>
</body>

</html>

