				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
							<header>
									<span class="widget-icon"> <i class="fa fa-eye"></i> </span>
									<h2>Navigation</h2>
								</header>
								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body">
										<div class="widget-body-toolbar">
											Scroll to specific metric <span style="float: right">Last Update: <?php echo getFormatedLocalTime($node->last_contact_ts)?> (Interval: <?php echo $node->query_interval ?> Minutes)</span>
										</div>
										<div class="btn-group">
											<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
												(Quick Jump) - Available Metrics 
											</button>
											<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<?php echo $tpl->links?>
											</ul>
										</div>	
										<div class="btn-group">
											<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
												Change Timeframe 
											</button>
											<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><a href="<?php echo getCurUrl()?>&period=24h">24 Hour</a></li>
												<li><a href="<?php echo getCurUrl()?>&period=week">1 Week</a></li>
												<li><a href="<?php echo getCurUrl()?>&period=month">1 Month</a></li>
												<li><a href="<?php echo getCurUrl()?>&period=tmonth">3 Months</a></li>
												<li><a href="<?php echo getCurUrl()?>&period=full">1 Year</a></li>
											</ul>
										</div>	
									
										<?php if($_SESSION['role'] == "admin" || $_SESSION['role'] == "userext") { ?>
										<div class="btn-group" style="float: right; padding-left: 10px">
											<button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
												Admin Options
											</button>
											<button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><a href="<?php echo getCurUrl()?>&action=reloadplugins"><i class="fa fa-refresh"></i> Reload Plugins</a></li>	
												<li><a href="<?php echo getCurUrl()?>&action=edit"><i class="fa fa-edit"></i> Edit Node Details</a></li>	
												<li class="divider"></li>
												<li><a href="<?php echo getCurUrl()?>&action=delete"><i class="fa fa-trash-o"></i> Delete Node</a></li>	
											</ul>
										</div>	
										<?php } ?>	
										
										<?php
										if(key_exists($node->id, $_SESSION['disableevents']))
										{ 											
												echo '<a href="'.getCurUrl().'&disableevents=false" class="btn btn-default" style="float: right; margin-left: 10px"><i class="fa fa-bar-chart-o"></i> Enable Events </a>';
										} else {
												echo '<a href="'.getCurUrl().'&disableevents=true" class="btn btn-default" style="float: right; margin-left: 10px"><i class="fa fa-bar-chart-o"></i> Disable Events</a>';
										} 
										?>
										
										<?php
										$c = explode(",",$_COOKIE['favorites']);
										if(!in_array($node->id."|".$node->hostname." [".$node->groupname."]",$c)) 
										{ 											
												echo '<a href="'.getCurUrl().'&favorite=true" class="btn btn-default" style="float: right; margin-left: 10px"><i class="fa fa-star"></i> Add to Favorites</a>';
										} else {
												echo '<a href="'.getCurUrl().'&favorite=false" class="btn btn-default" style="float: right; margin-left: 10px"><i class="fa fa-star-o"></i> Remove from Favorites</a>';
										} 
										?>
									
										<div class="btn-group" style="float: right;">
											<button class="btn dropdown-toggle btn-default" data-toggle="dropdown">
											Change Global Graph Mode <i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu">
												<li><a href="<?php echo getCurUrl()?>&stype=line">Line</a></li>
												<li><a href="<?php echo getCurUrl()?>&stype=area">Area</a></li>
												<li><a href="<?php echo getCurUrl()?>&stype=areastack">Area Stacked</a></li>
												<li><a href="<?php echo getCurUrl()?>&stype=column">Column</a></li>
											</ul>
										</div>																														
									</div>
								</div>
							</div>
					</article>
				</div>
				<!-- end row -->


									
