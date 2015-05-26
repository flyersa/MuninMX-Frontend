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
	<?php $tpl->title = APP_NAME . " - Alerts"; include("templates/core/head.tpl.php"); ?>
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
					<li><a href="index.php">Home</a></li><li>Alerts</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-bell"></i> Alert Management</span></h1>
					</div>
				</div>

				

				<?php 
				if(!$_GET)
				{
					renderAlertTable();	
				}
				// notification logs
				elseif($_GET['action'] == "logmetrics")
				{
					// SELECT *,alerts.node_id,contacts.contact_name FROM `notification_log` LEFT JOIN alerts ON notification_log.cid = alerts.id RIGHT JOIN contacts ON notification_log.contact_id = contacts.id ORDER BY notification_log.created_at DESC  
					
					renderNotLogTable("metrics");
				}
				// contacts
				elseif($_GET['action'] == "contacts")
				{
					if(!$_GET['sub'])
					{
						renderContactTable();
					}
					// add contact
					elseif($_GET['sub'] == "add")
					{
						if(!$_POST)
						{
							include("templates/contacts/forms/add.tpl.php");
						}
						else
						{
							addContactViaPost();
						}	
					}
					// edit contact
					elseif($_GET['sub'] == "edit" && is_numeric($_GET['cid']))
					{
						$tpl = returnContact($_GET['cid']);
						if(!$_POST)
						{
							if($tpl == false)
							{
								header("Location: /404.php");
								die;
							}
							if($_SESSION['role'] != "admin")
							{	
								if($tpl->user_id != $_SESSION['user_id'])
								{
									header("Location: /403.php");
									die;
								}
							}
							include("templates/contacts/forms/edit.tpl.php");	
						}	
						else
						{
							if($_SESSION['role'] != "admin")
							{	
								if($tpl->user_id != $_SESSION['user_id'])
								{
									header("Location: /403.php");
									die;
								}
							}							
							editContactViaPost();		
						}
					}
					// view contact
					elseif($_GET['sub'] == "view" && is_numeric($_GET['cid']))
					{
						$tpl = returnContact($_GET['cid']);
						if($tpl == false)
						{
							header("Location: /404.php");
							die;
						}	
						if($_SESSION['role'] != "admin")
						{
							if($tpl->user_id != $_SESSION['user_id'])
							{
								header("Location: /403.php");
								die;
							}	
						}
						include("templates/contacts/view.tpl.php");	
											
					}
					// delete contact
					elseif($_GET['sub'] == "delete" && is_numeric($_GET['cid']))
					{
						$tpl = returnContact($_GET['cid']);
						if($tpl == false)
						{
							header("Location: /404.php");
							die;
						}
						if($_SESSION['role'] != "admin")
						{							
							if($tpl->user_id != $_SESSION['user_id'])
							{
								header("Location: /403.php");
								die;
							}	
						}
						// delete or just view
						if($_GET['deletefinal'])
						{
							checkToken();
							$db->query("DELETE FROM contacts WHERE id = $tpl->id");
							if($db->affected_rows < 1 )
							{
								display_error("Database Error","Cannot delete contact. Try again later");
							}
							else
							{
								display_ok("Contact removed","The contact was purged");
								renderContactTable();
							}
						}
						else
						{				
							include("templates/contacts/delete.tpl.php");
							include("templates/contacts/view.tpl.php");				
						}
			
									
					}					
				} // end contacts
				elseif($_GET['action'] == "alerts")
				{
					// add alert
					if($_GET['sub'] == "create" && is_numeric($_GET['node']) && is_numeric($_GET['plugin']))
					{
						if(!accessToNode($_GET['node']))
						{
							header("Location: 403.php");
							die;
						}
						
						$plugin = getPlugin($_GET['node'], $_GET['plugin']);
						$node = getNode($_GET['node']);
						if(!$plugin)
						{
							display_error("Plugin not found","invalid plugin specified");	
						}
						else
						{
						
							if(!$_POST)
							{
								//echo "hello";
								if(getContactCountForUser($_SESSION['user_id'],true) < 1)
								{
									display_warning("No Contacts Defined", "You need to add a contact first before you can add notifications. ".'<a href="alerts.php?action=contacts&sub=add">Click here to add a contact</a>');
								}	
								else 
								{
									renderAlertTable($node->id,$plugin->pluginname);
									include("templates/alerts/forms/add.tpl.php");
								    renderSingleGraph($_GET['node'], $_GET['plugin']);
								}
							}
							else
							{
								//print_r($_POST);
								$err = false;
								if(!is_numeric($_POST['raise_value']))
								{
									display_error("Raise Value Error","Raise Value requires to be numeric");
									$err = true;
								}
								
								if(sizeof($_POST['contacts']) < 1)
								{
									display_error("Contact Required","You need to add a contact");
									$err = true;									
								}
								else
								{
									$cvalid = true;
									foreach($_POST['contacts'] as $contact)
									{
										$c = getContact($contact);
										if($_SESSION['role'] != "admin")
										{
											if($c->user_id != $_SESSION['user_id'])
											{
												$cvalid = false;
											}
										}
									}
								}
								
								
								if($cvalid == false)
								{
									display_error("Contact Security Violation","The contact you added does not belong to your account");
									$err = true;
								}
								
								// display error or not.
								if($err)
								{
									include("templates/alerts/forms/add.tpl.php");
									renderSingleGraph($_GET['node'], $_GET['plugin']);
									
								}
								else
								{
									// wohooo add this shit
									$_POST['graph'] = $db->real_escape_string($_POST['graph']);
									$_POST['condition'] = $db->real_escape_string($_POST['condition']);
									
									// Array ( [_csrf_protect_token] => f8b95e8d8059a9280b4e1eb0bda1de21f47d5d4a [graph] => dev8_0_rtime [raise_value] => 200 [condition] => lt [num_samples] => 2 [alert_time] => 5 [contacts] => Array ( [0] => 3 ) )
									$db->query("INSERT INTO alerts (user_id,node_id,pluginname,graphname,raise_value,`condition`,alert_limit,num_samples) VALUES (
									'$_SESSION[user_id]',
									'$node->id',
									'$plugin->pluginname',
									'$_POST[graph]',
									'$_POST[raise_value]',
									'$_POST[condition]',
									'$_POST[alert_time]',
									'$_POST[num_samples]')");
									
									if($db->affected_rows > 0)
									{
										$aid = $db->insert_id;
										reset($_POST['contacts']);
										foreach($_POST['contacts'] as $contact)
										{
											$db->query("INSERT INTO alert_contacts (alert_id,contact_id) VALUES ('$aid','$contact')");	
										}
										$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/addalert/$aid");	
										if(trim($ret) == "true")
										{
											display_ok("Alert created","Alert stored and added to running configuration" . ' <a href="view.php?nid='.$node->id.'#'.$plugin->pluginname.'">Back to Node</a>');
										}
										else
										{
											display_error("Communication Error","Alert stored but unable to add to running config. Delete and try again or ignore (will fix itself)");	
										}	
										renderAlertTable($node->id,$plugin->pluginname);
									}
									else
									{
										display_error("Save Error","Unable to save alert. try again later");
										include("templates/alerts/forms/add.tpl.php");
								   		renderSingleGraph($_GET['node'], $_GET['plugin']);
									}	
								}
							}
						}
							
					}
					elseif($_GET['sub'] == "edit" && is_numeric($_GET['aid']))
					{
						$a = getAlert($_GET['aid']);
						$node = getNode($a->node_id);
						$tpl = $a;
						$aid = $a->id;
						if($a == false)
						{
							display_error("Invalid Alert ID","Alert not found");
							renderAlertTable();
						}
						else
						{
							if($_SESSION['role'] != "admin")
							{
								if($a->user_id != $_SESSION['user_id'])
								{
									header("Location: 403.php");
									die;
								}
							}
							
							if(!$_POST)
							{
								include("templates/alerts/forms/edit.tpl.php");	
								renderSingleGraphByName($a->node_id, $a->pluginname);
							}
							else
							{
								//print_r($_POST);
								$err = false;
								if(!is_numeric($_POST['raise_value']))
								{
									display_error("Raise Value Error","Raise Value requires to be numeric");
									$err = true;
								}
								
								$cvalid = true;
								if(sizeof($_POST['contacts']) < 1)
								{
									display_error("Contact Required","You need to add a contact");
									$err = true;									
								}
								else
								{
									$cvalid = true;
									foreach($_POST['contacts'] as $contact)
									{
										$c = getContact($contact);
										if($_SESSION['role'] != "admin")
										{
											if($c->user_id != $_SESSION['user_id'])
											{
												$cvalid = false;
											}
										}
									}
								}
								
								
								if($cvalid == false)
								{
									display_error("Contact Security Violation","The contact you added does not belong to your account");
									$err = true;
								}
								
								// display error or not.
								if($err)
								{
									include("templates/alerts/forms/edit.tpl.php");
									renderSingleGraphByName($a->node_id, $a->pluginname);						
								}
								else
								{
									// wohooo add this shit
									$_POST['graph'] = $db->real_escape_string($_POST['graph']);
									$_POST['condition'] = $db->real_escape_string($_POST['condition']);
									
									// Array ( [_csrf_protect_token] => f8b95e8d8059a9280b4e1eb0bda1de21f47d5d4a [graph] => dev8_0_rtime [raise_value] => 200 [condition] => lt [num_samples] => 2 [alert_time] => 5 [contacts] => Array ( [0] => 3 ) )
									/*$db->query("INSERT INTO alerts (user_id,node_id,pluginname,graphname,raise_value,`condition`,alert_limit,num_samples) VALUES (
									'$_SESSION[user_id]',
									'$node->id',
									'$plugin->pluginname',
									'$_POST[graph]',
									'$_POST[raise_value]',
									'$_POST[condition]',
									'$_POST[alert_time]',
									'$_POST[num_samples]')");
									*/
									
									$db->query("UPDATE alerts SET graphname = '$_POST[graph]', raise_value = '$_POST[raise_value]', `condition`='$_POST[condition]', alert_limit = '$_POST[alert_time]',num_samples = '$_POST[num_samples]' WHERE id = '$a->id'");
									//echo "UPDATE alerts SET graphname = '$_POST[graph]', raise_value = '$_POST[raise_value]', condition='$_POST[condition]', alert_limit = '$_POST[alert_time]',num_samples = '$_POST[num_samples]' WHERE id = '$a->id'";
									
									if($db->affected_rows > 0)
									{
										$reschedule = true;	
									}
									else
									{
										$reschedule = false;
									}						
									
									// update contacts
									$db->query("DELETE FROM alert_contacts WHERE alert_id = $a->id");
									reset($_POST['contacts']);
									foreach($_POST['contacts'] as $contact)
									{
										$db->query("INSERT INTO alert_contacts (alert_id,contact_id) VALUES ('$aid','$contact')");	
									}
									
									$a = getAlert($a->id);
									if($reschedule)
									{
										$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletealert/$aid");	
										sleep(1);
										$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/addalert/$aid");
										if(trim($ret) == "true")
										{
											display_ok("Alert Updated","Alert updated and changes applied to running configuration");
										}
										else
										{
											display_error("Communication Error","Alert changed but unable to apply changes to running config. Try again later");	
										}											
										renderAlertTable($node->id,$a->pluginname);	
									}
									else
									{
										display_ok("Alert Updated","Alert updated and changes saved");
										renderAlertTable($node->id,$a->pluginname);	
									}												
								}
							}
						}
					}
					elseif($_GET['sub'] == "delete" && is_numeric($_GET['aid']))
					{
						$a = getAlert($_GET['aid']);
						if($a == false)
						{
							display_error("Invalid Alert ID","Alert not found");
							renderAlertTable();
						}
						else
						{
							if($_SESSION['role'] != "admin")
							{
								if($a->user_id != $_SESSION['user_id'])
								{
									header("Location: 403.php");
									die;
								}
							}
							if(!$_GET['deletefinal'])
							{
								include("templates/alerts/delete.tpl.php");
								renderAlertTableSingle($_GET['aid']);
							}
							else
							{
								checkToken();
								$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletealert/$a->id");	
								if(trim($ret) == "true")
								{
									$db->query("DELETE FROM alerts WHERE id = $a->id");
									display_ok("Alert removed","Alert removed and purged from running configuration");
									renderAlertTable();
								}
								else
								{
									display_error("Communication Error","Alert cannot be removed from running config. Please try again later");	
									renderAlertTableSingle($_GET['aid']);
								}	
							}
							
						}
						//renderAlertTableSingle($_GET['aid']);
					}					
				}
				?>



			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
		<script>
			// make contacts a datatable
			$(document).ready(function() {
				var oTable = $('#contacttable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});
				var aTable = $('#alertTable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});		
				
				//
				var caTable = $('#checkAlertTable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});	
				
				var lTable = $('#logTable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});		
				lTable.fnSort( [ [6,'desc'] ] );				
										
			})
			
			// formular input validation for contacts
			var $contactForm = $('#contactform').validate({
			// Rules for form validation
				rules : {
					contact_name : {
						required : true,
						minlength : 3
					},
					contact_email : {
						required : true,
						email : true
					},
					contact_mobile_nr : {
						number : true
					},
					contact_callback : {
						url : true
					}
				},
		
				// Messages for form validation
				messages : {
					contact_name : {
						required : 'Please enter a descriptive name for this contact'
					},
					contact_email : {
						required : 'Please enter a valid e-mail address'
					}					
				},
		
				// Do not change code below
				//errorPlacement : function(error, element) {
				//	error.insertAfter(element.parent());
				//}
			});		

			// formular input validation for contacts
			var $contactForm = $('#addalertform').validate({
			// Rules for form validation
				rules : {
					raise_value : {
						required : true,
						number : true
					},
					contacts : {
						required : true,
					}		
				},
	
		
				// Do not change code below
				//errorPlacement : function(error, element) {
				//	error.insertAfter(element.parent());
				//}
			});		
			
			function sendPushOverTest()
			{
				var pkey = $("#pushover_key").val();
				if(pkey == "")
				{
					$.bigBox({
						title : "Error",
						content : "You need to set a PushOver Key",
						color : "#C46A69",
						//timeout: 6000,
						icon : "fa fa-warning shake animated",
						timeout : 3000
					});


				}
				else
				{
			            $.ajax({
			                type: 'get',
			                url: 'ajax/pushovertest.php?token=<?php echo getToken()?>&userKey='+pkey,
			                success: function(b) {
			                $.bigBox({
								title : "Message Send",
								content : "If you do not receive this message please check your PushOver key",
								color : "#739E73",
								//timeout: 6000,
								icon : "fa fa-thumbs-o-up shake animated",
								timeout : 3000
							});	
         	
			                }
			            });			
				}
				
				return false;
			}	
		</script>
		
	<script>
			// add magic
			$('#condition').on('change', function() {
				if($( "#condition" ).val() == "gtavg" || $( "#condition" ).val() == "ltavg")
				{
					$( "#hideme" ).show();
				}
				else
				{
					$( "#hideme" ).hide();	
				}
			});		
	</script>			
		
	</body>

</html>