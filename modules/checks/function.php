<?php

function checkGotDowntimeDurations($cid)
{
	global $db;
	$db->query("SELECT * FROM `downtimes_durations` WHERE check_id = $cid LIMIT 1");
	if($db->affected_rows > 0)
	{
		return true;
	}
	return false;
}

function renderDowntimes($cid)
{
	global $db;
	$result = $db->query("SELECT * FROM downtimes_durations WHERE check_id = $cid");
	if($db->affected_rows > 0)
	{
		while($tpl = $result->fetch_object())
		{
			$bands[] = $tpl;	
		}	
	}


	// render plotBands
	if(sizeof($bands) > 0)
	{

		//echo ",";	
		
		echo "
		  plotBands: [";
		foreach($bands as $band)
		{
			
			$shorttext = "Downtime";
			
			$start = $band->down_at * 1000;
			$end = $band->up_at * 1000;
			echo "
			  {
		    color: 'red', 
		    from: '".$start."', 
		    to: '".$end."',
		    zIndex: 3,
			label: { 
			    text: '".$shorttext."',
                    verticalAlign: 'top',
                    rotation: 90,
                    x: 10,
                    textAlign: 'middle',
			  },
 			  events: {
                    click: function (e) {
                        console.log('todo');
              	},
              }			  		      
		  	},	
			";
		}
		echo "
		  ],		
		";			
	}
		
}

function renderCheckAlertTableForContact($cid)
{
	global $db;
	include("templates/checks/tables/contacts/alert.table.head.tpl.php");	
	
	$result = $db->query("SELECT notifications.check_id,contacts.user_id,service_checks.*,check_types.check_name as check_desc FROM notifications LEFT JOIN service_checks on notifications.check_id = service_checks.id RIGHT JOIN contacts ON notifications.contact_id = contacts.id INNER JOIN check_types ON service_checks.check_type = check_types.id WHERE notifications.contact_id = $cid");
	//echo "SELECT alerts.*,nodes.hostname FROM alerts LEFT JOIN nodes ON alerts.node_id = nodes.id $filter $where";
	while($tpl = $result->fetch_object())
	{
		
		include("templates/checks/tables/contacts/alert.table.item.tpl.php");
	}
	include("templates/checks/tables/contacts/alert.table.end.tpl.php");	
}


function getNotificationResult($cid)
{
	global $db;
	$result = $db->query("SELECT *,UNIX_TIMESTAMP(created_at) as unixtime FROM `check_notification_log` WHERE cid = '$cid' ORDER BY created_at DESC");
	if($db->affected_rows > 0)
	{
		return $result;
	}
	else
	{
		return false;
	}
}

function getCurrentCheckCount($user_id)
{
	global $db;
	$db->query("SELECT id FROM service_checks WHERE user_id = '$user_id'");
	return $db->affected_rows;
}

function getCheckUptimeOld($cid,$user_id,$days)
{
	global $m; 
	$dbname = MONGO_DB_CHECKS;
	$dbm = $m->$dbname;
	$colname = $user_id."cid".$cid;
	$collection = $dbm->$colname;
	$start = strtotime("-$days day", time());
	

	$res = $collection->find(array('cid' => new MongoInt32($cid), 'time' => array('$gt' => $start), 'status' => array('$gt' => new MongoInt32(1))));	
	
	$failcount = $res->count();
	if($failcount == 0)
	{
		return "100 %";
	}


	$res = $collection->find(array('cid' => new MongoInt32($cid), 'time' => array('$gt' => $start), 'status' => array('$lt' => new MongoInt32(2))));
	
	$okcount = $res->count();
	
	$guptime = getUptime($failcount,$okcount) ;
	if($guptime == "100.00" ) { $guptime = "100"; };
	return $guptime . " %";
}

// function to calculate the uptime ratio in percentage
function getUptime($failcount,$okcount)
{
    $ratio=($okcount*100)/($failcount+$okcount);
    return number_format($ratio, 2, '.', ' ');
}

function renderServiceCheckTable($contact=false)
{
	global $db;
	include("templates/checks/tables/tbl.checks.head.tpl.php");
	if($contact != false)
	{
		$result = $db->query("SELECT notifications.id as nid,service_checks.*,check_types.check_name as check_desc_name FROM `notifications` LEFT JOIN service_checks ON check_id = service_checks.id LEFT JOIN check_types ON service_checks.check_type = check_types.id WHERE contact_id = $contact");	
	}
	else
	{
		if($_SESSION['role'] == "admin")
		{
			$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id");	
		}
		else
		{
			if($_SESSION['role'] == "userext")
			{
				$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE service_checks.user_id = '$_SESSION[user_id]'");		
			}	
			else
			{
				$usql = getUserGroupsSQL(false,"service_checks.","accessgroup");
				$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE service_checks.user_id = '$_SESSION[user_id]' OR ($usql)");
				//echo "SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE service_checks.user_id = '$_SESSION[user_id]' OR ($usql)";		
			}
		}
		
	}
	while($tpl = $result->fetch_object())
	{
		include("templates/checks/tables/tbl.checks.item.tpl.php");
	}
	include("templates/checks/tables/tbl.generic.body.tpl.php");
}

function accessToCheck($cid)
{
	global $db;
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE service_checks.id = '$cid'");	
		if($db->affected_rows > 0)
		{
			return true;
		}
	}
	else
	{
		if($_SESSION['role'] == "userext")
		{
			$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE service_checks.user_id = '$_SESSION[user_id]' AND service_checks.id = '$cid'");
			if($db->affected_rows > 0)
			{
				return true;
			}					
		}	
		else
		{
			$usql = getUserGroupsSQL(false,"service_checks.","accessgroup");
			$result = $db->query("SELECT service_checks.*,check_types.check_name as check_desc_name,users.username FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id INNER JOIN users ON service_checks.user_id = users.id WHERE service_checks.id = '$cid' AND ($usql)");
			if($db->affected_rows > 0)
			{
				return true;
			}					
		}
	}	
	return false;
}

function checkGotErrors($cid)
{
	global $m; 
	$check = returnServiceCheck($cid);
	$dbname = MONGO_DB_CHECKS;
	$dbm = $m->$dbname;
	$user_id = $check->user_id;
	$colname = $user_id."cid".$cid;
	$collection = $dbm->$colname;	
	$res = $collection->find(array('cid' => new MongoInt32($cid), 'status' => array('$gt' => 1)))->limit(10);
	if($res->count() > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
			
}

function isServiceUP($cid,$location=false)
{
	$check = returnServiceCheck($cid);
	if(!$check)
	{
		return false;
	}
	global $m; 
	$dbname = MONGO_DB_CHECKS;
	$dbm = $m->$dbname;
	$user_id = $check->user_id;
	$colname = $user_id."cid".$cid;
	$collection = $dbm->$colname;	
	if($location == false)
	{
		$res = $collection->find(array('cid' => new MongoInt32($cid)))->sort(array('time' => -1))->limit(10);
	}
	else
	{
		$res = $collection->find(array('cid' => new MongoInt32($cid), 'location' => "$location"))->sort(array('time' => -1))->limit(10);	
	}
	$ok = 0;
	$fail = 0;
	foreach($res as $obj)
	{
		if($obj['status'] < 2)
		{
			$ok++;
		}
		else
		{
			$fail++;
		}
	}
	if($fail == 0)
	{
		return '<a class="btn btn-green" rel="tooltip" data-placement="top" data-original-title="Last 10 Results all OK" style="width: 50px">UP</a>';
	}
	
	if($fail > 2)
	{
		return '<a class="btn btn-red" rel="tooltip" data-placement="top" data-original-title="More then 3 of the last results failed" style="width: 50px">DOWN</a>';
	}
	else
	{
		return '<a class="btn btn-gold" rel="tooltip" data-placement="top" data-original-title="at least 2 of 10 last results failed" style="width: 50px">WARNING</a>';
	}
}

function getAllUserChecksAsExplodeString($user_id)
{
	global $db;
	$result = $db->query("SELECT * FROM service_checks WHERE user_id = $user_id");
	$r = "";
	while($tpl = $result->fetch_object())
	{
		$r.= $tpl->id . ",";
	}
	$r = substr($r,0,-1);
	return $r;
}

function getMyServiceCheckOptions($selected=false)
{
	$uid = $_SESSION['user_id'];
	global $db;
	$result = $db->query("SELECT service_checks.*,check_types.check_Name AS check_desc FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id WHERE service_checks.user_id = '$uid'");
	$r = "";
	
	if($selected != false)
	{
		$sa = explode(",",$selected);
	}
	
	while($tpl = $result->fetch_object())
	{
		$sv = "";
		if($selected != false)
		{
			if(in_array($tpl->id, $sa))
			{
				$sv = " selected";
			}	
		}
		else
		{
			$sv = "";
		}
		$r.= '<option value="'.$tpl->id.'" '.$sv.'>'.htmlspecialchars($tpl->check_name).' - '.$tpl->check_desc.'</option>';
	}	
	return $r;
}

function checkSupportsTrace($ctype)
{
	switch($ctype)
	{
		case 1:
			return true;
			break;
		case 2:
			return true;
			break;
		default: 
			return false;
			break;
	}
}

function getMyTagOptions()
{
	$uid = $_SESSION['user_id'];
	// "red", "green", "blue"
	global $db;
	$result = $db->query("SELECT * FROM service_check_tags WHERE user_id = $uid GROUP BY tagname");
	if($db->affected_rows > 0)
	{
		$r = "";
		while($tpl = $result->fetch_object())
		{
			$r.='<option value="'.htmlspecialchars($tpl->tagname).'">'.htmlspecialchars($tpl->tagname).'</option>';
		}	
		return $r;
		
	}
	else
	{
		return "";
	}	
}

function getMyTagList()
{
	$uid = $_SESSION['user_id'];
	// "red", "green", "blue"
	global $db;
	$result = $db->query("SELECT * FROM service_check_tags WHERE user_id = $uid GROUP BY tagname");
	if($db->affected_rows > 0)
	{
		$r = "";
		while($tpl = $result->fetch_object())
		{
			$r.='"'.htmlspecialchars($tpl->tagname).'",';
		}	
		$r = substr($r,0,-1);
		return $r;
		
	}
	else
	{
		return "";
	}
}

function getTagsForCheck($cid)
{
	global $db;
	$result = $db->query("SELECT * FROM service_check_tags WHERE check_id = $cid GROUP BY tagname");
	if($db->affected_rows > 0)
	{
		$r = "";
		while($tpl = $result->fetch_object())
		{
			$r.= htmlspecialchars($tpl->tagname).",";
		}	
		$r = substr($r,0,-1);
		return $r;
		
	}
	else
	{
		return "";
	}	
}

function postCheckToJson($arr, $userid)
{
	
	if(!isset($arr['param']))
	{
		$arr['param'] = array();	
	} 
	// format json for check_http if given
	if($arr['command'] == "check_http")
	{
		$url = parse_url($arr['uri']);
		$arr['param']['H'] = $url['host'];
		
		if($url['scheme'] == "https")
		{
			$arr['param']['S'] = " ";	
		}
		
		if(isset($url['port']))
		{
			$arr['param']['p'] = $url['port'];
		}
		
		if(isset($url['path']))
		{
			$arr['param']['u'] = $url['path'];
		}
		
		if($arr['user'])
		{
			$arr['param']['a'] = $arr['user'].":".$arr['pass'];
		}
		$arr['uri'] = "";
	}
	
	
	unset($arr['_csrf_protect_token']);
	if($arr['nonearg'])
	{
		//$arr['nonearg'] = escapeshellarg($arr['nonearg']);
	}
	
	$i = 0;
	$keys = array_keys($arr['param']);
	foreach($arr['param'] as $params)
	{
		//print_r($params);
		//echo $keys[$i];
		
		$k = $keys[$i];
		$e = $params;
		if($e == "")
		{
			$i++;
			continue;
		}
		$far[] = "-$k|##|$e";
		$i++;
	}
	
	$arr['user_id'] = $userid?$userid:$_SESSION['user_id'];
	//print_r($far);
	$arr['param'] = $far;
	
	//return json_encode(array_filter($arr,'strlen'));
	return json_encode(array_filter($arr,zeroFilter));
}

function zeroFilter($var){
  return ($var !== NULL && $var !== FALSE && $var !== '');
}

function returnServiceCheck($cid)
{
	global $db;
	$result = $db->query("SELECT service_checks.*,check_types.check_Name AS check_desc FROM service_checks LEFT JOIN check_types ON service_checks.check_type = check_types.id WHERE service_checks.id = '$cid'");
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

function isContactForCheck($contact_id,$cid)
{
	global $db;
	$db->query("SELECT * FROM notifications WHERE check_id = $cid AND contact_id = $contact_id");
	if($db->affected_rows > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}


function bodyPost($url,$data)
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,            $url );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($ch, CURLOPT_POST,           1 );
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,     $data ); 
	curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 
	
	$result=curl_exec ($ch);
	$info = curl_getinfo($ch);
	return $info['http_code'];
}


function cvdQueueCheck($cid)
{
	global $db;
	$check = returnServiceCheck($cid);
	if(!$check)
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode,debugtxt) VALUES ('$cid',1,'queue','check not found, returnServiceCheck was false')");	
		return false;
	}
	$posturi = CVD_URI."/check/queue/$cid";
	if(bodyPost($posturi,$check->json) == 200)
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode) VALUES ('$cid',0,'queue')");	
		return true;
	}
	else 
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode,debugtxt) VALUES ('$cid',1,'queue','response code was not 200 to CVD')");	
		return false;
	}
}

function cvdDeleteCheck($cid)
{
	global $db;
	$check = returnServiceCheck($cid);
	if(!$check)
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode,debugtxt) VALUES ('$cid',1,'delete','check not found, returnServiceCheck was false')");	
		return false;	
	}
	$posturi = CVD_URI."/check/dequeue/$cid";
	if(bodyPost($posturi,$check->json) == 200)
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode) VALUES ('$cid',0,'delete')");	
		return true;
	}
	else 
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode,debugtxt) VALUES ('$cid',1,'delete','response code was not 200 to CVD')");	
		return false;
	}
}

function cvdPauseCheck($cid)
{
	global $db;
	$check = returnServiceCheck($cid);
	if(!$check)
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode,debugtxt) VALUES ('$cid',1,'pause','check not found, returnServiceCheck was false')");	
		return false;	
	}
	$posturi = CVD_URI."/check/pause/$cid";
	if(bodyPost($posturi,$check->json) == 200)
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode) VALUES ('$cid',0,'delete')");	
		return true;
	}
	else 
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode,debugtxt) VALUES ('$cid',1,'pause','response code was not 200 to CVD')");	
		return false;
	}
}

function cvdContinueCheck($cid)
{
	global $db;
	$check = returnServiceCheck($cid);
	if(!$check)
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode,debugtxt) VALUES ('$cid',1,'continue','check not found, returnServiceCheck was false')");	
		return false;	
	}
	$posturi = CVD_URI."/check/continue/$cid";
	if(bodyPost($posturi,$check->json) == 200)
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode) VALUES ('$cid',0,'continue')");	
		return true;
	}
	else 
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode,debugtxt) VALUES ('$cid',1,'continue','response code was not 200 to CVD')");	
		return false;
	}
}

function cvdRefreshCheck($cid)
{
	global $db;
	$check = returnServiceCheck($cid);
	if(!$check)
	{
		$db->query("INSERT INTO queue_log (cid,failed,queuemode,debugtxt) VALUES ('$cid',1,'refresh','check not found, returnServiceCheck was false')");	
		return false;	
	}
	cvdDeleteCheck($cid);
	sleep(1);
	return(cvdQueueCheck($cid));
}


function getDownTimeCountForServiceCheck($cid)
{
	global $db;
	$db->query("SELECT * FROM downtimes WHERE check_id = '$cid'");
	return $db->affected_rows;	
}


function getSparkLine($cid,$user_id)
{
	global $m;
	$dbname = MONGO_DB_CHECKS;
	$dbm = $m->$dbname;
	
	$check = returnServiceCheck($cid);
	
	$colname = $user_id."cid".$cid;
	$collection = $dbm->$colname;
	//db.getCollection("1").find({cid: 29}).sort({time:-1}).limit(60)
	$start = time() - 3600;
	$end = time() - 301;
	$res = $collection->find(array('cid' => new MongoInt32($cid), 'time' => array('$gt' => $start, '$lt' => $end)))->sort(array('time' => 1));
	
	// label for avg
	// if ping then use rta
	if($check->check_type == 1)
	{
		$label = "rta";
		$cut   = -3;
		$tlabel = "ms";
	}
	elseif($check->check_type == 5)
	{
		$label = "days";
		$cut   = -4;
		$tlabel = " days";		
	}
	else
	{
		$label = "time";
		$cut   = -4;
		$tlabel = "s";
	}
	
	$res = returnAverageMedianFrom($res,$label,$check->cinterval);


	foreach($res as $avg)
	{
		if(trim($avg['avg']) == "" || $avg['avg'] == -10)
		{
			$ret.= "-10,";	
		}
		else 
		{
			if($avg['avg'] != -10)
			{
				$ret.= number_format_global($avg['avg']).",";
			}
			else
			{
				$ret.= number_format_global($avg['avg']).",";	
			}			
		}
	}
	$ret = substr($ret,0,-1);

	return '<div id="sparkline'.$cid.'"></div>
	<script type="text/javascript">
	$("#sparkline'.$cid.'").sparkline(['.$ret.'], { type: \'line\', lineColor: \'#3195d8\', fillColor: null, lineWidth: 2, spotColor: null, minSpotColor: null, maxSpotColor: null, highlightSpotColor: null, highlightLineColor: null, height: 35, width: 300, tooltipSuffix: " '.$tlabel.'" });
	</script>
	';
}

function returnAverageMedianFrom($array,$label,$interval=1) 
{

    $timestamp = 0;
    $counter = 0;
    $error_counter = 0;
    
    $pre_array = array();
    $output = "";

    foreach($array as $obj)
    {
        # use floor insead of round to have 0 to 59 second for one running minute
		
        //$obj['time'] = floor($obj['time']/(60*$interval))*(60*$interval);
        $pre_array[] = $obj;
    }

    foreach($pre_array as $obj)
    {
        $write_object = FALSE;
        $NPD_array = parse_NPD($obj['pdata']);
        if ( $obj['pdata'] == "" ) {
            $error_counter=$error_counter-1;
        } else {
            $error_counter=$error_counter+1;
        }
        $status = $obj['status'];
        if ( $timestamp == 0 ) {
            $timestamp = $obj['time'];
            foreach($NPD_array as $tmp_NPD_array)
            {
                if ( $tmp_NPD_array['label'] == $label ) {
                    if ( $status == 2 || $status == 3 ) {
                        $avr_value = "-10";
                    } else {
                        $avr_value = $tmp_NPD_array['value'];
                        $counter++;
                    }
                }
            }
        } else {
            if ( $obj['time'] == $timestamp ) {
                foreach($NPD_array as $tmp_NPD_array)
                {
                    if ( $tmp_NPD_array['label'] == $label ) {
                        if ( $status != 2 && $status != 3 ) {
                            $avr_value = $avr_value + $tmp_NPD_array['value'];
                            $counter++;
                        }
                    }
                }
            } else {
                if ( $avr_value != "-10" && $counter != "0" ) {
                    $avr_value = $avr_value/$counter;
                }
                if ( $error_counter < 0 ) {
                    $avr_value = -10;
                }
                if ($output == "") {
                    $output = array(array("cid" => $obj['cid'], "avg" => $avr_value, "time" => $timestamp));
                } else {
                    array_push($output, array("cid" => $obj['cid'], "avg" => $avr_value, "time" => $timestamp));
                }
                $write_object = TRUE;
                $error_counter=0;
                $counter = 0;
                $timestamp = $obj['time'];
                foreach($NPD_array as $tmp_NPD_array)
                {
                    if ( $tmp_NPD_array['label'] == $label ) {
                        if ( $status == 2 || $status == 3 ) {
                            $avr_value = "-10";
                        } else {
                            $avr_value = $tmp_NPD_array['value'];
                            $counter++;
                        }
                        $write_object = FALSE;
                    }
                }
            }
        }
    }

    if ( $write_object == FALSE ) {
        if ( $counter > 1 ) {
            $avr_value = $avr_value/$counter;
        }
        if ( $error_counter < 0 ) {
            $avr_value = -10;
        }
        if ($output == "") {
            $output = array(array("cid" => $obj['cid'], "avg" => $avr_value, "time" => $timestamp));
        } else {
            array_push($output, array("cid" => $obj['cid'], "avg" => $avr_value, "time" => $timestamp));
        }
    }
    return $output;
}

function parse_NPD($npd) {

  # function to separate nagios performance data
  # example call: $array = parse_NPD("rta=0.053ms;200.000;500.000;0; pl=0%;40;80;; rtmax=0.133ms;;;; rtmin=0.028ms;;;;");

  $output = "";
  $npd_elements = split(" ", $npd);

  # split different performance elements like time, size, etc
  foreach($npd_elements as $element) {

    $value = split("=", $element);
    $lable = $value[0];
    $data = split(";", $value[1]);

    # correct last perf value if empty
    $data_size = sizeof($data);
    if ( $data_size < 4) {
      array_push($data, "null");
    }

    # correct empty data
    if ($data[0] == "") { $data[0] = "0null"; }
    if ($data[1] == "") { $data[1] = "null"; }
    if ($data[2] == "") { $data[2] = "null"; }
    if ($data[3] == "") { $data[3] = "null"; }

    # separate Value and Unit
    #preg_match("/(\\d*\.?\d+)([a-zA-Z]+)/", $data[0],$matches);
    $r=preg_match("/(\\d*\.?\d+)([a-zA-Z%]+)/", $data_0,$matches);
    if ( $r == 0 )
    {
        $matches[0]=$data[0];
        $matches[1]=$data[0];
        $matches[2]="";
    }


    # nagios perf data fields
    # Label -> $value[0]
    # Value -> $matches[1]
    # Unit -> $matches[2]
    # Warn -> $data[1]
    # Crit -> $data[2]
    # Min or Max -> $data[3]

    if ($output == "") {
      $output = array(array("label" => $value[0], "value" => $matches[1], "unit" => $matches[2], "warn" => $data[1], "crit" => $data[2], "minmax" => $data[3]));
    } else {
      array_push($output, array("label" => $value[0], "value" => $matches[1], "unit" => $matches[2], "warn" => $data[1], "crit" => $data[2], "minmax" => $data[3]));
    }

  }

  return $output;
}

function checkGotNotifications($cid)
{
	global $db;
	$db->query("SELECT id FROM check_notification_log WHERE cid = '$cid' LIMIT 1");
	if($db->affected_rows > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
