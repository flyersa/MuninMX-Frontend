<?php
if($_GET['logout'])
{
	$_COOKIE = array();	
	setcookie("scs_key","");
	setcookie("scs_user","");
	setcookie("lastseen","");
}
include("inc/startup.php");
if($_GET['logout'])
{
	$_SESSION['login'] = false;
	$_SESSION = array();

	
	session_destroy();
	header("Location: login.php");
}
if($_POST)
{
	//print_r($_POST);
	
	$ltpl = logIn($_POST['username'],$_POST['password']);
	if($ltpl != false)
	{
		if($_POST['remember'] == "on")
		{
			// TODO: add scs key
			$ctime = time() + 604800;
			setcookie("scs_key",$ltpl->password,$ctime);
			setcookie("scs_user",$ltpl->id,$ctime);
		}
		if(isset($_SESSION['REAL_REFERRER']))
		{
			header("Location: $_SESSION[REAL_REFERRER]");
		}
		else
		{
			header("Location: index.php");
		}
	}
	else
	{
		$error = true;
	}
}
else 
{
	if($_SESSION['login'] == true)
	{
		if(isset($_SESSION['REAL_REFERRER']))
		{
			header("Location: $_SESSION[REAL_REFERRER]");
		}
		else
		{
			header("Location: index.php");
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> MuninMX </title>
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Use the correct meta names below for your web application
			 Ref: http://davidbcalhoun.com/2010/viewport-metatag 
			 
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">-->
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">	
		<link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">

		<!-- MuninMX Styles : Please note (MuninMX-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/MuninMX-production.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/MuninMX-skins.css">	
		
		<!-- MuninMX RTL Support is under construction
			<link rel="stylesheet" type="text/css" media="screen" href="css/MuninMX-rtl.css"> -->
		
		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/demo.css">

		<!-- FAVICONS -->
		<?php if($_SERVER['SERVER_NAME'] == "muninmx.unbelievable-machine.net") { ?>
			<link rel="shortcut icon" href="img/favicon_um.ico" type="image/x-icon">
			<link rel="icon" href="img/favicon_um.ico" type="image/x-icon">
		<?php } else { ?>
			<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
			<link rel="icon" href="img/favicon.ico" type="image/x-icon">
		<?php } ?>

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

	</head>
	<body id="login" class="animated fadeInDown">
		<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
		<header id="header">
			<!--<span id="logo"></span>-->

			<div id="logo-group">
				<span id="logo"> <img src="img/muninmx-trans-small-single.png" alt="MuninMX"> </span>

				<!-- END AJAX-DROPDOWN -->
			</div>
		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
					<div>
						<?php if($error) { display_error("Error", " Login failed"); } ?>
						<div class="well no-padding">
							<form action="login.php" id="login-form" method="POST" class="smart-form client-form">
								<header>
									Sign In
								</header>

								<fieldset>
									
									<section>
										<label class="label">Username</label>
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="username">
											<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter username</b></label>
									</section>

									<section>
										<label class="label">Password</label>
										<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="password">
											<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
										<div class="note">
											<a href="#" id="resetpw">Forgot password?</a>
										</div>
									</section>

									<section>
										<label class="checkbox">
											<input type="checkbox" name="remember" checked="">
											<i></i>Stay signed in</label>
									</section>
								</fieldset>
								<footer>
									<button type="submit" class="btn btn-primary">
										Sign in
									</button>
								</footer>
							</form>
					</div>
<?php
if(isset($_GET['rkey']))
{
	$rkey = $db->real_escape_string($_GET['rkey']);
	$result = $db->query("SELECT * FROM users WHERE rkey = '$rkey'");
	if($db->affected_rows > 0)
	{
		$tpl = $result->fetch_object(); 
		$newpw = random_password(14);
		$pass = sha1($newpw);
		$db->query("UPDATE users SET password = '$pass', rkey=NULL WHERE id = '$tpl->id' ");
		$headers = 'From: '.MAIL_ADDR . "\r\n";
		$msg = "Hello,\n\nWe received a password recovery request for your MuninMX Account (".BASEURL.") account from IP: ".getUserIP()."\n\nYour New Password is: ".$newpw;
		mail ( $tpl->email, "MuninMX Password Recovery" , $msg ,$headers);		
		display_ok("Password Reset","Please check your Mailbox, you received a new password");
	}
	else
	{
		display_error("Invalid Recovery Key","The specified Recovery Key is not valid");
	}
	
}

?>
							
					</div>
				</div>
			</div>

		</div>

		<!--================================================== -->	

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script src="js/plugin/pace/pace.min.js"></script>

	    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	    <script src="js/libs/jquery-2.0.2.min.js"></script>
		<script> if (!window.jQuery) { document.write('<script src="js/libs/jquery-2.0.2.min.js"><\/script>');} </script>

	    <script src="js/jquery-ui.min.js"></script>
		<script> if (!window.jQuery.ui) { document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
		<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

		<!-- BOOTSTRAP JS -->		
		<script src="js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="js/smartwidgets/jarvis.widget.min.js"></script>
		
		<!-- EASY PIE CHARTS -->
		<script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
		
		<!-- SPARKLINES -->
		<script src="js/plugin/sparkline/jquery.sparkline.min.js"></script>
		
		<!-- JQUERY VALIDATE -->
		<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
		
		<!-- JQUERY MASKED INPUT -->
		<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
		
		<!-- JQUERY SELECT2 INPUT -->
		<script src="js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
		
		<!-- browser msie issue fix -->
		<script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
		
		<!-- FastClick: For mobile devices -->
		<script src="js/plugin/fastclick/fastclick.js"></script>
		
		<!--[if IE 7]>
			
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
			
		<![endif]-->

		<!-- MAIN APP JS FILE -->
		<script src="js/app.js"></script>

		<script type="text/javascript">
			runAllForms();

			$(function() {
				// Validation
				$("#login-form").validate({
					// Rules for form validation
					rules : {
						username : {
							required : true
						},
						password : {
							required : true,
							minlength : 3
						}
					},

					// Messages for form validation
					messages : {
						username : {
							required : 'Please enter a valid username',
						},
						password : {
							required : 'Please enter your password'
						}
					},

					// Do not change code below
					errorPlacement : function(error, element) {
						error.insertAfter(element.parent());
					}
				});
			});
			
			// reset password
			$("#resetpw").click(function(e) {
		
				$.SmartMessageBox({
					title : "Reset Password",
					content : "Please enter your e-mail address",
					buttons : "[Cancel][Reset Password]",
					input : "text",
					placeholder : "Please enter your e-mail address"
				}, function(ButtonPress, Value) {
					if (ButtonPress == "Cancel") {
						return 0;
					}
		
					Value1 = Value.toUpperCase();
					ValueOriginal = Value;
					//alert("email: " + ValueOriginal);
					$.post( "ajax/resetpw.php", { mail: ValueOriginal } );
					$.bigBox({
					title : "Reset Link Sent",
					content : "Check Your Mailbox for your Password reset link. Dont forget to check your Spam folder",
					color : "#739E73",
					//timeout: 8000,
					icon : "fa fa-check"
					}, function() {
						closedthis();
					});

				});
		
				e.preventDefault();
			});			

		</script>

	</body>
</html>
