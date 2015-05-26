<?php

function display_error($title,$text,$icon=false,$close=true)
{
	if(!$close)
	{
		$tpl->close = false;
	}
	else
	{
		$tpl->close = true;
	}
	if(!$icon)
	{
		$tpl->icon = "times";
	}
	$tpl->alerttype = "danger";
	
	$tpl->title = $title;
	$tpl->text = $text;
	include("templates/alerts/alert.tpl.php");
}

function display_warning($title,$text,$icon=false,$close=true)
{
	if(!$close)
	{
		$tpl->close = false;
	}
	else
	{
		$tpl->close = true;
	}
	if(!$icon)
	{
		$tpl->icon = "warning";
	}
	$tpl->alerttype = "warning";
	
	$tpl->title = $title;
	$tpl->text = $text;
	include("templates/alerts/alert.tpl.php");
}


function display_ok($title,$text,$icon=false,$close=true)
{
	if(!$close)
	{
		$tpl->close = false;
	}
	else
	{
		$tpl->close = true;
	}
	if(!$icon)
	{
		$tpl->icon = "check";
	}
	$tpl->alerttype = "success";
	
	$tpl->title = $title;
	$tpl->text = $text;
	include("templates/alerts/alert.tpl.php");
}

function display_info($title,$text,$icon=false,$close=true)
{
	if(!$close)
	{
		$tpl->close = false;
	}
	else
	{
		$tpl->close = true;
	}
	if(!$icon)
	{
		$tpl->icon = "info";
	}
	$tpl->alerttype = "info";
	
	$tpl->title = $title;
	$tpl->text = $text;
	include("templates/alerts/alert.tpl.php");
}

function getAlert($aid)
{
	global $db;
	$result = $db->query("SELECT * FROM alerts WHERE id = $aid");
	if($db->affected_rows < 1)
	{
		return false;
	}
	else
	{
		return $result->fetch_object();
	}
}


function renderNotLogTable($modus)
{
	global $db;
	if($modus == "metrics")
	{
		$tpl->title = "Notification Log - Metrics";
		$sql = "SELECT *,alerts.node_id,contacts.contact_name FROM `notification_log` LEFT JOIN alerts ON notification_log.cid = alerts.id RIGHT JOIN contacts ON notification_log.contact_id = contacts.id WHERE (" . getSQLMetricAlertIdsForUser() . ") ORDER BY notification_log.id DESC";		
		include("templates/alerts/tables/log.table.head.tpl.php");
	}
	else
	{

		$tpl->title = "Notification Log - Service Checks";
		$sql = "SELECT *,service_checks.*,contacts.contact_name FROM `check_notification_log` LEFT JOIN service_checks ON check_notification_log.cid = service_checks.id RIGHT JOIN contacts ON check_notification_log.contact_id = contacts.id WHERE (" . getSQLCheckAlertIdsForUser() . ") ORDER BY check_notification_log.created_at DESC";		
		include("templates/alerts/tables/logchecks.table.head.tpl.php");	
	}
	if($_GET['debug'])
	{
		echo $sql;
	}
	$result = $db->query($sql);
	while($tpl = $result->fetch_object())
	{
		if($modus == "metrics")
		{
			$tpl->node = getNode($tpl->node_id);
			include("templates/alerts/tables/log.item.tpl.php");
		}
		else
		{
			include("templates/alerts/tables/logcheck.item.tpl.php");	
		}
	}
	
	include("templates/alerts/tables/log.table.end.tpl.php");	
}

function renderAlertTableSingle($aid)
{
	global $db;
	include("templates/alerts/tables/alert.table.head.tpl.php");	
	
	$result = $db->query("SELECT alerts.*,nodes.hostname FROM alerts LEFT JOIN nodes ON alerts.node_id = nodes.id WHERE alerts.id = $aid");
	//echo "SELECT alerts.*,nodes.hostname FROM alerts LEFT JOIN nodes ON alerts.node_id = nodes.id $filter $where";
	while($tpl = $result->fetch_object())
	{
		$r2 = $db->query("SELECT * FROM alert_contacts WHERE alert_id = $tpl->id");
		while($t2 = $r2->fetch_object())
		{
			$c = getContact($t2->contact_id);
			$tpl->contacts.= '<a href="alerts.php?action=contacts&sub=view&cid='.$c->id.'">'.htmlspecialchars($c->contact_name).'</a> ,';
			$c = "";
		}
		$tpl->contacts = substr($tpl->contacts,0,-1);
		include("templates/alerts/tables/alert.table.item.tpl.php");
	}
	include("templates/alerts/tables/alert.table.end.tpl.php");	
}

function renderAlertTableForContact($cid)
{
	global $db;
	include("templates/alerts/tables/alert.table.head.tpl.php");	
	
	$result = $db->query("SELECT alert_contacts.contact_id,alerts.*,nodes.hostname FROM `alert_contacts` LEFT JOIN alerts ON alert_contacts.alert_id = alerts.id RIGHT JOIN nodes ON alerts.node_id = nodes.id WHERE alert_contacts.contact_id = $cid");
	//echo "SELECT alerts.*,nodes.hostname FROM alerts LEFT JOIN nodes ON alerts.node_id = nodes.id $filter $where";
	while($tpl = $result->fetch_object())
	{
		$r2 = $db->query("SELECT * FROM alert_contacts WHERE alert_id = $tpl->id");
		while($t2 = $r2->fetch_object())
		{
			$c = getContact($t2->contact_id);
			$tpl->contacts.= '<a href="alerts.php?action=contacts&sub=view&cid='.$c->id.'">'.htmlspecialchars($c->contact_name).'</a> ,';
			$c = "";
		}
		$tpl->contacts = substr($tpl->contacts,0,-1);
		include("templates/alerts/tables/alert.table.item.tpl.php");
	}
	include("templates/alerts/tables/alert.table.end.tpl.php");	
}


function getSQLCheckAlertIdsForUser()
{
	
}

function getSQLMetricAlertIdsForUser()
{
	global $db;
	if($_SESSION['role'] != "admin")
	{
		if(strlen($filter) > 1)
		{
			$where = " AND alerts.user_id = '$_SESSION[user_id]'";
		}
		else
		{
			$where = " WHERE alerts.user_id = '$_SESSION[user_id]'";	
		}
	}
	$result = $db->query("SELECT alerts.*,nodes.hostname FROM alerts LEFT JOIN nodes ON alerts.node_id = nodes.id $filter $where");	
	
	$i = 0;
	while($tpl = $result->fetch_object())
	{
		$or = " ";
		if($i > 0)
		{
			$or = " OR ";
		}
		$r.= "$or cid = $tpl->id";
		$i++;
	}
	return $r;
}

function renderAlertTable($node=false,$plugin=false)
{
	global $db;

	
	if($node != false && $plugin != false)
	{
		$filter = " WHERE node_id = '$node' AND pluginname = '$plugin'";
	}
	
	if($_SESSION['role'] != "admin")
	{
		if(strlen($filter) > 1)
		{
			$where = " AND alerts.user_id = '$_SESSION[user_id]'";
		}
		else
		{
			$where = " WHERE alerts.user_id = '$_SESSION[user_id]'";	
		}
	}
	$result = $db->query("SELECT alerts.*,nodes.hostname FROM alerts LEFT JOIN nodes ON alerts.node_id = nodes.id $filter $where");

	$render = true;
	if($node != false && $plugin != false && $db->affected_rows < 1)
	{
		$render = false;
	}		
	
	if($render)
	{
		include("templates/alerts/tables/alert.table.head.tpl.php");	
		//echo "SELECT alerts.*,nodes.hostname FROM alerts LEFT JOIN nodes ON alerts.node_id = nodes.id $filter $where";
		while($tpl = $result->fetch_object())
		{
			$r2 = $db->query("SELECT * FROM alert_contacts WHERE alert_id = $tpl->id");
			while($t2 = $r2->fetch_object())
			{
				$c = getContact($t2->contact_id);
				$tpl->contacts.= '<a href="alerts.php?action=contacts&sub=view&cid='.$c->id.'">'.htmlspecialchars($c->contact_name).'</a> ,';
				$c = "";
			}
			$tpl->contacts = substr($tpl->contacts,0,-1);
			include("templates/alerts/tables/alert.table.item.tpl.php");
		}
		include("templates/alerts/tables/alert.table.end.tpl.php");
	}
}
?>