<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
if($_SESSION['role'] == "admin")
{
	//die("This is a user/userext tool, please use the user management tab");
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
					<li>Home</li><li>Account</li><li>Settings</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-user"></i> Account<span>> Settings</span></h1>
					</div>
				</div>

				<!-- row -->
				<div class="row">
						<!-- NEW WIDGET START -->
						<article class="col-sm-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					
								<header>
									<span class="widget-icon"> <i class="fa fa-user"></i> </span>
									<h2>Your Account Details </h2>
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
										if($_POST)
										{
											// update user
											$user = getUserObject($_SESSION['user_id']);
											$curpw = sha1($_POST['password']);
											if($curpw != $user->password)
											{
												display_error("Password does not match","Your old password does not match.");
											}
											else
											{
												$newpw = sha1($_POST['passwordn']);
												if($_POST['email'] != $user->email)
												{
													if(trim($_POST['email']) != "")
													{
														$ande = ",email='$_POST[email]'";
													}
												}	
												$db->query("UPDATE users SET password = '$newpw'$ande WHERE id = '$user->id'");
												if($db->affected_rows > 0)
												{
													display_ok("Save ok","Account changes saved");
												}
											}
											
										}
										
										
										renderUserSettingTable();
										$tpl = getUserObject($_SESSION['user_id']);
										include("templates/user/forms/changepw.tpl.php");
										
										?>
								
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
		<script>
				// validate edit field
			var $editForm = $('#customform').validate({
			// Rules for form validation
				rules : {
					email : {
						email : true
					},
					password : {
						required : true
					},					
					passwordn : {
						required : true,
						minlength : 6
					},
					repeat : {
						required : true,
						 equalTo: "#passwordn"
					},					
				},
		
				// Messages for form validation
				messages : {
					email : {
						required : 'Please enter a valid email'
					},
					password : {
						required : 'your old password is required'
					},
					passwordn : {
						required : 'Please enter a valid password'
					},
					repeat : {
						required : 'Please repeat the new password'
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