<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	$_SESSION['REAL_REFERRER'] = $_SERVER['REQUEST_URI'];
	header("Location: login.php");
	die;
}
if($_GET['debug'])
{
	print_r($_SERVER);
}

if($_SESSION['role'] != "admin")
{
	header("Location: 403.php");
	die;
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = APP_NAME . " - MuninMX Status"; include("templates/core/head.tpl.php"); ?>
	</head>
	<body <?php if($_SESSION['minify'] == true) { echo 'class="desktop-detected pace-done minified"'; } else { echo 'class=""';} ?>>
		<a name="top"></a>
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
					<li><a href="index.php">Home</a></li><li>MuninMX Status</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-info"></i> MuninMX Status</h1>
					</div>
				</div>
				
				<section id="widget-grid" class="">
				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
									<h2>MuninMX Status</h2>
								</header>
								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body no-padding">
										<div class="widget-body-toolbar">
											<?php 
											if($_GET['action'] == "noplugins")
											{
												display_info("No Plugins on Nodes","MuninMX is unable to load plugins from munin-node on the following hosts. If \"Last Contact\" is 
												never the machine might be offline or no munin-node is running. If Last Contact contains a valid time, the host running MuninMXcd might not be allowed to query the munin-node (munin-node.conf, allow section)");
											}
											elseif($_GET['action'] == "joblist")
											{
												display_info("Scheduler Status","This table will list the next firetime for metric gathering of MuninMXcd to the munin-node");	
											}
											?>
										</div>
										<div class="widget-body-toolbar">
										</div>
											<?php
												if(!$_GET['action'])
												{
													display_info("No Action","Please select a item from the submenu");
												}
												elseif($_GET['action'] == "noplugins")
												{

													renderNoPluginTable();
												}
												elseif($_GET['action'] == "joblist")
												{

													renderJobListTable();
												}	
												elseif($_GET['action'] == "customjoblist")
												{
													renderCustomJobListTable();
												}											
											?>

								
									</div>
								</div>
							</div>
					</article>
				</div>
				<!-- end row -->

				</section>

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
		<script type="application/javascript">
			$(document).ready(function() {
				$('#statustable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 30 
				});
			})
							
		</script>
	</body>

</html>