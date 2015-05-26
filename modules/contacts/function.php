<?php


function editContactViaPost()
{
	global $db;
		$contact = returnContact($_GET['cid']);
		if($contact->user_id != $_SESSION['user_id'])
		{
			header("Location: /403.php");
			die;
		}
		

		$tpl = json_decode(json_encode($_POST), FALSE);
		
		$tpl->id = $contact->id;
		$_POST = secureArray($_POST);
		
		//print_r($_POST);
		// build schedule
		if(isset($_POST['s_mon_none']))	{
			$s_mon = "disabled";
		} else {
			$s_mon = $_POST['s_mon_from'].";".$_POST['s_mon_to'];
		}
		if(isset($_POST['s_tue_none']))	{
			$s_tue = "disabled";
		} else {
			$s_tue = $_POST['s_tue_from'].";".$_POST['s_tue_to'];
		}
		if(isset($_POST['s_wed_none']))	{
			$s_wed = "disabled";
		} else {
			$s_wed = $_POST['s_wed_from'].";".$_POST['s_wed_to'];
		}
		if(isset($_POST['s_thu_none']))	{
			$s_thu = "disabled";
		} else {
			$s_thu = $_POST['s_thu_from'].";".$_POST['s_thu_to'];
		}
		if(isset($_POST['s_fri_none']))	{
			$s_fri = "disabled";
		} else {
			$s_fri = $_POST['s_fri_from'].";".$_POST['s_fri_to'];
		}
		if(isset($_POST['s_sat_none']))	{
			$s_sat = "disabled";
		} else {
			$s_sat = $_POST['s_sat_from'].";".$_POST['s_sat_to'];
		}
		if(isset($_POST['s_sun_none']))	{
			$s_sun = "disabled";
		} else {
			$s_sun = $_POST['s_sun_from'].";".$_POST['s_sun_to'];
		}
		
		// check for errors
		$err = false;
		if(trim($_POST['contact_mobile_nr']) == "")
		{
			if(isset($_POST['sms_active']) || isset($_POST['tts_active']))
			{
				display_error("ERROR","<br />Phone Notifications activated but no valid mobile number given");
				include("templates/contacts/forms/edit.tpl.php");
				$err = true;
			}
		}

		if(trim($_POST['contact_callback']) == "")
		{
			if(isset($_POST['callback_active']))
			{
				display_error("ERROR","<br />JSON Callback activated but no callback url set");
				include("templates/contacts/forms/edit.tpl.php");
				$err = true;
			}
		}
		
		if(trim($_POST['pushover_key']) == "")
		{
			if(isset($_POST['pushover_active']))
			{
				display_error("ERROR","<br />Pushover activated but no user key set");
				include("templates/contacts/forms/edit.tpl.php");
				$err = true;
			}
		}		
		
		$callback_active = 0;
		$email_active = 0;
		$sms_active = 0;
		$tts_active = 0;
		$pushover_active = 0;
		if(isset($_POST['callback_active']))
		{
			$callback_active = 1;
		}
		if(isset($_POST['email_active']))
		{
			$email_active = 1;
		}		
		if(isset($_POST['sms_active']))
		{
			$sms_active = 1;
		}	
		if(isset($_POST['tts_active']))
		{
			$tts_active = 1;
		}
		if(isset($_POST['pushover_active']))
		{
			$pushover_active = 1;
		}					
		// TODO: check if mobile number is valid	
		
		// okey lets go
		if(!$err)
		{
			$db->query("UPDATE contacts SET contact_name = '$_POST[contact_name]',
			contact_email = '$_POST[contact_email]',
			contact_mobile_nr = '$_POST[contact_mobile_nr]', 
			contact_callback = '$_POST[contact_callback]', 
			pushover_key = '$_POST[pushover_key]',
			callback_active = '$callback_active', 
			email_active = '$email_active', 
			sms_active = '$sms_active', 
			tts_active = '$tts_active', 
			pushover_active = '$pushover_active', 
			s_mon = '$s_mon',
			s_tue = '$s_tue',
			s_wed = '$s_wed',
			s_thu = '$s_thu',
			s_fri = '$s_fri',
			s_sat = '$s_sat',
			s_sun = '$s_sun',
			timezone = '$_SESSION[timezone]' WHERE id = '$contact->id'");
			
			if($db->affected_rows > 0)
			{
				display_ok("OK","<br />Contact ".htmlspecialchars($_POST[contact_name])." edited and active.");	
				renderContactTable();	
			}
			else
			{
				display_error("SAVE ERROR","<br />There was a issue with the database operation. Please try again later or contact support if the problem persist. Maybe you did not change anything?");
				include("templates/contacts/forms/edit.tpl.php");
			}
		}		
		
}

function addContactViaPost()
{
	global $db;
	$_POST = secureArray($_POST);
		//print_r($_POST);
		// build schedule
		if(isset($_POST['s_mon_none']))	{
			$s_mon = "disabled";
		} else {
			$s_mon = $_POST['s_mon_from'].";".$_POST['s_mon_to'];
		}
		if(isset($_POST['s_tue_none']))	{
			$s_tue = "disabled";
		} else {
			$s_tue = $_POST['s_tue_from'].";".$_POST['s_tue_to'];
		}
		if(isset($_POST['s_wed_none']))	{
			$s_wed = "disabled";
		} else {
			$s_wed = $_POST['s_wed_from'].";".$_POST['s_wed_to'];
		}
		if(isset($_POST['s_thu_none']))	{
			$s_thu = "disabled";
		} else {
			$s_thu = $_POST['s_thu_from'].";".$_POST['s_thu_to'];
		}
		if(isset($_POST['s_fri_none']))	{
			$s_fri = "disabled";
		} else {
			$s_fri = $_POST['s_fri_from'].";".$_POST['s_fri_to'];
		}
		if(isset($_POST['s_sat_none']))	{
			$s_sat = "disabled";
		} else {
			$s_sat = $_POST['s_sat_from'].";".$_POST['s_sat_to'];
		}
		if(isset($_POST['s_sun_none']))	{
			$s_sun = "disabled";
		} else {
			$s_sun = $_POST['s_sun_from'].";".$_POST['s_sun_to'];
		}
		
		// check for errors
		$err = false;
		if(trim($_POST['contact_mobile_nr']) == "")
		{
			if(isset($_POST['sms_active']) || isset($_POST['tts_active']))
			{
				display_error("ERROR","<br />Phone Notifications activated but no valid mobile number given");
				include("templates/contacts/forms/add.tpl.php");
				$err = true;
			}
		}

		if(trim($_POST['contact_callback']) == "")
		{
			if(isset($_POST['callback_active']))
			{
				display_error("ERROR","<br />JSON Callback activated but no callback url set");
				include("templates/contacts/forms/add.tpl.php");
				$err = true;
			}
		}
		
		if(trim($_POST['pushover_key']) == "")
		{
			if(isset($_POST['pushover_active']))
			{
				display_error("ERROR","<br />Pushover activated but no user key set");
				include("templates/contacts/forms/add.tpl.php");
				$err = true;
			}
		}		
		
		$callback_active = 0;
		$email_active = 0;
		$sms_active = 0;
		$tts_active = 0;
		$pushover_active = 0;
		if(isset($_POST['callback_active']))
		{
			$callback_active = 1;
		}
		if(isset($_POST['email_active']))
		{
			$email_active = 1;
		}		
		if(isset($_POST['sms_active']))
		{
			$sms_active = 1;
		}	
		if(isset($_POST['tts_active']))
		{
			$tts_active = 1;
		}
		if(isset($_POST['pushover_active']))
		{
			$pushover_active = 1;
		}					
		// TODO: check if mobile number is valid
	
		// okey lets go
		if(!$err)
		{
			$db->query("INSERT INTO contacts (contact_name,contact_email,contact_mobile_nr, contact_callback, pushover_key, user_id, callback_active, email_active, sms_active, tts_active, pushover_active, s_mon,s_tue,s_wed,s_thu,s_fri,s_sat,s_sun,timezone)
			VALUES (
			'$_POST[contact_name]',
			'$_POST[contact_email]',
			'$_POST[contact_mobile_nr]',
			'$_POST[contact_callback]',
			'$_POST[pushover_key]',	
			'$_SESSION[user_id]',
			'$callback_active',
			'$email_active',
			'$sms_active',
			'$tts_active',	
			'$pushover_active',
			'$s_mon',
			'$s_tue',
			'$s_wed',
			'$s_thu',	
			'$s_fri',
			'$s_sat',
			'$s_sun',
			'$_SESSION[timezone]')");	
			
			
			if($db->affected_rows > 0)
			{
				display_ok("OK","<br />Contact ".htmlspecialchars($_POST[contact_name])." created and active.");	
				renderContactTable();		
			}
			else
			{
				display_error("SAVE ERROR","<br />There was a issue with the database operation. Please try again later or contact support if the problem persist");
				include("templates/contacts/forms/add.tpl.php");
			}
		}
}

function renderContactTable()
{
	global $db;
	include("templates/contacts/tables/tbl.contacts.head.tpl.php");
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT * FROM contacts");	
	}
	else
	{
		$result = $db->query("SELECT * FROM contacts WHERE user_id = '$_SESSION[user_id]'");	
	}
	
	while($tpl = $result->fetch_object())
	{
		include("templates/contacts/tables/tbl.contacts.item.tpl.php");
	}
	include("templates/contacts/tables/tbl.generic.body.tpl.php");
}


function getAssignedAlertsForContact($cid)
{
	global $db;
	$db->query("SELECT * FROM alert_contacts WHERE contact_id = $cid");
	return $db->affected_rows;
}

function getAssignedCheckAlertsForContact($cid)
{
	global $db;
	$db->query("SELECT * FROM notifications WHERE contact_id = $cid");
	return $db->affected_rows;	
}

function returnContact($contact_id)
{
	global $db;
	$result = $db->query("SELECT * FROM contacts WHERE id = '$contact_id'");
	if($db->affected_rows < 1)
	{
		return false;
	}
	else
	{
		$tpl = $result->fetch_object();
		return $tpl;	
	}
}


// WOHOOO CLOSE YOUR EYES, DEEP MEGASHIT INCOMING!
function renderTimeDropDown($name,$default)
{
	if($default == "00:00")
	{
		$s0 = " selected";
	}
	elseif($default == "01:00")
	{
		$s1 = " selected";
	}
	elseif($default == "02:00")
	{
		$s2 = " selected";
	}	
	elseif($default == "03:00")
	{
		$s3 = " selected";
	}
	elseif($default == "04:00")
	{
		$s4 = " selected";
	}	
	elseif($default == "05:00")
	{
		$s5 = " selected";
	}
	elseif($default == "06:00")
	{
		$s6 = " selected";
	}		
	elseif($default == "07:00")
	{
		$s7 = " selected";
	}
	elseif($default == "08:00")
	{
		$s8 = " selected";
	}	
	elseif($default == "09:00")
	{
		$s9 = " selected";
	}
	elseif($default == "10:00")
	{
		$s10 = " selected";
	}	
	elseif($default == "11:00")
	{
		$s11 = " selected";
	}
	elseif($default == "12:00")
	{
		$s12 = " selected";
	}	
	elseif($default == "13:00")
	{
		$s13 = " selected";
	}
	elseif($default == "14:00")
	{
		$s14 = " selected";
	}	
	elseif($default == "15:00")
	{
		$s15 = " selected";
	}
	elseif($default == "16:00")
	{
		$s16 = " selected";
	}		
	elseif($default == "17:00")
	{
		$s17 = " selected";
	}
	elseif($default == "18:00")
	{
		$s18 = " selected";
	}	
	elseif($default == "19:00")
	{
		$s19 = " selected";
	}
	elseif($default == "20:00")
	{
		$s20 = " selected";
	}
	elseif($default == "21:00")
	{
		$s21 = " selected";
	}
	elseif($default == "22:00")
	{
		$s22 = " selected";
	}	
	elseif($default == "23:00")
	{
		$s23 = " selected";
	}
	elseif($default == "24:00")
	{
		$s24 = " selected";
	}				
	echo '
	<select class="form-control" style="display: inline; width: 250px" id="'.$name.'" name="'.$name.'">
		<option value="00:00"'.$s0.'>00:00</option>
		<option value="01:00"'.$s1.'>01:00</option>
		<option value="02:00"'.$s2.'>02:00</option>
		<option value="03:00"'.$s3.'>03:00</option>
		<option value="04:00"'.$s4.'>04:00</option>
		<option value="05:00"'.$s5.'>05:00</option>
		<option value="06:00"'.$s6.'>06:00</option>
		<option value="07:00"'.$s7.'>07:00</option>
		<option value="08:00"'.$s8.'>08:00</option>
		<option value="09:00"'.$s9.'>09:00</option>
		<option value="10:00"'.$s10.'>10:00</option>
		<option value="11:00"'.$s11.'>11:00</option>	
		<option value="12:00"'.$s12.'>12:00</option>
		<option value="13:00"'.$s13.'>13:00</option>
		<option value="14:00"'.$s14.'>14:00</option>
		<option value="15:00"'.$s15.'>15:00</option>
		<option value="16:00"'.$s16.'>16:00</option>
		<option value="17:00"'.$s17.'>17:00</option>
		<option value="18:00"'.$s18.'>18:00</option>
		<option value="19:00"'.$s19.'>19:00</option>
		<option value="20:00"'.$s20.'>20:00</option>
		<option value="21:00"'.$s21.'>21:00</option>
		<option value="22:00"'.$s22.'>22:00</option>
		<option value="23:00"'.$s23.'>23:00</option>
		<option value="24:00"'.$s24.'>24:00</option>															
	</select>
	';
}

function getAlertsForNodeAndPlugin($nid,$plugin)
{
	global $db;
	if($_SESSION['role'] != "admin")
	{
		$and = " AND user_id = '$_SESSION[user_id]'";
	}	
	$plugin = $db->real_escape_string($plugin);	
	$db->query("SELECT id FROM alerts WHERE node_id = '$nid' AND pluginname='$plugin' $and");
	return $db->affected_rows;
}

function getAlertsForNode($nid)
{
	global $db;	
	if($_SESSION['role'] == "admin")
	{
		$db->query("SELECT id FROM alerts WHERE node_id = '$nid'");	
	}
	else
	{
		$db->query("SELECT id FROM alerts WHERE node_id = '$nid' AND user_id = '$_SESSION[user_id]'");	
	}
	
	return $db->affected_rows;
}

function getContactCountForUser($uid,$admin=false)
{
	global $db;
	if($admin)
	{
		$db->query("SELECT id FROM contacts");		
	}
	else
	{
		$db->query("SELECT id FROM contacts WHERE user_id = '$uid'");	
	}
	
	return $db->affected_rows;
}


function getContact($cid)
{
	global $db;
	$result = $db->query("SELECT * FROM contacts WHERE id = '$cid'");
	return $result->fetch_object();
}

function renderContactDropDown($admin=false,$aid=false)
{
	global $db;
	echo '<select name="contacts[]" id="contacts" multiple class="select2" style="width: 100%">';
	if($admin)
	{
		$result = $db->query("SELECT contacts.*,users.username FROM contacts LEFT JOIN users ON contacts.user_id = users.id");	
	}
	else
	{
		$result = $db->query("SELECT * FROM contacts WHERE user_id = '$_SESSION[user_id]'");	
	}
	
	while($tpl = $result->fetch_object())
	{
		if($admin)
		{
			$un = '['.htmlspecialchars($tpl->username).'] ';	
		}
		if($aid != false)
		{
			$db->query("SELECT * FROM alert_contacts WHERE alert_id = $aid AND contact_id = $tpl->id");
			if($db->affected_rows > 0)
			{
				$sel = ' selected';
			}
			else
			{
				$sel = "";
			}
		}
		echo '<option value="'.$tpl->id.'"'.$sel.'>'.$un.htmlspecialchars($tpl->contact_name).'</option>';
	}
	echo '</select>';
}

function renderContactDropDownForCheckEdit($cid)
{
	
	global $db;
	echo '<select name="contacts[]" id="contacts" multiple class="select2" style="width: 100%">';
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT contacts.*,users.username FROM contacts LEFT JOIN users ON contacts.user_id = users.id");	
	}
	else
	{
		$result = $db->query("SELECT * FROM contacts WHERE user_id = '$_SESSION[user_id]'");	
	}
	
	$carry = getContactArrayForCheck($cid);
	while($tpl = $result->fetch_object())
	{
		
		if(in_array($tpl->id,$carry))
		{
			$sel = ' selected';
		}
		else
		{
			$sel = "";
		}
		
		echo '<option value="'.$tpl->id.'"'.$sel.'>'.$un.htmlspecialchars($tpl->contact_name).'</option>';
	}
	echo '</select>';	
}

function getContactArrayForCheck($cid)
{
	$ret = array();
	global $db;
	$result = $db->query("SELECT notifications.*,contacts.user_id FROM `notifications` LEFT JOIN contacts ON notifications.contact_id = contacts.id WHERE check_id = $cid");
	while($tpl = $result->fetch_object())
	{
		$ret[] = $tpl->contact_id;
	}
	return $ret;
}	
?>
