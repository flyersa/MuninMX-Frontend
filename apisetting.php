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
	<?php $tpl->title = APP_NAME . " - API Key"; include("templates/core/head.tpl.php"); ?>
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
					<li>Home</li><li>Dashboard</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-gears"></i> API <span>> Key Management</span></h1>
					</div>
				</div>

				<!-- row -->
				<div class="row">
						<!-- NEW WIDGET START -->
						<article class="col-sm-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					
								<header>
									<span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
									<h2>YOUR API KEY </h2>
								</header>
								<!-- widget div-->
								<div>
				
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
				
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body">
										<?php
										$user = getUserObject($_SESSION['user_id']);
										if($_GET['generate'])
										{
											checkToken();
											$key = sha1($_SESSION['user_id'].microtime());
											$db->query("UPDATE users SET apikey = '$key' WHERE id = '$user->id'");
											display_ok("Key Generated","New API Key stored");
											$user = getUserObject($_SESSION['user_id']);
										}
										
										if(trim($user->apikey) == "" )
										{
											$key = sha1($_SESSION['user_id'].microtime());
											$db->query("UPDATE users SET apikey = '$key' WHERE id = '$user->id'");		
											$user = getUserObject($_SESSION['user_id']);								
										}
										?>									
										<p>MuninMX features a RESTful API Interface at: <a href="api.php" target="_blank"><?php echo $_SERVER['HTTP_HOST']?>/api.php</a></p>
										<p>Please <a href="apidoc.php">read the API Documentation</a> for details.</p>
									
										<h3>Your Key:</h3>
										<p>
											<code><?php echo $user->apikey?></code>
										</p>
										<br />
										<?php if(!isset($_SESSION['viacrowd'])) { ?>
											<footer>
												<a href="apisetting.php?generate=true&token=<?php echo getToken()?>" class="btn-lg btn-primary">
													 Generate new key
												</a>
											</footer>
										<?php } ?>
									</div>
									
								</div>
									
							</div>
						</article>
				</div>
				<!-- end row -->


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
	</body>

</html>