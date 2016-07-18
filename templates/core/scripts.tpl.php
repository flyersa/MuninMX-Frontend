		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>

		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
		<script src="js/libs/jquery-2.0.2.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="js/libs/jquery-2.0.2.min.js"><\/script>');
			}
		</script>

		<script src="js/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
		<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

		<!-- BOOTSTRAP JS -->
		<script src="js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

		<!-- SPARKLINES -->
		<script src="js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>

		<!-- JQUERY MASKED INPUT -->
		<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>

		<!-- JQUERY SELECT2 INPUT -->
		<script src="js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="js/plugin/fastclick/fastclick.js"></script>
		<!-- PAGE RELATED PLUGIN(S) -->
		
		<script src="js/plugin/datatables/jquery.dataTables-cust.min.js"></script>
		<script src="js/plugin/datatables/ColReorder.min.js"></script>
		<script src="js/plugin/datatables/FixedColumns.min.js"></script>
		<script src="js/plugin/datatables/ColVis.min.js"></script>
		<script src="js/plugin/datatables/ZeroClipboard.js"></script>
		<script src="js/plugin/datatables/media/js/TableTools.min.js"></script>
		
		
		<script src="js/plugin/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
		<script src="js/plugin/datatables/DT_bootstrap.js"></script>
		<script src="js/jstz.min.js" type="text/javascript"></script>  
		<script src="js/jquery.timers.js" type="text/javascript"></script>
		<script src="js/jquery.form.min.js" type="text/javascript"></script>
		<script src="js/jquery.validationEngine.js" type="text/javascript"></script>
		<script src="js/jquery.validationEngine-en.js" type="text/javascript"></script>
		<!--[if IE 7]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->

		<!-- MAIN APP JS FILE -->
		<script src="js/app.js"></script>
		
	
		<script>
			$(document).ready(function() {
				// DO NOT REMOVE : GLOBAL FUNCTIONS!
				<?php if(!isset($tpl->enablejarvis) || !$tpl->enablejarvis) { ?>
				$.enableJarvisWidgets = false;
				<?php } ?>
				pageSetUp();
				// set up timezone
				var tz = jstz.determine(); // Determines the time zone of the browser client 
		    	  $.get( "ajax/timezone.php?timezone="+tz.name(), function( data ) {
				  console.log( "Timezone is " + tz.name() );
				});
			})
		</script>
		
		<script>
			function minify()
			{
				$.get( "ajax/minify.php?token=<?php echo getToken()?>", function( data ) {});
			}
			
		</script>
