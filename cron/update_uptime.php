<?php
if(php_sapi_name() != "cli")
{
	echo "no cli..\n";
	die;
}
chdir("..");
include("inc/startup.php");

$result = $db->query("SELECT * FROM service_checks");
while($tpl = $result->fetch_object())
{
	$luptime = 0;
	echo date(DATE_RFC822) . " [$tpl->check_name] C - Updating 30 Day Uptime - ";
	$luptime = getCheckUptimeOld($tpl->id,$tpl->user_id,30);
	if($luptime == "100.00 %")
	{
		$luptime = "100 %";
	}
	echo $luptime . "\n";
	$db->query("UPDATE service_checks SET luptime = '$luptime' WHERE id = '$tpl->id'");
}
