<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}

if(is_numeric($_GET['dashboard']))
{
	$did = $_GET['dashboard'];
	$board = getDashboard($did);
	if($board == false)
	{
		header("Location: 404.php");
		die;
	}
						

	if(!gotAccessToDashboard($did))
	{
		header("Location: 403.php");
		die;							
	}
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = APP_NAME . " - Dashboard"; include("templates/core/head.tpl.php"); ?>
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
					<li>Dashboards</li><li><?php echo htmlspecialchars($board->dashboard_name)?></li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->



			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-7">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-dashboard"></i> Dashboards <span> > <?php echo htmlspecialchars($board->dashboard_name)?></span></h1>							
					</div>
					<?php
					if($_SESSION['role'] == "admin" || $_SESSION['user_id'] == $board->user_id) { 
						if(is_numeric($_GET['dashboard'])) {
						?>
									<div class="btn-group" style="float: right; padding-right: 15px">
											<button class="btn btn-xl btn-danger">
												Dashboard Settings
											</button>
											<button class="btn btn-xl btn-danger dropdown-toggle" data-toggle="dropdown">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li>
													<a href="dashboard.php?dashboard=<?php echo $board->id?>&edit=true"><i class="fa fa-edit"></i> Edit</a>
												</li>
												
												<li class="divider"></li>
												<li>
													<a href="dashboard.php?dashboard=<?php echo $board->id?>&delete=true&token=<?php echo getToken()?>"><i class="fa fa-trash-o"></i> Delete Dashboard</a>
												</li>
											</ul>
										</div>
				    <?php } 
					} ?>					
				</div>
			
				<!-- widget grid -->
				<section id="widget-grid" class="">
				<!-- row -->
				<div class="row">

				<?php 
				if(!is_numeric($_GET['dashboard']))
				{
					// render overview
					if(isset($_SESSION['dok']) && $_SESSION['dok'] != "false")
					{
						display_ok("Dashboard Removed","Dashboard ".htmlspecialchars($_SESSION['dok'])," removed");
						$_SESSION['dok'] = "false";
					}
					renderDashboardTable();
				}
				else 
				{
						if($_GET['edit'])
						{
							if($_SESSION['role'] == "admin" || $_SESSION['user_id'] == $board->user_id) {
								if(!$_POST)
								{
									include("templates/dashboards/edit.tpl.php");
								}
								else
								{
									$dname = $db->real_escape_string($_POST['dashboard_name']);
									if($_POST['groupname'] != "XXnoneXX")
									{
										$groupname = $db->real_escape_string($_POST['groupname']);
										$g = ",groupname = '$groupname'";
									}
									else
									{
										$g = ",groupname = ''";	
									}
									if(!is_numeric($_POST['global_refresh']))
									{
										$gr = 5;
									}
									else
									{
										$gr = $_POST['global_refresh'];
									}
									$db->query("UPDATE dashboards SET global_refresh = $gr, dashboard_name = '$dname'$g WHERE id = $board->id");
									display_ok("OK","Dashboard Changes Saved");
								}
							}
						}
						elseif($_GET['delete'])
						{
							if($_SESSION['role'] == "admin" || $_SESSION['user_id'] == $board->user_id) {
								checkToken();
								include("templates/dashboards/delete.tpl.php");
							}
						}
						elseif($_GET['deletefinal'])
						{
							if($_SESSION['role'] == "admin" || $_SESSION['user_id'] == $board->user_id) {
								checkToken();
								$db->query("DELETE FROM dashboards WHERE id = $board->id");
								$_SESSION['dok'] = $board->dashboard_name;
								header("Location: dashboard.php");
								die;
							}
						}						
						elseif(is_numeric($_GET['item']))
						{
							$item = $_GET['item'];
							// remove a item from board
							if($_GET['remove'])
							{
								checkToken();
								$db->query("DELETE FROM dashboard_items WHERE dashboard_id = $board->id AND id = $item");
								if($db->affected_rows > 0)
								{
									display_ok("Widget Removed","The chosen widget was removed from this dashboard");
								}
								else
								{
									display_error("Unable to remove widget","Please try again later");
								}
							}
							elseif(isset($_GET['period']))
							{
								switch($_GET['period'])
								{
									case "30min":
										$period = "30min";
										break;
									case "1hour":
										$period = "1hour";
										break;											
									case "2hour":
										$period = "2hour";
										break;
									case "4hour":
										$period = "4hour";
										break;	
									case "24hour":
										$period = "24hour";
										break;	
									default:
										$period = "1hour";
										break;																														
								}	
								checkToken();
								$db->query("UPDATE dashboard_items SET period = '$period' WHERE dashboard_id = $board->id AND id = $item");
								display_ok("Period Updated","The period has been updated for the chosen widget");
							}
							elseif(isset($_GET['wsize']))
							{
								switch($_GET['wsize'])
								{
									case "small":
										$wsize = "small";
										break;
									case "large":
										$wsize = "large";
										break;											
									default:
										$wsize = "small";
										break;																														
								}	
								checkToken();
								$db->query("UPDATE dashboard_items SET wsize = '$wsize' WHERE dashboard_id = $board->id AND id = $item");
								display_ok("Widget Size Updated","The size has been updated for the chosen widget");
							}							
							elseif(isset($_GET['stype']))
							{
								switch($_GET['stype'])
								{
									case "area":
										$stype = "area";
										break;
									case "areastack":
										$stype = "areastack";
										break;
									case "line":
										$stype = "line";
										break;
									case "column":
										$stype = "column";
										break;
									default:
										$stype = "area";
										break;
								}
								checkToken();
								$db->query("UPDATE dashboard_items SET stype = '$stype' WHERE dashboard_id = $board->id AND id = $item");
								display_ok("Graph Mode Updated","The graph mode has been updated for the chosen widget");									
							}
						}
						
						$r = renderDashboardWidgets($did);
						if($r == false)
						{
							display_warning("No Items in Dashboard", "This Dashboard has no associated metrics. Go to a node, select a metric and select \"Add to Dashboard\" on the settings button");
						}
					}	
	
				?>
				
				
			
						
				</div>
				<!-- end row -->			

				</section>
				<!-- end widget grid -->


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php $tpl->enablejarvis = true; include("templates/core/scripts.tpl.php"); ?>
		
		<script>
						// validate edit field
			var $customform = $('#dashform').validate({
			// Rules for form validation
				rules : {
					dashboard_name : {
						required : true,
						minlength : 4
					}
					
				},
		
				// Messages for form validation
				messages : {
					dashboard_name : {
						required : 'Please enter a valid name for this dashboard'
					}		
				},
		

			});	
			
				var oTableDashboards = $('#dashboardtable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 50 
				});	
				
				
		</script>
	</body>

</html>