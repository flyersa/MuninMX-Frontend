<?php

function renderCustomJobs($nid,$plugin=false)
{
	global $db;
	if($_SESSION['role'] != "admin")
	{
		$and = " AND user_id = '$_SESSION[user_id]'";
	}
	if($plugin != false)
	{
		$result = $db->query("SELECT * FROM `plugins_custom_interval` WHERE node_id = '$nid' AND pluginname = '$plugin' $and");
	}
	else
	{
		$result = $db->query("SELECT * FROM `plugins_custom_interval` WHERE node_id = '$nid' $and");	
	}
	if($db->affected_rows > 0)
	{
		include("templates/cinterval/tables/joblist.header.tpl.php");
		include("templates/cinterval/tables/table.joblist.head.tpl.php");
		while($tpl = $result->fetch_object())
		{
			//print_r($tpl);
			
			$tpl->timerange = "Repeating Forever";
			if($tpl->to_time != 0)
			{
				$tpl->timerange = getFormatedLocalTime($tpl->from_time) . " - " . getFormatedLocalTime($tpl->to_time);
			}
			if($tpl->crontab == "false")
			{
				$tpl->crontab = "";
			}
			include("templates/cinterval/tables/table.joblist.item.tpl.php");
		}
		include("templates/core/tableEnd.tpl.php");
		include("templates/cinterval/tables/joblist.body.tpl.php");
	}
}

function getUserIdForCustomJob($cid)
{
	global $db;
	$result = $db->query("SELECT plugins_custom_interval.id,nodes.user_id FROM `plugins_custom_interval` LEFT JOIN nodes ON node_id = nodes.id WHERE plugins_custom_interval.id = $cid");
	if($db->affected_rows < 1)
	{
		return 1;
	}
	else
	{
		$tpl = $result->fetch_object();
		return $tpl->user_id;	
	}
}

function getCustomIntervalCountForPlugin($pid)
{
	global $db;
	if($_SESSION['role'] != "admin")
	{
		$and = " AND user_id = '$_SESSION[user_id]'";
	}	
	$db->query("SELECT * FROM plugins_custom_interval WHERE plugin_id = '$pid' $and");
	return $db->affected_rows;
}

function getCustomIntervalCountForNode($nid)
{
	global $db;
	if($_SESSION['role'] != "admin")
	{
		$and = " AND user_id = '$_SESSION[user_id]'";
	}
	$db->query("SELECT * FROM plugins_custom_interval WHERE node_id = $nid $and");
	return $db->affected_rows;
}
?>