				<?php 
				    // show cienabled button?
					$cienabled = false;
					if($_SESSION['role'] == "admin")
					{
						$cienabled = true;
					}
					else
					{
						//print_r($user);
						if($user->max_customs > 0)
						{
							
							$cienabled = true;
						}	
					}
				?>
				<?php if($tpl->plugincategory == "null") { $tpl->plugincategory = "other";} ?>
				<!-- row -->
				<div class="row">
					<a name="<?php echo htmlspecialchars($tpl->pluginname)?>"></a>
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-eye"></i> </span>
									<h2><?php echo htmlspecialchars($tpl->plugincategory) ?> - <?php echo htmlspecialchars($tpl->plugintitle)?></h2>
									<div class="widget-toolbar">
	

									<!-- add: non-hidden - to disable auto hide -->	
									<!-- http://skoll/live/graphframe.php?node=1&plugin=cpu -->
										<div class="btn-group" id="snapBtn<?php echo $tpl->pluginname?>" style="display: none">
											<button class="btn dropdown-toggle btn-xs btn-default"  rel="tooltip" data-placement="top" data-original-title="View System Snapshot">
													<i class="fa fa-camera-retro"></i> <span id="snapLnk<?php echo $tpl->pluginname?>">Show Snapshot</span>
											</button>
										</div>										

										<div class="btn-group" id="sdataBtn<?php echo $tpl->pluginname?>" style="display: none">
											<button class="btn btn-xs btn-default" rel="tooltip" data-placement="top" data-original-title="Open single data view (pinpoint)">
													<i class="fa fa-bullseye"></i> <span id="sdataLnk<?php echo $tpl->pluginname?>">/span>
											</button>
										</div>	

																		
										<?php if($node->via_host == "unset") { ?>										
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-danger" onclick="loadnewframe('live/graphframe.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo $tpl->pluginname?>','frame<?php echo $tpl->id?>')">
													Show in Realtime <i class="fa fa-eye-slash"></i>
											</button>
										</div>
										<?php } ?>	
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
												Graph Mode <i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu" style="text-align: left;">
												<li><a onClick="reloadFrameWithType('frame<?php echo $tpl->id?>','line','<?php echo $tpl->node_id ?>','<?php echo htmlspecialchars($tpl->pluginname); ?>'); return false;">Line</a></li>
												<li><a onClick="reloadFrameWithType('frame<?php echo $tpl->id?>','area','<?php echo $tpl->node_id ?>','<?php echo htmlspecialchars($tpl->pluginname); ?>'); return false;">Area</a></li>
												<li><a onClick="reloadFrameWithType('frame<?php echo $tpl->id?>','areastack','<?php echo $tpl->node_id ?>','<?php echo htmlspecialchars($tpl->pluginname); ?>'); return false;">Area Stacked</a></li>
												<li><a href="#" onClick="reloadFrameWithType('frame<?php echo $tpl->id?>','column','<?php echo $tpl->node_id ?>','<?php echo htmlspecialchars($tpl->pluginname); ?>'); return false;">Column</a></li>
											</ul>
										</div>											
																									
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
												Timeframe <i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu" style="text-align: left;">
														<li><a href="graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=24h" target="frame<?php echo $tpl->id?>" onClick="trackLog('graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=24h','frame<?php echo $tpl->id?>')">24 Hour</a></li>
														<li><a href="graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=week" target="frame<?php echo $tpl->id?>" onClick="trackLog('graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=week','frame<?php echo $tpl->id?>')">1 Week</a></li>
														<li><a href="graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=month" target="frame<?php echo $tpl->id?>" onClick="trackLog('graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=month','frame<?php echo $tpl->id?>')">1 Month</a></li>
														<li><a href="graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=tmonth" target="frame<?php echo $tpl->id?>" onClick="trackLog('graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=tmonth','frame<?php echo $tpl->id?>')">3 Months</a></li>
														<li><a href="graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>" target="frame<?php echo $tpl->id?>" onClick="trackLog('graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>','frame<?php echo $tpl->id?>')">1 Year</a></li>
											</ul>
										</div>	

										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
												Settings <i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu" style="text-align: left;">
												<li><a href="view.php?nid=<?php echo $tpl->node_id?>&action=dashboard&plugin=<?php echo $tpl->id?>""><i class="fa fa-dashboard"></i> Add to Dashboard</a></li>
												<?php if($cienabled) { ?>
													<?php $pc = getCustomIntervalCountForPlugin($tpl->id); if ($pc < 1) { $pcc = "";} else { $pcc = "[$pc]";}; ?>
													<li><a href="view.php?nid=<?php echo $tpl->node_id?>&action=customs&plugin=<?php echo $tpl->id?>"><i class="fa fa-clock-o"></i> Custom Intervals <?php echo $pcc?></a>	</li>	
												<?php } ?>	
												<?php $ac = getAlertsForNodeAndPlugin($tpl->node_id,$tpl->pluginname); if ($ac < 1) { $acc = "";} else { $acc = "[$ac]";}; ?>
													<li><a href="alerts.php?action=alerts&sub=create&node=<?php echo $tpl->node_id?>&plugin=<?php echo htmlspecialchars($tpl->id)?>"><i class="fa fa-bell-o"></i> Notifications <?php echo $acc?></a></li>	
											</ul>
										</div>											
										<div class="btn-group">
										<a class="btn btn-xs btn-default" title="Pop Out Window" href="javascript:popOutFrame('frame<?php echo $tpl->id?>','graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=<?php echo $tpl->period?>')"><i class="fa fa-external-link-square"></i></a>
										</div>																																															
										<div class="btn-group">
											
											<a href="#top" class="btn dropdown-toggle btn-xs btn-info"><i class="fa fa-sort-up"></i> Back to Top</a>		
										</div>										
									</div>
									
								</header>
								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body no-padding">
										<div class="widget-body-toolbar">
											<span style="float: left">
											<div class="btn-group">
												<button class="btn dropdown-toggle btn btn-default" data-toggle="dropdown">
													Jump to other metrics <i class="fa fa-caret-down"></i>
												</button>
												<ul class="dropdown-menu pull-left">
													<?php echo $tpl->links?>
												</ul>
											</div>
											</span>												
											<span style="float: left; padding-left: 10px">
												<input type="text" id="date<?php echo $tpl->id?>" style="width: 500px" name="date<?php echo $tpl->id?>" onchange="loadnew('graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>','frame<?php echo $tpl->id?>','date<?php echo $tpl->id?>')" placeholder="or click here to select a single day for this metric" class="form-control datepicker" data-dateformat="dd/mm/yy">	
											</span>
										</div>
										<?php if($tpl->plugininfo != "null") { ?>
										<div class="widget-body-toolbar">
											<?php echo htmlspecialchars($tpl->plugininfo); ?>				
										</div>
										<?php } ?>
										  <iframe name="frame<?php echo $tpl->id?>" id="frame<?php echo $tpl->id?>" onload="lzld(this)" width="100%" src="loadme.html" data-src="graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=<?php echo $tpl->period?>" scrolling="no" height="500px" frameborder="0" ></iframe>		
									</div>
								</div>
							</div>
					</article>
				</div>
				<!-- end row -->
