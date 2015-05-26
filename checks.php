<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
// download check for exports
if($_GET['a'] == "export" && $_GET['download'] == "true")
{
	checkToken();
	$check = returnServiceCheck($_GET['cid']);
	
	if(!accessToCheck($check->id))
	{
		header('HTTP/1.0 401 Unauthorized');
		die;	
	}	
	if(is_file(EXPORTDIR.'/'.$check->id.".zip"))
	{
		$fileid = "export_check_".$_GET['cid'];
		header("Content-Type: application/zip");
	    header("Content-Disposition: attachment; filename=\"$fileid.zip\"");
	    readfile(EXPORTDIR.'/'.$check->id.".zip");	
		die;
	}
	else
	{
		header('HTTP/1.0 404 Not Found');
		die;			
	}
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = APP_NAME . " - Service Checks"; include("templates/core/head.tpl.php"); ?>
	<?php include("templates/core/scripts.tpl.php"); ?>
    <script src="js/highstock.js" type="text/javascript"></script>
    <script src="js/modules/exporting.js"></script>	
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
					<li>Home</li><li>Service Checks</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-medkit"></i> Service Checks</span></h1>
					</div>
				</div>

				
				<section id="widget-grid" class="">

				<?php
				
				if(!$_GET && !$_POST)
				{
					renderServiceCheckTable();
				}
				// details
				elseif($_GET['action'] == "view" && is_numeric($_GET['cid']))
				{
					$cid = $_GET['cid'];
					if(!accessToCheck($cid))
					{
						display_error("Check not found","You have no access to this service check or the check does not exists");
					}
					else
					{
						$check = returnServiceCheck($cid);
						//print_r($check);
						include("templates/checks/graph.tpl.php");	
						include("templates/checks/tables/CheckResultMongoTable.tpl.php");	
					}
				}
				// export
				elseif($_GET['action'] == "export" && is_numeric($_GET['cid']))
				{
					$cid = $_GET['cid'];
					if(!accessToCheck($cid))
					{
						display_error("Check not found","You have no access to this service check or the check does not exists");
					}
					else
					{
						$check = returnServiceCheck($cid);
						//print_r($check);
						$dbname = MONGO_DB_CHECKS;
						$dbm = $m->$dbname;
						$colname = $check->user_id."cid".$check->id;
						$colnamet = $check->user_id."traces".$check->id;
						$tpl->stats = $dbm->command(array('collStats' => $colname)); 
						$tpl->statst = $dbm->command(array('collStats' => $colnamet)); 
						$tpl->totalsize = $tpl->stats['size'] + $tpl->statst['size'];
						//echo '<pre>';
						//print_r($tpl->stats);
						//echo '</pre>';
						include("templates/checks/export.tpl.php");
						include("templates/checks/graph.tpl.php");	
						include("templates/checks/tables/CheckResultMongoTable.tpl.php");	
					}
				}				
				// delete
				elseif($_GET['action'] == "delete" && is_numeric($_GET['cid']))
				{
					
					$cid = $_GET['cid'];
					$check = returnServiceCheck($cid);
					$access = false;
					if($_SESSION['role'] == "admin")
					{
						$access = true;
					}
					
					if($check->user_id == $_SESSION['user_id'])
					{
						$access = true;
					}	
									
					if(!$access)
					{
						display_error("Check not found","You have no access to this service check or the check does not exists");
					}
					else
					{
						
						if(!$_GET['dowhatisay'])
						{
							include("templates/checks/delete.tpl.php");
							//print_r($check);
							include("templates/checks/graph.tpl.php");	
							include("templates/checks/tables/CheckResultMongoTable.tpl.php");	
						}
						else
						{
							checkToken();
							$dbname = MONGO_DB_CHECKS;
							$dbm = $m->$dbname;
							$colname = $check->user_id."cid".$check->id;
							$colnamet = $check->user_id."traces".$check->id;
							cvdDeleteCheck($check->id);
							$dbm->$colname->drop();
							$dbm->$colnamet->drop();
							$db->query("DELETE FROM service_checks WHERE id = '$check->id'");
							display_ok("SUCCESS", "<br />Service Check dequeued and data removed");							
						}
	
					}
				}				
				// add check
				elseif($_GET['action'] == "add")
				{
					$user = getUserObject($_SESSION['user_id']);
					if(!$_POST)
					{
						if(getCurrentCheckCount($user->id) < $user->max_checks)
						{
							include("templates/checks/forms/new.tpl.php");
						}
						else
						{
							display_error("To many checks","You need to upgrade your plan or buy check slots to create a new service check.");
						}						
					}	
					else
					{
						if(getCurrentCheckCount($user->id) < $user->max_checks)
						{
							$result = $db->query("SELECT * FROM check_types WHERE id = '$_POST[checktype]'");
							if($db->affected_rows < 1)
							{
								 display_error("Undefined Check", '<br />Check Type not defined');
								 include("templates/checks/forms/new.tpl.php");		
							}
							else 
							{
								$err = false;
								$PBJ = $_POST;
								// build json
								$tpl = $result->fetch_object();
								$_POST['command'] = $tpl->executable;
								$json = postCheckToJson($_POST);
								$user = getUserObject($_SESSION['user_id']);
							
								if(getCurrentCheckCount($user->id) < $user->max_checks)
								{
									//	
								}		
								else
								{
									display_error("Too many checks","You need to upgrade your plan or buy check slots to create a new service check.");
									$noform = true;
									$err = true;		
								}
							
						
								
								// check if choosen contacts belong to this user 
								$contact_err = false;
								if($_SESSION['role'] != "admin")
								{
									if(sizeof($PBJ['contacts']) > 0)
									{
										foreach($PBJ['contacts'] as $contact)
										{
											$db->query("SELECT id FROM contacts WHERE user_id = '$user->id' AND id = '$contact'");
											if($db->affected_rows < 1)
											{
												$contact_err = true;
											}
										}
										
										if($contact_err)
										{
											display_error("Contact Mismatch","You can only specify notify contacts that belong to your account");
											$err = true;
										}
									}
								}
									
								
								// save check if no error occured
								if(!$err)
								{
									$_POST = secureArray($_POST);
									$db->query("INSERT INTO service_checks (user_id,check_type,check_name,cinterval,json,accessgroup)
									VALUES 
									($user->id,
									$_POST[checktype],
									'$_POST[checkname]',
									$_POST[interval],
									'$json',
									'$_POST[accessgroup]'
									)
									");
									
									
									if($db->insert_id <= 1)
									{
										display_error("Backend Error"," Unable to save service check. Try again later");
									}
									else 
									{
										//$PBJ = secureArray($PBJ);
										$cid = $db->insert_id;
										
										// add tags
										$tags = explode(",",$PBJ['tags']);
										foreach($tags as $tag)
										{
											$tag = $db->real_escape_string($tag);
											if(trim($tag) != "")
											{
												$db->query("INSERT INTO service_check_tags (tagname,check_id,user_id) VALUES ('$tag','$cid','$_SESSION[user_id]')");
											}
										}

										foreach($PBJ['contacts'] as $contact)
										{
											$db->query("INSERT INTO notifications (contact_id,check_id,notifydown,notifyagain,notifyifup,notifyflap)
											VALUES (
											$contact,
											$cid,
											$_POST[notifydown],
											$_POST[notifyagain],
											$_POST[notifyifup],
											$_POST[notifyflap]
											)
											");
										}	
										cvdQueueCheck($cid);
										display_ok("Service Check Stored","<br />Service Check successfully stored. Redirecting to check in 5 seconds or <a href=\"/checks.php?action=view&cid=".$cid."\">click here</a>. Please note that it can take a few minutes for your firsts results to apear");
										echo '
										<meta http-equiv="refresh" content="5; url='.BASEURL.'/checks.php?action=view&cid='.$cid.'">
										';							
										$noform = true;
									}
								}
								
								if(!$noform)
								{
									include("templates/checks/forms/new.tpl.php");			
								}
							}							
						}
						else
						{
							display_error("To many checks","You need to upgrade your plan or buy check slots to create a new service check.");
						}	
					}
				}
				// edit a check
				elseif($_GET['action'] == "edit" && is_numeric($_GET['cid']))
				{
					$check = returnServiceCheck($_GET['cid']);	
					if($check->user_id != $_SESSION['user_id'] && $_SESSION['role'] != "admin")
					{
						display_error("Error","Service Check not found or uid missmatch");
					}
					else
					{
						if(!$_POST)
						{
							$tpl->editmode = true;
							$tpledit = json_decode($check->json);
							include("templates/checks/forms/edit.tpl.php");
						}
						else
						{
							$result = $db->query("SELECT * FROM check_types WHERE id = '$_POST[checktype]'");
							if($db->affected_rows < 1)
							{
								 display_error("Undefined Check", '<br />Check Type not defined');
								 include("templates/checks/forms/new.tpl.php");		
							}
							else 
							{
								$err = false;
								$PBJ = $_POST;
								// build json
								$tpl = $result->fetch_object();
								$_POST['command'] = $tpl->executable;
								$json = postCheckToJson($_POST);
								$user = getUserObject($_SESSION['user_id']);
							
								// check if choosen contacts belong to this user 
								$contact_err = false;
								if($_SESSION['role'] != "admin")
								{
									if(sizeof($PBJ['contacts']) > 0)
									{
										foreach($PBJ['contacts'] as $contact)
										{
											$db->query("SELECT id FROM contacts WHERE user_id = '$user->id' AND id = '$contact'");
											if($db->affected_rows < 1)
											{
												$contact_err = true;
											}
										}
										
										if($contact_err)
										{
											display_error("Contact Mismatch","You can only specify notify contacts that belong to your account");
											$err = true;
										}
									}
								}

								// save check if no error occured
								if(!$err)
								{
									$cid = $check->id;
									$_POST = secureArray($_POST);
									$db->query("UPDATE service_checks 
									SET check_type = '$_POST[checktype]',
									check_name = '$_POST[checkname]',
									cinterval = '$_POST[interval]',
									json = '$json',
									accessgroup = '$_POST[accessgroup]'
									WHERE id = $cid;
									");
									
							
										
										// update tags
										$db->query("DELETE FROM service_check_tags WHERE check_id = '$check->id'");
										// add tags
										$tags = explode(",",$PBJ['tags']);
										foreach($tags as $tag)
										{
											$tag = $db->real_escape_string($tag);
											if(trim($tag) != "")
											{
												$db->query("INSERT INTO service_check_tags (tagname,check_id,user_id) VALUES ('$tag','$cid','$_SESSION[user_id]')");
											}
										}						
										
										//$PBJ = secureArray($PBJ);
										$db->query("DELETE FROM notifications WHERE check_id = $cid");
										foreach($PBJ['contacts'] as $contact)
										{
											$db->query("INSERT INTO notifications (contact_id,check_id,notifydown,notifyagain,notifyifup,notifyflap)
											VALUES (
											$contact,
											$cid,
											$_POST[notifydown],
											$_POST[notifyagain],
											$_POST[notifyifup],
											$_POST[notifyflap]
											)
											");
										}	
										cvdRefreshCheck($cid);
										display_ok("Service Check saved","Service Check successfully stored. <a href=\"checks.php?action=view&cid=".$cid."\">click here to go back to check detail page</a>.");
									
								}
								$check = returnServiceCheck($cid);
								$tpledit = json_decode($check->json);
								$tpledit->locations = explode(",",$check->locations);
								include("templates/checks/forms/edit.tpl.php");
							}
						} // end if, else POST EDIT
					}
				}
				
				?>
				
				</section>


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		

		<script type="application/javascript">
			$(document).ready(function() {
				var aTable = $('#checkTable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});		
			
												
						// validate edit field
			var $editForm = $('#newCheckForm').validate({
			// Rules for form validation
				rules : {
					checkname : {
						required : true
					},				
				},
		
				// Messages for form validation
				messages : {
					checkname : {
						required : 'Please enter a valid name for this check'
					}			
				},
		
				// Do not change code below
				//errorPlacement : function(error, element) {
				//	error.insertAfter(element.parent());
				//}
			});	
			
			<?php 
			if($_GET['action'] == "add" || $_GET['action'] == "edit")
			{
			?>

			<?php if($_GET['action'] == "edit") { ?>
				$( "#subform" ).load( "templates/checks/checktypes/<?php echo $check->check_type?>.tpl.php?cid=<?php echo $check->id?>" );
			<?php } else { ?>
				$( "#subform" ).load( "templates/checks/checktypes/1.tpl.php" );
			<?php } ?>
			$( "#checktype" ).click(function() {
				$( "#subform" ).load( "templates/checks/checktypes/"+this.value+".tpl.php" );	
				$( "#testOutput" ).html("");
			})

			
			$("#tags").select2({tags:[<?php echo getMyTagList()?>]});		
	
			<?php 
			} 
			?>			
			
			})
			
			
			
			function testCheck()
			{
				console.log("testCheck trigger");
				if ( $("#newCheckForm").validationEngine('validate') ) 
				{
					$( "#testOutput" ).html('<img src="img/loading.gif" align="center">');
					var options = { 
			        target:        '#testOutput',   // target element(s) to be updated with server response 
			        url: 'ajax/testCheck.php<?php if($_GET['action'] == "edit") { echo '?checktype='.$check->check_type;}?>',
			        type: 'post'
			    	}; 
			    	$("#tcbtn").attr("disabled", true);
				    $("#newCheckForm").ajaxSubmit(options)
				}
				else 
				{
					//alert("Please fill in all required fields before test submission");
				}
				
			}		
						
		</script>	

	</body>

</html>