<?php
function returnGraphVisibility($plugin,$graph)
{
	$hide = "visible: false,";
	if($plugin == "cpu" && $graph == "idle")
	{
		return $hide;
	}	
	elseif($plugin == "open_files" && $graph == "max open files")
	{
		return $hide;
	}
	elseif($plugin == "open_inodes" && $graph == "inode table size")
	{
		return $hide;	
	}
	elseif($plugin == "memory")
	{
		switch($graph)
		{
			case "inactive":
				return $hide;
			case "active":
				return $hide;
			case "mapped":
				return $hide;
			case "committed":
				return $hide;
			case "vmalloc_used":
				return $hide;
			case "page_tables":
				return $hide;
			case "swap_cache":
				return $hide;	
			case "slab_cache":
				return $hide;																					
			default:
				return "";
		}
	}
	elseif($plugin == "mysql_connections")
	{
		switch($graph)
		{
			case "Max connections":
				return $hide;
			case "Max used":
				return $hide;																			
			default:
				return "";
		}		
	}
	
	return "";
}

function getCustomInterval($jobid)
{
	global $db;
	$result = $db->query("SELECT plugins_custom_interval.*,nodes.hostname FROM `plugins_custom_interval` LEFT JOIN nodes ON node_id = nodes.id WHERE plugins_custom_interval.id = $jobid");
	if($db->affected_rows < 1)
	{
		return false;
	}
	return $result->fetch_object();
}

function getNodeCountForUser($uid)
{
	global $db;
	$db->query("SELECT * FROM nodes WHERE user_id = '$uid'");
	return $db->affected_rows;
}

function getNode($nid)
{
	global $db;
	$result = $db->query("SELECT *,UNIX_TIMESTAMP(last_contact) AS last_contact_ts FROM nodes WHERE id = '$nid'");
	if($db->affected_rows < 1)
	{
		return false;
	}
	else
	{
		return $result->fetch_object();
	}
}

function isPortOpen($host,$port,$ignore=false)
{
	if($ignore == true)
	{
		return true;
	}
	
	$waitTimeoutInSeconds = 5; 
	if($fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds)){
	   fclose($fp);   
	   return true;
	} else {
	   fclose($fp);
	   return false;
	} 
	fclose($fp);
}

function getAutoCompleteGroups()
{
	global $db;
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT groupname FROM nodes GROUP BY groupname ORDER BY groupname");	
	}
	else 
	{
		$result = $db->query("SELECT groupname FROM nodes WHERE user_id = $_SESSION[user_id] GROUP BY groupname ORDER BY groupname");
	}
	
	$r = '';
	while($tpl = $result->fetch_object())
	{
		$r.= '"'.$tpl->groupname.'",';
	}
	$r = substr($r,0,-1);
	return $r;
}

function accessToNode($nid,$p_uid=false)
{
	if($p_uid == false)
	{
		$uid = $_SESSION['user_id'];
		$role = $_SESSION['role'];
		$user = getUserObject($_SESSION['user_id']);
	}
	else
	{
		$uid = $p_uid;
		$user = getUserObject($uid);
		$role = $user->userrole;
	}
	
	if($role == "admin")
	{
		return true;
	}
	
	
	
	global $db;
	$result = $db->query("SELECT * FROM nodes WHERE id = '$nid'");
	if($db->affected_rows < 1)
	{
		return false;
	}
	else
	{
		$tpl = $result->fetch_object();
		if($uid == $tpl->user_id)
		{
			return true;
		}
		else
		{
			if($role != "userext")
			{
				if($user->accessgroup == $tpl->groupname)
				{
					return true;
				}
				$groups = getUserGroupsArray($uid);
				if(in_array($tpl->groupname, $groups))
				{
					return true;
				}
			}
		}
		
		// user got permission because of something else?
		// TODO: fff
		return false;
	}	
}

function getUserGroupsArrayForUserExt()
{
	global $db;
	$result = $db->query("SELECT * FROM nodes WHERE user_id = '$_SESSION[user_id]' GROUP BY groupname");
	while($tpl = $result->fetch_object())
	{
		$r.= "$tpl->groupname".",";	
	}

	$groups = explode(",",$r);
	return $groups;	
}

function getUserGroupsArray($uid=false)
{
	if($uid == false)
	{
		$user = getUserObject($_SESSION['user_id']);
	}
	else
	{
		$user = getUserObject($uid);	
	}
	if(trim($user->accessgroup) == "")
	{
		return false;
	}
	
	$groups = explode(",",$user->accessgroup);
	return $groups;
}


function getUserGroupsSQL($uid=false,$tablealt="",$match="groupname")
{
	if($uid == false)
	{
		$user = getUserObject($_SESSION['user_id']);
	}
	else
	{
		$user = getUserObject($uid);	
	}
	if(trim($user->accessgroup) == "")
	{
		return false;
	}
	
	$groups = explode(",",$user->accessgroup);
	if(sizeof($groups) < 2)
	{
		$r = $tablealt.$match." = '$groups[0]'";
		return $r;
	}
	
	foreach($groups as $group)
	{
		$r.= $tablealt.$match." = '$group' OR ";
	}
	$r = substr($r,0,-3);
	return $r;
}

function getUserGroupsSQLforUserExt()
{
	global $db;
	$result = $db->query("SELECT * FROM nodes WHERE user_id = '$_SESSION[user_id]' GROUP BY groupname");
	while($tpl = $result->fetch_object())
	{
		$r.= "groupname = '$tpl->groupname' OR ";	
	}

	$r = substr($r,0,-3);
	return $r;
}

function renderCustomGraphTable()
{
	global $db;
	include("templates/munin/tables/tableHead.custom.tpl.php");

	
	$user_id = $_SESSION['user_id'];
	if($_SESSION['role'] == "user")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQL($user_id).")";	
	}
	elseif($_SESSION['role'] == "userext")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQLforUserExt($user_id).")";	
	}
	else
	{
		$and = "";	
	}	
	
	$result = $db->query("SELECT custom_graphs.*,users.username FROM custom_graphs LEFT JOIN users ON custom_graphs.user_id = users.id $and");
	while($tpl = $result->fetch_object())
	{
		$db->query("SELECT * FROM custom_graph_items WHERE custom_graph_id = $tpl->id");
		$aff = $db->affected_rows;
		echo '<tr><td><a href="customs.php?action=view&gid='.$tpl->id.'">'.htmlspecialchars($tpl->graph_name).'</a></td>
		          <td>'.htmlspecialchars($tpl->graph_desc).'</td>
		          <td>'.htmlspecialchars($tpl->groupname).'</td>
		          <td>'.$tpl->username.'</td>
		          <td>'.$aff.'</td></tr>';
	}
	include("templates/core/tableEnd.tpl.php");
}

function renderCustomGraphGroupTable()
{
	global $db;
	include("templates/munin/tables/tableHead.customGroups.tpl.php");
	$user_id = $_SESSION['user_id'];
	if($_SESSION['role'] == "user")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQL($user_id,"","accessgroup").")";	
	}
	elseif($_SESSION['role'] == "userext")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQLforUserExt($user_id,"","accessgroup").")";	
	}
	else
	{
		$and = "";	
	}	
	$result = $db->query("SELECT * FROM custom_graph_groups $and");
	while($tpl = $result->fetch_object())
	{
		$db->query("SELECT * FROM custom_graph_group_items WHERE graph_group_id = $tpl->id");
		$aff = $db->affected_rows;
		echo '<tr><td><a href="customs.php?action=view&group='.$tpl->id.'">'.htmlspecialchars($tpl->groupname).'</a></td>
		          <td>'.htmlspecialchars($tpl->accessgroup).'</td>
		          <td>'.$aff.'</td></tr>';
	}		
	include("templates/core/tableEnd.tpl.php");	
}

function renderGroupTable()
{
	global $db;
	include("templates/munin/tables/tableHead.groups.tpl.php");
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT * FROM nodes GROUP BY groupname");	
	}
	
	if($_SESSION['role'] == "userext")
	{
		$w = getUserGroupsSQLforUserExt();
		$result = $db->query("SELECT * FROM nodes WHERE ($w) GROUP BY groupname");		
	}

	if($_SESSION['role'] == "user")
	{
		$w = getUserGroupsSQL();
		$result = $db->query("SELECT * FROM nodes WHERE ($w) GROUP BY groupname");	
	}	
	
	while($tpl = $result->fetch_object())
	{
		$db->query("SELECT id FROM nodes WHERE groupname = '$tpl->groupname'");
		$aff = $db->affected_rows;
		echo '<tr><td><a href="index.php?action=groups&selection='.$tpl->groupname.'">'.htmlspecialchars($tpl->groupname).'</a></td><td>'.$aff.'</td></tr>';
	}
	include("templates/core/tableEnd.tpl.php");
}

function renderNodesFromGroup($group)
{
	if($_SESSION['role'] == "user")
	{
		$groups = getUserGroupsArray();
		if(!in_array($group, $groups))
		{
			header("Location: 403.php");
			die;
		}				
	}
	elseif($_SESSION['role'] == "userext")
	{
		$groups = getUserGroupsArrayForUserExt();	
		if(!in_array($group, $groups))
		{
			header("Location: 403.php");
			die;
		}	
		$w = "AND user_id = '$_SESSION[user_id]'";
	}
	
	global $db;
	$group = $db->real_escape_string($group);
	include("templates/munin/tables/tableHead.nodes.tpl.php");	
	$result = $db->query("SELECT * FROM nodes WHERE groupname = '$group' $w");
	while($tpl = $result->fetch_object())
	{
		echo '
		<tr>
			<td><a href="view.php?nid='.$tpl->id.'">'.$tpl->hostname.'</a></td>
			<td>'.htmlspecialchars($tpl->groupname).'</td>
			<td>'.getMetricCategoryLinks($tpl->id).'</td>
		</tr>	  
		';
												
	}	
	include("templates/core/tableEnd.tpl.php");
}

function renderNodeTableAdmin()
{
	include("templates/munin/tables/tableHead.nodesAdmin.tpl.php");	
	include("templates/core/tableEnd.tpl.php");
}

function renderNodeTable($search=false)
{
	global $db;
	include("templates/munin/tables/tableHead.nodes.tpl.php");
	if($search != false)
	{
		$where_a = " WHERE hostname LIKE '$search%' ";
		$where_u = " AND hostname LIKE '$search%'";	
	}
	
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT * FROM nodes $where_a ORDER BY groupname");	
	}
	elseif($_SESSION['role'] == "userext")
	{
		$result = $db->query("SELECT * FROM nodes WHERE user_id = '$_SESSION[user_id]' $where_u ORDER BY groupname");	
	}
	else
	{
		$result = $db->query("SELECT * FROM nodes WHERE (".getUserGroupsSQL()." OR user_id = $_SESSION[user_id]) $where_u");	
	}
	if($db->affected_rows > 0)
	{
		while($tpl = $result->fetch_object())
		{
			$acis = getCustomIntervalCountForNode($tpl->id);
			$tpl->acis = "";
			if($acis > 0)
			{
				$tpl->acis = ' <span class="badge" style="float: right"><a href="view.php?nid='.$tpl->id.'" style="color: #fff;filter:none" rel="tooltip" data-placement="top" data-original-title="'.$acis.' configured custom intervals">'.$acis.'</a></span>';
			}
			$acic = getAlertsForNode($tpl->id);
			if($acic > 0 )
			{
				$tpl->acis.= ' &nbsp; <span class="badge bg-color-red" style="float: right"><a href="view.php?nid='.$tpl->id.'" style="color: #fff;filter:none" rel="tooltip" data-placement="top" data-original-title="'.$acic.' configured alert notifications">'.$acic.'</a></span>';	
			}
			
			echo '
			<tr>
				<td><a href="view.php?nid='.$tpl->id.'">'.$tpl->hostname.'</a>'.$tpl->acis.'</td>
				<td>'.htmlspecialchars($tpl->groupname).'</td>
				<td>'.getMetricCategoryLinks($tpl->id).'</td>
			</tr>	  
			';
													
		}
	}
	include("templates/core/tableEnd.tpl.php");
}

function getPluginText($host,$plugin)
{
	global $db;
	$result = $db->query("SELECT * FROM node_plugins WHERE node_id = '$host' AND pluginname = '$plugin'");
	if($db->affected_rows < 1)
	{
		return $plugin;
	}
	else
	{
		$tpl = $result->fetch_object();
		if($tpl->plugintitle == "null")
		{
			return $tpl->pluginname;
		}
		else
		{
			return $tpl->plugintitle;
		}
	}
}

function getPluginLinemode($host,$plugin)
{
	global $db;
	$result = $db->query("SELECT * FROM node_plugins WHERE node_id = '$host' AND pluginname = '$plugin'");
	if($db->affected_rows < 1)
	{
		return $plugin;
	}
	else
	{
		$tpl = $result->fetch_object();
		return $tpl->linemode;
	}
}


function getMetricCategoryLinks($nid)
{
	global $db;
	$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid GROUP BY plugincategory");
	while($tpl = $result->fetch_object())
	{
		if($tpl->plugincategory != "null")
		{
			$r.= '<a href="view.php?nid='.$tpl->node_id.'&category='.htmlspecialchars($tpl->plugincategory).'">'.htmlspecialchars($tpl->plugincategory).'</a> ,';
		}
	}
	return substr($r,0,-1);
}

function renderCustomGraph($gid)
{
	global $db;
	$result = $db->query("SELECT * FROM custom_graphs WHERE id = $gid");
	if($db->affected_rows < 1)
	{
		display_error("Unknown Graph","Graph not found");
	}
	
	$tpl = $result->fetch_object();
	// access?
	if($_SESSION['role'] != "admin")
	{
		if($tpl->user_id != $_SESSION['user_id'])
		{
			if(!accessToCustomGraph($gid))
			{
				display_error("Permission Denied","You are not allowed to access this graph");
				return;				
			}
			
		}
	}
	include("templates/munin/custom_graph.tpl.php");
}

function pluginExists($node,$plugin)
{
	global $db;
	$db->query("SELECT * FROM node_plugins WHERE node_id = $node AND id = $plugin");
	if($db->affected_rows < 1)
	{
		return false;
	}
	return true;
}

function getPlugin($node,$plugin)
{
	global $db;
	$result = $db->query("SELECT * FROM node_plugins WHERE node_id = $node AND id = $plugin");
	if($db->affected_rows < 1)
	{
		return false;
	}
	return $result->fetch_object();
}

function renderSingleGraphByName($nid,$plugin,$period="24h")
{
	global $db;
	$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid AND pluginname = '$plugin'");	
	
	
	
	if($db->affected_rows > 0)
	{
		//$links = returnAnchorLinks($nid,$category);
		//renderAnchorDropDown($nid,$category);
		while($tpl = $result->fetch_object())
		{
			$tpl->links = $links;
			$tpl->period = $period;
			if($tpl->plugintitle == "null")
			{
				$tpl->plugintitle = $tpl->pluginname;
			}
			$node = getNode($nid);
			include("templates/munin/graph.tpl.php");
		}
	}
	else
	{
		if($_SESSION['role'] == "admin" || $_SESSION['role'] == "userext")
		{
			$elink = " or <a href=\"view.php?nid=$nid&action=edit\">Edit this node</a>";
		}
		display_warning("No Plugins","We found no plugins (yet?) for this node. <a href=\"view.php?nid=$nid&action=reloadplugins\">Click here to try to load plugins from munin-node</a>$elink. If this node was just added please wait a minute and reload this page");	
	}
}

function renderSingleGraphByPluginNameForRCA($nid,$plugin,$start,$end)
{
	global $db;
	$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid ANd pluginname = '$plugin'");	
	
	
	if($db->affected_rows > 0)
	{
		//$links = returnAnchorLinks($nid,$category);
		//renderAnchorDropDown($nid,$category);
		while($tpl = $result->fetch_object())
		{
			$tpl->links = $links;
			$tpl->period = $period;
			if($tpl->plugintitle == "null")
			{
				$tpl->plugintitle = $tpl->pluginname;
			}
			$node = getNode($nid);
			
			$tpl->period = "customtime&start=$start&end=$end";
			include("templates/munin/graph.tpl.php");
		}
	}

}

function renderSingleGraph($nid,$plugin,$period="24h")
{
	global $db;
	$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid ANd id = $plugin");	
	
	
	
	if($db->affected_rows > 0)
	{
		//$links = returnAnchorLinks($nid,$category);
		//renderAnchorDropDown($nid,$category);
		while($tpl = $result->fetch_object())
		{
			$tpl->links = $links;
			$tpl->period = $period;
			if($tpl->plugintitle == "null")
			{
				$tpl->plugintitle = $tpl->pluginname;
			}
			$node = getNode($nid);
			include("templates/munin/graph.tpl.php");
		}
	}
	else
	{
		if($_SESSION['role'] == "admin" || $_SESSION['role'] == "userext")
		{
			$elink = " or <a href=\"view.php?nid=$nid&action=edit\">Edit this node</a>";
		}
		display_warning("No Plugins","We found no plugins (yet?) for this node. <a href=\"view.php?nid=$nid&action=reloadplugins\">Click here to try to load plugins from munin-node</a>$elink. If this node was just added please wait a minute and reload this page");	
	}
}

function renderAllGraphs($nid,$period="24h",$category=false)
{
	global $db;
	if(!$category)
	{
		$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid ORDER BY plugincategory");	
	}
	else
	{
		$category = $db->real_escape_string($category);
		$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid AND plugincategory = '$category'");		
	}
	
	if($db->affected_rows > 0)
	{
		$links = returnAnchorLinks($nid,$category);
		renderAnchorDropDown($nid,$category);
		renderCustomJobs($nid);
		$user = getUserObject($_SESSION['user_id']);
		while($tpl = $result->fetch_object())
		{
			$tpl->links = $links;
			$tpl->period = $period;
			if($tpl->plugintitle == "null")
			{
				$tpl->plugintitle = $tpl->pluginname;
			}
			$node = getNode($nid);
			include("templates/munin/graph.tpl.php");
		}
	}
	else
	{
		if($_SESSION['role'] == "admin" || $_SESSION['role'] == "userext")
		{
			$elink = " or <a href=\"view.php?nid=$nid&action=edit\">Edit this node</a>";
		}
		display_warning("No Plugins","We found no plugins (yet?) for this node. <a href=\"view.php?nid=$nid&action=reloadplugins\">Click here to try to load plugins from munin-node</a>$elink. If this node was just added please wait a minute and reload this page");	
	}
}

function returnAnchorLinksOld($nid,$category=false)
{
	global $db;
	if(!$category)
	{
		$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid ORDER BY plugincategory");
	}
	else
	{
		$category = $db->real_escape_string($category);
		$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid AND plugincategory = '$category'");		
	}	
	
	while($tpl = $result->fetch_object())
	{
		if($tpl->plugintitle == "null")
		{
			$tpl->plugintitle = $tpl->pluginname;
		}		
		$links.= '<li><a href="#'.$tpl->pluginname.'">'.$tpl->plugintitle.'</a></li>';
	}	
    return $links;
}

function returnAnchorLinks($nid,$category=false)
{
	return renderAnchorDropDown($nid,$category,true);
}

function renderAnchorDropDown($nid,$category=false,$returnv=false)
{
	global $db;
	
	if(!$category)
	{
		$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid ORDER BY plugincategory");
	}
	else
	{
		$category = $db->real_escape_string($category);
		$result = $db->query("SELECT * FROM `node_plugins` WHERE node_id = $nid AND plugincategory = '$category'");		
	}	
	
	if($category)
	{
		while($tpl = $result->fetch_object())
		{
			if($tpl->plugintitle == "null")
			{
				$tpl->plugintitle = $tpl->pluginname;
			}		
			$links.= '<li><a href="#'.htmlspecialchars($tpl->pluginname).'">'.htmlspecialchars($tpl->plugintitle).'</a></li>';
		}	
		$tpl->links = $links;
	}
	else
	{
		while($tpl = $result->fetch_object())
		{
			$a[$tpl->plugincategory][] = $tpl->pluginname.",".$tpl->plugintitle; 
		}	
		foreach ($a as $key => $value)  {
			$links.='<li class="dropdown-submenu">';
			if($key == "null")
			{
				$key = "Other";
			}
			$links.='<a tabindex="-1" href="javascript:void(0);">'.$key.'</a>';
			$links.='<ul class="dropdown-menu">';
			foreach($value as $plugs)
			{
				$item = explode(",",$plugs);
				if($item[1] == "null")
				{
					$item[1] = $item[0];
				}
				$links.= '<li><a href="#'.htmlspecialchars($item[0]).'">'.htmlspecialchars($item[1]).'</a></li>';
			}
			$links.='</ul>';
			$links.="</li>";
		}
		$tpl->links = $links;
	}
	if(!$returnv)
	{
		$node = getNode($nid);
		include("templates/munin/anchorDropDown.tpl.php");
	}
	else
	{
		return $tpl->links;
	}
}


function renderViaHostDropDown($sel=false)
{
	if($sel == false)
	{
		$defsel = " selected";
	}
	global $db;
	echo '<select name="via_host" id="via_host" class="select2">';
	echo '<option value="unset"'.$defsel.'>No, use hostname</option>';
	if($_SESSION['role'] != "admin")
	{
		$result = $db->query("SELECT * FROM nodes WHERE user_id = " . $_SESSION['user_id']);
	}
	else
	{
		$result = $db->query("SELECT * FROM nodes ORDER BY hostname");	
	}
	while($tpl = $result->fetch_object())
	{
		$issel = "";
		if($sel == $tpl->hostname)
		{
			$issel = " selected";
		}
		echo '<option value="'.$tpl->hostname.'"'.$issel.'>'.htmlspecialchars($tpl->hostname).'</option>';
	}
	echo '</select>';	
}

function renderHostDropDown($sel=false)
{
	$user_id = $_SESSION['user_id'];
	if($sel == false)
	{
		$defsel = " selected";
	}
	
	if($_SESSION['role'] == "user")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQL($user_id).")";	
	}
	elseif($_SESSION['role'] == "userext")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQLforUserExt($user_id).")";	
	}
	else
	{
		$and = "";	
	}

	
	global $db;
	echo '<select name="basehost" id="basehost" class="select2">';
	echo '<option>Please Select a Host</option>';
	//echo '<option value="mxinvalidmx">Please select a host</option>';
	$result = $db->query("SELECT * FROM nodes $and ORDER BY groupname,hostname ");
	echo "SELECT * FROM nodes $and ORDER BY groupname,hostname ";
	while($tpl = $result->fetch_object())
	{
		$issel = "";
		if($sel == $tpl->hostname)
		{
			$issel = " selected";
		}
		echo '<option value="'.$tpl->id.'"'.$issel.'> ['.htmlspecialchars($tpl->groupname).'] &nbsp; &nbsp; '.htmlspecialchars($tpl->hostname).'</option>';
	}
	echo '</select>';	
}

function renderHostDropDownMulti($sel=false)
{
	if($sel == false)
	{
		$defsel = " selected";
	}
	
	if($_SESSION['role'] == "user")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQL($user_id).")";	
	}
	elseif($_SESSION['role'] == "userext")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQLforUserExt($user_id).")";	
	}
	else
	{
		$and = "";	
	}
	
	global $db;
	echo '<select name="otherhosts[]" multiple id="otherhosts" class="select2">';
	echo '<option value="mxinvalidmx">Please select hosts to include in the graph</option>';
	$result = $db->query("SELECT * FROM nodes $and ORDER BY groupname,hostname ");
	while($tpl = $result->fetch_object())
	{
		$issel = "";
		if($sel == $tpl->hostname)
		{
			$issel = " selected";
		}
		echo '<option value="'.$tpl->id.'"'.$issel.'> ['.htmlspecialchars($tpl->groupname).'] &nbsp; &nbsp; '.htmlspecialchars($tpl->hostname).'</option>';
	}
	echo '</select>';	
}

function renderHostDropDownMultiTwo($sel=false)
{
	if($sel == false)
	{
		$defsel = " selected";
	}
	
	$user_id = $_SESSION['user_id'];
	
	if($_SESSION['role'] == "user")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQL($user_id).")";	
	}
	elseif($_SESSION['role'] == "userext")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR (".getUserGroupsSQLforUserExt($user_id).")";	
	}
	else
	{
		$and = "";	
	}
	
		
	global $db;
	echo '<select name="otherhosts[]" multiple id="otherhosts" class="select2">';
	echo '<option value="mxinvalidmx">Please select hosts to include in the graph</option>';
	$result = $db->query("SELECT * FROM nodes $and ORDER BY groupname,hostname ");
	while($tpl = $result->fetch_object())
	{
		$issel = "";
		if(in_array($tpl->hostname,$sel))
		{
			$issel = " selected";
		}
		echo '<option value="'.$tpl->id.'"'.$issel.'> ['.htmlspecialchars($tpl->groupname).'] &nbsp; &nbsp; '.htmlspecialchars($tpl->hostname).'</option>';
	}
	echo '</select>';	
}

function renderPluginsForNode($nid,$sel=false)
{
	global $db;
	$result = $db->query("SELECT * FROM node_plugins WHERE node_id = $nid ORDER BY pluginname");
	echo '<select name="plugin" id="plugin" class="select2" style="width: 100%">';
	echo '<option value="mxinvalidmx">Please select a plugin</option>';
	while($tpl = $result->fetch_object())
	{
			$selv = "";
			$cat = "";
			if($sel == $tpl->pluginname) { $selv = " selected"; };
			if($tpl->plugincategory != "null") { $cat = ' ['.htmlspecialchars($tpl->plugincategory).'] '; };
			echo '<option value="'.htmlspecialchars($tpl->pluginname).'"'.$selv.'>'.$cat.htmlspecialchars($tpl->plugintitle).'</option>';
	}
	echo '</select>';
}

function renderGraphsForPluginAndNode($nid,$plugin,$sel=false,$multiple=true)
{
	$node = getNode($nid);
	$json  = json_decode(file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/$node->id/fetch/$plugin"));
	if($multiple)
	{
		echo '<select name="graphs[]" id="graphs" multiple class="select2" style="width: 100%">';
	}
	else
	{
		echo '<select name="graph" id="graph" class="select2" style="width: 100%">';	
	}
	foreach ($json as $tpl)
	{
		if(trim($tpl->str_GraphLabel) == "")
		{
			$tpl->str_GraphLabel = $tpl->str_GraphName;
		}
		$selv = "";

		if($sel == $tpl->str_GraphName) { $selv = " selected"; };	
		
		if($tpl->str_GraphLabel != $tpl->str_GraphName)
		{
			$tpl->addinfo = " (".htmlspecialchars($tpl->str_GraphName).")";
		}	
		else
		{
			$tpl->addinfo = "";
		}
		echo '<option value="'.htmlspecialchars($tpl->str_GraphName).'"'.$selv.'>'.htmlspecialchars($tpl->str_GraphLabel).$tpl->addinfo.'</option>';
	}
	echo '</select>';
}


function getCustomMetricGroupOptions($selected=false,$uid=false)
{
	global $db;
	if($uid == false)
	{
		$result = $db->query("SELECT * FROM custom_graph_groups GROUP BY groupname");
	}
	else
	{
		$result = $db->query("SELECT * FROM custom_graph_groups WHERE user_id = '$uid' GROUP BY groupname");	
	}
	while($tpl = $result->fetch_object())
	{
		if($tpl->id == $selected)
		{
			$sv = "selected";
		}	
		else
		{
			$sv = "";
		}
		$r.='<option value="'.$tpl->id.'" '.$sv.'>'.htmlspecialchars($tpl->groupname).'</option>';
	}
	return $r;
}

function getCustomGraph($gid)
{
	global $db;
	$result = $db->query("SELECT * FROM custom_graphs WHERE id = $gid");
	if($db->affected_rows > 0)
	{
		$tpl = $result->fetch_object();
		return $tpl;
	}
	else
	{
		return false;
	}
}

function getFirstCustomGraphItem($gid)
{
	global $db;
	$result = $db->query("SELECT custom_graph_items.*,nodes.hostname FROM `custom_graph_items` LEFT JOIN nodes ON node_id = nodes.id WHERE custom_graph_id = '$gid' ORDER BY custom_graph_items.id ASC LIMIT 1");
	if($db->affected_rows > 0)
	{
		$tpl = $result->fetch_object();
		return $tpl;
	}
	else
	{
		return false;
	}	
}

function getOtherCustomGraphItemsAsHost($gid)
{
	global $db;
	$result = $db->query("SELECT custom_graph_items.*,nodes.hostname FROM `custom_graph_items` LEFT JOIN nodes ON node_id = nodes.id WHERE custom_graph_id = '$gid' ORDER BY custom_graph_items.id ASC");
	if($db->affected_rows > 0)
	{
		if($db->affected_rows < 2)
		{
			return false;
		}
		$i = 0;
		while($tpl = $result->fetch_object())
		{
			if($i > 0)
			{
				$hosts[] = $tpl->hostname;
			}
			$i++;
		}
		return $hosts;
	}
	else
	{
		return false;
	}		
}

function getAssignedGroupFromCustomGraph($gid)
{
	global $db;
	$result = $db->query("SELECT * FROM `custom_graph_group_items` WHERE custom_graph_id =  $gid");
	if($db->affected_rows > 0)
	{
		$tpl = $result->fetch_object();
		return $tpl->graph_group_id;
	}
	else
	{
		return false;
	}	
}

function accessToCustomGraph($gid)
{
	$g = getCustomGraph($gid);
	if($g == false)
	{
		return false;
	}
	else 
	{
		if($_SESSION['role'] != "admin")
		{
			if($g->user_id != $_SESSION['user_id'])
			{
				if($g->groupname == $_SESSION['accessgroup'])	
				{
					return true;
				}
				else
				{
					if($_SESSION['accessgroup'] == $g->groupname)
					{
						return true;
					}
					$groups = getUserGroupsArray();
					if(in_array($g->groupname, $groups))
					{
						return true;
					}	
					else
					{
						return false;	
					}							
				}
			}
			else
			{
				return true;
			}	
		}
		else
		{
			return true;
		}
	}
}


function renderGroupDropDown($sel=false)
{
	global $db;
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT * FROM nodes GROUP BY groupname");
		while($tpl = $result->fetch_object())
		{
			$g[] = $tpl->groupname;
		}
	}
	elseif($_SESSION['role'] == "user")
	{
		$g = getUserGroupsArray();
	}
	elseif($_SESSION['role'] == "userext")
	{
		$g = getUserGroupsArrayForUserExt();
	}
	
	foreach($g as $group)
	{
		$sell = "";
		if($sel != false)
		{
			if($group == $sel)
			{
				$sell = " selected";
			}
		}
	
		echo '<option value="'.htmlspecialchars($group).'"'.$sell.'>'.htmlspecialchars($group).'</option>';	
	}
}


function renderPluginCategoryDropDown()
{
	global $db;
	$user_id = $_SESSION['user_id'];
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT * FROM node_plugins GROUP BY plugincategory ORDER BY plugincategory");	
	}
	elseif($_SESSION['role'] == "user")
	{
		$result = $db->query("SELECT node_plugins.*,nodes.groupname FROM node_plugins LEFT JOIN nodes ON node_id = nodes.id WHERE (".getUserGroupsSQL($user_id).") GROUP BY plugincategory ORDER BY plugincategory");	
	}
	else
	{
		$result = $db->query("SELECT node_plugins.*,nodes.groupname FROM node_plugins LEFT JOIN nodes ON node_id = nodes.id WHERE (".getUserGroupsSQLforUserExt($user_id).") GROUP BY plugincategory ORDER BY plugincategory");	
	}

	//$result = $db->query("SELECT * FROM node_plugins GROUP BY plugincategory ORDER BY plugincategory");
	while($tpl = $result->fetch_object())
	{
		
		echo '<option value="'.htmlspecialchars($tpl->plugincategory).'">'.htmlspecialchars($tpl->plugincategory).'</option>';
	}		
}

?>