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
	<?php $tpl->title = APP_NAME . " - User Management"; include("templates/core/head.tpl.php"); ?>
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
					<li><a href="index.php">Home</a></li><li>User Management</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<?php if(!$_GET['username']) { ?>
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-user"></i> User Management</h1>
						<?php } else { ?>
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-user"></i> User Management <span> > <?php echo htmlspecialchars($_GET['username'])?></span></h1>	
						<?php } ?>
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
									<h2>User Management</h2>
								</header>
								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body <?php if(!$_GET) { echo 'no-';}?>padding">
										<div class="widget-body-toolbar">
											<div class="btn-group">
												<?php if(!$_GET['username'] && !$_GET['delete'] ){ ?>
													<a href="users.php?action=add" class="btn btn-primary">Add User</a>		
												<?php } elseif(!$_GET['username'] && !$_GET['dowhatisay']) { ?>
													<a href="users.php?username=<?php echo $_GET['username']?>&delete=true" class="btn btn-danger">Delete this User</a>			
												<?php } ?>
											</div>
										</div>
										<div class="widget-body-toolbar">
										</div>
											<?php
												if(!$_GET['username'] && !$_GET['action'])
												{
													renderUserTable();
												}
												else if($_GET['username']) {
													$username = $db->real_escape_string($_GET['username']);
													$result = $db->query("SELECT * FROM users WHERE username = '$username'");
													if($db->affected_rows < 1)
													{
														display_error("User not found","This user does not exist");
													}
													else
													{
														$tpl = $result->fetch_object();
														$uid = $tpl->id;
														if(!$_POST)
 														{
 															if(!$_GET['delete'])
															{
																include("templates/user/edit.tpl.php");	
															}
															else
															{
																if(!$_GET['dowhatisay'])
																{
																	include("templates/user/delete.tpl.php");	
																	include("templates/user/edit.tpl.php");	
																}
																else
																{
																	if($uid == 1)
																	{
																		display_error("Error","Initial Admin user cannot be deleted");	
																	}
																	else
																	{
																		checkToken();
																		$db->query("DELETE FROM users WHERE id = '$uid'");
																		display_ok("User Removed","This user has been removed");
																	}
																}
															}
															
														}
														else
														{
															// [username] => ghei [email] => demo@example.com [password] => [autologinkey] => z5RLAGy27P6B5e4352u4299T2l2dbU5YfnuIX394t5M7F8T4195F [userrole] => user [accessgroup] => Array ( [0] => ALDI [1] => ALDI-18 [2] => GHEI-n15 ) )
															//print_r($_POST);	
															foreach($_POST['accessgroup'] as $group)
															{
																$accessgroup.= $db->real_escape_string($group) . ",";
															}	
															$accessgroup = substr($accessgroup,0,-1);
															if(trim($_POST['password']) != "")
															{
																$pass = sha1($_POST['password']);
																$upass = ",password = '$pass'";
															}
															$_POST = secureArray($_POST);
															$_POST['username'] = $db->real_escape_string($_GET['username']);
															$db->query("UPDATE users SET username = '$_POST[username]'$upass,email = '$_POST[email]',retention='$_POST[retention]',userrole='$_POST[userrole]',autologinkey='$_POST[autologinkey]',accessgroup='$accessgroup',apikey='$_POST[apikey]',max_customs='$_POST[maxcustoms]',max_nodes='$_POST[max_nodes]',sms_tickets='$_POST[sms_tickets]',tts_tickets='$_POST[tts_tickets]',eventsallowed='$_POST[eventsallowed]',max_checks='$_POST[max_checks]' WHERE id = $uid");
															if($db->affected_rows > 0)
															{
																display_ok("User Data Updated","Database Update Successfull");
															}
															else
															{
																display_error("Database Error","Unable to update database. please try again later");
																//echo "UPDATE users SET username = '$_POST[username]'$upass,email = '$_POST[email]',userrole='$_POST[userrole]',autologinkey='$_POST[autologinkey]',accessgroup='$accessgroup',apikey='$_POST[apikey]',max_customs='$_POST[maxcustoms]' WHERE id = $uid";
															}
														}
													}
												}
												else if($_GET['action'] == "add")
												{
													if(!$_POST)
													{
														include("templates/user/add.tpl.php");
													}	
													else
													{
														// [username] => aldi [email] => aldi@aldi.com [password] => changeme [autologinkey] => [userrole] => user [accessgroup] => Array ( [0] => ALDI [1] => ALDI-12 [2] => ALDI-14 [3] => ALDI-18 [4] => ALDI-8 [5] => ALDI-NIT ) )
														foreach($_POST['accessgroup'] as $group)
														{
															$accessgroup.= $db->real_escape_string($group) . ",";
														}	
														$accessgroup = substr($accessgroup,0,-1);	
														$pass = sha1($_POST['password']);
														$_POST = secureArray($_POST);
																											
														$db->query("SELECT * FROM users WHERE username = '$_POST[username]'");
														if($db->affected_rows > 0)
														{
															display_error("User already exist","There is already a user in the database with this username");
															include("templates/user/add.tpl.php");
														}
														else
														{
															$db->query("INSERT INTO users (username,password,autologinkey,userrole,accessgroup,email,apikey,max_customs,max_nodes,sms_tickets,tts_tickets,retention,eventsallowed,max_checks) VALUES ('$_POST[username]','$pass','$_POST[autologinkey]','$_POST[userrole]','$accessgroup','$_POST[email]','$_POST[apikey]','$_POST[maxcustoms]','$_POST[max_nodes]','$_POST[sms_tickets]','$_POST[tts_tickets]','$_POST[retention]','$_POST[eventsallowed]','$_POST[max_checks]')");
															if($db->affected_rows > 0)
															{
																display_ok("User added","User successfully added in database");
															}	
															else
															{
																display_error("Database Error","cannot add user. try again later");
																include("templates/user/add.tpl.php");
															}
														}
														//print_r($_POST);
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
			$(document).ready(function() {
				var oTable =  $('#usertable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25
				});
				oTable.fnSort( [ [4,'desc'] ] );
			})
					
			
			// validate edit field
			var $editForm = $('#editForm').validate({
			// Rules for form validation
				rules : {
					username : {
						required : true
					},				
					email : {
						required : true
					},
					maxcustoms : {
						required : true,
						digits : true
					},
					max_nodes : {
						digits : true
					},
					max_checks : {
						digits : true
					},
					sms_tickets : {
						digits : true
					},	
					tts_tickets : {
						digits : true
					},							
				},
		
				// Messages for form validation
				messages : {
					username : {
						required : 'Please enter a username'
					},
					email : {
						required : 'Please enter a email'
					}				
				},
		
				// Do not change code below
				//errorPlacement : function(error, element) {
				//	error.insertAfter(element.parent());
				//}
			});
			
			// validate edit field
			var $addForm = $('#addForm').validate({
			// Rules for form validation
				rules : {
					username : {
						required : true
					},		
					password : {
						required : true
					},							
					email : {
						required : true
					},
					maxcustoms : {
						required : true,
						digits : true
					},
					max_nodes : {
						digits : true
					},
					max_checks : {
						digits : true
					},					
					sms_tickets : {
						digits : true
					},	
					tts_tickets : {
						digits : true
					},														
					autologinkey : {
						required : true
					}					
				},
		
				// Messages for form validation
				messages : {
					username : {
						required : 'Please enter a username'
					},
					email : {
						required : 'Please enter a email'
					},
					password : {
						required : 'Please enter a password'
					},
					autologinkey : {
						required : 'Please set a autologinkey, enter unset as value if you want to disable autologin'
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