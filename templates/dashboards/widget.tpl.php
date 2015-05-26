			
						<!-- NEW WIDGET START -->
						<?php
						if($tpl->wsize == "large") {
						?>
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<?php } else { ?>
						<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<?php } ?>
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget" id="wid-id-<?php echo $tpl->plugin_id?>" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false">
								<header>
									<h2><strong><?php echo htmlspecialchars($tpl->groupname)?></strong> <i><?php echo htmlspecialchars($tpl->hostname)?></i> / <i><?php echo htmlspecialchars($tpl->plugintitle)?></i></h2>				
									<div class="widget-toolbar">
										
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
												Actions <i class="fa fa-caret-down"></i>
											</button>	
											<ul class="dropdown-menu pull-right">
												<li><a href="view.php?nid=<?php echo $tpl->node_id?>#<?php echo htmlspecialchars($tpl->pluginname)?>"><i class="fa fa-sitemap"></i> Goto Node</a></li>
												<li class="dropdown-submenu">
													<a tabindex="-1" href="javascript:void(0);"><i class="fa fa-calendar"></i> Timeframe</a>
													<ul class="dropdown-menu">
														<li>
															<a tabindex="-1" href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&period=30min&token=<?php echo getToken()?>">30 Minutes</a>
														</li>
														<li>
															<a href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&period=1hour&token=<?php echo getToken()?>">1 Hour</a>
														</li>
														<li>
															<a href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&period=2hour&token=<?php echo getToken()?>">2 Hours</a>
														</li>
														<li>
															<a href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&period=4hour&token=<?php echo getToken()?>">4 Hours</a>
														</li>	
														<li>
															<a href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&period=24hour&token=<?php echo getToken()?>">1 Day</a>
														</li>																																										
													</ul>
												</li>	
												<li class="dropdown-submenu">
													<a tabindex="-1" href="javascript:void(0);"><i class="fa fa-bar-chart-o"></i> Graph Mode</a>
													<ul class="dropdown-menu">
														<li>
															<a tabindex="-1" href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&stype=area&token=<?php echo getToken()?>">Area</a>
														</li>
														<li>
															<a href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&stype=line&token=<?php echo getToken()?>">Line</a>
														</li>
														<li>
															<a href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&stype=areastack&token=<?php echo getToken()?>">Area Stacked</a>
														</li>
														<li>
															<a href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&period=column&token=<?php echo getToken()?>">Column</a>
														</li>																																										
													</ul>
												</li>	
												<?php if($node->via_host == "unset") { ?>				
												<li class="dropdown-submenu">
													<a tabindex="-1" href="javascript:void(0);"><i class="fa fa-eye-slash"></i> Toggle Modus</a>
													<ul class="dropdown-menu">
														<li>
															<a tabindex="-1" href="live/graphframe.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo $tpl->pluginname?>&token=<?php echo getToken()?>&stype=<?php echo $tpl->stype?>" target="frame<?php echo $tpl->plugin_id?>">Live</a>
														</li>
														<li>
															<a href="graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=<?php echo $tpl->period?>&stype=<?php echo $tpl->stype?>&dateformat=short&notitle=true&refresh=<?php echo $tpl->refresh?>" target="frame<?php echo $tpl->plugin_id?>">Static</a>
														</li>																																									
													</ul>
												</li>														
												<?php } ?>
												<!--
												<li class="dropdown-submenu">
													<a tabindex="-1" href="javascript:void(0);"><i class="fa fa-resize-horizontal"></i> Widget Size</a>
													<ul class="dropdown-menu">
														<li>
															<a tabindex="-1" href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&wsize=small&token=<?php echo getToken()?>">Small</a>
														</li>
														<li>
															<a href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&wsize=large&token=<?php echo getToken()?>">Large</a>
														</li>																																										
													</ul>
												</li>	
												-->																																	
												<li class="divider"></li>
												<li>
												   <a href="dashboard.php?dashboard=<?php echo $_GET['dashboard']?>&item=<?php echo $tpl->item_id?>&remove=true&token=<?php echo getToken()?>"><i class="fa fa-trash-o"></i> Remove</a>
												</li>
												
											</ul>
										</div>
									</div>

								</header>
				
								<!-- widget div-->
								<div>
									
									
									<!-- widget content -->
									<div class="widget-body" style="min-height: 350px">
										
									<iframe name="frame<?php echo $tpl->plugin_id?>" id="frame<?php echo $tpl->plugin_id?>" onload="lzld(this)" width="100%"  height="350px"  src="loadme.html" data-src="graph.php?node=<?php echo $tpl->node_id ?>&plugin=<?php echo htmlspecialchars($tpl->pluginname)?>&period=<?php echo $tpl->period?>&stype=<?php echo $tpl->stype?>&dateformat=short&notitle=true&refresh=<?php echo $tpl->refresh?>" scrolling="no" frameborder="0"></iframe>		
									
										
									</div>
									<!-- end widget content -->
									
								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->
						</article>
					