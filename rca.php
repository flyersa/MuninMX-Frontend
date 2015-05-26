<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = APP_NAME . " - Analyzer"; include("templates/core/head.tpl.php"); ?>
	</head>
	<body <?php if($_SESSION['minify'] == true) { echo 'class="desktop-detected pace-done minified"'; } else { echo 'class=""';} ?>>

		<!-- HEADER -->
		<header id="header">
		<?php include("templates/core/header.tpl.php"); ?>
		</header>
		<!-- END HEADER -->

		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<?php include("templates/nav/left.tpl.php"); ?>
		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">
			   <!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="index.php">Home</a></li><li>Analyzer</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-stethoscope"></i> Analyzer</h1>
					</div>
				</div>

			<!-- content -->
			<?php
			if(!$_GET && !$_POST)
			{
				include("templates/rca/forms/newjob.tpl.php");
			}
			elseif($_GET['viewpast'] == "true")
			{
				renderRcaPastTable();	
			}
			// view running analysis
			elseif(isset($_GET['viewrunning']))
			{
				$rca = getRcaJob($_GET['viewrunning']);		
				if($rca == false)
				{
					header("Location: 404.php");
					die;
				}
				//print_r($rca);
				$status = getRcaStatus($rca->rcaId);
				if(!$status)
				{
					header("Location: 404.php");
					die;
				}
				$tpl = $rca;
				include("templates/rca/runningStatus.tpl.php");
			}
			elseif(isset($_GET['view']))
			{
				$rca = getRcaJob($_GET['view']);		
				if($rca == false)
				{
					header("Location: 404.php");
					die;
				}
				if($_SESSION['role'] != "admin")
				{
					if($rca->user_id != $_SESSION['user_id'])
					{
						header("Location: 403.php");
						die;
					}	
				}
				$tpl = $rca;
				$tpl->json = json_decode($rca->output);
				include("templates/rca/search-details.tpl.php");
				if($tpl->json->matchcount > 0)
				{	
					include("templates/rca/view.tpl.php");
				}
				else
				{
					display_info("No Results","The Analysis did not return any matches");	
				}			
			}
			elseif($_POST)
			{
				// [input_day] => 08/07/2014 [start_hour] => 06:00 [end_hour] => 07:00 [analysis_days] => 7 [group] => GHEI-n15 [categoryfilter] => redis )
				//print_r($_POST);
				$_POST = secureArray($_POST);
				
				$reset = date_default_timezone_get();
				date_default_timezone_set($_SESSION['timezone']);
				
				$start_time = strtotime($_POST['input_day']." ".$_POST['start_hour']);
				$end_time = strtotime($_POST['input_day']." ".$_POST['end_hour']);
				
				$rcaId = sha1($start_time.$end_time.$_POST['analysis_days'].$_POST['group'].$_POST['categoryfilter'].$_POST['percentage'].$_SESSION['user_id']);
				//echo $rcaId;
				date_default_timezone_set($reset);
				
				
				// check if we have a finished result for this rcaId , if so redirect to result, or start a new one
				$rca = getRcaJob($rcaId);
				if($rca == false)
				{
					
					// set groupfilter,categoryfilter
					if($_POST['group'] == "allXXall")
					{
						$_POST['group'] = "NULL";	
					}
					else
					{
						$_POST['group'] = "'$_POST[group]'";		
					}

					
					// set groupfilter,categoryfilter
					if($_POST['categoryfilter'] == "allXXall")
					{
						$_POST['categoryfilter'] = "NULL";	
					}
					else
					{
						$_POST['categoryfilter'] = "'$_POST[categoryfilter]'";		
					}
					
					// start analysis
					$db->query("INSERT INTO rca (user_id,groupname,categoryfilter,rcaId,percentage,querydays,start_time,end_time,threshold)
					VALUES (
					'$_SESSION[user_id]',
					$_POST[group],
					$_POST[categoryfilter],
					'$rcaId',
					'$_POST[percentage]',
					'$_POST[analysis_days]',
					'$start_time',
					'$end_time',
					'$_POST[threshold]'
					)
					");
					// queue
					if($db->affected_rows > 0)
					{
						$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/addrca/$rcaId");	
						if(trim($ret) == "true")
						{
							display_ok("Job Queued","Please wait. Redirecting to Running View. Or <a href=\"".BASEURL."/rca.php?viewrunning=$rcaId\">click here</a>");
							echo '<meta http-equiv="refresh" content="3; URL='.BASEURL.'/rca.php?viewrunning='.$rcaId.'">';
						}
						else
						{
							$db->query("DELETE FROM rca WHERE rcaId = '$rcaId'");
							display_error("Queue Error","Unable to queue job. Communication Error with Collector. Please try again later");	
							include("templates/rca/forms/newjob.tpl.php");
						}
					}
					else
					{
						display_error("Storage Error","Error saving Job. Try again later");
						include("templates/rca/forms/newjob.tpl.php");
					}
				}
				else
				{
					if($rca->is_finished == 1)
					{
						header("Location: rca.php?view=".$rca->rcaId);	
						die;
					}
					else
					{
						header("Location: rca.php?viewrunning=".$rca->rcaId);	
						die;						
					}
					
				}
				
				/*
				 * CREATE TABLE IF NOT EXISTS `rca` (
				  `id` int(4) NOT NULL AUTO_INCREMENT,
				  `user_id` int(4) NOT NULL,
				  `groupname` varchar(40) DEFAULT NULL,
				  `categoryfilter` varchar(80) DEFAULT NULL,
				  `rcaId` varchar(40) NOT NULL,
				  `is_finished` smallint(1) NOT NULL DEFAULT '0',
				  `last_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  `output` longtext NOT NULL,
				  `singlehost` int(4) DEFAULT '0',
				  `percentage` int(1) NOT NULL DEFAULT '10',
				  `querydays` int(1) NOT NULL DEFAULT '3',
				  `start_time` int(8) NOT NULL,
				  `end_time` int(8) NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `rcaId` (`rcaId`),
				  KEY `user_id` (`user_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
								 * 
				 */
			}
			?>
			<!-- end content -->


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
		
		<script>
				$("#input_day").datepicker({
			    //defaultDate: "+1w",
			    changeMonth: true,
			    numberOfMonths: 2,
			    prevText: '<i class="fa fa-chevron-left"></i>',
			    nextText: '<i class="fa fa-chevron-right"></i>'	
			});
			
			var oTablePast = $('#viewpast').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 50
				});
			
			
			// validate edit field
			var $rcaform = $('#rcaform').validate({
			// Rules for form validation
				rules : {
					input_day : {
						required : true,
					},
					threshold : {
						required : true,
						number : true
					}
					
				},
		
				// Messages for form validation
				messages : {
					input_day: {
						required : 'Please enter a valid date'
					}		
				},
		

			});	
			
		//	$('#start_hour').timepicker();
		//	$('#end_hour').timepicker();

    	  $('#start_hour').timepicker({
  				minuteStep: 1,
                showSeconds: false,
                showMeridian: false,
                defaultTime: "0:00"           
	  	  });
	  	  
    	  $('#end_hour').timepicker({
  				minuteStep: 1,
                showSeconds: false,
                showMeridian: false,
                defaultTime: "23:59"           
	  	  });	  	  
			
			<?php if($_GET['viewrunning']) { ?>
				$("#viewrunning").everyTime(3000,'timer', function(i) {
					$.getJSON('ajax/rcaStatus.php?rcaId=<?php echo $_GET['viewrunning']?>', function (json) { 
						if(json.status == "Analysis complete")
						{
							$("#viewrunning").stopTime('timer');
							$('#rcabar').css('width', "100%");
							$("#viewrunning").html('Analysis Complete. <a href="<?php echo BASEURL?>/rca.php?view=<?php echo $_GET['viewrunning']?>">Click here</a> to view results');
							window.location = "<?php echo BASEURL?>/rca.php?view=<?php echo $_GET['viewrunning']?>";	
						}
						$('#rcabar').css('width', json.w + "%");	
						$("#viewrunning").html(json.status); 
					});  
				});
			<?php } ?>	
			
			<?php include("templates/core/viewScripts.tpl.php"); ?>		
			oTablePast.fnSort( [ [9,'desc'] ] );
		</script>
	</body>

</html>