<?php

// todo dummy#

function getRcaJob($rcaId)
{
	global $db;
	$result = $db->query("SELECT * FROM rca WHERE rcaId = '$rcaId'");
	if($db->affected_rows < 1)
	{
		return false;
	}
	return $result->fetch_object();
}


function getRcaStatus($rcaId)
{
	$rca = getRcaJob($rcaId);
	if($_SESSION['role'] != "admin")
	{
		if($_SESSION['user_id'] != $rca->user_id)
		{
			return false;
		}
	}
	// http://muninmx:49000/rcastatus/d2b561c0ea83257446632f0fe3e6d0bda492330d
	$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/rcastatus/$rcaId");	
	
	if($ret == false)
	{
		return false;
	}
	return json_decode($ret);
}


function renderRcaPastTable()
{
	global $db;
	include("templates/rca/tables/pastTable.head.tpl.php");
	if($_SESSION['role'] != "admin")
	{
		$result = $db->query("SELECT *,UNIX_TIMESTAMP(last_change) as last_change_ts  FROM rca WHERE user_id = '$_SESSION[user_id]' AND is_finished = 1 ORDER BY id DESC");
	}
	else
	{
		$result = $db->query("SELECT *,UNIX_TIMESTAMP(last_change) as last_change_ts  FROM rca WHERE is_finished = 1 ORDER BY id DESC");	
	}
	while($tpl = $result->fetch_object())
	{
		$json = json_decode($tpl->output);
		if($json != false)
		{
			if($json->matchcount > 0)
			{
				$tpl->results = $json->matchcount;
				include("templates/rca/tables/pastTable.item.tpl.php");
			}
		}
	}
	include("templates/rca/tables/pastTable.end.tpl.php");
}
?>