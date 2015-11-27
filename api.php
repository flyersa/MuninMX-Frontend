<?php
include("inc/startup.php");
if(isset($_COOKIE['key']))
{
	die;
}
if(!$_REQUEST['key'])
{
	forbidden("no key parameter received");
}

if(!$_REQUEST['method'])
{
	badrequest("no method parameter received");
}

$key = $db->real_escape_string($_REQUEST['key']);
$method = $_REQUEST['method'];

$result = $db->query("SELECT * FROM users WHERE apikey = '$key'");
if($db->affected_rows < 1)
{
	forbidden("Access Denied");
}
global $user;
$user = $result->fetch_object();

// ok user authorized, lets go with the api stuff
switch($method)
{
	// list All nodes
	case "listNodes":
		if(isset($_REQUEST['search']))
		{
			$search = $_REQUEST['search'];
		}
		else
		{
			$search = false;
		}	
		api_listNodes($search);
		break;

	// list All nodes by Group
	case "listNodesByGroup":
		if(!isset($_REQUEST['group']))
		{
			badrequest("group parameter missing");
		}

		api_listNodesByGroup($_REQUEST['group']);
		break;

	// list All groups
	case "listGroups":
		api_listGroups();
		break;
		
		
	// list userRole	
	case "getRole":
		echo json_encode(array("role" => $user->userrole));
		break;
		
	// return node Details, fetch directly from MCD	
	case "getNode":
		if(!is_numeric($_REQUEST['nodeid']))
		{
			badrequest("nodeid parameter missing");	
		}
		api_listNode($_REQUEST['nodeid']);
		break;
	
	// get chartDAta
	case "getChartData":
		if(!is_numeric($_REQUEST['nodeid']))
		{
			badrequest("nodeid parameter missing");	
		}
		if(!isset($_REQUEST['plugin']))
		{
			badrequest("plugin parameter missing");	
		}				
		api_getChartData($_REQUEST['nodeid'],$_REQUEST['plugin']);
		break;
	
	// list bucketstats
	case "listBuckets":
		api_listBuckets();
		break;
	
	// show a single bucket
	case "getBucket":
		if(!is_numeric($_REQUEST['bucketid']))
		{
			badrequest("bucketid parameter missing");	
		}		
		api_getBucket($_REQUEST['bucketid']);
		break;
	
	// get data from a bucket
	case "getBucketData":
		if(!is_numeric($_REQUEST['bucketid']))
		{
			badrequest("bucketid parameter missing");	
		}		
		api_getBucketData($_REQUEST['bucketid']);	
		break;	
		
	// add a new bucket stat
	case "addBucket":
		if(!isset($_REQUEST['graphname']) && !isset($_REQUEST['graphlabel']))
		{
			badrequest("graphname and/or graphlabel parameter missing");
		}
		if(isset($_REQUEST['groupname']))
		{
			$groupname = $_REQUEST['groupname'];
		}
		else
		{
			$groupname = false;
		}
		api_addBucket($_REQUEST['graphname'],$_REQUEST['graphlabel'],$groupname);
		break;	
	
	// edit a bucket
	case "editBucket":
		if(!isset($_REQUEST['graphname']) && !isset($_REQUEST['graphlabel']) && !isset($_REQUEST['statid']))
		{
			badrequest("graphname and / or graphlabel and / or bucketid parameter missing");
		}
		if(isset($_REQUEST['groupname']))
		{
			$groupname = $_REQUEST['groupname'];
		}
		else
		{
			$groupname = false;
		}
		api_editBucket($_REQUEST['bucketid'], $_REQUEST['graphname'],$_REQUEST['graphlabel'],$groupname);
		break;				
	
	// delete a bucket
	case "deleteBucket":
		if(!is_numeric($_REQUEST['bucketid']))
		{
			badrequest("bucketid parameter missing");
		}
		api_deleteBucket($_REQUEST['bucketid']);
		break;
		
	// refresh plugins on a node
	case "reloadPlugins":
		if(!is_numeric($_REQUEST['nodeid']))
		{
			badrequest("nodeid parameter missing");
		}
		api_reloadPlugins($_REQUEST['nodeid']);
		break;
	
	// add a new muninnode
	case "addNode":
		if($user->userrole != "admin" && $user->userrole != "userext")
		{
			forbidden("required role: userext or admin");
		}
		if(!isset($_REQUEST['hostname']) && !is_numeric($_REQUEST['port']) && !is_numeric($_REQUEST['interval']))
		{
			badrequest("hostname, port and interval parameters required");
		}
		if(isset($_REQUEST['groupname']))
		{
			$groupname = $_REQUEST['groupname'];
		}
		else
		{
			$groupname = "";	
		}
		
		if(isset($_REQUEST['viahost']))
		{
			$viahost = $_REQUEST['viahost'];
		}
		else
		{
			$viahost = false;	
		}		
		
		if(isset($_REQUEST['authpw']))
		{
			$authpw = $_REQUEST['authpw'];
		}
		else
		{
			$authpw = false;	
		}			
		
		api_addNode($_REQUEST['hostname'],$_REQUEST['port'],$_REQUEST['interval'],$groupname,$viahost,$authpw);		
		break;

	case "deleteNode":
		if(!is_numeric($_REQUEST['nodeid']))
		{
			badrequest("nodeid parameter is required");	
		}
		api_deleteNode($_REQUEST['nodeid'])	;
		break;
	
	case "listChecksByName":
		if(!isset($_REQUEST['name']))
		{
			badrequest("name parameter is required");	
		}
		api_listChecksByName($_REQUEST['name']);
		break;
		
	case "listChecks":
		api_listChecks();
		break;
		
	case "deleteCheck":
		if(!is_numeric($_REQUEST['checkid']))
		{
			badrequest("checkid parameter is required");
		}
		api_deleteCheck($_REQUEST['checkid']);
		break;
		
	case "editNode":
		if($user->userrole != "admin" && $user->userrole != "userext")
		{
			forbidden("required role: userext or admin");
		}		
		if(!isset($_REQUEST['hostname']) && !is_numeric($_REQUEST['port']) && !is_numeric($_REQUEST['interval']) && !is_numeric($_REQUEST['nodeid']))
		{
			badrequest("nodeid, hostname, port and interval parameters required");
		}		
		if(isset($_REQUEST['groupname']))
		{
			$groupname = $_REQUEST['groupname'];
		}
		else
		{
			$groupname = "";	
		}
		
		if(isset($_REQUEST['viahost']))
		{
			$viahost = $_REQUEST['viahost'];
		}
		else
		{
			$viahost = false;	
		}	
		
		if(isset($_REQUEST['authpw']))
		{
			$authpw = $_REQUEST['authpw'];
		}
		else
		{
			$authpw = false;	
		}				
		api_editNode($_REQUEST['nodeid'],$_REQUEST['hostname'],$_REQUEST['port'],$_REQUEST['interval'],$groupname,$viahost,$authpw);		
		break;
	
	case "addUser":
		api_addUser();
		break;
		
	case "checkUsername":
		api_checkUser();
		break;
	
	case "getUser":
		api_getUser();
		break;

	case "listUsers":
		api_getUsers();
		break;
	
	case "setUserPassword":
		api_setPass();
		break;
		
	case "packageList":
		api_packageList();
		break;
		
	case "addEvent":
		api_addEvent();
		break;
		
	case "addCheck":
		if(!isset($_REQUEST['checkname']))
		{
			badrequest("checkname parameter is required");
		}
		if(!is_numeric($_REQUEST['interval']))
		{
			badrequest("interval parameter is required");
		}
		if(!is_numeric($_REQUEST['notifydown']))
		{
			badrequest("notifydown parameter is required");
		}
		if(!is_numeric($_REQUEST['notifyagain']))
		{
			badrequest("notifyagain parameter is required");
		}
		if(!is_numeric($_REQUEST['notifyifup']))
		{
			badrequest("notifyifup parameter is required");
		}
		if(!is_numeric($_REQUEST['checktype']))
		{
			badrequest("checktype parameter is required");
		}
		api_addCheck();
	break;
		
	
	case "addAlert":
		if(!is_numeric($_REQUEST['nodeid']))
		{
			badrequest("nodeid parameter is required");
		}
		if(!isset($_REQUEST['pluginname']))
		{
			badrequest("pluginname parameter is required");
		}
		if(!isset($_REQUEST['graphname']))
		{
			badrequest("graphname parameter is required");
		}
		if(!is_numeric($_REQUEST['raisevalue']))
		{
			badrequest("raisevalue parameter is required");
		}
		if(!isset($_REQUEST['condition']))
		{
			badrequest("condition parameter is required");
		}
		if (!in_array($_REQUEST['condition'], array('lt','eq','gt','ltavg','gtavg'))) 
		{
			badrequest("condition parameter must be one of ('lt','eq','gt','ltavg','gtavg')!");
		}
		if(($_REQUEST['condition'] == 'ltavg' || $_REQUEST['condition'] == 'gtavg') && !is_numeric($_REQUEST['limit']))
		{
			badrequest("limit parameter is required when condition is one of ltavg or gtavg");
		}
		if(!is_numeric($_REQUEST['samples']))
		{
			badrequest("samples parameter is required");
		}
		if(!isset($_REQUEST['contacts']))
		{
			badrequest("contacts parameter is required");
		}
		api_addAlert($_REQUEST['nodeid'],$_REQUEST['pluginname'],$_REQUEST['graphname'],$_REQUEST['raisevalue'],$_REQUEST['condition'],$_REQUEST['limit'],$_REQUEST['samples'],$_REQUEST['contacts']);
		break;
	
		case "deleteAlert":
			if(!is_numeric($_REQUEST['alertid']))
			{
				badrequest("alertid parameter is required");
			}
			api_deleteAlert($_REQUEST['alertid']);
		break;
	
		
		case "listAlertsByNode":
			if(!is_numeric($_REQUEST['nodeid']))
			{
				badrequest("nodeid parameter is required");
			}
			api_listAlertsByNode($_REQUEST['nodeid']);
		break;
		
		case "getAlert":
			if(!is_numeric($_REQUEST['alertid']))
			{
				badrequest("alertid parameter is required");
			}
			api_getAlert($_REQUEST['alertid']);
		break;
		
		case "listContacts":
			api_listContacts();
		break;
		
		
		case "addContact":
			// errors
			if(trim($_REQUEST['contact_mobile_nr']) == "")
			{
				if(isset($_REQUEST['sms_active']) || isset($_REQUEST['tts_active']))
				{
					badrequest("Phone Notifications activated but no valid mobile number given: contact_mobile_nr is required");
				}
			}
				
			if(trim($_REQUEST['contact_callback']) == "")
			{
				if(isset($_REQUEST['callback_active']))
				{
					badrequest("JSON Callback activated but no callback url set: contact_callback is required");
				}
			}
				
			if(trim($_REQUEST['pushover_key']) == "")
			{
				if(isset($_REQUEST['pushover_active']))
				{
					badrequest("Pushover activated but no user key set: pushover_key is required");
				}
			}
				
			if (!isset($_REQUEST['contact_name'])) {
				badrequest("contact_name is required");
			}
			if (!isset($_REQUEST['contact_email'])) {
				badrequest("contact_email is required");
			}
			
			// schedule
			$schedule = array("mon"=>"00:00;24:00", "tue"=>"00:00;24:00", "wed"=>"00:00;24:00", "thu"=>"00:00;24:00",
					 "fri"=>"00:00;24:00", "sat"=>"00:00;24:00", "sun"=>"00:00;24:00");
			
			if(isset($_REQUEST['s_mon_none']) && filter_var($_REQUEST['s_mon_none'], FILTER_VALIDATE_BOOLEAN))	{
				$schedule->mon = "disabled";
			} else if (isset($_REQUEST['s_mon_from']) && isset($_REQUEST['s_mon_to'])) {
				$schedule->mon = $_REQUEST['s_mon_from'].";".$_REQUEST['s_mon_to'];
			}
			
			if(isset($_REQUEST['s_tue_none']) && filter_var($_REQUEST['s_tue_none'], FILTER_VALIDATE_BOOLEAN))	{
				$schedule->tue = "disabled";
			} else if (isset($_REQUEST['s_tue_from']) && isset($_REQUEST['s_tue_to'])) {
				$schedule->tue = $_REQUEST['s_tue_from'].";".$_REQUEST['s_tue_to'];
			}
			
			if(isset($_REQUEST['s_wed_none']) && filter_var($_REQUEST['s_wed_none'], FILTER_VALIDATE_BOOLEAN))	{
				$schedule->wed = "disabled";
			} else if (isset($_REQUEST['s_wed_from']) && isset($_REQUEST['s_wed_to'])) {
				$schedule->wed = $_REQUEST['s_wed_from'].";".$_REQUEST['s_wed_to'];
			}
			
			if(isset($_REQUEST['s_thu_none']) && filter_var($_REQUEST['s_thu_none'], FILTER_VALIDATE_BOOLEAN))	{
				$schedule->thu = "disabled";
			} else if (isset($_REQUEST['s_thu_from']) && isset($_REQUEST['s_thu_to'])) {
				$schedule->thu = $_REQUEST['s_thu_from'].";".$_REQUEST['s_thu_to'];
			}
			
			if(isset($_REQUEST['s_fri_none']) && filter_var($_REQUEST['s_fri_none'], FILTER_VALIDATE_BOOLEAN))	{
				$schedule->fri = "disabled";
			} else if (isset($_REQUEST['s_fri_from']) && isset($_REQUEST['s_fri_to'])) {
				$schedule->fri = $_REQUEST['s_fri_from'].";".$_REQUEST['s_fri_to'];
			}
			
			if(isset($_REQUEST['s_sat_none']) && filter_var($_REQUEST['s_sat_none'], FILTER_VALIDATE_BOOLEAN))	{
				$schedule->sat = "disabled";
			} else if (isset($_REQUEST['s_sat_from']) && isset($_REQUEST['s_sat_to'])) {
				$schedule->sat = $_REQUEST['s_sat_from'].";".$_REQUEST['s_sat_to'];
			}
			
			if(isset($_REQUEST['s_sun_none']) && filter_var($_REQUEST['s_sun_none'], FILTER_VALIDATE_BOOLEAN))	{
				$schedule->sun = "disabled";
			} else if (isset($_REQUEST['s_sun_from']) && isset($_REQUEST['s_sun_to'])) {
				$schedule->sun = $_REQUEST['s_sun_from'].";".$_REQUEST['s_sun_to'];
			}
			
			// mapping
			$notifications = array("callback"=>0, "email"=>0, "sms"=>0, "tts"=>0, "pushover"=>0);
			if(isset($_REQUEST['callback_active']) && filter_var($_REQUEST['callback_active'], FILTER_VALIDATE_BOOLEAN))
			{
				$notifications['callback'] = 1;
			}
			if(isset($_REQUEST['email_active']) && filter_var($_REQUEST['email_active'], FILTER_VALIDATE_BOOLEAN))
			{
				$notifications['email'] = 1;
			}
			if(isset($_REQUEST['sms_active']) && filter_var($_REQUEST['sms_active'], FILTER_VALIDATE_BOOLEAN))
			{
				$notifications['sms'] = 1;
			}
			if(isset($_REQUEST['tts_active']) && filter_var($_REQUEST['tts_active'], FILTER_VALIDATE_BOOLEAN))
			{
				$notifications['tts'] = 1;
			}
			if(isset($_REQUEST['pushover_active']) && filter_var($_REQUEST['pushover_active'], FILTER_VALIDATE_BOOLEAN))
			{
				$notifications['pushover'] = 1;
			}
			
			// timezone
			if (isset($_REQUEST['timezone'])) {
				$timezone = $_REQUEST['timezone'];
			}
			else {
				$timezone = "Europe/Berlin"; // Default
			}
			
			api_addContact($_REQUEST['contact_name'],$_REQUEST['contact_email'],$_REQUEST['contact_mobile_nr'],$_REQUEST['contact_callback'],
					$_REQUEST['pushover_key'],$timezone, $notifications, $schedule);
		break;
		
		
		case "deleteContact":
			if(!is_numeric($_REQUEST['contactid']))
			{
				badrequest("contactid parameter is required");
			}
			api_deleteContact($_REQUEST['contactid']);
		break;
		
		case "addAlertContact":
			if(!is_numeric($_REQUEST['alertid']))
			{
				badrequest("alertid parameter is required");
			}
			if(!is_numeric($_REQUEST['contactid']))
			{
				badrequest("contactid parameter is required");
			}
			api_addAlertContact($_REQUEST['alertid'],$_REQUEST['contactid']);
		break;
		
		case "deleteAlertContact":
			if(!is_numeric($_REQUEST['alertid']))
			{
				badrequest("alertid parameter is required");
			}
			if(!is_numeric($_REQUEST['contactid']))
			{
				badrequest("contactid parameter is required");
			}
			api_deleteAlertContact($_REQUEST['alertid'],$_REQUEST['contactid']);
			break;
		
	default:
		badrequest("unknown method specified");
	
		

}


function api_addContact($name,$email,$mobile_nr,$callback,$pushover_key,$timezone,$notifications,$schedule)
{
	global $user;
	global $db;

	// escaping
	$name = $db->real_escape_string($name);
	$email = $db->real_escape_string($email); // TODO: check if email is valid
	$mobile_nr = $db->real_escape_string($mobile_nr); // TODO: check if mobile number is valid
	$callback = $db->real_escape_string($callback);  // TODO: check if url is valid
	$pushover_key = $db->real_escape_string($pushover_key);  // TODO: check if key is valid
	$timezone = $db->real_escape_string($timezone); // TODO: check if timezone is valid
	
	foreach($notifications as &$value) { $value = $db->real_escape_string($value); }
	unset($value);
	foreach($schedule as &$value) { $value = $db->real_escape_string($value); }
	unset($value);
	
	// okey lets go
	$db->query("INSERT INTO contacts (contact_name,contact_email,contact_mobile_nr, contact_callback, pushover_key, user_id, callback_active, email_active, sms_active, tts_active, pushover_active, s_mon,s_tue,s_wed,s_thu,s_fri,s_sat,s_sun,timezone)
			VALUES (
			'$name',
			'$email',
			'$mobile_nr',
			'$callback',
			'$pushover_key',
			'$user->id',
			'$notifications->callback',
			'$notifications->email',
			'$notifications->sms',
			'$notifications->tts',
			'$notifications->pushover',
			'$schedule->mon',
			'$schedule->tue',
			'$schedule->wed',
			'$schedule->thu',
			'$schedule->fri',
			'$schedule->sat',
			'$schedule->sun',
			'$timezone')");
		
		
	if($db->affected_rows > 0)
	{
		$cid = $db->insert_id;
		
		$tpl->status = "ok";
		$tpl->message = "Contact '$name' created.";
		$tpl->id = $cid;

		echo json_encode($tpl);
	}
	else
	{
		badrequest("There was a issue with the database operation. Please try again later or contact support if the problem persist");
	}
}

function api_deleteContact($contactid)
{
	global $user;
	global $db;

	// escaping
	$contactid = $db->real_escape_string($contactid);
	
	$contact = returnContact($contactid);
	if($contact == false)
	{
		notfound("Cannot find contact for contactid $contactid");
	}
	if($user->userrole != "admin")
	{
		if($contact->user_id != $user->id)
		{
			forbidden("Access to contact denied!");
		}
	}
	// delete or just view
	$db->query("DELETE FROM contacts WHERE id = '$contact->id'");
	if($db->affected_rows < 1 )
	{
		badrequest("Cannot delete contact. Try again later");
	}
	else
	{
		$tpl->status = "ok";
		$tpl->message = "Contact with contactid $contact->id deleted.";
		echo json_encode($tpl);
	}
}

function api_addAlertContact($alertid, $contactid)
{
	global $user;
	global $db;
	
	// escaping
	$alertid = $db->real_escape_string($alertid);
	$contactid = $db->real_escape_string($contactid);
	
	$a = getAlert($alertid);
	if($a == false)
	{
		notfound("Alert with alertid $alertid not found");
	}
	else
	{
		if($user->userrole != "admin")
		{
			if($a->user_id != $user->id)
			{
				forbidden("Access to alert denied!");
			}
		}
		
		$c = getContact($contactid);
		if ($c == false) 
		{
			notfound("Contact with contactid $contactid not found");
		}
		else {
			if($user->userrole != "admin")
			{
				if($c->user_id != $user->id)
				{
					forbidden("The contact with contactid $c->id does not belong to your account!");
				}
			}
			
			$db->query("INSERT INTO alert_contacts (alert_id,contact_id) VALUES ('$a->id','$c->id')");
			
			$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletealert/$a->id");
			sleep(1);
			$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/addalert/$a->id");
			if(trim($ret) != "true") {
				$db->query("DELETE FROM alert_contacts WHERE alert_id = '$a->id' AND contact_id = '$c->id'");
				
				badrequest("Alert notification contact was added but unable to update running config -- notification was removed again automatically.");
			}
			
			$tpl->status = "ok";
			$tpl->msg = "Alert notification contact added and running configuration updated.";
			echo json_encode($tpl);
			
		}
	}
}

function api_deleteAlertContact($alertid, $contactid)
{
	global $user;
	global $db;

// escaping
	$alertid = $db->real_escape_string($alertid);
	$contactid = $db->real_escape_string($contactid);
	
	$a = getAlert($alertid);
	if($a == false)
	{
		notfound("Alert with alertid $alertid not found");
	}
	else
	{
		if($user->userrole != "admin")
		{
			if($a->user_id != $user->id)
			{
				forbidden("Access to alert denied!");
			}
		}
		
		$c = getContact($contactid);
		if ($c == false) 
		{
			notfound("Contact with contactid $contactid not found");
		}
		else {
			if($user->userrole != "admin")
			{
				if($c->user_id != $user->id)
				{
					forbidden("The contact with contactid $c->id does not belong to your account!");
				}
			}
			
			$db->query("DELETE FROM alert_contacts WHERE alert_id = '$a->id' AND contact_id = '$c->id'");
			
			$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletealert/$a->id");
			sleep(1);
			$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/addalert/$a->id");
			if(trim($ret) != "true") {
				$db->query("INSERT INTO alert_contacts (alert_id,contact_id) VALUES ('$a->id','$c->id')");
				
				badrequest("Alert notification contact was removed but unable to update running config -- notification was added again automatically.");
			}
			
			$tpl->status = "ok";
			$tpl->msg = "Alert notification contact removed and running configuration updated.";
			echo json_encode($tpl);
			
		}
	}
}

	
function api_addAlert($nodeid, $pluginname, $graphname, $raisevalue, $condition, $limit, $samples, $contacts)
{
	global $user;
	global $db;
	
	// escaping
	$nodeid = $db->real_escape_string($nodeid);
	$graphname = $db->real_escape_string($graphname);
	$raisevalue = $db->real_escape_string($raisevalue);
	$condition = $db->real_escape_string($condition);
	$limit = $db->real_escape_string($limit);
	$samples = $db->real_escape_string($samples);
	
	// check node access and get node object
	if(!accessToNode($nodeid,$user->id)) forbidden("Access to node denied!");
	$node = getNode($nodeid);
	if (!$node)	notfound("Cannot find node for nodeid $nodeid");
	
	// get plugin object
	$pluginname = $db->real_escape_string($pluginname);
	$result = $db->query("SELECT id FROM node_plugins WHERE node_id = '$node->id' AND pluginname='$pluginname' $and");
	if($db->affected_rows == 0) {
		notfound("Cannot find plugin for nodeid $nodeid and pluginname $pluginname");
	}
	$pluginid = $result->fetch_object()->id;
	
	$plugin = getPlugin($nodeid, $pluginid);
	if (!$plugin) 
	{
		badrequest("Cannot find pluginid $pluginid for node $nodeid");
	}
	
	// prepare contact data
	$contactsList = explode(',', $contacts);
	foreach($contactsList as &$contactid) { $contactid = $db->real_escape_string($contactid); } // escaping
	unset($contactid);
	
	// contact security
	foreach($contactsList as $contactid)
	{
		$c = getContact($contactid);
		if ($c == false)
		{
			notfound("Contact with contactid $contactid not found");
		}
		else {
			if($user->userrole != "admin")
			{
				if($c->user_id != $user->id)
				{
					forbidden("The contact with contactid $c->id does not belong to your account!");
				}
			}
		}
	}
	
	// add alert
	$db->query("INSERT INTO alerts (user_id,node_id,pluginname,graphname,raise_value,`condition`,alert_limit,num_samples) VALUES (
			'$user->id',
			'$node->id',
			'$plugin->pluginname',
			'$graphname',
			'$raisevalue',
			'$condition',
			'$limit',
			'$samples')");
		
	if($db->affected_rows > 0)
	{
		$aid = $db->insert_id;
		
		// add contacts
		foreach($contactsList as $contactid)
		{
			$db->query("INSERT INTO alert_contacts (alert_id,contact_id) VALUES ('$aid','$contactid')");
		}
		
		// add to running configuration
		$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/addalert/$aid");
		if(trim($ret) != "true") {
			// cleanup after error
			$db->query("DELETE FROM alert_contacts WHERE alert_id = '$aid'");
			$db->query("DELETE FROM alerts WHERE id = '$aid'");
			
			badrequest("Alert could be stored but unable to add to running config -- alert was removed again automatically.");
		}
		
		$tpl->status = "ok";
		$tpl->msg = "Alert stored and added to running configuration.";
		$tpl->id = $aid;
	}
	else
	{
		badrequest("Unable to store alert information, try again later");
	}
	
	echo json_encode($tpl);
}

function api_deleteAlert($alertid)
{
	global $user;
	global $db;
	
	$a = getAlert($alertid);
	if($a == false)
	{
		notfound("Alert not found");
	}
	else
	{
		if($user->userrole != "admin")
		{
			if($a->user_id != $user->id)
			{
				forbidden("Access to alert denied!");
			}
		}
		$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletealert/$a->id");
		if(trim($ret) != "true")
		{
			badrequest("Alert cannot be removed from running config. Please try again later");
		}
		
		$db->query("DELETE FROM alerts WHERE id = $a->id");
		
		$tpl->status = "ok";
		$tpl->msg = "Alert removed and purged from running configuration.";
		echo json_encode($tpl);
	}
}

function api_listAlertsByNode($nodeid)
{

	global $user;
	global $db;

	// escaping
	$nodeid = $db->real_escape_string($nodeid);
	
	$node = getNode($nodeid);
	if (!$node)	notfound("Cannot find node for nodeid $nodeid");
	
	$filter = " WHERE node_id = '$nodeid'";
	
	if($user->userrole != "admin")
	{
		$where = " AND alerts.user_id = '$user->id'";
	}
	$result = $db->query("SELECT alerts.*,nodes.hostname FROM alerts LEFT JOIN nodes ON alerts.node_id = nodes.id $filter $where");

	if($db->affected_rows == 0)
	{
		notFound("alert not found");
	}
	
	$r = array();
	while($entry = $result->fetch_object())
	{
		$r[] = $entry;
	}
	
	echo json_encode($r);
}


function api_getAlert($alertid)
{
	global $user;
	global $db;

	// escaping
	$alertid = $db->real_escape_string($alertid);
	
	$filter = " WHERE alerts.id = '$alertid'";
	if($user->userrole != "admin")
	{
		$where = " AND alerts.user_id = '$user->id'";
	}
	$result = $db->query("SELECT alerts.*,nodes.hostname FROM alerts LEFT JOIN nodes ON alerts.node_id = nodes.id $filter $where");
	
	if($db->affected_rows == 0) 
	{
		notFound("alert not found");
	}
	
	$entry = $result->fetch_object();
	echo json_encode($entry);
}

function api_listContacts()
{
	global $user;
	global $db;

	if($user->userrole == "admin")
	{
		$result = $db->query("SELECT * FROM contacts");
	}
	else
	{
		$result = $db->query("SELECT * FROM contacts WHERE user_id = '$user->id'");
	}
	
	$r = array();
	while($tpl = $result->fetch_object()) 
	{
		$r[] = $tpl;
	}
		
	echo json_encode($r);
}










function api_addCheck() 
{
	global $user;
	global $db;
	if (getCurrentCheckCount ( $user->id ) < $user->max_checks) {
	} else {
		forbidden ( "To many checks. You need to upgrade your plan or buy check slots to create a new service check." );
	}
	
	if (getCurrentCheckCount ( $user->id ) < $user->max_checks) {
		$result = $db->query ( "SELECT * FROM check_types WHERE id = '$_REQUEST[checktype]'" );
		if ($db->affected_rows < 1) {
			badrequest ( "Undefined Check. Check Type not defined" );
		} else {
			$PBJ = $_REQUEST;
			
			// build json
			$tpl = $result->fetch_object ();
			
			$params = array_merge ( array (), $_REQUEST );
			$params ['command'] = $tpl->executable;
			unset($params['key']);
			unset($params['method']);
			$json = postCheckToJson ( $params , $user->id );
			
			if (getCurrentCheckCount ( $user->id ) < $user->max_checks) {
				//
			} else {
				forbidden ( "Too many checks. You need to upgrade your plan or buy check slots to create a new service check." );
			}
			
			// check if choosen contacts belong to this user
			$contacts = explode ( ",", $PBJ ['contacts'] );
			if ($user->userrole != "admin") {
				if (sizeof ( $contacts ) > 0) {
					foreach ( $contacts as $contact ) {
						$db->query ( "SELECT id FROM contacts WHERE user_id = '$user->id' AND id = '$contact'" );
						if ($db->affected_rows < 1) {
							forbidden ( "Contact Mismatch at contactid $contact. You can only specify notify contacts that belong to your account." );
						}
					}
				}
			}
			
			// save check if no error occured
			$escapedData = secureArray ( $_REQUEST );
			$db->query ( "INSERT INTO service_checks (user_id,check_type,check_name,cinterval,json,accessgroup)
				VALUES
				(
						'$user->id',
						'$escapedData[checktype]',
						'$escapedData[checkname]',
						'$escapedData[interval]',
						'$json',
						'$escapedData[accessgroup]'
				)");
			
			$checkInsertId = $db->insert_id;
			
			if ($checkInsertId <= 1) {
				badrequest ( "Backend Error. Unable to save service check. Try again later" );
			} else {
				$cid = $db->insert_id;
				
				// add tags
				$tags = explode ( ",", $PBJ ['tags'] );
				foreach ( $tags as $tag ) {
					$tag = $db->real_escape_string ( $tag );
					if (trim ( $tag ) != "") {
						$db->query ( "INSERT INTO service_check_tags (tagname,check_id,user_id) VALUES ('$tag','$cid','$user->id')" );
					}
				}
				
				foreach ( $contacts as $contact ) {
					$db->query ( "INSERT INTO notifications (contact_id,check_id,notifydown,notifyagain,notifyifup,notifyflap)
								VALUES (
									'$contact',
									'$cid',
									'$escapedData[notifydown]',
									'$escapedData[notifyagain]',
									'$escapedData[notifyifup]',
									'$escapedData[notifyflap]'
								)");
				}
				cvdQueueCheck ( $cid );
			}
		}
	}
	
	$tpl->status = "ok";
	$tpl->id = $checkInsertId;
	$tpl->msg = "Check added.";
	echo json_encode($tpl);
}



function api_addEvent()
{
	global $db;
	global $user;
	$callowed = false;
	if($user->userrole != "admin" && $user->userrole != "userext")
	{
		if($user->eventsallowed == 0)
		{
			forbidden("role admin or userext is required, or eventsallowed has to be set to 1 for role USER. Your role: ".$user->userrole);
		}
		else 
		{
			$callowed = true;
		}
	}	

	if(isset($_REQUEST['event_start']))
	{
		if(!is_numeric($_REQUEST['event_start']))
		{
			badrequest("event_start needs to be numeric");
		}
	}

	// check if color is valid
	if(isset($_REQUEST['color']))
	{
		$col = strtolower($_REQUEST['color']);
		switch($col)
		{
			case "red":
				// fine
				break;
			case "blue":
				// fine
				break;	
			case "green":
				// fine
				break;
			case "orange":
				// fine
				break;	
			case "yellow":
				// fine
				break;
			case "blue":
				// fine
				break;												
			default:
				badrequest("invalid color specified, valid colors: red, blue,green,orange,yellow,blue");
		}
	}

	if(isset($_REQUEST['event_end']))
	{
		if(!is_numeric($_REQUEST['event_end']))
		{
			badrequest("event_end needs to be numeric");
		}
	}

	if(!isset($_REQUEST['event_title']))
	{
		badrequest("event_title (varchar) parameter missing");	
	}
	
	if(!isset($_REQUEST['group']) && !isset($_REQUEST['node']))
	{
		badrequest("group OR node parameter missing");
	}
	
	if(isset($_REQUEST['group']) && isset($_REQUEST['node']))
	{
		badrequest("group and node cannot be mixed. use node OR group parameter, not both together");	
	}
	
	if(isset($_REQUEST['group']))
	{
		$modus = "group";
		$group = $db->real_escape_string($_REQUEST['group']);
		if($user->userrole == "admin")
		{
			$db->query("SELECT * FROM nodes WHERE groupname = '$group' LIMIT 0,1");
		}
		else
		{
			if($callowed)
			{
				$ags = getUserGroupsArray($user->id);
				if(!in_array($group, $ags))
				{
					notFound("no group found for query: " . htmlspecialchars($_REQUEST['group']));		
				}
				$db->query("SELECT * FROM nodes WHERE (".getUserGroupsSQL($user->id).") LIMIT 0,1");	
			}
			else
			{
				$db->query("SELECT * FROM nodes WHERE  user_id = '$user->id' AND groupname = '$group' LIMIT 0,1");	
			}
		}
		if($db->affected_rows < 1)
		{
			notFound("no group found for query: " . htmlspecialchars($_REQUEST['group']));
		}
		else
		{
			// so we got a node now :P
			//print_r($node);
			if(!isset($_REQUEST['event_start']))
			{
				$start = time();
			}
			else
			{
				$start = $_REQUEST['event_start'];
			}
			
			if(!isset($_REQUEST['event_end']))
			{
				$end = 0;
			}
			else
			{
				$end = $_REQUEST['event_end'];
			}	
			$title = $db->real_escape_string($_REQUEST['event_title']);
			if(!isset($_REQUEST['color']))
			{
				$color = "red";
			}
			else
			{
				$color = $db->real_escape_string($_REQUEST['color']);
			}
			$db->query("INSERT INTO events (user_id,event_start,event_end,event_title,groupname,color) 
			VALUES ($user->id,$start,$end,'$title','$group','$color')");	
			if($db->affected_rows > 0)
			{
				$eid = $db->insert_id;
				$event = getEvent($eid);
				echo json_encode($event);	
			}
			else
			{
				badrequest("error occured during save. please try again later");	
			}			
		}
	}
	else
	{
		if(is_numeric($_REQUEST['node']))
		{
			$node = getNode($_REQUEST['node']);
			if($node == false)
			{
				notFound("invalid node specified");
			}
			
			if(!accessToNode($node->id,$user->id))
			{
				forbidden("access to node denied");	
			}
			$nid = $node->id;
		}
		else
		{
			$hname = $db->real_escape_string($_REQUEST['node']);
			$result = $db->query("SELECT * FROM nodes WHERE hostname = '$hname'");
			if($db->affected_rows < 1)
			{
				if(!filter_var($hname, FILTER_VALIDATE_IP))
				{
					notFound("no node found for query:" . htmlspecialchars($_REQUEST['node']));
				}
				else
				{
					$hname = gethostbyaddr($hname);
					$result = $db->query("SELECT * FROM nodes WHERE hostname = '$hname'");
					if($db->affected_rows < 1)
					{
						notFound("no node found for query:" . htmlspecialchars($_REQUEST['node']));	
					}
					else
					{
						$tpl = $result->fetch_object();
						$nid = $tpl->id;
						if(!accessToNode($nid,$user->id))
						{
							forbidden("access to node denied");		
						}	
						$node = getNode($nid);						
					}
				}
				
			}			
			else
			{
				$tpl = $result->fetch_object();
				$nid = $tpl->id;
				if(!accessToNode($nid,$user->id))
				{
					forbidden("access to node denied");		
				}	
				$node = getNode($nid);
			}
		}
		// so we got a node now :P
		//print_r($node);
		if(!isset($_REQUEST['event_start']))
		{
			$start = time();
		}
		else
		{
			$start = $_REQUEST['event_start'];
		}
		
		if(!isset($_REQUEST['event_end']))
		{
			$end = 0;
		}
		else
		{
			$end = $_REQUEST['event_end'];
		}	
		$title = $db->real_escape_string($_REQUEST['event_title']);
		if(!isset($_REQUEST['color']))
		{
			$color = "red";
		}
		else
		{
			$color = $db->real_escape_string($_REQUEST['color']);
		}
		$db->query("INSERT INTO events (user_id,event_start,event_end,event_title,node,color) 
		VALUES ($user->id,$start,$end,'$title',$node->id,'$color')");	
		if($db->affected_rows > 0)
		{
			$eid = $db->insert_id;
			$event = getEvent($eid);
			// execute munin run?
			if(isset($_GET['update']))
			{
				$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/$node->id/updateAll");	
			}			
			echo json_encode($event);	

		}
		else
		{
			badrequest("error occured during save. please try again later");	
		}
	}
	//print_r($_REQUEST);
}

function api_getUsers()
{
	global $db;
	global $user;
	if($user->userrole != "admin")
	{
		forbidden("role admin is required. Your role: ".$user->userrole);
	}	
	$result = $db->query("SELECT * FROM users");
	while($tpl = $result->fetch_object())
	{
		$r[] = $tpl;
	}
	echo json_encode($r);
}


function api_setPass()
{
	global $db;
	global $user;
	if(!is_numeric($_REQUEST['uid']))
	{
		badrequest("uid parameter required");
	}	
	if(!isset($_REQUEST['password']))
	{
		badrequest("password paramter required");
	}
	if($user->userrole != "admin")
	{
		forbidden("role admin is required. Your role: ".$user->userrole);
	}	
	$pw = $db->real_escape_string($_REQUEST['password']);
	$tpl = getUserObject($_REQUEST['uid']);
	if($tpl == false)
	{
		notFound("invalid user");
	}
	else
	{
		$db->query("UPDATE users SET password = '$pw' WHERE id = '$tpl->id'");	
		if($db->affected_rows > 0)
		{
			echo json_encode(true);	
		}	
		else
		{
			badrequest("unable to change password");
		}
	}
	
}

function api_getUser()
{
	global $db;
	global $user;
	if(!is_numeric($_REQUEST['uid']))
	{
		badrequest("uid parameter required");
	}	
	if($user->userrole != "admin")
	{
		forbidden("role admin is required. Your role: ".$user->userrole);
	}	
	$tpl = getUserObject($_REQUEST['uid']);
	if($tpl == false)
	{
		notFound("user not found");
	}
	else
	{
		$tpl->nodecount = getNodeCountForUser($tpl->id);
		echo json_encode($tpl);
	}
}

function api_checkUser()
{
	global $db;
	global $user;
	if(!isset($_REQUEST['username']))
	{
		badrequest("username parameter required");
	}
	if($user->userrole != "admin")
	{
		forbidden("role admin is required. Your role: ".$user->userrole);
	}	
	$rusername = $db->real_escape_string($_REQUEST['username']);
	$db->query("SELECT * FROM users WHERE username = '$rusername'");	
	if($db->affected_rows > 0)
	{
		echo json_encode(true);
	}
	else
	{
		echo json_encode(false);
	}
}

function api_addUser()
{
	global $db;
	global $user;
	if($user->userrole != "admin")
	{
		forbidden("role admin is required. Your role: ".$user->userrole);
	}	
	
	if(!isset($_REQUEST['username']) || !isset($_REQUEST['email']) || !isset($_REQUEST['role']) || !isset($_REQUEST['password']) || !isset($_REQUEST['retention'])  || !isset($_REQUEST['maxnodes']) || !isset($_REQUEST['maxcustoms'])  
	|| !isset($_REQUEST['smstickets']) || !isset($_REQUEST['ttstickets']) || !isset($_REQUEST['maxchecks']) )
	{
		badrequest("All parameters required: username, email,role,password,retention,maxnodes,maxcustoms,smstickets,ttstickets,maxchecks");	
	}
	else
	{
		if($_REQUEST['role'] != "admin" && $_REQUEST['role'] != "user" && $_REQUEST['role'] != "userext")
		{
			badrequest("role needs to be admin, user or userext");
		}
		
		if(!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL))
		{
			badrequest("invalid email");	
		}

		if(strlen($_REQUEST['password']) < 6)
		{
			badrequest("password needs to be at least 6 chars in length");
		}

		if(!is_numeric($_REQUEST['retention']) || !is_numeric($_REQUEST['maxcustoms']) || !is_numeric($_REQUEST['smstickets']) || !is_numeric($_REQUEST['ttstickets']) || !is_numeric($_REQUEST['maxnodes']) || !is_numeric($_REQUEST['maxchecks']) )
		{
			badrequest("retention,maxnodes,maxcustoms,smstickets,ttstickets,maxchecks need to be numeric");
		}
		
		$_REQUEST = secureArray($_REQUEST);
		$db->query("SELECT * FROM users WHERE username = '$_REQUEST[username]'");
		if($db->affected_rows > 0)
		{
			badrequest("username already in use");	
		}
		
		$db->query("SELECT * FROM users WHERE email = '$_REQUEST[email]'");
		if($db->affected_rows > 0)
		{
			badrequest("e-mail already in use");	
		}	
		
		if(!isset($_REQUEST['autologinkey']))
		{
			$autologinkey = "unset";
		}
		else
		{
			$autologinkey = $_REQUEST['autologinkey'];
		}
		
		if(!isset($_REQUEST['accessgroup']))
		{
			$agroup = "NULL";
		}
		else
		{
			$agroup = "'".$_REQUEST['accessgroup']."'";
		}		
		
		$pw = sha1($_REQUEST['password']);
		// soooo all in order til now, create user ;)
		$db->query("INSERT INTO users (username,password,email,userrole,autologinkey,retention,max_nodes,max_customs,tts_tickets,sms_tickets,max_checks,accessgroup)
		VALUES (
		'$_REQUEST[username]',
		'$pw',
		'$_REQUEST[email]',
		'$_REQUEST[role]',
		'$autologinkey',
		'$_REQUEST[retention]',
		'$_REQUEST[maxnodes]',
		'$_REQUEST[maxcustoms]',
		'$_REQUEST[ttstickets]',
		'$_REQUEST[smstickets]',
		'$_REQUEST[maxchecks]',
		'$agroup'
		)
		");
		
		$auid = $db->insert_id;
		if($auid > 0)
		{
			$auser = getUserObject($auid);
			echo json_encode($auser);
		}
		else
		{
			badrequest("Error saving changes. please try again");
		}
	}
}

function api_packageList()
{
	global $db;
	global $user;
	if(!is_numeric($_REQUEST['node']))
	{
		moveAllPackagesToTempTable($user->id);
		renderAllPackageTableTD(false,true,$user->id,true);
	}
	else
	{
		if(!accessToNode($_REQUEST['node'],$user->id))
		{
			forbidden("access to node denied");
		}
		else
		{
			$res = renderPackageListForNode($_REQUEST['node'],true);
			$i = 0;
			foreach($res as $obj)
			{
				$package = $obj['package'];	
				$tpl[]->package = $package;
				$i++;
			}
			if($i == 0)
			{
				notFound("no packages for node");
			}
			echo json_encode($tpl);	
		}
	}
}

function api_editNode($nodeid,$hostname,$port,$interval,$groupname,$viahost,$authpw)
{
	global $db;
	global $user;
	if($user->userrole != "admin" && $user->userrole != "userext")
	{
		forbidden("role admin or userext is required. Your role: ".$user->userrole);
	}	
	if(!accessToNode($nodeid,$user->id))
	{
		forbidden("access denied");
	}	

	if($interval != 5 && $interval != 10 && $interval != 1 && $interval != 15)
	{
		badrequest("interval needs to be: 1, 5, 10 or 15");
	}
	
	$hostname = $db->real_escape_string($hostname);
	$groupname = $db->real_escape_string($groupname);
	if($viahost != false) 
	{
		$viahost = $db->real_escape_string($viahost);
		$poignore = true;
	}
	else
	{
		$poignore = false;
		$viahost = "unset";
	}	
	
	if($authpw != false)
	{
		$authpw = $db->real_escape_string($authpw);
	}
	else
	{
		$authpw = "";
	}
	
	$node = getNode($nodeid);

	if(!isPortOpen($hostname,$port,$poignore))
	{
		badrequest("cannot connect to $hostname on port $port");	
	}	
	
	$db->query("UPDATE nodes SET hostname = '$hostname', port = '$port', query_interval = '$interval', groupname = '$groupname', via_host = '$viahost', authpw = '$authpw' WHERE id = '$node->id'");
	if($db->affected_rows > 0)
	{
		if($interval!= $node->query_interval || $viahost != $node->via_host || $authpw != $node->authpw)
		{
			$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletejob/$node->id/$node->user_id");
			sleep(3);
			$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/queuejob/$node->id");
			if(trim($json) == "true")
			{
				$tpl->status = "ok";
				$tpl->msg = "Node updated and requeued";
				echo json_encode($tpl);
			}	
			else
			{
				badrequest("Query Interval changed but we failed to requeue. Try again?");
			}		
		}
		else
		{
			$tpl->status = "ok";
			$tpl->msg = "Node updated and requeued";
			echo json_encode($tpl);
		}
	}
	else
	{
		$tpl->status = "ok";
		echo json_encode($tpl);	
	}		
}

function api_listChecksByName($name)
{
	global $db;
	global $user;
	
	
	$name = $db->real_escape_string($name);
	
	if($user->userrole == "admin"){
		$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE lower(service_checks.check_name) like lower('$name%')");
	}
	else{
		if($user->userrole == "userext"){
			$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE service_checks.user_id = '$user->id' AND lower(service_checks.check_name) like lower('$name%')");
		}
		else{
			$usql = getUserGroupsSQL(false,"service_checks.","accessgroup");
			$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE (service_checks.user_id = '$user->id' OR ($usql)) AND lower(service_checks.check_name) like lower('$name%')");
			//echo "SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE service_checks.user_id = '$_SESSION[user_id]' OR ($usql)";
		}
	
	}
	if($db->affected_rows < 1)	{
		notFound("no results");
	}
	
	$tpl['checks'] = array();
	while($entry = $result->fetch_object())
	{
		$tpl['checks'][] = $entry;
	}
	
	$tpl['status'] = "ok";
	echo json_encode($tpl);
}

function api_listChecks()
{
	global $db;
	global $user;



	if($user->userrole == "admin"){
		$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id");
	}
	else{
		if($user->userrole == "userext"){
			$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE service_checks.user_id = '$user->id'");
		}
		else{
			$usql = getUserGroupsSQL(false,"service_checks.","accessgroup");
			$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE (service_checks.user_id = '$user->id' OR ($usql))");
		}

	}
	if($db->affected_rows < 1)	{
		notFound("no results");
	}

	$tpl['checks'] = array();
	while($entry = $result->fetch_object())
	{
		$tpl['checks'][] = $entry;
	}

	$tpl['status'] = "ok";
	echo json_encode($tpl);
}

function api_deleteCheck($checkid)
{
	global $db;
	global $user;
	
	$checkid = $db->real_escape_string($checkid);
	
	if($user->userrole == "admin"){
		$result = $db->query("DELETE FROM service_checks WHERE id = '$checkid'");
	}
	else{
		if($user->userrole == "userext"){
			$result = $db->query("DELETE FROM service_checks WHERE service_checks.user_id = '$user->id' AND id = '$checkid'");
		}
		else{
			$usql = getUserGroupsSQL(false,"service_checks.","accessgroup");
			$result = $db->query("DELETE FROM service_checks WHERE (service_checks.user_id = '$user->id' OR ($usql)) AND id = '$checkid'");
		}
	
	}
	
	$affected_rows = $db->affected_rows;
	if($affected_rows < 1){
		notFound("no results");
	}
	
	$tpl['status'] = "ok";
	echo json_encode($tpl);
}


function api_deleteNode($nodeid)
{
	
	global $db;
	global $user;
	
	if($user->userrole != "admin" && $user->userrole != "userext")
	{
		forbidden("role admin or userext is required. Your role: ".$user->userrole);
	}		
	
	if(!accessToNode($nodeid,$user->id))
	{
		forbidden("access denied");
	}
	
	
	$node = getNode($nodeid);
	$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletejob/$node->id/$node->user_id");
	if(trim($ret) == "true")
	{
		$db->query("DELETE FROM nodes WHERE id = $node->id");	
		if($db->affected_rows > 0)
		{
			// kill mongo collection for that node
			global $m;
			$colname = $node->user_id."_".$node->id;
			$dbname = MONGO_DB;
			$dbm = $m->$dbname;
			$dbm->$colname->drop();	
			
			// remove system snapshots
			$dbm = $m->selectDB(MONGO_DB_ESSENTIALS);
			$colname = $node->id."_ess";
			$dbm->$colname->drop();	
			
			// remove trackpkg
			$dbm = $m->selectDB(MONGO_DB_ESSENTIALS);
			$c = new MongoCollection($dbm, "trackpkg");
			$c->remove(array("node" => $node->id));			
				
			$tpl->status = "ok";
			echo json_encode($tpl);
		}	
		else
		{
			badrequest("node dequeued but cannot remove from database. try again later please");
		}
	}
	else
	{
		badrequest("unable to dequeue at the moment, try again later please");
	}
}

function api_addNode($hostname,$port,$interval,$groupname,$viahost=false,$authpw)
{
	global $db;
	global $user;
	
	if($user->userrole != "admin")
	{
		$niu = getNodeCountForUser($user->id);
		if($niu == $user->max_nodes || $niu > $user->max_nodes)
		{
			forbidden("maximum number of nodes ($niu) for your account reached.");
		}
	}
	
	if($interval != 5 && $interval != 10 && $interval != 1 && $interval != 15)
	{
		badrequest("interval needs to be: 1, 5, 10 or 15");
	}
	
	$hostname = $db->real_escape_string($hostname);
	$groupname = $db->real_escape_string($groupname);
	if($viahost != false) 
	{
		$viahost = $db->real_escape_string($viahost);
		$poignore = true;
	}
	else
	{
		$poignore = false;
		$viahost = "unset";
	}
	
	if($authpw != false)
	{
		$authpw = $db->real_escape_string($authpw);
	}
	else
	{
		$authpw = "";
	}
		
	if($user->userrole != "admin" && $user->userrole != "userext")
	{
		forbidden("role admin or userext is required. Your role: ".$user->userrole);
	}	
	
	$db->query("SELECT * FROM nodes WHERE hostname = '$hostname' AND port = $port AND user_id = '$user->id'");
	if($db->affected_rows > 0)
	{
		badrequest("We have already a node with this hostname associated to this user");
	}
	
	if(!isPortOpen($hostname,$port,$poignore))
	{
		badrequest("cannot connect to $hostname on port $port");	
	}
	
	$db->query("INSERT INTO nodes (user_id,hostname,port,query_interval,groupname,via_host,authpw) values ($user->id,'$hostname',$port,$interval,'$groupname','$viahost','$authpw')");
	$nodeId = $db->insert_id;
	if($nodeId < 1)
	{
		badrequest("unabel to store node informations, try again later");
	}
	$node = getNode($nodeId);
	$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/queuejob/$nodeId");
	echo json_encode($node);

}

function api_reloadPlugins($nodeid)
{
	global $user;
	if(accessToNode($nodeid,$user->id))
	{
		$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/$nodeid/loadplugins");	
		if(trim($ret) == "true")
		{
			$tpl->status = "ok";
			echo json_encode($tpl);
		}
		else
		{
			badrequest("unable to reload plugins. node available?");
		}	
	}
	else
	{
		forbidden("access denied");
	}
}

function api_deleteBucket($bid)
{
	global $db;
	global $user;	
	if(gotAccessToBucket($bid))
	{
		$bucket = returnBucket($bid);
		$db->query("DELETE FROM buckets WHERE id = '$bucket->id'");
		if($db->affected_rows > 0)
		{
			global $m;
			$dbm = $m->buckets;
			$colname = $bucket->statid;
			$dbm->$colname->drop();	
			$tpl->status = "ok";
			echo json_encode($tpl);
		}
		else
		{
			badrequest("unable to delete bucket");
		}		
	}
	else
	{
		forbidden("access to bucket denied");
	}	
}


function api_editBucket($bid,$graphname,$graphlabel,$groupname)
{
	global $db;
	global $user;	
	if(gotAccessToBucket($bid))
	{
		$bucket = returnBucket($bid);
		$graphname = $db->real_escape_string($graphname);
		$graphlabel = $db->real_escape_string($graphlabel);
		if($groupname != false)
		{
			$groupname = $db->real_escape_string($groupname);
		}
		else
		{
			$groupname = "";
		}		
		$db->query("UPDATE buckets SET statname = '$graphname', statlabel = '$graphlabel', groupname = '$groupname' WHERE user_id = '$user->id'");
		if($db->affected_rows > 0)
		{
			$bucket = returnBucket($bid);
			echo json_encode($bucket);
		}
		else
		{
			badrequest("unable to edit bucket, maybe no change?");	
		}
	}
	else
	{
		forbidden("access to bucket denied");
	}	
}

function api_addBucket($graphname,$graphlabel,$groupname=false)
{
	global $db;
	global $user;
	$statid = sha1(uniqid($user->id.time(),true));
	
	
	
	$graphname = $db->real_escape_string($graphname);
	$graphlabel = $db->real_escape_string($graphlabel);
	if($groupname != false)
	{
		$groupname = $db->real_escape_string($groupname);
	}
	else
	{
		$groupname = "";
	}
	$db->query("INSERT INTO buckets (user_id,statname,statlabel,statid,groupname) VALUES ($user->id,'$graphname','$graphlabel','$statid','$groupname')");
	//echo "INSERT INTO simplestats (user_id,statname,statlabel,statid,shareid) VALUES ($_SESSION[user_id],'$_POST[statname]','$_POST[statlabel]','$statid','$shareid')";
	if($db->affected_rows > 0)
	{

		global $m; 
		$dbm = $m->buckets;
		$colname = $statid;
		$collection = $dbm->$colname;
		$collection->ensureIndex(array("timestamp" => 1));
		$collection->ensureIndex(array("timestamp" => -1));
		$tpl->statid = $statid;
		$tpl->statlabel = $graphlabel;
		$tpl->statname = $graphname;
		$tpl->groupname = $groupname;
		echo json_encode($tpl);
	}
	else
	{
		badrequest("unable to create graph");
	}	
}

function api_getBucketData($bid)
{
	if(gotAccessToBucket($bid))
	{
		$bucket = returnBucket($bid);
		global $m; 
		$dbm = $m->buckets;
		$colname = $bucket->statid;
		$collection = $dbm->$colname;
		
		
		// check if we have data at all
		$res = $collection->find()->limit(1);
		
		
		if($res->count() < 1)
		{
			notFound("no data for bucket yet");
		}	
		
		if(!is_numeric($_REQUEST['start']))
		{
			$start = time() - 2629743;
			$end = time();
		}
		else
		{
			if(!is_numeric($_REQUEST['end']))
			{
				$end = time();
			}
			else
			{
				$end = (int)$_REQUEST['end'];
			}
			$start = (int)$_REQUEST['start'];
		}
		$res = $collection->find(array('timestamp' => array('$gt' => new MongoInt32($start), '$lt' => new MongoInt32($end))))->sort(array('timestamp' => 1));
		
		$graphdata = "";
		$r = array();	
		foreach($res as $avg)
		{
			unset($avg["_id"]);
			$r[] = $avg;
		}		
		echo json_encode($r);			
	}	
	else
	{
		forbidden("access denied or bucket not found");
	}	
}
function api_getBucket($bid)
{
	if(gotAccessToBucket($bid))
	{
		echo json_encode(returnBucket($bid));
	}	
	else
	{
		forbidden("access denied or bucket not found");
	}
}

function api_listBuckets()
{
	global $db;
	global $user;	

	if($user->userrole != "admin")
	{
		$and = " WHERE user_id = $user->id OR groupname = '$user->accessgroup'";
	}
	if($user->userrole == "userext")
	{
		$and = " WHERE user_id = $user->id";
	}
	$result = $db->query("SELECT buckets.*,users.username FROM buckets LEFT JOIN users ON buckets.user_id = users.id $and");
	if($db->affected_rows < 1)
	{
		notFound("no results");	
	}
	while($tpl = $result->fetch_object())	
	{
		$r[] = $tpl;	
	}
	echo json_encode($r);
}

function api_getChartData($nid,$plugin)
{
	global $db;
	global $user;
	if(!accessToNode($nid,$user->id))
	{
		forbidden("access to node denied");
	}
	$node = getNode($nid);
	$plugin = $db->real_escape_string($plugin);
	$db->query("SELECT * FROM node_plugins WHERE node_id = $nid AND pluginname = '$plugin'");
	if($db->affected_rows < 1)
	{
		notFound("plugin not found for node");
	}
	
	$json  = json_decode(file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/$nid/fetch/$plugin"));
	if(!$json)
	{
		notFound("Unable to locate node on MCD");
	}
	
    $period = "-1 month";
	
	foreach ($json as $g)
	{
		if(!is_numeric($_REQUEST['start']))
		{
			$q = getChartDataArray($nid,$plugin,$g->str_GraphName,$period);	
		}	
		else
		{
			if(!is_numeric($_REQUEST['end']))
			{
				$end = time();	
			}
			else
			{
				$end = $_REQUEST['end'];
			}
			$start = $_REQUEST['start'];
			$q = getChartDataArray($nid,$plugin,$g->str_GraphName,true,$start,$end);	
		}
		
		//$rt[$g->str_GraphName];
		$data = array();
		foreach($q as $item)
		{
			$time = "";
			$time = $item['recv'];
			$val  = $item['value'];
	
			$data[] = array("timestamp" => $time, "value" => $val);
		}
		$r[$g->str_GraphName] = $data;
	}
	echo json_encode($r);
}

function api_listNode($nid)
{
	global $user;
	$node = getNode($nid);
	if(!$node)
	{
		notFound("invalid nodeid specified");
	}
	
	if(!accessToNode($nid,$user->id))
	{
		forbidden("access to node denied");
	}
	
	$plugins = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/".$node->id."/plugins");
	$plugins = json_decode($plugins);
	
	$r->node = $node;
	$r->plugins = $plugins;
	
	echo json_encode($r);
}



function api_listNodes($search=false)
{
	global $user;
	global $db;
	if($search != false)
	{
		$where_a = " WHERE hostname LIKE '$search%' ";
		$where_u = " AND hostname LIKE '$search%'";	
	}
	
	if($user->userrole == "admin")
	{
		$result = $db->query("SELECT * FROM nodes $where_a ORDER BY groupname");	
	}
	elseif($user->userrole == "userext")
	{
		$result = $db->query("SELECT * FROM nodes WHERE user_id = '$user->id' $where_u ORDER BY groupname");	
	}
	else
	{
		$result = $db->query("SELECT * FROM nodes WHERE (".getUserGroupsSQL($user->id)." OR user_id = $user->id) $where_u");	
	}
	if($db->affected_rows > 0)
	{
		while($tpl = $result->fetch_object())
		{
			$r[] = $tpl;
		}
		echo json_encode($r);
	}	
	else
	{
		notFound("no nodes found.");	
	}	
}

function api_listNodesByGroup($group)
{
	global $db;
	global $user;	
	if($user->userrole == "admin")
	{
		$result = $db->query("SELECT * FROM nodes WHERE groupname = '$group'");		
	}
	elseif($user->userrole == "userext")
	{		
		$result = $db->query("SELECT * FROM nodes WHERE user_id = $user->id AND groupname = '$group'");	
	}
	else
	{
		$gallowed = getUserGroupsArray($user->id);
		if(!in_array($group, $gallowed))
		{
			forbidden("access denied");
		}
		$result = $db->query("SELECT * FROM nodes WHERE groupname = '$group'");			
	}	

	if($db->affected_rows < 1)
	{
		notFound("no nodes available");
	}
	$r = array();
	while($tpl = $result->fetch_object())
	{
		$r[] = $tpl;
		$tpl = null;
	}
	echo json_encode($r);	
}

function api_listGroups()
{
	global $db;
	global $user;
	
	if($user->userrole == "admin")
	{
		$result = $db->query("SELECT * FROM nodes GROUP BY groupname");
		if($db->affected_rows < 1)
		{
			notFound("no nodes available");
		}
		$r = array();
		while($tpl = $result->fetch_object())
		{
			$db->query("SELECT * FROM nodes WHERE groupname = '$tpl->groupname'");
			$t->group = $tpl->groupname;
			$t->nodes = $db->affected_rows;
			$r[] = $t;
			$t = null;
		}
		echo json_encode($r);
	}
	elseif($user->userrole == "userext")
	{
		$result = $db->query("SELECT * FROM nodes WHERE user_id = $user->id GROUP BY groupname");
		if($db->affected_rows < 1)
		{
			notFound("no nodes available");
		}
		$r = array();
		while($tpl = $result->fetch_object())
		{
			$db->query("SELECT * FROM nodes WHERE user_id = $user->id AND groupname = '$tpl->groupname'");
			$t->group = $tpl->groupname;
			$t->nodes = $db->affected_rows;
			$r[] = $t;
			$t = null;
		}
		echo json_encode($r);		
	}
	else
	{
		$result = $db->query("SELECT * FROM nodes WHERE (".getUserGroupsSQL($user->id)." OR user_id = $user->id) GROUP BY groupname");	
		if($db->affected_rows < 1)
			{
				notFound("no nodes available");
			}
			$r = array();
			while($tpl = $result->fetch_object())
			{
				$db->query("SELECT * FROM nodes WHERE groupname = '$tpl->groupname'");
				$t->group = $tpl->groupname;
				$t->nodes = $db->affected_rows;
				$r[] = $t;
				$t = null;
			}
			echo json_encode($r);				
	}
}

function notFound($msg)
{
	header("HTTP/1.0 404 Not Found");	
	echo json_encode(array("status" => 404,"msg" => $msg));
	die;
}

function forbidden($msg)
{
	header('HTTP/1.0 403 Forbidden');
	echo json_encode(array("status" => 403,"msg" => $msg));
	die;
}

function badrequest($msg)
{
	header('HTTP/1.0 400 Bad Request');
	echo json_encode(array("status" => 400,"msg" => $msg));
	die;
}

