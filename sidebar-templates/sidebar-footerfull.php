<?php
/**
 * Sidebar setup for footer full
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );

?>

<style>
.timestamp-container {
  color: #333;
  margin: 0 auto;
  text-align: center;
}

.timestamp-container h1 {
  font-weight: normal;
  letter-spacing: .125rem;
  text-transform: uppercase;
  font-size: 1.5rem;
  margin: 0px;
  padding: 0px;
}

.timestamp-container li {
  display: inline-block;
  font-size: 1.5em;
  list-style-type: none;
  padding: 1em;
  text-transform: uppercase;
  padding-top: 0px;
  padding-bottom: 0px;
}

.timestamp-container li span {
  display: block;
  font-size: 4.5rem;
  margin: 0px;
}

</style>

<script type="text/javascript">

	let end = new Date('08/11/2023 10:1 AM');


	let _second = 1000;
	let _minute = _second * 60;
	let _hour = _minute * 60;
	let _day = _hour * 24;
	let timer;

	function showRemaining() {


		let now = new Date();
		let distance = end - now;
		if (distance < 0) {

			clearInterval(timer);
			document.getElementById("days").innerText = 0,
			document.getElementById("hours").innerText = 0,
			document.getElementById("minutes").innerText = 0,
			document.getElementById("seconds").innerText = 0;

			return;
		}
		let days = Math.floor(distance / _day);
		let hours = Math.floor((distance % _day) / _hour);
		let minutes = Math.floor((distance % _hour) / _minute);
		let seconds = Math.floor((distance % _minute) / _second);

		document.getElementById("days").innerText = days,
		document.getElementById("hours").innerText = hours,
		document.getElementById("minutes").innerText = minutes,
		document.getElementById("seconds").innerText = seconds;
	}
	function startCountdown(endDate){
		end = new Date(endDate * 1000);
		timer = setInterval(showRemaining, 1000);
	}



</script>
<?php
	$countdownDate = get_theme_mod('understrap_countdown');
	$countdownLabel = get_theme_mod('understrap_countdown_label');

	$a = strptime($countdownDate, '%Y -%m-%dT%H:%M');
	$timestamp = mktime($a['tm_hour'], $a['tm_min'], $a['tm_sec'], $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
	$timestamp -= 3600 ; //todoFB convert to GMT
	echo "
	<script type=\"text/javascript\">
		startCountdown(" .$timestamp. ");
	</script>
";
?>



<?php if ( is_active_sidebar( 'footerfull' ) ) : ?>

	<!-- ******************* The Footer Full-width Widget Area ******************* -->

	<div class="wrapper" id="wrapper-footer-full" role="complementary">

		<div class="<?php echo esc_attr( $container ); ?>" id="footer-full-content" tabindex="-1">

			<div class="row">

				<?php dynamic_sidebar( 'footerfull' ); ?>

			</div>
			<div class="row">
			
				<div class="col d-flex justify-content-center">
					
					<div class="timestamp-container m-0">
  					<h1 id="headline"> <?php echo $countdownLabel ?></h1>
					<div id="countdown m-0">
						<ul class="mt-0 mb-0">
						<li><span id="days"></span>Tage</li>
						<li><span id="hours"></span>Stunden</li>
						<li><span id="minutes"></span>Minuten</li>
						<li><span id="seconds"></span>Sekunden</li>
						</ul>
					</div>
					</div>
				</div>
			</div>
		</div>

	</div><!-- #wrapper-footer-full -->

	<?php
endif;
