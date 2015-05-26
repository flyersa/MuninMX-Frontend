							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-check" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"> 
								<!-- widget options:
									usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
									
									data-widget-colorbutton="false"	
									data-widget-editbutton="false"
									data-widget-togglebutton="false"
									data-widget-deletebutton="false"
									data-widget-fullscreenbutton="false"
									data-widget-custombutton="false"
									data-widget-collapsed="true" 
									data-widget-sortable="false"
									
								-->
								<header>
									<span class="widget-icon"> <i class="fa fa-eye"></i> </span>
									<h2><strong><?php echo htmlspecialchars($check->check_name)?></strong> <i><?php echo $check->check_desc?></i></h2>		
																			
									<div class="widget-toolbar">
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
												Timeframe <i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu" style="text-align: left;">
												<li><a href="" onClick="applyTime('86400'); return false;">24 Hour</a></li>
												<li><a href="" onClick="applyTime('172800'); return false;">48 Hours</a></li>
												<li><a href="" onClick="applyTime('604800'); return false;">1 Week</a></li>
												<li><a href="" onClick="applyTime('2629743'); return false">1 Month</a></li>
												<li><a href="" onClick="applyTime('7889229'); return false;">3 Months</a></li>
												<li><a href="" onClick="applyTime('15778458'); return false;">6 Months</a></li>
												<li><a href="" onClick="applyTime('30758400'); return false;">1 Year</a></li>
											</ul>
										</div>		
										<div class="btn-group">
										    	<button class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">Tasks <span class="caret"></span></button>
										    	<ul class="dropdown-menu" style="text-align: left;">
										    		<li><a href="checks.php?action=view&cid=<?php echo $check->id?>"><i class="icon-search"></i> Details</a></li>
										    		<li><a href="checks.php?action=export&cid=<?php echo $check->id?>"><i class="icon-download"></i> Export</a></li>
										    		<?php if($check->user_id == $_SESSION['user_id'] || $_SESSION['role'] == "admin") { ?>
										         	<li><a href="checks.php?action=edit&cid=<?php echo $check->id?>"><i class="icon-edit"></i> Edit</a></li>
													<li class="divider"></li>
										            <li><a href="checks.php?action=delete&cid=<?php echo $check->id?>"><i class="icon-trash"></i> Delete</a></li>
										            <?php } ?>
										         </ul>
									</div>																												
									</div>
								</header>
				
									<div class="widget-body-toolbar no-padding">
										<span style="float: left;">
											<input type="text" id="date<?php echo $tpl->id?>" style="width: 500px" name="date<?php echo $tpl->id?>" onchange="applyFilter()" placeholder="Click here to select a single day for results" class="form-control datepicker" data-dateformat="dd/mm/yy">	
										</span>
									</div>
				
								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body" id="graph" style="min-height: 500px">
										<div align="center" style="margin-top: 100px"><img src="img/loading_icon.gif" align="center"></div>
									</div>
									<!-- end widget content -->
									<script type="application/javascript">
										$( "#graph" ).load( "graphCheck.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>" );
										
										function applyTime(timeframe)
										{
											//timeframe = $( "#date<?php echo $tpl->id?>" ).val();
											$( "#graph" ).html('<div align="center" style="margin-top: 100px"><img src="img/loading_icon.gif" align="center"></div>');
											$( "#graph" ).load("graphCheck.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>&timeframe="+timeframe);
										}
										
										function applyFilter()
										{
											timeframe = $( "#date<?php echo $tpl->id?>" ).val();
											$( "#graph" ).html('<div align="center" style="margin-top: 100px"><img src="img/loading_icon.gif" align="center"></div>');
											$( "#graph" ).load("graphCheck.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>&day="+timeframe);
										}
									</script>
								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->