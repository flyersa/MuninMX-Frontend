<?php

function renderNodesWithSam()
{
	include("templates/sam/tables/tableNodesWithSam.tpl.php");	
	include("tempaltes/sam/tables/tableEndRow.tpl.php");
}

function getDnameForDist($dist)
{
		$dname = htmlspecialchars($dist);
		switch($dist)
		{
			case "centos":
				$dname = '<img src="img/centos-icon.png" style="vertical-align: middle"> CentOS'; 
				break;
			case "redhat":
				$dname = '<img src="img/redhat-icon.png" style="vertical-align: middle"> RedHat'; 
				break;
			case "debian":
				$dname = '<img src="img/debian-icon.png" style="vertical-align: middle"> Debian'; 
				break;	
			case "ubuntu":
				$dname = '<img src="img/ubuntu-icon.png" style="vertical-align: middle"> Ubuntu'; 
				break;		
			case "xenserver":
				$dname = '<img src="img/xen-icon.png" style="vertical-align: middle"> XenServer'; 
				break;												
			default:
				$dname = htmlspecialchars($dist);
				break;
		}
		return $dname;	
}

function renderInstalledPackageTable($package)
{
	global $m;

	$tpl->package = $package;

	include("templates/sam/tables/tableInstalledPKG.tpl.php");
	$dbname = MONGO_DB_ESSENTIALS;
	$db = $m->$dbname;

	$colname = "trackpkg";
	$col = $db->$colname;
	
	// get last stamp
	$res = $col->find(array("package" => "$package"));
	foreach($res as $obj)
	{
		$display = true;
		if($_SESSION['role'] != "admin")
		{
			if(!accessToNode($obj['node']))
			{
				$display = false;
			}
		}
		$node = getNode($obj['node']);
		//print_r($obj);
		//echo $obj['time'];
		//$time = $obj['time'];
		//echo $time;

		if($display)
		{
			
			echo '<tr>';
			echo '<td><a href="sam.php?action=view&nid='.$obj['node'].'">'.htmlspecialchars($node->hostname).'</a></td>';
			echo '<td>'.htmlspecialchars($node->groupname).'</td>';  
			echo '<td>'.getDnameForDist($node->track_dist).'</td>';
			echo '<td>'.htmlspecialchars($node->track_ver).'</td>';
			echo '<td>'.htmlspecialchars($node->track_kernel).'</td>';
			echo '<td><a href="sam.php?action=view&nid='.$node->id.'">'.getLatestPackageCountForNode($node->id).'</a></td>';
			echo '<td>'.$node->track_update.'</td>';	
			echo '</tr>';
		}
	}	
	include("tempaltes/sam/tables/tableEndRow.tpl.php");		
}

function jsonInstalledPackages($package,$uid)
{
	global $m;
	$tpl->package = $package;
	$dbname = MONGO_DB_ESSENTIALS;
	$db = $m->$dbname;

	$colname = "trackpkg";
	$col = $db->$colname;
	
	$user_id = $uid;
	$user = getUserObject($user_id);
	$role = $user->userrole;
	
	// get last stamp
	$res = $col->find(array("package" => "$package"));
	foreach($res as $obj)
	{
		$display = true;
		if($role != "admin")
		{
			if(!accessToNode($obj['node'],$user_id))
			{
				$display = false;
			}
		}
		//$node = getNode($obj['node']);
		//print_r($obj);
		//echo $obj['time'];
		//$time = $obj['time'];
		//echo $time;

		if($display)
		{
			$rsjon->nodes.=$obj['node'].",";
		}
	}	
	$rsjon->nodes = substr($rsjon->nodes,0,-1);
	return $rsjon->nodes;	
}

function renderAllPackageTable()
{
	moveAllPackagesToTempTable();
	include("templates/sam/tables/tableAll.tpl.php");
	//renderAllPackageTableTD();
	include("tempaltes/sam/tables/tableEndRow.tpl.php");	
}


function moveAllPackagesToTempTable($uid=false)
{
	if($uid == false)
	{
		$user_id = $_SESSION['user_id'];
		$role = $_SESSION['role'];
	}
	else
	{
		$user_id = $uid;
		$user = getUserObject($user_id);
		$role = $user->userrole;
	}
	
	$lmaptime = $_SESSION['maptotmp'] ;
	if($lmaptime > 0)
	{
		$targettime = $lmaptime + 300;
		
		
		if(time() < $targettime)
		{
			return;	
		}
	}
	
	global $db;
	if($role == "admin")
	{
		$usesw = false;
		$sw = " WHERE track_update != 'NULL'";
	} 
	elseif($role == "userext")
	{
		$sw = " WHERE user_id = '$user_id' AND track_update != 'NULL'";	
	}
	else
	{
		$sw = " WHERE (".getUserGroupsSQL($user_id)." OR user_id = $user_id) AND track_update != 'NULL'";	
	} 	
	
	$result = $db->query("SELECT * FROM nodes $sw");
	while($tpl = $result->fetch_object())
	{
		$res = renderPackageListForNode($tpl->id,true);
		foreach($res as $obj)
		{
			$pname = $obj['package'];
			$r[$pname] = $r[$pname] + 1;
		}	
	}
	
	$db->query("DELETE FROM tmp_ess WHERE user_id = '$user_id'");
	while($count = current($r)) {
		$package = $db->real_escape_string(key($r));
		$pcount = $count;
		$db->query("INSERT INTO tmp_ess (user_id,package,pcount) VALUES ($user_id,'$package',$count)");
	 	next($r);	
	}
	$_SESSION['maptotmp'] = time();
}

function renderAllPackageTableTD($cli=false,$json=false,$uid=false,$api=false)
{
	global $m;
	global $db;
	
	if($uid == false)
	{
		$user_id = $_SESSION['user_id'];
		$role = $_SESSION['role'];
	}
	else
	{
		$user_id = $uid;
		$user = getUserObject($user_id);
		$role = $user->userrole;
	}
	
	if($cli == false)
	{
		if($role == "admin")
		{
			$usesw = false;
			$sw = " WHERE track_update != 'NULL'";
		} 
		elseif($role == "userext")
		{
			$sw = " WHERE user_id = '$user_id' AND track_update != 'NULL'";	
		}
		else
		{
			$sw = " WHERE (".getUserGroupsSQL($user_id)." OR user_id = $user_id) AND track_update != 'NULL'";	
		} 	
	}
	$result = $db->query("SELECT * FROM nodes $sw");
	while($tpl = $result->fetch_object())
	{
		$res = renderPackageListForNode($tpl->id,true);
		foreach($res as $obj)
		{
			$pname = $obj['package'];
			$r[$pname] = $r[$pname] + 1;
		}	
	}
	
	$rjson;
	$i = 0;
	while($count = current($r)) {
		
		if($json)
		{
			//$t = null;
			if($api == false)
			{
				$rjson->aaData[]->package = '<a href="sam.php?action=packagedetail&package='.htmlspecialchars(urlencode(key($r))).'" target="_blank">'.htmlspecialchars(key($r)).'</a>';
				$rjson->aaData[]->pcount = '<a href="sam.php?action=packagedetail&package='.htmlspecialchars(urlencode(key($r))).'" target="_blank">'.$count.'</a>';	
			}
			else 
			{
				$rjson->packages[$i]->package->name = htmlspecialchars(key($r));
				$rjson->packages[$i]->package->pcount = $count;
				$rjson->packages[$i]->package->affected_nodes = jsonInstalledPackages(key($r),$user_id);
			}
			$i++;
			//$rjson[] = $t;	
		}
		else
		{
			echo '<tr>';
		    echo '<td><a href="sam.php?action=packagedetail&package='.htmlspecialchars(urlencode(key($r))).'" target="_blank">'.htmlspecialchars(key($r)).'</a></td>';
			echo '<td><a href="sam.php?action=packagedetail&package='.htmlspecialchars(urlencode(key($r))).'" target="_blank">'.$count.'</a></td>';
			echo '</tr>';			
		}
	    next($r);
	}

	if($json)
	{
		echo json_encode($rjson);
	}
	//print_r($r);
}

function getLastPackageCountStamp($node)
{
	global $m;
	// db.trackpkg.find().sort({node:5,time:1}).
		
	//$node = getNode($node);
	$time = 0;
	// select a database
	$dbname = MONGO_DB_ESSENTIALS;
	$db = $m->$dbname;

	$colname = "trackpkg";
	$col = $db->$colname;
	
	// get last stamp
	$res = $col->find(array("node" => new MongoInt32($node)))->sort(array('time' => 1))->limit(1);
	foreach($res as $obj)
	{
		//print_r($obj);
		//echo $obj['time'];
		$time = $obj['time'];
		//echo $time;
	}
	return $time;	
}

function getLatestPackageCountForNode($node)
{
	global $m;
	// db.trackpkg.find().sort({node:5,time:1}).
		
	//$node = getNode($node);
	
	// select a database
	$dbname = MONGO_DB_ESSENTIALS;
	$db = $m->$dbname;

	$colname = "trackpkg";
	$col = $db->$colname;
	
	// get last stamp
	$res = $col->find(array("node" => new MongoInt32($node)))->sort(array('time' => 1))->limit(1);
	foreach($res as $obj)
	{
		//print_r($obj);
		//echo $obj['time'];
		$time = $obj['time'];
		//echo $time;
	}
	//echo $time;
	if($time < 1)
	{
		return 0;
	}
	
	$res = $col->find(array("node" => new MongoInt32($node), "time" => new MongoInt32($time)))->sort(array('time' => 1))->count();
	return $res;
}

function renderPackageListForNode($nodeid,$return=false)
{
	$node = getNode($nodeid);
	$tpl = $node;	
	//print_r($node);
	if(!$return)
	{
		include("templates/sam/tables/tablePackageListNode.tpl.php");
	}
	global $m;
	$searchtime = getLastPackageCountStamp($node->id);
	$dbname = MONGO_DB_ESSENTIALS;
	$db = $m->$dbname;

	$colname = "trackpkg";
	$col = $db->$colname;
	
	// get last stamp
	$res = $col->find(array("node" => new MongoInt32($node->id), "time" => new MongoInt32($searchtime)));
	if($return)
	{
		return $res;
	}
	foreach($res as $obj)
	{
		echo '<tr>';
		echo '<td>'.htmlspecialchars($obj[package]).'</td>';
		//echo '<td></td>';
		echo '</tr>';

	}	
	include("tempaltes/sam/tables/tableEndRow.tpl.php");			
}
?>