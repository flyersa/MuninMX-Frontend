<?php

function getDashboard($did)
{
	global $db;
	$result = $db->query("SELECT * FROM dashboards WHERE id = '$did'");
	if($db->affected_rows < 1)
	{
		return false;
	}
	return $result->fetch_object();
}


function renderDashboardWidgets($did)
{
	global $db;
	$result = $db->query("SELECT *,dashboard_items.id as item_id FROM dashboard_items LEFT JOIN node_plugins ON dashboard_items.plugin_id = node_plugins.id WHERE dashboard_id = '$did'");
	if($db->affected_rows < 1 )
	{
		return false;
	}
	$board = getDashboard($did);
	
	while($tpl = $result->fetch_object())
	{
		//print_r($tpl);
		$node = getNode($tpl->node_id);
		$plugin = getPlugin($node->id, $tpl->plugin_id);
		
		$tpl->node_id = $node->id;
		$tpl->hostname = $node->hostname;
		$tpl->groupname = $node->groupname;
		$tpl->pluginname = $plugin->pluginname;
		$tpl->refresh = $board->global_refresh;
		include("templates/dashboards/widget.tpl.php");
	}
	return true;
}


function gotAccessToDashboard($did)
{
	global $db;
	if($_SESSION['role'] == "admin")
	{
		return true;
	}
	$board = getDashboard($did);
	if(!$board)
	{
		return false;
	}
	
	if($_SESSION['user_id'] == $board->user_id)
	{
		return true;
	}
	
	if($_SESSION['role'] == "userext")
	{
		if($_SESSION['user_id'] == $board->user_id)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	elseif($_SESSION['role'] == "user")
	{
		$groups = getUserGroupsArray();
		if(!in_array($board->groupname, $groups))
		{
			return false;
		}
		else
		{
			return true;	
		}			
	}
}

function renderDashboardTable()
{
	global $db;
	include("templates/dashboards/tables/dashboards.head.tpl.php");
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT dashboards.*,users.username FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id ORDER BY id DESC");	
	}
	
	if($_SESSION['role'] == "userext")
	{
		$w = getUserGroupsSQLforUserExt();
		$result = $db->query("SELECT dashboards.*,users.username  FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id  WHERE (".$w." OR user_id = $_SESSION[user_id])  ORDER BY id DESC");		
	}

	if($_SESSION['role'] == "user")
	{
		$w = getUserGroupsSQL();
		$result = $db->query("SELECT dashboards.*,users.username  FROM dashboards  LEFT JOIN users ON dashboards.user_id = users.id WHERE (".$w." OR user_id = $_SESSION[user_id])   ORDER BY id DESC");	
	}	
	
	while($tpl = $result->fetch_object())	
	{
		$db->query("SELECT id FROM dashboard_items WHERE dashboard_id = $tpl->id");
		$mcount = $db->affected_rows;
		echo '<tr>';
		echo '<td><a href="dashboard.php?dashboard='.$tpl->id.'">'.htmlspecialchars($tpl->dashboard_name).'</a></td>';
		echo '<td>'.$mcount.'</td>';
		echo '<td>'.htmlspecialchars($tpl->groupname).'</td>';
		echo '<td>'.htmlspecialchars($tpl->username).'</td>';
		echo '</tr>';
	}
	include("templates/dashboards/tables/tableEnd.tpl.php");
}

function getDashboardCount()
{
	global $db;
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT dashboards.*,users.username FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id ORDER BY id DESC");	
	}
	
	if($_SESSION['role'] == "userext")
	{
		$w = getUserGroupsSQLforUserExt();
		$result = $db->query("SELECT dashboards.*,users.username  FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id WHERE (".$w." OR user_id = $_SESSION[user_id])   ORDER BY id DESC");		
	}

	if($_SESSION['role'] == "user")
	{
		$w = getUserGroupsSQL();
		$result = $db->query("SELECT dashboards.*,users.username  FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id WHERE (".$w." OR user_id = $_SESSION[user_id])    ORDER BY id DESC");	
	}	
	return $db->affected_rows;	
}


function getDashboardOptions()
{
	global $db;
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT dashboards.*,users.username FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id ORDER BY id DESC");	
	}
	
	if($_SESSION['role'] == "userext")
	{
		$w = getUserGroupsSQLforUserExt();
		$result = $db->query("SELECT dashboards.*,users.username  FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id  WHERE (".$w." OR user_id = $_SESSION[user_id])  ORDER BY id DESC");		
	}

	if($_SESSION['role'] == "user")
	{
		$w = getUserGroupsSQL();
		$result = $db->query("SELECT dashboards.*,users.username  FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id  WHERE (".$w." OR user_id = $_SESSION[user_id])   ORDER BY id DESC");	
	}	
	
	while($tpl = $result->fetch_object())
	{
		echo '<option value="'.$tpl->id.'">'.htmlspecialchars($tpl->dashboard_name).'</option>';	
	}	
}

function accessToDashBoard($did)
{
	global $db;
	if($_SESSION['role'] == "admin")
	{
		return true;
	}
	
	if($_SESSION['role'] == "userext")
	{
		$w = getUserGroupsSQLforUserExt();
		$result = $db->query("SELECT dashboards.*,users.username  FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id WHERE (".$w." OR user_id = $_SESSION[user_id]) AND dashboards.id = $did   ORDER BY id DESC");		
	}

	if($_SESSION['role'] == "user")
	{
		$w = getUserGroupsSQL();
		$result = $db->query("SELECT dashboards.*,users.username  FROM dashboards LEFT JOIN users ON dashboards.user_id = users.id  WHERE (".$w." OR user_id = $_SESSION[user_id]) AND dashboards.id = $did  ORDER BY id DESC");	
	}	
	
	if($db->affected_rows > 0)
	{
		return true;
	}
	return false;
}
?>