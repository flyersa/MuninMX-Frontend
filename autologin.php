<?php
include("inc/startup.php");
if(!$_GET['key'])
{
	die;
}

if(!$_GET['username'])
{
	die;
}


$_COOKIE = array();	
setcookie("scs_key","");
setcookie("scs_user","");
setcookie("lastseen","");
$_SESSION['login'] = false;
$_SESSION = array();

	
session_destroy();
session_start();
$r = loginAs($_GET['username'],$_GET['key']);

if($r != false)
{
	header("Location: index.php");
}
else
{
	echo "access denied";
}


