<?php
chdir("..");
include("inc/startup.php");
if(!isLoggedIn())
{
	$_SESSION['REAL_REFERRER'] = $_SERVER['REQUEST_URI'];
	header("Location: login.php");
	die;
}

if(!isset($_GET['rcaId']))
{
	die;
}

$json = getRcaStatus($_GET['rcaId']);

if($json->status != "Analysis complete")
{
	$r->status = '[ Node: '.$json->nodes_processed.' of '.$json->nodes_affected.' Matches: '.$json->matchcount.' ] - ' . $json->status;
}
else
{
	$r->status = $json->status;
}

$w = ($json->nodes_processed * 100) / $json->nodes_affected;

$r->w = number_format($w,2);

echo json_encode($r);
?>
