<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	$_SESSION['REAL_REFERRER'] = $_SERVER['REQUEST_URI'];
	header("Location: login.php");
	die;
}

?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = APP_NAME . " - Custom Graphs"; include("templates/core/head.tpl.php"); ?>
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
					<li><a href="index.php">Home</a></li><li>Custom Metrics</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-info"></i> Custom Metrics</h1>
					</div>
				</div>
				
				<section id="widget-grid" class="">
				<?php if(!$_GET['action']) { ?>
				<!-- row -->
				<a href="customs.php?action=add" class="btn dropdown-toggle btn btn-primary">New Grouped Metric</a><br />
				<div class="row" style="padding-top: 20px">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
									<h2>Grouped Metrics</h2>
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
												if(!$_GET['vgroup'])
												{
													renderCustomGraphTable();	
												}
												else
												{
													renderCustomGraphGroupTable();
												}
											?>
									</div>
								</div>
							</div>
					</article>
				</div>
				<!-- end row -->
				<?php } ?>
				
				<?php
				if($_GET['stype'] == "line" || $_GET['stype'] == "area")
				{
					setcookie("random",uniqid(microtime()));
					$_SESSION['stype'] = $_GET['stype'];
					display_ok("Graph Style Changed","New global graph style (for session) set to: " . $_GET['stype']);
				}				
				
				if($_GET['action'] == "view" && is_numeric($_GET['gid']))
				{
					renderCustomGraph($_GET['gid']);
				}
				elseif($_GET['action'] == "view" && is_numeric($_GET['group']))
				{
					$allowed = false;
					$result = $db->query("SELECT * FROM custom_graph_groups WHERE id = $_GET[group]");	
					if($db->affected_rows < 1)
					{
						display_error("Invalid Group","Group not found");
					}
					else
					{					
						$tpl = $result->fetch_object();

						if($_SESSION['role'] != "admin")
						{
							if($tpl->user_id != $_SESSION['user_id'])
							{
								$agroups = explode(",",$_SESSION['accessgroup']);
								if(in_array($tpl->accessgroup,$agroups))	
								{
									$allowed = true;
								}
							}
							else
							{
								$allowed = true;
							}	
						}
						else
						{
							$allowed = true;
						}
					}

					if($allowed)
					{
						$result = $db->query("SELECT * FROM custom_graph_group_items WHERE graph_group_id = $_GET[group]");
						if($db->affected_rows > 0 )
						{
							while($tpl = $result->fetch_object())
							{
								renderCustomGraph($tpl->custom_graph_id);	
							}
						}
						else
						{
							display_error("No Items found","No graphs in this group");
						}
					}	
					else
					{
						display_error("Permission denied","or unknown graph group");
					}			
				}	
				// delete
				elseif($_GET['action'] == "delete" && is_numeric($_GET['gid']))
				{
					$g = getCustomGraph($_GET['gid']);
					if($g == false)
					{
						display_error("Graph not found","We did send out our hamster army, but they did not return :()");	
					}
					else 
					{

						// chck permissions
						$denied = false;
						if($_SESSION['role'] != "admin")
						{
							if($g->user_id != $_SESSION['user_id'])
							{
								$denied = true;
							}	
						}
						if(!$denied)
						{
							if(!$_GET['dowhatisay'])
							{
								include("templates/munin/deleteCustomGraph.tpl.php");
								renderCustomGraph($_GET['gid']);
							}
							else
							{
								checkToken();	
								$db->query("DELETE FROM custom_graphs WHERE id = '$_GET[gid]'");
								if($db->affected_rows > 0)
								{
									display_ok("Graph Removed","We purged this graph from this little funny planet");
								}				
								else
								{
									display_error("Database Error","Sorry. Unable to delete this graph: " . $db->errorInfo());
								}
							}
						}
						else
						{
							display_error("Access Denied","You have no permission to access this graph");
						}
					}
				}
				// edit
				elseif($_GET['action'] == "edit" && is_numeric($_GET['gid']))
				{
					if(accessToCustomGraph($_GET['gid']))
					{
						$g = getCustomGraph($_GET['gid']);
						if(!$_POST)
						{
							include("templates/customs/forms/editgraph.tpl.php");
						}
						else
						{
							// save this shit	
							checkToken();
							
							//print_r($_POST);
							$graph_name = $db->real_escape_string($_POST['graph_name']);
							$graph_desc = $db->real_escape_string($_POST['graph_desc']);
							$hosts = $_POST['otherhosts'];
							$graphs = $_POST['graphs'];
							array_push($hosts, $_POST['basehost']);
							$hosts = array_unique ( $hosts );
							$plugin = $db->real_escape_string($_POST['plugin']);
							$groupname = $db->real_escape_string($_POST['groupname']);		
							
							// check permissions
							$denied = false;
							foreach($hosts as $host)
							{
								if(!accessToNode($host))
								{
									$denied = true;
								}	
							}
												
							if($denied)
							{
								display_error("Access Denied","You tried to add a node to a custom graph that does not belong to you");	
							}
							else
							{												
									// delete 
								$db->query("DELETE FROM custom_graph_group_items WHERE custom_graph_id = $g->id");
								$db->query("DELETE FROM custom_graph_items WHERE custom_graph_id = $g->id");
								
							    // ok lets add the metric first
								if($groupname != "none")
								{
									$db->query("UPDATE custom_graphs SET graph_name = '$graph_name', graph_desc = '$graph_desc', groupname = '$groupname' WHERE id = '$g->id'");
								}
								else 
								{
									$db->query("UPDATE custom_graphs SET graph_name = '$graph_name', graph_desc = '$graph_desc', groupname = '' WHERE id = '$g->id'");
								}	
								
								foreach($hosts as $host)
								{
									foreach($graphs as $graph)
									{
										$db->query("INSERT INTO custom_graph_items (custom_graph_id,node_id,plugin,graph) VALUES ($g->id,$host,'$plugin','$graph')");
									}
								}
								
								// add new metric group too
								if(isset($_POST['newcustommetricgroup']))
								{
									$nmgroup = $db->real_escape_string($_POST['newcustommetricgroup']);
									$db->query("INSERT INTO custom_graph_groups (user_id,groupname) VALUES ($_SESSION[user_id],'$nmgroup')");
									$agroup = $db->insert_id;
								}
								else
								{
									// assign to existing group?
									if(is_numeric($_POST['assigntogroup']))
									{
										$agroup = $_POST['assigntogroup'];
									}
								}	
								
								if(is_numeric($agroup))
								{
									$db->query("INSERT INTO custom_graph_group_items (graph_group_id,custom_graph_id) VALUES ($agroup,$g->id)");
								}
								// set new cache key
								setcookie("random",uniqid(microtime()));	
								display_ok("Graph Saved","Graph changes successfully applied");
								renderCustomGraph($g->id);									
							}				
						}
					}
					else
					{
						display_error("Access Denied","Permission to this custom graph denied or custom graph not found");	
					}
				}				
				// add new
				elseif($_GET['action'] == "add" && !$_POST)
				{
					// clear add session
					display_info("Important","You can only group stats from the same plugins together. Mixing cpu usage with disk i/o as example wont work. You can also add custom groups to display more then one custom graph per page, or use a custom graph to group plugins from multipe hosts per page");
					include("templates/customs/forms/newgraph.tpl.php");
				}
				elseif($_GET['action'] == "add" && $_POST)		
				{
					//  [graph_name] => test [graph_desc] => [groupname] => none [assigntogroup] => 1 [basehost] => 646 [otherhosts] => Array ( [0] => 1087 ) [plugin] => cpu [graphs] => Array ( [0] => user ) )
					//$_POST = secureArray($_POST);
					$graph_name = $db->real_escape_string($_POST['graph_name']);
					$graph_desc = $db->real_escape_string($_POST['graph_desc']);
					$hosts = $_POST['otherhosts'];
					$graphs = $_POST['graphs'];
					array_push($hosts, $_POST['basehost']);
					$hosts = array_unique ( $hosts );
					$plugin = $db->real_escape_string($_POST['plugin']);
					$groupname = $db->real_escape_string($_POST['groupname']);
					//print_r($graphs);
					//print_r($hosts);
					//print_r($_POST);
					
					// check permissions
					$denied = false;
					foreach($hosts as $host)
					{
						if(!accessToNode($host))
						{
							$denied = true;
						}	
					}
										
					if($denied)
					{
						display_error("Access Denied","You tried to add a node to a custom graph that does not belong to you");	
					}
					else
					{
						// ok lets add the metric first
						if($groupname != "none")
						{
							$db->query("INSERT INTO custom_graphs (user_id,graph_name,graph_desc,groupname) VALUES ($_SESSION[user_id],'$graph_name','$graph_desc','$groupname')");	
						}
						else 
						{
							$db->query("INSERT INTO custom_graphs (user_id,graph_name,graph_desc) VALUES ($_SESSION[user_id],'$graph_name','$graph_desc')");
						}
						
						$graph_id = $db->insert_id;
						if($graph_id > 0)
						{
							foreach($hosts as $host)
							{
								foreach($graphs as $graph)
								{
									$db->query("INSERT INTO custom_graph_items (custom_graph_id,node_id,plugin,graph) VALUES ($graph_id,$host,'$plugin','$graph')");
								}
							}
							
							// add new metric group too
							if(isset($_POST['newcustommetricgroup']))
							{
								$nmgroup = $db->real_escape_string($_POST['newcustommetricgroup']);
								$db->query("INSERT INTO custom_graph_groups (user_id,groupname) VALUES ($_SESSION[user_id],'$nmgroup')");
								$agroup = $db->insert_id;
							}
							else
							{
								// assign to existing group?
								if(is_numeric($_POST['assigntogroup']))
								{
									$agroup = $_POST['assigntogroup'];
								}
							}	
							
							if(is_numeric($agroup))
							{
								$db->query("INSERT INTO custom_graph_group_items (graph_group_id,custom_graph_id) VALUES ($agroup,$graph_id)");
							}
							
							display_ok("Graph Created","Graph Successfully stored");
							renderCustomGraph($graph_id);
						}
						else
						{
							display_error("Database Error","Cannot add custom graph: " . $db->errorInfo());	
						}					
					}
					

				}		
				?>
				</section>

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>		
		
		
		
		<script type="application/javascript">

			<?php if($_GET['action'] == "edit" && !$_POST) { ?>
						
					$(document).ready(function() {
						$( "#loadplugins" ).load( "ajax/customPluginLoad.php?node=<?php echo $fitem->node_id?>&selv=<?php echo $fitem->plugin?>&token=<?php echo getToken()?>" , function() {
							$( "#loadgraphs" ).load( "ajax/customGraphLoad.php?node="+$( "#basehost" ).val()+"&plugin="+$( "#plugin" ).val()+"&selv=<?php echo $fitem->graph?>&token=<?php echo getToken()?>" , function() {
								$( "#preview" ).html('<iframe name="frame" id="frame" width="100%" src="custGraphPreview.php?graphs='+$( "#graphs" ).val()+'&plugin='+$( "#plugin" ).val()+'&nodes='+$( "#otherhosts" ).val()+'&base='+$( "#basehost" ).val()+'" scrolling="no" height="500px" frameborder="0" ></iframe>');		
								$( "#togglebutton" ).show();
							})	
						})
					    		
					})	
								
			<?php } ?>

			$(document).ready(function() {
				$('#customtable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});
			})
			function loadnew(url,frame,field)
			{
				var date = $( "#"+field ).val();
				var dateObject = $('#'+field).datepicker("getDate");
				var dateString = $.datepicker.formatDate("dd.mm.yy", dateObject);
				window.frames[frame].location = url + "&day="+dateString;
			}
			function loadnewframe(url,frame)
			{
				window.frames[frame].location = url;
			}	
			
			// add magic
			$('#basehost').on('change', function() {
			  //alert( this.value ); // or $(this).val()
			  $( "#loadplugins" ).html("");
			  $( "#loadgraphs" ).html("");
			  $( "#preview" ).html("");
			  $( "#togglebutton" ).hide();
			  $( "#loadplugins" ).load( "ajax/customPluginLoad.php?node="+this.value+"&token=<?php echo getToken()?>" );
			});		
			
			$('#otherhosts').on('change', function() {
			  //alert( this.value ); // or $(this).val()
			  $( "#preview" ).html('<iframe name="frame" id="frame" width="100%" src="custGraphPreview.php?graphs='+$( "#graphs" ).val()+'&plugin='+$( "#plugin" ).val()+'&nodes='+$( "#otherhosts" ).val()+'&base='+$( "#basehost" ).val()+'" scrolling="no" height="500px" frameborder="0" ></iframe>');

			});	
			
			$('#assigntogroup').on('change', function() {
				
				if($( "#assigntogroup" ).val() == "newgroup")
				{
					$( "#newmetricgroup" ).html('<input class="form-control" id="newcustommetricgroup" name="newcustommetricgroup" placeholder="Enter a name for the new metric group" type="text">');
				}
			});
			// validate edit field
			var $addForm = $('#addForm').validate({
			// Rules for form validation
				rules : {
					graph_name : {
						required : true
					}
				},
		
				// Messages for form validation
				messages : {
					graph_name : {
						required : 'Please enter a name for this graph'
					}				
				},
		
				// Do not change code below
				//errorPlacement : function(error, element) {
				//	error.insertAfter(element.parent());
				//}
			});																					
		</script>
	</body>

</html>