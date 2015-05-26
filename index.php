<?php
if($_GET['info'] && $_GET['debug'])
{
	phpinfo();
	die;
}
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
if($_GET['action'] == "add")
{
	if($_SESSION['role'] != "admin")
	{
		$user = getUserObject($_SESSION['user_id']);
		$niu = getNodeCountForUser($user->id);
		$niuleft = " ( $niu / $user->max_nodes )";
	}
	else
	{
		$niuleft = "";
	}
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = APP_NAME . " - Your Nodes"; include("templates/core/head.tpl.php"); ?>
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
					<li>Home</li><li>Your Nodes</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Home <span>> Your Nodes</span></h1>
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
									<h2><?php  if($_SERVER['REQUEST_URI']  != "/index.php?action=add" && $_GET['action'] != "groups") { echo 'Your Nodes'; } elseif ($_GET['action'] == "groups") { echo 'Your Groups'; } else { echo 'Add Nodes'; echo $niuleft;}?></h2>									
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
										</div>

										
										<?php 
											if(!$_GET['action'] == "add")
											{
												// load with ajax if user is admin to speed up frontpage loading time (because of high number of munin nodes)
												if($_SESSION['role'] == "admin")
												{
													$atable = true;
													renderNodeTableAdmin();	
												}	
												else
												{
													renderNodeTable(); 	
												}	
												include("templates/munin/add.snipet.php");
													
											}
											elseif($_GET['action'] == "groups")
											{
												/*
												if($_SESSION['role'] != "admin")
												{
													display_error("Access Denied","This feature is restricted to admins");
												}
												else
												{*/
													if(!$_GET['selection'])
													{
														renderGroupTable();	
													}
													else {
														renderNodesFromGroup($_GET['selection']);
													}
												//}
											}
											elseif($_GET['action'] == "add")
											{
												if($_SESSION['role'] == "admin" || $_SESSION['role'] == "userext")
												{
													if(!$_POST)
													{
														if($_SESSION['role'] != "admin")
														{
															if($niu == $user->max_nodes || $niu > $user->max_nodes)
															{
																display_error("Maximum Node Number reached","You reched the maximum amount of nodes for your account. Upgrade to add more");
															}
															else
															{
																include("templates/munin/add.tpl.php");
															}
																				
														}
														else
														{
															include("templates/munin/add.tpl.php");	
														}													
														
													}
													else
													{
														if($_SESSION['role'] != "admin")
														{
															if($niu == $user->max_nodes || $niu > $user->max_nodes)
															{
																display_error("Maximum Node Number reached"," Maximum nodes allowed for your account reached. Upgrade your plan to add more.");
																die;
															}
																				
														}
														
														//print_r($_POST);
														$_POST = secureArray($_POST);
														$db->query("SELECT * FROM nodes WHERE hostname = '$_POST[hostname]' AND port = $_POST[port] AND user_id = '$_SESSION[user_id]'");
														if($db->affected_rows > 0)
														{
															display_error("Node exists","We have already a node with this hostname associated to this user");
															include("templates/munin/add.tpl.php");
														}
														else
														{
															if($_POST['via_host'] != "unset")
															{
																$poignore = true;
															}
															else
															{
																$poignore = false;
															}
															if(!isPortOpen($_POST['hostname'],$_POST['port'],$poignore))
															{
																display_error("Port not open",$_POST['port']. " on host ".$_POST['hostname']." is not open");	
																include("templates/munin/add.tpl.php");
															}
															else
															{
																$db->query("INSERT INTO nodes (user_id,hostname,port,query_interval,groupname,via_host,authpw) values ($_SESSION[user_id],'$_POST[hostname]',$_POST[port],$_POST[query_interval],'$_POST[groupname]','$_POST[via_host]','$_POST[authpw]')");
																if($db->insert_id < 1)
																{
																	display_error("Database Error","Unable to add node to database. Try again later or check input");
																	include("templates/munin/add.tpl.php");
																}
																else
																{
																	$nid = $db->insert_id;
																	// skoll:49000/queuejob/
																	$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/queuejob/$nid");
																	if(trim($json) == "true")
																	{
																		sleep(1);
																		display_ok("Node Added",'<a href="view.php?nid='.$nid.'">'.$_POST[hostname].'</a> added to database and queued for first run. It may take a minute to see the initial plugin information.');	
																	}
																	else
																	{
																		if(startsWith($json, "License Error"))
																		{
																			display_error("License Error","Maximum Number of Nodes for this MuninMX Installation reached");	
																			$db->query("DELETE FROM nodes WHERE id = '$nid'");
																		}
																		else 
																		{
																			display_warn("Node Added but unable to queue",'<a href="view.php?nid='.$nid.'">'.$_POST[hostname].'</a> added to database but queue failed. MuninMX restart might be required');		
																		}
																		
																	}
																}
															}
														}
													}
												}
												else
												{
													display_error("Access Denied","You cannot add nodes (restricted to admin)");
												}
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
		jQuery.ajaxSetup({ cache: false });
		<?php if(!$atable) { ?>
			$(document).ready(function() {
				var oTable = $('#nodetable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});
				<?php if(!$_GET['action'] == "groups") { ?>
				oTable.fnSort( [ [1,'asc'] ] );
				<?php } else { ?>
				oTable.fnSort( [ [1,'desc'] ] );	
				<?php } ?>
			})
		<?php } else { ?>	
			$(document).ready(function() {
				var oTablea = $('#nodetableAdmin').dataTable({
					"sPaginationType" : "bootstrap_full",
					"aoColumnDefs": [{ "bSortable": false, "aTargets": [ 2 ] }],					
					"iDisplayLength" : 25,
					"bProcessing": true,
					"oLanguage":      { sProcessing: "Please Wait. Loading Nodes..." },
			        "sAjaxSource": "dtaTable.php"
				});
				oTablea.fnSort( [ [1,'asc'] ] );
			})	
		<?php } ?>			
			// validate edit field
			var $editForm = $('#editForm').validate({
			// Rules for form validation
				rules : {
					hostname : {
						required : true
					},
					groupname : {
						required : true
					},					
					port : {
						required : true,
						digits : true
					}
				},
		
				// Messages for form validation
				messages : {
					hostname : {
						required : 'Please enter a valid hostname or ip'
					},
					port : {
						required : 'Please enter the port of the munin node. 4949 is default'
					},
					port : {
						required : 'Please enter a groupname'
					}					
				},
		
				// Do not change code below
				//errorPlacement : function(error, element) {
				//	error.insertAfter(element.parent());
				//}
			});	
			
			$('#tabs').tabs();
			$.fn.dataTableExt.sErrMode = 'throw';
		</script>
	</body>

</html>