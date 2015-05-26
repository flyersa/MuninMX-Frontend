<?php
function getVlabelForPlugin($host,$pluginp)
{

	$url = "http://".MCD_HOST.":".MCD_PORT."/node/$host/plugins";

	$jcat = json_decode(file_get_contents($url), false);	
	foreach($jcat as $plugin)
	{
		if($plugin->str_PluginName == $pluginp)
		{
			return str_replace('${graph_period}',GRAPH_PERIOD,$plugin->str_PluginLabel);
		}	
	}
}

function renderNoPluginTable()
{
	include("templates/mcd/tables/noPluginTable.head.tpl.php");
	$url = "http://".MCD_HOST.":".MCD_PORT."/list/nodes";	
	$json = json_decode(file_get_contents($url));	
	foreach($json as $node)
	{
   		if($node->last_plugin_load == 0)
        {
        	if($node->i_lastRun == 0)
			{
				$lcon = "Never, maybe no munin-node running?";
			}
			else
			{
				$lcon = getFormatedLocalTime($node->i_lastRun);
			}
			echo '
			<tr>
				<td><a href="view.php?nid='.$node->node_id.'">'.$node->str_nodename.'</a></td>
				<td>'.$lcon.'</td>
			</tr>	  
			';
        }
	}
	include("templates/core/tableEnd.tpl.php");
}

function renderJobListTable()
{
	include("templates/mcd/tables/jobListTable.head.tpl.php");
	$url = "http://".MCD_HOST.":".MCD_PORT."/joblist/list";	
	$json = json_decode(file_get_contents($url));	
	foreach($json as $node)
	{
   		if($node->last_plugin_load == 0)
        {
			$nodeobj = getNode($node->jobId);
			$nodename = $nodeobj->hostname;
			echo '
			<tr>
				<td><a href="view.php?nid='.$node->jobId.'">'.$nodename.'</a></td>
				<td>'.$node->nextFireTime.'</td>
			</tr>	  
			';
        }
	}
	include("templates/core/tableEnd.tpl.php");	
}

function renderCustomJobListTable()
{
	include("templates/mcd/tables/jobListTable.head.tpl.php");
	$url = "http://".MCD_HOST.":".MCD_PORT."/customjoblist/list";	
	$json = json_decode(file_get_contents($url));	
	foreach($json as $job)
	{
			$ci = getCustomInterval($job->jobId);
			$nodename = $ci->hostname;
			if($ci->from_time == 0 && $ci->to_time == 0)
			{
				$rd = "repeating forever, no fixed date";
			}
			else
			{
				$rd = getFormatedLocalTime($ci->from_time) . " - " . getFormatedLocalTime($ci->to_time);	
			}
			echo '
			<tr>
				<td><a href="view.php?nid='.$ci->node_id.'">'.$nodename.'</a> - &nbsp; Plugin: '.htmlspecialchars($ci->pluginname).', Interval: '.$ci->query_interval.'s Timed Cron: '.$ci->crontab.' Ranged Date: '.$rd.'</td>
				<td>'.$job->nextFireTime.'</td>
			</tr>	  
			';
	}
	include("templates/core/tableEnd.tpl.php");	
}
?>