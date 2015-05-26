		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> <?php echo $tpl->title?> </title>


		<!-- Use the correct meta names below for your web application
			 Ref: http://davidbcalhoun.com/2010/viewport-metatag 
			 
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">-->
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/validation.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/icheck.css">
		<!-- MuninMX Styles : Please note (MuninMX-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/MuninMX-production.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/MuninMX-skins.css">

		<!-- MuninMX RTL Support is under construction
		<link rel="stylesheet" type="text/css" media="screen" href="css/MuninMX-rtl.css"> -->

		<!-- We recommend you use "your_style.css" to override MuninMX
		     specific styles this will also ensure you retrain your customization with each MuninMX update.
		<link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

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
		<script src="js/lazyload.min.js"></script>