<?php


function getSystemSnapShotPrevNextStatus($nid,$timestamp)
{
	global $m;
	$node = getNode($nid);
	if(!$node)
	{
		return false;
	}
	$secs = $node->query_interval * 58;
	
	$prev = $timestamp - $secs;
	$next = $timestamp + $secs;
	
	// select a database
	$dbname = MONGO_DB_ESSENTIALS;
	$db = $m->$dbname;
	
	//   col = db.getCollection(doc.getString("user_id") + "_" + doc.getString("nodeid")+"_"+doc.getString("plugin"));
	$colname = $nid."_ess";
	$col = $db->$colname;	
	$res = $col->find(array('time' => array('$gt' => new MongoInt32($timestamp))))->limit(1);	
	if($res->count() < 1)
	{
		$tpl->datanext = false;
	}	
	else
	{
		$tpl->datanext = true;
	}
	
	$res = $col->find(array('time' => array('$lt' => new MongoInt32($timestamp))))->limit(1);	
	if($res->count() < 1)
	{
		$tpl->dataprev = false;
	}	
	else
	{
		$tpl->dataprev = true;
	}		
	$tpl->prevtime = $prev;
	$tpl->nexttime = $next;
	return $tpl;	
}

function getEssentialSystemSnapShot($nid,$timestamp,$closest=false)
{
	global $m;
	$node = getNode($nid);
	if(!$node)
	{
		return false;
	}
	// select a database
	$dbname = MONGO_DB_ESSENTIALS;
	$db = $m->$dbname;
	
	//   col = db.getCollection(doc.getString("user_id") + "_" + doc.getString("nodeid")+"_"+doc.getString("plugin"));
	$colname = $nid."_ess";
	$col = $db->$colname;
		
	if($closest == false)
	{
		$res = $col->find(array('time' => "$timestamp"))->sort(array('time' => 1));		
	}
	else
	{
		$interval = $node->query_interval * 60;
		$start = $timestamp - 60;
		$end = $timestamp + $interval;
		$res = $col->find(array('time' => array('$gt' => $start, '$lt' => $end)))->sort(array('time' => 1));	
	}
	if($res->count() < 1)
	{
		return false;
	}
	
	//echo "start: $start end: $end timestamp: $timestamp result:". $res->count();
	$count = 0;
	foreach($res as $obj)
	{
		return $obj;
	}
	
	return false;
}

function EssentialDataAvailable($nid)
{
	// connect
	global $m;
	
	
	// select a database
	$dbname = MONGO_DB_ESSENTIALS;
	$db = $m->$dbname;
	
	//   col = db.getCollection(doc.getString("user_id") + "_" + doc.getString("nodeid")+"_"+doc.getString("plugin"));
	$colname = $nid."_ess";
	$col = $db->$colname;
	//$res = $col->find(array("plugin" => "$plugin"))->sort(array('recv' => 1))->limit(1);
	
	$item = $col->findOne();
	if($item != null)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function decodeEssentialString($tstring)
{
        $tstring = base64_decode($tstring);
        $input = gzdecode($tstring);
        if($input)
        {
                return $input;
        }
        return false;
}

function buildEssentialArrayFromString($tstring)
{

    $procs_array = "";
    $stat_array = "";
    $meminfo_array = "";
    $netstat_array = "";
    $who_array = "";
    $meminfo = "";
    $netstat = "";
    $who = "";
    $procs  = "";
    $stat  = "";

    $input = decodeEssentialString($tstring);
    $lines = explode(PHP_EOL, $input);

    foreach($lines as $line)
    {

		$output=getInnerSubstring($line,'###');
		if ($output) {
            $current_block=$output;
		}

        if ($current_block == "PROCS" && !$output && $line) {

            $line = preg_replace('/\s+/', ' ',$line);
            $line = trim($line);
            $procs_values = explode(" ", $line);
            
            if ($procs_array == "") {
                $procs_array = array($procs_values);
            } else {
                array_push($procs_array, $procs_values);
            }

        } elseif ($current_block == "STAT" && !$output && $line) {

            $line = preg_replace('/\s+/', ' ',$line);
            $line = trim($line);
            $stat_values = explode(" ", $line);

            if ($stat_array == "") {
                $stat_array = array($stat_values);
            } else {
                array_push($stat_array, $stat_values);
            }

        } elseif ($current_block == "NETSTAT" && !$output && $line) {

            $line = preg_replace('/\s+/', ' ',$line);
            $line = trim($line);
            $netstat_values = explode(" ", $line);

            if ($netstat_array == "") {
                $netstat_array = array($netstat_values);
            } else {
                array_push($netstat_array, $netstat_values);
            } 

        } elseif ($current_block == "MEMINFO" && !$output && $line) {

            $line = preg_replace('/\s+/', ' ',$line);
            $line = trim($line);
            $meminfo_values = explode(" ", $line);
            $meminfo_values[0] = chop($meminfo_values[0],":");

            if ($meminfo_array == "") {
                $meminfo_array = array($meminfo_values);
            } else {
                array_push($meminfo_array, $meminfo_values);
            }
                   
        } elseif ($current_block == "WHO" && !$output && $line) {

            $line = preg_replace('/\s+/', ' ',$line);
            $line = trim($line);
            $who_values = explode(" ", $line);

            if ($who_array == "") {
                $who_array = array($who_values);
            } else {
                array_push($who_array, $who_values);
            }
        }
    }

    # split arrays from numbers to value names

    # SPLIT procs
    for ($i = 1; $i < count($procs_array); $i++) {
        for ($j = 0; $j <= 10; $j++) {
            if ($j == 10) {
                $loop_count = 0;
                $procs_command = "";
                while ($loop_count >= 0) {
                    $k = $j+$loop_count;
                    if (isset($procs_array[$i][$k])) {
                        $procs_command = $procs_command." ".$procs_array[$i][$k];
                    } else {
                        break;
                    }
                    $loop_count++;
                }
                $procs_tmp[$procs_array[0][$j]] = $procs_command;
            } else {
                $procs_tmp[$procs_array[0][$j]] = $procs_array[$i][$j];   
            }
            
        }

        if ($procs == "") {
            $procs = array($procs_tmp);
        } else {
            array_push($procs, $procs_tmp);
        }
        
        $procs_tmp == "";
    }

    # SPLIT stat
    foreach ($stat_array as $type => $properties) {
        foreach ($properties as $property => $value) {
            if (stristr($stat_array[$type][0],'cpu')) {
                if ($property != 0) {
                    $stat["cpustats"][$stat_array[$type][0]][] = $stat_array[$type][$property];    
                }
            } else {
                
                if ($property != 0) {
                    $stat[$stat_array[$type][0]][] = $stat_array[$type][$property];    
                }
            }
        }
    }

    # SPLIT netstat
    for ($i = 1; $i < count($netstat_array); $i++) {
        for ($j = 0; $j <= 4; $j++) {
            if ($j == 3) {
                $dummy = $netstat_array[0][3]." ".$netstat_array[0][4];
                $netstat_tmp[$dummy] = $netstat_array[$i][$j];
            } elseif ($j == 4) {
                $dummy = $netstat_array[0][5]." ".$netstat_array[0][6];
                $netstat_tmp[$dummy] = $netstat_array[$i][$j];
            } else {
                $netstat_tmp[$netstat_array[0][$j]] = $netstat_array[$i][$j];
            }
        }

        if ($netstat == "") {
            $netstat = array($netstat_tmp);
        } else {
            array_push($netstat, $netstat_tmp);
        }
        
        $netstat_tmp == "";
    }

    # SPLIT meminfo 
    for ($i = 0; $i <= 41; $i++) {      
        $meminfo[$meminfo_array[$i][0]] = $meminfo_array[$i][1]; 
    }

    # SPLIT who
    for ($i = 0; $i < count($who_array); $i++) {
        for ($j = 0; $j <= 3; $j++) {
            if ($j == 0) {
                $who_tmp["Name"] = $who_array[$i][$j];
            } elseif ($j == 1) {
                $who_tmp["Line"] = $who_array[$i][$j];
            } elseif ($j == 2) {
                $who_tmp["Time"] = $who_array[$i][2]." ".$who_array[$i][3]." ".$who_array[$i][4];
            } elseif ($j == 3) {
                $who_tmp["Comment"] = $who_array[$i][5];
            }
        }

        if ($who == "") {
            $who = array($who_tmp);
        } else {
            array_push($who, $who_tmp);
        }
        
        $who_tmp == "";
    }

    return array("procs" => $procs, "stat" => $stat, "meminfo" => $meminfo, "netstat" => $netstat, "who" => $who);
}

function getInnerSubstring($string,$delim)
{
    // "foo a foo" becomes: array(""," a ","")
    $string = explode($delim, $string, 3); // also, we only need 2 items at most
    // we check whether the 2nd is set and return it, otherwise we return an empty string
    return isset($string[1]) ? $string[1] : '';
}
?>
