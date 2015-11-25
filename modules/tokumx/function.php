<?php

function trackPkgDataAvailable()
{
	// connect
	global $m;
	if (!$m) return false; // no mongodb connection
	
	//$node = getNode($host);
	
	// select a database
	$dbname = MONGO_DB_ESSENTIALS;
	$db = $m->$dbname;
	
	//   col = db.getCollection(doc.getString("user_id") + "_" + doc.getString("nodeid")+"_"+doc.getString("plugin"));
	$colname = "trackpkg";
	$col = $db->$colname;
	
	$res = $col->find(array("plugin" => "$plugin"))->limit(1);
	if(sizeof($res) > 0)
	{
		return true;
	}
	else
	{
		return false;	
	}	
	/*
	$item = $col->findOne();
	if($item != null)
	{
		return true;
	}
	else
	{
		return false;
	}*/
}

function dataAvailable($host,$plugin)
{
	// connect
	global $m;
	if (!$m) return false; // no mongodb connection
	
	$node = getNode($host);
	
	// select a database
	$dbname = MONGO_DB;
	$db = $m->$dbname;
	
	//   col = db.getCollection(doc.getString("user_id") + "_" + doc.getString("nodeid")+"_"+doc.getString("plugin"));
	$colname = $node->user_id."_".$node->id;
	$col = $db->$colname;
	//$res = $col->find(array("plugin" => "$plugin"))->sort(array('recv' => 1))->limit(1);
	
	$res = $col->find(array("plugin" => "$plugin"))->limit(1);
	if(sizeof($res) > 0)
	{
		return true;
	}
	else
	{
		return false;	
	}
	/*
	$item = $col->findOne(array("plugin" => "$plugin"));
	if($item != null)
	{
		return true;
	}
	else
	{
		return false;
	}*/
}

function getChartDataArray($host,$plugin,$graph,$timeframe=false,$startt=false,$endt=false,$returnobject=false)
{
	// connect
	global $m;
	if (!$m) new SplFixedArray(0); // no mongodb connection

	$node = getNode($host);
	
	// select a database
	$dbname = MONGO_DB;
	$db = $m->$dbname;
	
	//   col = db.getCollection(doc.getString("user_id") + "_" + doc.getString("nodeid")+"_"+doc.getString("plugin"));
	$colname = $node->user_id."_".$node->id;
	$col = $db->$colname;
	
	if(!$timeframe)
	{
		$res = $col->find(array('graph' => "$graph", "plugin" => "$plugin"))->sort(array('recv' => 1));		
	}
	else
	{
		if(!$startt)
		{
			$start = strtotime($timeframe);
		}
		else 
		{
			$start = (int)$startt;
		}
		
		if(!$endt)
		{
			$end = time();
		}
		else
		{
			$end = (int)$endt;
		}
		$res = $col->find(array('recv' => array('$gt' => new MongoInt32($start), '$lt' => new MongoInt32($end)) ,'graph' => "$graph", "plugin" => "$plugin"))->sort(array('recv' => 1));	
	}
	
	$i = 0;
	if($returnobject)
	{
		return $res;
	}
	
	$asize = $res->count();
	$rs = new SplFixedArray($asize);
	foreach($res as $obj)
	{
		$jit = null;
		if(IGNORE_NEGATIVES)
		{
			if($obj['value'] < 0)
			{
				continue;	
			}
		}
		$jit['recv'] = $obj['recv'];
		$jit['value'] = $obj['value'];
		$rs[$i] = $jit;	
		$i++;
	}	
	return $rs;
}

# function to calculate average between one hour of given values
# purpose is to reduce the amount of points to draw 
function returnAverageValue($array)
{
	# must be set to avoid date errors
	#date_default_timezone_set('Europe/Berlin');
    $counter = 0;
    $prequelDate = 0;
    $prequelHour = 0;
    $average = 0;
    $output = "";

    foreach($array as $obj)
    {
		#convert unix timestamp to human readable time
		#use only the given hour for teh calculation
        $date = date('Y-m-d', $obj['recv']); 
        $hour = date('H', $obj['recv']) . ':00';

        if ( $prequelDate == $date ) {
            if ( $prequelHour == $hour ) {
                $average = $average + $obj['value'];
                $counter++;
            } else {
                if ( $prequelHour != 0 ) {
                    $average = $average / $counter;
                    $timestamp = "$prequelDate $prequelHour";
                    $unixtime = strtotime($timestamp);

                    if ($output == "") {
                        $output = array(array("recv" => $unixtime, "value" => $average));
                    } else {
                        array_push($output, array("recv" => $unixtime, "value" => $average));
                    }

                    $average = $obj['value'];
                    $counter = 1;
                }
                $prequelDate = $date;
                $prequelHour = $hour;
            }
        } else {
            if ( $prequelHour != 0 ) {
                $average = $average / $counter;
                $timestamp = "$prequelDate $prequelHour";
                $unixtime = strtotime($timestamp);

                if ($output == "") {
                    $output = array(array("recv" => $unixtime, "value" => $average));
                } else {
                    array_push($output, array("recv" => $unixtime, "value" => $average));
                }

                $average = $obj['value'];
                $counter = 1;
            }
            $prequelDate = $date;
            $prequelHour = $hour;
        }
    }
    return $output;
}
?>