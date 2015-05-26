				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-eye"></i> </span>
									<h2><?php echo $bucket->statname?></h2>
									<div class="widget-toolbar">
															
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
												Timeframe for this metric<i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu">
														<li><a href="graphBucket.php?bid=<?php echo $bucket->id ?>&period=24h" target="frame<?php echo $bucket->id?>">24 Hour</a></li>
														<li><a href="graphBucket.php?bid=<?php echo $bucket->id?>&period=week" target="frame<?php echo $bucket->id?>">1 Week</a></li>
														<li><a href="graphBucket.php?bid=<?php echo $bucket->id ?>&period=month" target="frame<?php echo $bucket->id?>">1 Month</a></li>
														<li><a href="graphBucket.php?bid=<?php echo $bucket->id?>&period=tmonth" target="frame<?php echo $bucket->id?>">3 Months</a></li>
														<li><a href="graphBucket.php?bid=<?php echo $bucket->id?>" target="frame<?php echo $bucket->id?>">1 Year</a></li>
											</ul>
										</div>	
										<?php if($bucket->user_id == $_SESSION['user_id']) { ?>																			
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-danger" data-toggle="dropdown">
												Admin Options<i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu">
												<li><a href="buckets.php?action=edit&bid=<?php echo $bucket->id?>"><i class="fa fa-edit"></i> Edit</a></li>
												<li><a href="buckets.php?action=view&delete=true&bid=<?php echo $bucket->id?>"><i class="fa fa-trash-o"></i> Delete</a></li>
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
											<span style="float: left">
											</span>												
											<span style="float: left; padding-left: 10px">
												<input type="text" id="date<?php echo $tpl->id?>" style="width: 500px" name="date<?php echo $tpl->id?>" onchange="loadnew('graphBucket.php?bid=<?php echo $bucket->id ?>','frame<?php echo $bucket->id?>','date<?php echo $bucket->id?>')" placeholder="or click here to select a single day for this metric" class="form-control datepicker" data-dateformat="dd/mm/yy">	
											</span>
										</div>
										<?php if($tpl->plugininfo != "null") { ?>
										<div class="widget-body-toolbar">
											<?php echo $tpl->plugininfo; ?>				
										</div>
										<?php } ?>
										  <iframe name="frame<?php echo $bucket->id?>" id="frame<?php echo $tpl->id?>" onload="lzld(this)" width="100%" src="about:blank" data-src="graphBucket.php?bid=<?php echo $bucket->id?>&period=week" scrolling="no" height="500px" frameborder="0" ></iframe>		
									</div>
								</div>
							</div>
					</article>
				</div>
				<!-- end row -->