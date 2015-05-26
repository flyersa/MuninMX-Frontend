<?php
$dc = getDashboardCount();
?>
				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
									<h2>Add Plugin: <?php echo htmlspecialchars($plugin->pluginname)?> on Host: <?php echo htmlspecialchars($node->hostname)?> (<?php echo htmlspecialchars($node->groupname)?>) to Dashboard</h2>

									
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
										 
										<form class="smart-form" name="addtodashboard" id="addtodashboard" action="<?php echo getCurUrl()?>" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Dashboard</label>
														<div class="col-md-10" id="ffff">
															<?php if($dc < 1) { ?>
															<input class="form-control" id="dashboard_name" name="dashboard_name"  placeholder="" type="text" value="">
															<?php } else { ?>
																<span id="newdashgroup">
															<select  style="width:100%" name="dashboard_id" id="dashboard_id" class="select2">
																<option value="none">Please Select a Dashboard</option>
																<option value="newdash">Create a New Dashboard</option>
																<?php echo getDashboardOptions();?>
															</select>
															</span>
															<?php } ?>
															<div class="note">
															<strong>Info:</strong> Select a dashboard (if exists) or create a new dashboard by entering a name for the dashboard 
															</div>															
														</div>
													</div>	
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Timeframe</label>
														<div class="col-md-10" id="newmetricgroup">
															<select  style="width:100%" name="period" id="period" class="select2">
																<option value="30min">30 Minutes</option>
																<option value="1hour">1 Hour</option>
																<option value="2hour">2 Hours</option>
																<option value="4hour">4 Hours</option>
																<option value="24hour">24 Hours</option>
															</select>
															<div class="note">
															<strong>Info:</strong> Select the timeframe range for the metric on the dashboard. 
															</div>															
														</div>
													</div>	
												</fieldset>																						
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Graph Type</label>
														<div class="col-md-10" id="newmetricgroup">
															<select  style="width:100%" name="stype" id="stype" class="select2">
																<option value="area">Area</option>
																<option value="areastack">Area Stacked</option>
																<option value="line">Line</option>
																<option value="column">Column</option>
															</select>
															<div class="note">
															<strong>Info:</strong> Select the graph type for this metric on the dashboard.
															</div>															
														</div>
													</div>	
												</fieldset>													

																							
												<footer>												
												<button type="submit" class="btn btn-primary">
													Save Changes
												</button>
											</footer>
									
										</form>

									</div>
								</div>
							</div>
					</article>
				</div>
				<!-- end row -->