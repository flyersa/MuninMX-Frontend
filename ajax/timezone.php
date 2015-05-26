<?php
$noredirect = true;
chdir("..");
include("inc/startup.php");
if($_GET['timezone'])
{
	$_SESSION['timezone'] = $_GET['timezone'];
}
