<?php

function renderEventsTable()
{
	global $db;
	$time = time() - 604800;
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT events.*,nodes.hostname,nodes.groupname AS nodegroup FROM events LEFT JOIN nodes ON events.node = nodes.id WHERE event_start > $time ORDER BY id DESC");	
	}
	elseif($_SESSION['role'] == "user")
	{
		$g = getUserGroupsSQL(false,"nodes.");
		$result = $db->query("SELECT events.*,nodes.hostname, nodes.groupname AS nodegroup FROM events LEFT JOIN nodes ON events.node = nodes.id WHERE events.user_id = '$_SESSION[user_id]' OR ($g) ORDER BY id DESC");	
		//echo "SELECT events.*,nodes.groupname AS nodegroup,nodes.hostname FROM events LEFT JOIN nodes ON events.node = nodes.id WHERE events.user_id = '$_SESSION[user_id]' OR ($g) ORDER BY id DESC";
	}
	elseif($_SESSION['role'] == "userext")
	{
		$result = $db->query("SELECT events.*,nodes.hostname,nodes.groupname AS nodegroup FROM events LEFT JOIN nodes ON events.node = nodes.id WHERE events.user_id = '$_SESSION[user_id]' ORDER BY id DESC");
	}	
	include("templates/events/tables/top.tpl.php");
	while($tpl = $result->fetch_object())
	{
		if(!is_numeric($tpl->node))
		{
			continue;
		}
		include("templates/events/tables/item.tpl.php");
	}
	include("templates/core/tables/tableEnd.tpl.php");
	
}

function eventsAvail()
{
	global $db;
	$time = time() - 604800;
	if($_SESSION['role'] == "admin")
	{
		$result = $db->query("SELECT events.*,nodes.hostname,nodes.groupname AS nodegroup FROM events LEFT JOIN nodes ON events.node = nodes.id WHERE event_start > $time ORDER BY id DESC");	
	}
	elseif($_SESSION['role'] == "user")
	{
		$g = getUserGroupsSQL(false,"nodes.");
		$result = $db->query("SELECT events.*,nodes.hostname, nodes.groupname AS nodegroup FROM events LEFT JOIN nodes ON events.node = nodes.id WHERE events.user_id = '$_SESSION[user_id]' OR ($g) ORDER BY id DESC");	
		//echo "SELECT events.*,nodes.groupname AS nodegroup,nodes.hostname FROM events LEFT JOIN nodes ON events.node = nodes.id WHERE events.user_id = '$_SESSION[user_id]' OR ($g) ORDER BY id DESC";
	}
	elseif($_SESSION['role'] == "userext")
	{
		$result = $db->query("SELECT events.*,nodes.hostname,nodes.groupname AS nodegroup FROM events LEFT JOIN nodes ON events.groupname = nodes.groupname WHERE events.user_id = '$_SESSION[user_id]' ORDER BY id DESC");
	}	
	if($db->affected_rows > 0)
	{
		return true;
	}	
	else
	{
		return false;
	}
}

function getEvent($eid)
{
	global $db;
	$result = $db->query("SELECT * FROM events WHERE id = '$eid'");
	if($db->affected_rows < 1)
	{
		return false;
	}
	return $result->fetch_object();
}

function renderEvents($node)
{
	$nid = $node->id;
	if(key_exists($nid, $_SESSION['disableevents']))
	{
		return;
	}
	global $db;
	$result = $db->query("SELECT * FROM events WHERE node = '$node->id'");
	if($db->affected_rows > 0)
	{
		while($tpl = $result->fetch_object())
		{
			if($tpl->event_end == 0)
			{
				$lines[] = $tpl;
			}
			else
			{
				$bands[] = $tpl;
			}
		}	
	}

	// other stuff
	// check for groups and so on...
	// if userext, show only my own, else show all
	if($_SESSION['role'] == "userext")
	{
		$result = $db->query("SELECT * FROM events WHERE groupname = '$node->groupname' AND user_id = '$_SESSION[user_id]'");	
	}
	else
	{
		$result = $db->query("SELECT * FROM events WHERE groupname = '$node->groupname'");		
	}
	
	if($db->affected_rows > 0)
	{
		while($tpl = $result->fetch_object())
		{
			if(strtoupper($node->groupname) == strtoupper($tpl->groupname))
			{
				if($tpl->event_end == 0)
				{
					$lines[] = $tpl;
				}
				else
				{
					$bands[] = $tpl;
				}
			}
		}			
	}

	
	
	// render plotLines
	if(sizeof($lines) > 0)
	{
		$lasttime = 0;
		$showtext = true;
		echo "
		  ,plotLines: [";
		foreach($lines as $line)
		{
			if(strlen($line->event_title) > 40)
			{
				$shorttext = htmlspecialchars(substr($line->event_title,0,40))."...";
			}
			else
			{
				$shorttext = htmlspecialchars($line->event_title);
			}
			$start = $line->event_start * 1000;
			
			if($lasttime > 0)
			{
				$stime = $line->event_start - $lasttime;
				if($stime < 600)
				{
					$showtext = false;
				}	
			}
			else
			{
				$w = 10;	
			}
			echo "
			  {
		    color: '".$line->color."', 
		    value: '".$start."', 
		    width: '".$w."',
		    dashStyle: 'solid',
		    zIndex: 3,";
		    if($showtext) {
		    	echo "
			label: { 
			    text: '".$shorttext."',
                    verticalAlign: 'top',
                    rotation: 90,
                    x: 10,
                    textAlign: 'middle',
			  },";
			};
 			 echo " events: {
                    click: function (e) {
                    	d = new Date(".$start.");
                        alert(d+' - ".htmlspecialchars($line->event_title)."');
              	},
              }			  		      
		  	},	
			";
			$lasttime = $line->event_start;
		}
		echo "
		  ],		
		";	
	}
	
	// render plotBands
	if(sizeof($bands) > 0)
	{
		if(sizeof($lines) < 1)
		{
			echo ",";	
		}
		echo "
		  plotBands: [";
		foreach($bands as $band)
		{
			if(strlen($band->event_title) > 40)
			{
				$shorttext = htmlspecialchars(substr($band->event_title,0,40))."...";
			}
			else
			{
				$shorttext = htmlspecialchars($band->event_title);
			}
			$start = $band->event_start * 1000;
			$end = $band->event_end * 1000;
			echo "
			  {
		    color: '".$band->color."', 
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
                        alert('".htmlspecialchars($band->event_title)."');
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
