<?php
chdir("..");
include("inc/startup.php");
if(!isLoggedIn())
{
	$_SESSION['REAL_REFERRER'] = $_SERVER['REQUEST_URI'];
	header("Location: login.php");
	die;
}
checkToken();

if($_SESSION['minify'] == true)
{
	$_SESSION['minify'] = false;
}	
else
{
	$_SESSION['minify'] = true;
}		

