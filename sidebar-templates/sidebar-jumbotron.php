<?php
/**
 * Sidebar - The Hero Canvas Widget Area
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

    function parseOffset($offset){
      $offset = intval($offset);
      $defaultVal = "0%";
      if(!is_int($offset) || $offset < 0 ||$offset >100){
        return $defaultVal;
      }

      return $offset . "%";

    }


    $jumptronIsActive = get_theme_mod('understrap_jumbotron_isActive');

    //Text Settings
    $jumptronHeadline = get_theme_mod('understrap_jumbotron_headline');
    $jumptronParagraph = get_theme_mod('understrap_jumbotron_paragrahp');

    //Button Settings
    $jumptronButtonText = get_theme_mod('understrap_jumbotron_button_text');
    $jumptronButtonLink = get_theme_mod('understrap_jumbotron_button_link');

    //Image settings
    $jumptronBgOpacity = get_theme_mod('understrap_jumbotron_image_opacity');
  
    $jumptronBgXoffst = parseOffset(get_theme_mod('understrap_jumbotron_image_x_offset'));
    $jumptronBgYoffst = parseOffset(get_theme_mod('understrap_jumbotron_image_y_offset'));

    $jumptronBgImage = get_theme_mod('understrap_jumbotron_image');

    $jumptronBgImageUrl = wp_get_attachment_url( intval($jumptronBgImage) );


    if($jumptronIsActive){
?>
<!-- start -->
<style>
.jumbotron_background{
  background: url( "<?php echo $jumptronBgImageUrl; ?>");
  background-repeat: no-repeat;
  background-size: cover;
  background-position: <?php echo $jumptronBgXoffst . " " . $jumptronBgYoffst; ?>;
  opacity:<?php echo $jumptronBgOpacity; ?>;
  position: absolute;
  z-index: -1;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  width: 100%;
  height: 100%;
}
.jumbotron_content{
  position: relative;
  z-index: 1;
}
</style>



<div class="jumbotron jumbotron-fluid text-center bg-light m-0 p-3 jumbotron_content">
  <div class="jumbotron_background"> </div>
  <div class="container-fluid">
    <h1 class="display-5  fw-bold"><?php echo $jumptronHeadline; ?></h1>
    <p class="lead"><?php echo $jumptronParagraph; ?></p>
	  <a href="<?php echo $jumptronButtonLink; ?>" class="btn btn-outline-primary btn-lg rounded-3" role="button" aria-pressed="true">
      <?php echo $jumptronButtonText; ?>
    </a>
  </div>
</div>

<?php
   }