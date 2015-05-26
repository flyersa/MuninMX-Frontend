<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	$_SESSION['REAL_REFERRER'] = $_SERVER['REQUEST_URI'];
	header("Location: login.php");
	die;
}
if($_GET['debug'] && $_GET['debugcode'] == "eatkittens")
{
	print_r($_SERVER);
	print_r($_COOKIE);
	print_r($_SESSION);
}
if(!is_numeric($_GET['nid']))
{
	header("Location: index.php");
}
else
{
	if(!accessToNode($_GET['nid']))
	{
		header('HTTP/1.0 403 Forbidden');
		header("Location: 403.php");
		die;
	}
	
	$node = getNode($_GET['nid']);
	if(!$node)
	{
		header('HTTP/1.0 404 Not Found');
		header("Location: 404.php");
		die;		
	}
}
$ctime = time() + 172800;
if(isset($_COOKIE['lastseen']))
{
	$c = explode(",",$_COOKIE['lastseen']);
	if(!in_array($node->id."|".$node->hostname." [".$node->groupname."]",$c))
	{
	
		if(sizeof($c) > 10)
		{
			setcookie("lastseen",$node->id."|".$node->hostname." [".$node->groupname."]");		
		}
		else
		{
			setcookie("lastseen",$_COOKIE['lastseen'].",".$node->id."|".$node->hostname." [".$node->groupname."]");			
		}
	}

}
else
{
	setcookie("lastseen",$node->id."|".$node->hostname." [".$node->groupname."]");	
}

// warning of IMMINENT BRAIN DAMAGE!
if($_GET['favorite'] == "true")
{
	$ctime = time() + 31556926;
	$c = explode(",",$_COOKIE['favorites']);
	if(!isset($_COOKIE['favorites']) || sizeof($c) == 0)
	{
		setcookie("favorites",$node->id."|".$node->hostname." [".$node->groupname."]",$ctime);			
	}
	else
	{
		if(!in_array($node->id."|".$node->hostname." [".$node->groupname."]",$c))
		{
			setcookie("favorites",$_COOKIE['favorites'].",".$node->id."|".$node->hostname." [".$node->groupname."]",$ctime);		
		}	
	}
	header("Location: view.php?nid=".$node->id);
}
elseif($_GET['favorite'] == "false")
{
	$c = explode(",",$_COOKIE['favorites']);
	if(sizeof($c) == 1)
	{
		setcookie("favorites","");
	}
	else
	{
		//$_COOKIE['favorites'] = array_diff($_COOKIE['favorites'], array($node->id."|".$node->hostname." [".$node->groupname."]"));	
		$c = explode(",",$_COOKIE['favorites']);
		foreach ($c as $key => $value) {
		    if ($value == $node->id."|".$node->hostname." [".$node->groupname."]") {
		        unset($c[$key]);
		        // If you know you only have one line to remove, you can decomment the next line, to stop looping
		        break;
		    }
		}
		//print_r($c);
		$i = 0;
		foreach($c as $key => $value)
		{
			if($i == 0)
			{
				$_COOKIE['favorites'] = $value;
			}
			else
			{
				$_COOKIE['favorites'] = ",".$value;
			}
			$i++;
		}
		
		setcookie("favorites",$_COOKIE['favorites'],$ctime);
	}
	header("Location: view.php?nid=".$node->id);
}
// puh, your brain survived?
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = APP_NAME . " - " . $node->hostname . " (".htmlspecialchars($node->groupname).")"; include("templates/core/head.tpl.php"); ?>
	</head>
	<body <?php if($_SESSION['minify'] == true) { echo 'class="desktop-detected pace-done minified"'; } else { echo 'class=""';} ?>>
		<a name="top"></a>
		<!-- HEADER -->
		<header id="header">
		<?php include("templates/core/header.tpl.php"); ?>
		</header>
		<!-- END HEADER -->

		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<?php include("templates/nav/left.tpl.php"); ?>
		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">
			   <!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="index.php">Home</a></li><li><a href="index.php">Metrics</a></li><li><a href="view.php?nid=<?php echo $node->id?>"><?php echo $node->hostname?></a> (<?php echo htmlspecialchars($node->groupname)?>)</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-7">
						<?php if(!$_GET['category']) { ?>
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Metrics <span> > <a href="view.php?nid=<?php echo $node->id?>"><?php echo htmlspecialchars($node->hostname)?></a> (<?php echo htmlspecialchars($node->groupname)?>)</span></h1>
						<?php } else { ?>
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Metrics <span> > <a href="view.php?nid=<?php echo $node->id?>"><?php echo htmlspecialchars($node->hostname)?></a> > <?php echo htmlspecialchars($_GET['category'])?></span></h1>	
						<?php } ?>
					</div>
				</div>
				
				
				<section id="widget-grid" class="">

				<?php 
				if($_GET['stype'] == "line" || $_GET['stype'] == "area" || $_GET['stype'] == "areastack" || $_GET['stype'] == "column" )
				{
					setcookie("random",uniqid(microtime()));
					$_SESSION['stype'] = $_GET['stype'];
					display_ok("Graph Style Changed","New global graph style (for session) set to: " . $_GET['stype']);
				}

				if($_GET['debug'])
				{
					print_r($_SESSION);
				}

				if($_GET['disableevents'] == "true")
				{
					$nid = $node->id;
					$_SESSION['disableevents'][$nid] = true;	
					setcookie("random",uniqid(microtime()));
				}
				elseif($_GET['disableevents'] == "false")
				{
					$nid = $node->id;
					unset($_SESSION['disableevents'][$nid]);
					setcookie("random",uniqid(microtime()));
				}

				if($_SESSION['rplugs'])
				{
					if($_SESSION['rplugss'] == true)
					{
						display_ok("Plugins reloaded","Plugins have been loaded from munin-node and database was updated. If you stil see no plugins the node might be offline or MuninMXcd is not allowed to connect to this munin-node. Check munin-node.conf on this host if this is the case");	
					}
					else
					{
						display_error("Unable to reload Plugins","MuninMX reported a invalid response. Maybe the munin-node is offline");		
					}
					$_SESSION['rplugs'] = "";
				}
				if($_GET['action'] == "reloadplugins")
				{
					$_SESSION['rplugs'] = true;
					// http://skoll:49000/node/577/loadplugins
					$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/$node->id/loadplugins");	
					if(trim($ret) == "true")
					{
						$_SESSION['rplugss'] = true;
					}
					else
					{
						$_SESSION['rplugss'] = false;	
					}
					sleep(2);
					header("Location: view.php?nid=".$node->id."&cb=".time());
					die;
				}
				elseif($_GET['action'] == "delete")
				{
					if($_SESSION['role'] != "admin" && $_SESSION['role'] != "userext")
					{
						header("Location: 403.php");
						die;
					}					
					include("templates/munin/delete.tpl.php");
				}
				elseif($_GET['action'] == "deletemedowhatisay")
				{
					if($_SESSION['role'] != "admin" && $_SESSION['role'] != "userext")
					{
						header("Location: 403.php");
						die;
					}				
					// http://skoll:49000/deletejob/1085/1
					$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletejob/$node->id/$node->user_id");
					if(trim($ret) == "true")
					{
						$db->query("DELETE FROM nodes WHERE id = $node->id");
						if($db->affected_rows > 0)
						{
							display_ok("Node Removed","Nodeconfiguration have been purged.");
							setcookie("random",uniqid(microtime()));	
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
							$c->remove(array("node" => new MongoInt32($node->id)));
						}
						else
						{
							display_error("DB Error","Unable to delete node from database cache. Try again later");
						}
						
					}
					else
					{
						display_warning("Deleted but not dequeued","MuninMX reported a unsuccessfull dequeue. We deleted the node anyway");			
					}
					$nodisplay = true;
				}
				elseif($_GET['action'] == "edit")
				{
					$nodisplay = true;
				}
				// add plugin to dashboard
				elseif($_GET['action'] == "dashboard" && is_numeric($_GET['plugin']))
				{
					$fail = false;
					$plugin = getPlugin($node->id,$_GET['plugin']);
					if($plugin == false)
					{
						display_error("Plugin not found","Plugin does not exists for this node");
						$nodisplay = true;
						$fail = true;
					}		
					if(!$_POST)
					{
						include("templates/dashboards/addtoboard.tpl.php");	
					}	
					else
					{
						// create new dashboard
						$did = "nono";
						if($_POST['dashboard_name'])
						{
							if(trim($_POST['dashboard_name']) == "" || strlen(trim($_POST['dashboard_name'])) < 4)
							{
								display_error("Validation Error","If you want to create a new dashboard, dashboard name needs to have a value and must be more then 4 characters long");
								$fail = true;
							}
							else
							{
								// create dashboard
								$dname = $db->real_escape_string($_POST['dashboard_name']);	
								// check if we have already a dashboard with this name
								$db->query("SELECT * FROM dashboards WHERE dashboard_name = '$dname' AND user_id = '$_SESSION[user_id]'");
								if($db->affected_rows > 0)
								{
									$fail = true;
									display_error("Dashboard Naming Error","You already have a dashboard with the same name. Please chose another name");
								}
								else
								{	
									$db->query("INSERT INTO dashboards (dashboard_name,user_id) VALUES ('$dname','$_SESSION[user_id]')");
									if($db->affected_rows < 1)
									{
										$fail = true;
										display_error("Cannot create Dashboard","There was a error creating this dashboard. Please try again later");
									}
									else {
										$did = $db->insert_id;	
										$_POST['dashboard_id'] = $did;
									}
								}
							}
						}
						
						if($fail)
						{
							include("templates/dashboards/addtoboard.tpl.php");		
						}
						else
						{				
							if(!is_numeric($_POST['dashboard_id']))
							{
								if(!is_numeric($_POST['dashboard_id']))
								{
									display_error("Validation Error","You need to select a dashboard or create a new one");
									include("templates/dashboards/addtoboard.tpl.php");		
								}	
							}
							else
							{
								$did = $_POST['dashboard_id'];
								// check if we have access to the chosen board
								if(!accessToDashBoard($did))
								{
									display_error("Security Violation","You have no access to the specified board");
									include("templates/dashboards/addtoboard.tpl.php");	
								}
								else
								{
									$stype = $db->real_escape_string($_POST['stype']);
									$period = $db->real_escape_string($_POST['period']);
									$db->query("INSERT INTO dashboard_items (dashboard_id,plugin_id,period,stype) VALUES ('$did','$plugin->id','$period','$stype')");
									if($db->affected_rows > 0)
									{
										$board = getDashboard($did);
										display_ok("OK","Metric added to Dashboard: ".htmlspecialchars($board->dashboard_name).' <a href="view.php?nid='.$node->id.'">Back to Node</a> or <a href="dashboard.php?dashboard='.$did.'">Open Dashboard</a>');
									}
									else
									{
										display_error("Storage Error","Unable to save dashboard item, please try again later");
									}
								}
							}	
	
						}						
					}		
				

					renderSingleGraph($node->id,$_GET['plugin']);	
				}
				// add custom
				elseif($_GET['action'] == "customs" && is_numeric($_GET['plugin']))
				{
					$fail = false;
					$user = getUserObject($_SESSION['user_id']);
					$cinuse = getCustomsCount($user->id);
					if(!$_GET['sub'] == "delete")
					{
						
						if($user->userrole != "admin")
						{
							if($user->max_customs == 0)
							{
								display_error("Feature disabled","This feature is not enabled for your account");
								$nodisplay = true;
								$fail = true;
							}
						}	
					}
					
					$plugin = getPlugin($node->id,$_GET['plugin']);
					if($plugin == false)
					{
						display_error("Plugin not found","Plugin does not exists for this node");
						$nodisplay = true;
						$fail = true;
					}
					
					
									
					if(!$_POST)
					{
						if(!$fail)
						{
							if($_GET['sub'] == "delete" && is_numeric($_GET['jobid']))
							{
								checkToken();
								$ci = getCustomInterval($_GET['jobid']);
								if($ci == false)
								{
									display_error("Invalid Job","JobID not found");
								}
								else
								{
									$auth = true;
									if($_SESSION['role'] != "admin")
									{
										if($ci->user_id != $_SESSION['user_id'])
										{
											display_error("Authorisation denied","Access to this jobid denied");
											$auth = false;
										}
										else 
										{
											$auth = true;
										}
									}

								}
								
								if($auth == true)
								{
									$ciuser = getUserIdForCustomJob($ci->id);
									$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletecustomjob/$ci->id/$ciuser");
									if(trim($json) == "true")
									{
										$db->query("DELETE FROM plugins_custom_interval WHERE id = $ci->id");
										display_ok("Custom Interval Deleted","Custom Interval removed");
										
									}
									else
									{
										display_error("Deletion Error","Unable to dequeue custom interval on Collector. try again later");
									}		
								}
							}
							
							
							display_info("Custom Intervals","Here you can set custom query intervals to this specific plugin in seconds. Repeating forever, only between given hours or given date/time ranges. This will result in a higher graph resolution for this plugin");	
							
							if($user->userrole != "admin")
							{
								if($user->max_customs < $cinuse || $user->max_customs == $cinuse)
								{
									display_error("Limit Exceeded","Your account is only allowed to maintain $user->max_customs custom intervals and you have already $cinuse . Delete a custom to add a new one");
									$fail = true;
								}	
								else
								{
									include("templates/cinterval/forms/add.tpl.php");	
								}
							}
							else
							{
								include("templates/cinterval/forms/add.tpl.php");
							}
							
							// show list of custom plugins
							
							renderCustomJobs($node->id,$plugin->pluginname);
							// show plugin
							renderSingleGraph($node->id,$_GET['plugin']);
						}
						
						$nodisplay = true;	
					}
					else 
					{
						if($user->userrole != "admin")
						{
							if($user->max_customs < $cinuse || $user->max_customs == $cinuse)
							{
								display_error("Limit Exceeded","Your account is only allowed to maintain $user->max_customs custom intervals and you have already $cinuse . Delete a custom to add a new one");
								$fail = true;
							}
						}						
						//print_r($_POST);
						// add this shit woohooo
						if(!$fail)
						{
							if(!isset($_SESSION['timezone']))
							{
								$_SESSION['timezone'] = "Europe/Berlin";
							}
						
							// [query_interval] => 30 [from_time] => 0:00 [to_time] => 0:00 [from_date] => [to_date] => )
							// [query_interval] => 30 [from_time] => 0:00 [to_time] => 0:00 [from_date] => [to_date] => )
							if(!is_numeric($_POST['query_interval']))
							{
								display_error("Input Error","query interval needs to be a numeric value");
								include("templates/cinterval/forms/add.tpl.php");	
								renderSingleGraph($node->id,$_GET['plugin']);
							}
							else
							{
								if($_POST['from_time'] == "0:00" && $_POST['to_time'] == "0:00")
								{
									// simple interval
									if(trim($_POST['from_date'] == ""))
									{
										if(!is_numeric($_POST['retention']))
										{
											$retention = 0;
										}
										else
										{
											$retention = $_POST['retention'];
										}
										$db->query("INSERT INTO plugins_custom_interval (node_id,pluginname,query_interval,timezone,user_id,plugin_id,retention) VALUES ('$node->id','$plugin->pluginname','$_POST[query_interval]','$_SESSION[timezone]','$user->id','$plugin->id','$retention')");	
										if($db->affected_rows > 0)
										{
											$cid = $db->insert_id;
											// queue, if queue fails delete from db
											// example call: my.muninmx.com:49000/queuecustomjob/1/43
											$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/queuecustomjob/$cid/$user->id");
											if(trim($json) == "true")
											{
												display_ok("Custom Interval Saved","Custom Interval queued with immediate start and $_POST[query_interval] second interval");
												//include("templates/cinterval/forms/add.tpl.php");
												renderCustomJobs($node->id);
												renderSingleGraph($node->id,$_GET['plugin']);
											}
											else
											{
												$db->query("DELETE FROM plugins_custom_interval WHERE id = '$cid'");
												display_error("Queue Error","Unable to queue job on collector. Please try again later");
												include("templates/cinterval/forms/add.tpl.php");	
												renderSingleGraph($node->id,$_GET['plugin']);
											}	
										}	
										else
										{
											display_error("Queue Error","Unable to store custom interval in database, please try again later");
											include("templates/cinterval/forms/add.tpl.php");	
											renderSingleGraph($node->id,$_GET['plugin']);
										}

									}
									// date set, run from start to end
									else
									{
										if(trim($_POST['to_date']) == "")
										{
											display_error("Input Error","Start Date set but no Target Date");
											include("templates/cinterval/forms/add.tpl.php");	
											renderSingleGraph($node->id,$_GET['plugin']);											
										}			
										else
										{
											// run every specified interval but only from start to end date
											//print_r($_POST); 
											   
											$from = getTimeStampFromStringForZone($_POST['from_date']." 00:00",$_SESSION['timezone']);
											$to   = getTimeStampFromStringForZone($_POST['to_date']." 23:59:59",$_SESSION['timezone']);	
											//echo "from: $from to: $to";
											if(!is_numeric($_POST['retention']))
											{
												$retention = 0;
											}
											else
											{
												$retention = $_POST['retention'];
											}
											$db->query("INSERT INTO plugins_custom_interval (node_id,pluginname,query_interval,timezone,user_id,from_time,to_time,plugin_id,retention) VALUES ('$node->id','$plugin->pluginname','$_POST[query_interval]','$_SESSION[timezone]','$user->id','$from','$to','$plugin->id','$retention')");	
											if($db->affected_rows > 0)
											{
												$cid = $db->insert_id;
												$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/queuecustomjob/$cid/$user->id");
												if(trim($json) == "true")
												{
													display_ok("Custom Interval Saved","Custom Interval queued with start at ".htmlspecialchars($_POST['from_date'])." to ".htmlspecialchars($_POST['to_date'])." and a  $_POST[query_interval] second interval");
													//include("templates/cinterval/forms/add.tpl.php");
													renderCustomJobs($node->id);
													renderSingleGraph($node->id,$_GET['plugin']);
												}
												else
												{
													$db->query("DELETE FROM plugins_custom_interval WHERE id = '$cid'");
													display_error("Queue Error","Unable to queue job on collector. Please try again later");
													include("templates/cinterval/forms/add.tpl.php");	
													renderSingleGraph($node->id,$_GET['plugin']);
												}																
											}
											else
											{
												display_error("Queue Error","Unable to store custom interval in database, please try again later");
												include("templates/cinterval/forms/add.tpl.php");	
												renderSingleGraph($node->id,$_GET['plugin']);												
											}

										}
									}
								}
								else
								{
									//echo "from_time, to_time 0:00 else";
									//print_r($_POST);
									// parse hours
									$fht = explode(":",$_POST['from_time']);
									$tht = explode(":",$_POST['to_time']);
									
									if($tht[0] < $fht[0])
									{
										display_error("Input Error","Target Time needs to be higher then Source Time");
										include("templates/cinterval/forms/add.tpl.php");	
										renderSingleGraph($node->id,$_GET['plugin']);												
									}
									else
									{
										// run a interval in given hours with a start and end date
										if(trim($_POST['from_date']) != "")
										{
											if(trim($_POST['to_date']) == "")
											{
												display_error("Input Error","Start Date set but no Target Date");
												include("templates/cinterval/forms/add.tpl.php");	
												renderSingleGraph($node->id,$_GET['plugin']);											
											}			
											else
											{
												$from = getTimeStampFromStringForZone($_POST['from_date']." 00:00",$_SESSION['timezone']);
												$to   = getTimeStampFromStringForZone($_POST['to_date']." 23:59:59",$_SESSION['timezone']);		
												$from_hour = $fht[0];
												$to_hour = $tht[0];
												$to_hour = $to_hour - 1;
												// build cron
												// run every 30 seconds from 16 to 17:00 = 0/30 * 16 * * ?
												$cron = "0/".$_POST['query_interval']." * ".$from_hour."-".$to_hour." * * ?";
												if(!is_numeric($_POST['retention']))
												{
													$retention = 0;
												}
												else
												{
													$retention = $_POST['retention'];
												}
												$db->query("INSERT INTO plugins_custom_interval (node_id,pluginname,query_interval,timezone,user_id,from_time,to_time,crontab,plugin_id,retention) VALUES ('$node->id','$plugin->pluginname','$_POST[query_interval]','$_SESSION[timezone]','$user->id','$from','$to','$cron','$plugin->id','$retention')");	
												if($db->affected_rows > 0)
												{
													$cid = $db->insert_id;
													$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/queuecustomjob/$cid/$user->id");
													if(trim($json) == "true")
													{
														display_ok("Custom Interval Saved","Custom Interval queued with start at ".htmlspecialchars($_POST['from_date'])." to ".htmlspecialchars($_POST['to_date'])." and a  $_POST[query_interval] second interval between: $from_hour:00 - $to_hour:00 in $_SESSION[timezone]");
														//include("templates/cinterval/forms/add.tpl.php");
														renderCustomJobs($node->id);
														renderSingleGraph($node->id,$_GET['plugin']);
													}
													else
													{
														$db->query("DELETE FROM plugins_custom_interval WHERE id = '$cid'");
														display_error("Queue Error","Unable to queue job on collector. Please try again later");
														include("templates/cinterval/forms/add.tpl.php");	
														renderSingleGraph($node->id,$_GET['plugin']);
													}																
												}
												else
												{
													display_error("Queue Error","Unable to store custom interval in database, please try again later");
													include("templates/cinterval/forms/add.tpl.php");	
													renderSingleGraph($node->id,$_GET['plugin']);												
												}	
											}						
										}
										// run a simple cron daily with no set start and end date
										else
										{
											$from_hour = $fht[0];
											$to_hour = $tht[0];
											$to_hour = $to_hour - 1;
											// build cron
											// run every 30 seconds from 16 to 17:00 = 0/30 * 16 * * ?
											$cron = "0/".$_POST['query_interval']." * ".$from_hour."-".$to_hour." * * ?";	
											if(!is_numeric($_POST['retention']))
											{
												$retention = 0;
											}
											else
											{
												$retention = $_POST['retention'];
											}
											$db->query("INSERT INTO plugins_custom_interval (node_id,pluginname,query_interval,timezone,user_id,crontab,plugin_id,retention) VALUES ('$node->id','$plugin->pluginname','$_POST[query_interval]','$_SESSION[timezone]','$user->id','$cron','$plugin->id','$retention')");	
											if($db->affected_rows > 0)
											{
												$cid = $db->insert_id;
												$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/queuecustomjob/$cid/$user->id");
												if(trim($json) == "true")
												{
													display_ok("Custom Interval Saved","Custom Interval queued with $_POST[query_interval] second interval between: $from_hour:00 - $to_hour:00 in $_SESSION[timezone]. repeating forever");
													renderCustomJobs($node->id);
													renderSingleGraph($node->id,$_GET['plugin']);
												}
												else
												{
													$db->query("DELETE FROM plugins_custom_interval WHERE id = '$cid'");
													display_error("Queue Error","Unable to queue job on collector. Please try again later");
													include("templates/cinterval/forms/add.tpl.php");	
													renderSingleGraph($node->id,$_GET['plugin']);
												}																
											}
											else
											{
												display_error("Queue Error","Unable to store custom interval in database, please try again later");
												include("templates/cinterval/forms/add.tpl.php");	
												renderSingleGraph($node->id,$_GET['plugin']);												
											}				
										} 
									}
									
								}		
							}	
						}	// end if(!$fail)				
					}
				}
				?>

				<?php
				if(!$nodisplay)
				{
					if(!$_GET['period'])
					{
						$period = "24h";
					}
					else
					{
						$period = $_GET['period'];
					}				
					if(!$_GET['category'] && !$_GET['plugin'])
					{
	
						renderAllGraphs($node->id,$period);
					}
					elseif($_GET['category'])
					{
						renderAllGraphs($node->id,$period,$_GET['category']);								
					}
				}
				
				if($_GET['action'] == "edit")
				{
					if($_SESSION['role'] != "admin" && $_SESSION['role'] != "userext")
					{
						header("Location: 403.php");
						die;
					}
					if(!$_POST)
					{
						include("templates/munin/edit.tpl.php");
					}
					else
					{
						if($_POST['via_host'] != "unset")
						{
							$poignore = true;
						}
						else
						{
							$poignore = false;
						}	
											
						if(!isPortOpen($_POST['hostname'],$_POST['port'],$poignore))
						{
							display_error("Port not open",$_POST['port']. "on host ".$_POST['hostname']." is not open");	
							include("templates/munin/edit.tpl.php");
						}
						else
						{
							$_POST = secureArray($_POST);
							$db->query("UPDATE nodes SET hostname = '$_POST[hostname]', port = '$_POST[port]', query_interval = '$_POST[query_interval]', groupname = '$_POST[groupname]', via_host = '$_POST[via_host]', authpw = '$_POST[authpw]' WHERE id = '$node->id'");
							if($db->affected_rows > 0)
							{
									
								if($_POST['query_interval'] != $node->query_interval || $_POST['via_host'] != $node->via_host || $_POST['authpw'] != $node->authpw)
								{
									$ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletejob/$node->id/$node->user_id");
									sleep(3);
									$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/queuejob/$node->id");
									if(trim($json) == "true")
									{
										display_ok("Node Updated and Requeued","We updated the database and requeued the job on the MCD");	
									}	
									else
									{
										display_warn("Unable to requeue","Query Interval changed but we failed to requeue. Try again?");
									}		
								}
								else
								{
									display_ok("Node Updated","Changed Saved");	
								}
							}
							else
							{
								display_warning("No Change","Nothing on this node have been changed");	
							}
							// $ret = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/deletejob/$node->id/$node->user_id");
							// sleep(3);
							// $json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/queuejob/$node->id");	
						}
						setcookie("random",uniqid(microtime()));	
					}
				}

				?>



				</section>

<!-- Snapshot Modal -->
<!-- Modal -->
				<div class="modal fade" id="essentialModal" tabindex="-1" role="dialog" aria-labelledby="essentialModal" aria-hidden="true">
					<div class="modal-dialog" style="width:75%; height: 700px">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									&times;
								</button>
								<h4 class="modal-title" id="essentialModalLabel">System Snapshot</h4>
							</div>
							<div class="modal-body">
								 <iframe name="frameEssential" id="frameEssential" scrolling="no" height="500px" width="100%" frameborder="0" src="loadme.html"></iframe>				
							</div>
						</div>
					</div>
				</div>
<!-- End Snapshot Modal -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
		<script type="application/javascript">
			$(document).ready(function() {
				$('#nodetable').dataTable({
					"sPaginationType" : "bootstrap_full"
				});
			})

		
			<?php include("templates/core/viewScripts.tpl.php");?>		
		
			
			// validate edit field
			var $editForm = $('#editForm').validate({
			// Rules for form validation
				rules : {
					hostname : {
						required : true
					},
					groupname : {
						required : true
					},					
					port : {
						required : true,
						digits : true
					}
				},
		
				// Messages for form validation
				messages : {
					hostname : {
						required : 'Please enter a valid hostname or ip'
					},
					port : {
						required : 'Please enter the port of the munin node. 4949 is default'
					},
					port : {
						required : 'Please enter a groupname'
					}					
				},
		
				// Do not change code below
				//errorPlacement : function(error, element) {
				//	error.insertAfter(element.parent());
				//}
			});
			
			// validate edit field
			var $customform = $('#customform').validate({
			// Rules for form validation
				rules : {
					query_interval : {
						required : true,
						digits : true,
						min : 10,
						max: 300
					}
					
				},
		
				// Messages for form validation
				messages : {
					query_interval : {
						required : 'Please enter a valid query interval in seconds (10 minimum)'
					}		
				},
		

			});	
			
			
			 $('#from_time').timepicker({
                showSeconds: false,
                showMeridian: false,
                minuteStep: 60,
                defaultTime: "00:00"
            });
            
			 $('#to_time').timepicker({
                showSeconds: false,
                showMeridian: false,
                minuteStep: 60,
                defaultTime: "00:00"
            });		
            
            
			$("#from_date").datepicker({
			    defaultDate: "+1w",
			    changeMonth: true,
			    numberOfMonths: 3,
			    prevText: '<i class="fa fa-chevron-left"></i>',
			    nextText: '<i class="fa fa-chevron-right"></i>',
			    onClose: function (selectedDate) {
			        $("#to_date").datepicker("option", "minDate", selectedDate);
			    }
		
			});
			$("#to_date").datepicker({
			    defaultDate: "+1w",
			    changeMonth: true,
			    numberOfMonths: 3,
			    prevText: '<i class="fa fa-chevron-left"></i>',
			    nextText: '<i class="fa fa-chevron-right"></i>',
			    onClose: function (selectedDate) {
			        $("#from_date").datepicker("option", "maxDate", selectedDate);
			    }
			});
		
			$('#dashboard_id').on('change', function() {
				
				if($( "#dashboard_id" ).val() == "newdash")
				{
					$( "#newdashgroup" ).html('<input class="form-control" id="dashboard_name" name="dashboard_name" placeholder="Enter a name for this dashboard" type="text">');
				}
			});		            
		</script>
	</body>

</html>