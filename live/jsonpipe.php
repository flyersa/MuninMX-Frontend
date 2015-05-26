<?php
chdir("..");
include("inc/startup.php");
session_write_close();
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
if(!accessToNode($_GET['node']))
{
        display_error("Access denied for this host");
        die;
}
if(!$_GET['plugin'])
{
	die;
}
$node = getNode($_GET['node']);
echo file_get_contents("http://".MLD_HOST.":".MLD_PORT."/node/".$node->id."/fetch/".$_GET['plugin']);
?>