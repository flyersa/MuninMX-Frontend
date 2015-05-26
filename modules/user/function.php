<?php

function isLoggedIn()
{
	if($_SESSION['login'])
	{
		return true;
	}
	else
	{
		return false;
	}
}

function getUserObject($user_id)
{
	global $db;
	$result = $db -> query("SELECT * FROM users WHERE id = '$user_id'");
	return $result -> fetch_object();
}

function getCustomsCount($user_id)
{
	global $db;
	$db->query("SELECT * FROM plugins_custom_interval WHERE user_id = '$user_id'");
	return $db->affected_rows;
}

function logIn($username,$password)
{
	global $db;
	$pass = sha1($password);
	$email = $db->real_escape_string($username);
	$result = $db->query("SELECT * FROM users WHERE username = '$username' AND password = '$pass'");
	if($db->affected_rows < 1)
	{
		if(CROWD_ADMIN_FALLBACK == true)
		{
			return logInAdminViaCrowd($username,$password);	
		}
		else
		{
			return false;	
		}
	}
	else
	{
		$tpl = $result->fetch_object();
		$uip = getUserIP();
		$db->query("UPDATE users SET last_login = NOW(), last_login_ip = '$uip' WHERE id = $tpl->id");
	    session_start();
	    $_SESSION['login'] = true;
	    $_SESSION['username'] = htmlspecialchars($tpl->username);
	    $_SESSION['user_id'] = $tpl->id;
		$_SESSION['role'] = $tpl->userrole;
		$_SESSION['accessgroup'] = $tpl->accessgroup;
	    setcookie("login", "yes");	
		setcookie("random",uniqid(microtime()));	
		return $tpl;		
	}
	
}

function logInAdminViaCrowd($username,$password)
{
	$call = CROWD_PATH . "?username=" . $username;
	$data = array("value" => $password);
	$data_string = json_encode($data);                                                                                   
	$ch = curl_init($call);
	curl_setopt($ch, CURLOPT_USERPWD, CROWD_APPNAME.':'.CROWD_APPPASS);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);        
	$result = curl_exec($ch);
	$result = json_decode($result);	
	if(!isset($result->active))
	{
		return false;
	}
	else
	{
		global $db;
		$uip = getUserIP();
		$db->query("UPDATE users SET last_login = NOW(), last_login_ip = '$uip' WHERE id = ".CROWD_MAPUID);
	    session_start();		
	    $_SESSION['login'] = true;
	    $_SESSION['username'] = htmlspecialchars($result->{'display-name'});
	    $_SESSION['user_id'] = CROWD_MAPUID;
		$_SESSION['role'] = "admin";
		$_SESSION['viacrowd'] = true;
	    setcookie("login", "yes");	
		setcookie("random",uniqid(microtime()));	
		$tpl->id = CROWD_MAPUID;
		$tpl->username = $_SESSION['username'];
		$tpl->email = $result->email;
		$tpl->userrole = "admin";
		return $tpl;
	}
}


function loginAs($username,$key)
{
	global $db;
	$username = $db->real_escape_string($username);
	$key = $db->real_escape_string($key);
	
	$result = $db->query("SELECT * FROM users WHERE username = '$username' AND autologinkey = '$key'");
	if($db->affected_rows < 1)
	{
		return false;
	}
	else
	{
		$tpl = $result->fetch_object();
		if($tpl->autologinkey == "unset" || $tpl->autologinkey == "")
		{
			return false;
		}		
		$uip = getUserIP();
		$db->query("UPDATE users SET last_login = NOW(), last_login_ip = '$uip' WHERE id = $tpl->id");
	    session_start();
	    $_SESSION['login'] = true;
	    $_SESSION['username'] = htmlspecialchars($tpl->username);
	    $_SESSION['user_id'] = $tpl->id;
		$_SESSION['role'] = $tpl->userrole;
		$_SESSION['accessgroup'] = $tpl->accessgroup;
	    setcookie("login", "yes");	
		setcookie("random",uniqid(microtime()));	
		return true;	
	}
		
}

function getGroupOptions($selected)
{
	global $db;
	$given = explode(",",$selected);
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT * FROM nodes GROUP BY groupname");
	}
	elseif($_SESSION['role'] == "userext")
	{
		$result = $db->query("SELECT * FROM nodes WHERE user_id = '$_SESSION[user_id]' GROUP BY groupname");
	}
	
	if($_SESSION['role'] == "admin" || $_SESSION['role'] == "userext")
	{
		while($tpl = $result->fetch_object())
		{
			if(in_array($tpl->groupname,$given))
			{
				$sv = "selected";
			}	
			else
			{
				$sv = "";
			}
			$r.='<option value="'.$tpl->groupname.'" '.$sv.'>'.$tpl->groupname.'</option>';
		}
		return $r;
	}
	else
	{
		$user = getUserObject($_SESSION['user_id']);
		$groups = explode(",",$user->accessgroup);
		foreach($groups as $group)
		{
			if(in_array($group,$given))
			{
				$sv = "selected";
			}	
			else
			{
				$sv = "";
			}
			$r.='<option value="'.$group.'" '.$sv.'>'.$group.'</option>';	
		}
		return $r;
	}
	
}

function getGroupOptionsForUser($selected)
{
	global $db;
	$given = explode(",",$selected);
	$result = $db->query("SELECT * FROM nodes GROUP BY groupname");
	while($tpl = $result->fetch_object())
	{
		if(in_array($tpl->groupname,$given))
		{
			$sv = "selected";
		}	
		else
		{
			$sv = "";
		}
		$r.='<option value="'.$tpl->groupname.'" '.$sv.'>'.$tpl->groupname.'</option>';
	}
	return $r;
}

function renderUserTable()
{
	global $db;
	include("templates/user/tables/tableHead.users.tpl.php");
	/*
		<th>Username</th>
	   <th>Role</th>
		<th>E-Mail</th>
		<th>Access Groups</th>
		<th>Last Login</th>
		<th>Last Login IP</th>
	 */
	 $result = $db->query("SELECT * FROM users");
	 while($tpl = $result->fetch_object())
	 {
		echo '
		<tr>
			<td><a href="users.php?username='.$tpl->username.'">'.$tpl->username.'</a></td>
			<td>'.$tpl->userrole.'</td>
			<td>'.$tpl->email.'</td>
			<td>'.$tpl->accessgroup.'</td>
			<td>'.$tpl->last_login.'</td>
			<td>'.$tpl->last_login_ip.'</td>
		</tr>	  
		';				 	
	 }
	 include("templates/core/tableEnd.tpl.php");
}

function renderUserSettingTable()
{
	$user = getUserObject($_SESSION['user_id']);
	include("templates/user/tables/tableHead.settings.tpl.php");
	echo '<tr>';
	echo '<td>'.htmlspecialchars($user->username).'</td>';
	echo '<td>'.strtoupper($user->userrole).'</td>';
	echo '<td>'.getNodeCountForUser($user->id).' / '.$user->max_nodes.'</td>';
	echo '<td>'.getCustomsCount($user->id).' / '.$user->max_customs.'</td>';
	echo '<td>'.$user->sms_tickets.'</td>';
	echo '<td>'.$user->tts_tickets.'</td>';
	echo '<tr/>';
	include("templates/core/tableEnd.tpl.php");
	echo '<br /><div class="well">To change your plan, upgrade sms/tts tickets or for questions please visit <a href="'.OPERATOR_URL.'">'.htmlspecialchars(OPERATOR_NAME).'</a></div>';
	
}
?>