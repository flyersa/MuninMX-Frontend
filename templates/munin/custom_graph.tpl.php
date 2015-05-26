<?php if(!isset($tpl->period)) { $tpl->period = "24h";} ?>
				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-eye"></i> </span>
									<h2><?php echo htmlspecialchars($tpl->graph_name)?></h2>
									<div class="widget-toolbar">
	
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
											Change Global Graph Mode <i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu">
												<li><a href="<?php echo getCurUrl()?>&stype=line">Line</a></li>
												<li><a href="<?php echo getCurUrl()?>&stype=area">Area</a></li>
											</ul>
										</div>	
																	
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
												Timeframe for this metric<i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu">
														<li><a href="custGraph.php?gid=<?php echo $tpl->id ?>&period=24h" target="frame<?php echo $tpl->id?>">24 Hour</a></li>
														<li><a href="custGraph.php?gid=<?php echo $tpl->id ?>&period=week" target="frame<?php echo $tpl->id?>">1 Week</a></li>
														<li><a href="custGraph.php?gid=<?php echo $tpl->id ?>&period=month" target="frame<?php echo $tpl->id?>">1 Month</a></li>
														<li><a href="custGraph.php?gid=<?php echo $tpl->id ?>&period=tmonth" target="frame<?php echo $tpl->id?>">3 Months</a></li>
														<li><a href="custGraph.php?gid=<?php echo $tpl->id ?>" target="frame<?php echo $tpl->id?>">1 Year</a></li>
											</ul>
										</div>										
										<?php if($_SESSION['role'] == "admin" || $tpl->user_id == $_SESSION['user_id']) { ?>
										<div class="btn-group">
											<button class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
												Admin Options
											</button>
											<button class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><a href="customs.php?action=edit&gid=<?php echo $tpl->id?>"><i class="fa fa-edit"></i> Edit Metric</a></li>	
												<li class="divider"></li>
												<li><a href="customs.php?action=delete&gid=<?php echo $tpl->id?>"><i class="fa fa-trash-o"></i> Delete Metric</a></li>	
											</ul>
										</div>	
										<?php } ?>	
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
											<input type="text" id="date<?php echo $tpl->id?>" name="date<?php echo $tpl->id?>" onchange="loadnew('custGraph.php?gid=<?php echo $tpl->id ?>','frame<?php echo $tpl->id?>','date<?php echo $tpl->id?>')" placeholder="click here to select a single day" class="form-control datepicker" data-dateformat="dd/mm/yy">	
									
										</div>

										<div class="widget-body-toolbar">
											<?php echo $tpl->graph_desc; ?>				
										</div>

										  <iframe name="frame<?php echo $tpl->id?>" id="frame<?php echo $tpl->id?>" onload="lzld(this)" width="100%" src="about:blank" data-src="custGraph.php?gid=<?php echo $tpl->id ?>&plugin=<?php echo $tpl->pluginname?>&period=<?php echo $tpl->period?>" scrolling="no" height="500px" frameborder="0" ></iframe>		
									</div>
								</div>
							</div>
					</article>
				</div>
				<!-- end row -->