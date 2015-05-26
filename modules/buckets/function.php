<?php

function renderBucketStatTable()
{
	global $db;
	include("templates/buckets/tables/tableHead.tpl.php");
	if($_SESSION['role'] != "admin")
	{
		$and = " WHERE user_id = $_SESSION[user_id] OR groupname = '$_SESSION[accessgroup]'";
	}
	if($_SESSION['role'] == "userext")
	{
		$and = " WHERE user_id = $_SESSION[user_id]";
	}
	$result = $db->query("SELECT buckets.*,users.username FROM buckets LEFT JOIN users ON buckets.user_id = users.id $and");
	while($tpl = $result->fetch_object())
	{
		echo '<tr><td><a href="buckets.php?action=view&bid='.$tpl->id.'">'.htmlspecialchars($tpl->statname).'</a></td>
		          <td>'.htmlspecialchars($tpl->statid).'</td>
		          <td>'.htmlspecialchars($tpl->groupname).'</td>
		          <td>'.$tpl->username.'</td>';
	}
	include("templates/core/tableEnd.tpl.php");	
}

function returnBucket($bid)
{
	global $db;
	$result = $db->query("SELECT * FROM buckets WHERE id = '$bid'");
	if($db->affected_rows < 1)
	{
		return false;
	}	
	else
	{
		return $result->fetch_object();
	}
}

function gotAccessToBucket($bid,$uid=false)
{
	if($uid)
	{
		$user = getUserObject($uid);
	}
	else
	{
		$user = getUserObject($_SESSION['user_id']);
	}
			
		
	
	$bucket = returnBucket($bid);
	if($bucket == false)
	{
		return false;
	}
	if($user->userrole == "admin")
	{
		return true;
	}
	
	if($bucket->user_id == $user->id)
	{
		return true;		
	}
	else
	{
		if($bucket->groupname == "")
		{
			return false;
		}
		if($user->accessgroup == $bucket->groupname)
		{
			return true;
		}
		$groups = getUserGroupsArray($user->id);
		if(in_array($bucket->groupname, $groups))
		{
			return true;
		}							
	}
	return false;	
}
?>