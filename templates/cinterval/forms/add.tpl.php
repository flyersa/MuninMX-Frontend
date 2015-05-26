				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
									<h2>New Custom Interval for Plugin: <?php echo htmlspecialchars($plugin->pluginname)?> on Host: <?php echo htmlspecialchars($node->hostname)?> (<?php echo htmlspecialchars($node->groupname)?>)</h2>

									
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
										 
										<form class="smart-form" name="customform" id="customform" action="<?php echo getCurUrl()?>" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Interval in Seconds</label>
														<div class="col-md-10">
															<input class="form-control" name="query_interval" type="text" value="<?php echo $_POST['query_interval']?>" style="width: 550px">
															<div class="note">
															<strong>Info:</strong> Additional Query Interval for this Plugin in <b>seconds</b>. 10 seconds is the minimum supported value.
															</div>
														</div>
													</div>
												</fieldset>
												
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Data Retention</label>
														<div class="col-md-4">
															<select name="retention" class="select2">
																<option value="1">1 Day</option>
																<option value="2">2 Days</option>
																<option value="3">3 Days</option>
																<option value="7" selected>1 Week</option>
																<option value="14">2 Weeks</option>
																<option value="21">3 Weeks</option>
																<option value="31">1 Month</option>
																<option value="62">2 Months</option>
																<option value="92">3 Months</option>
																<option value="178">1/2 Year</option>
																<option value="356">1 Year</option>
																<option value="0">Keep Forever (NOT RECOMMENDED)</option>
															</select>
															<div class="note">
															<strong>Info:</strong> how long to keep custom interval data, default: delete data older 1 week
															</div>
														</div>
													</div>
												</fieldset>												

												<fieldset>

													<div class="form-group">
														<label class="col-md-2 control-label">Optional Daily Timerange</label>
														<div class="col-sm-4">
															From
															<div class="form-group">
																<div class="input-group">
																	<input class="form-control" name="from_time" id="from_time" type="text" placeholder="Select Start Time">
																	<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
																</div>
															</div>
					
														</div>
														<div class="col-sm-4" style="margin-left: 5px">
															To
															<div class="form-group">
																<div class="input-group">
																    <input class="form-control" id="to_time" name="to_time"type="text" placeholder="Select End Time">
																	<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
																</div>
															</div>
					
														</div>	
														
													</div>
													<div class="col-sm-4" style="margin-left: 5px">&nbsp;</div>	
												</fieldset>
																						
												<fieldset>

													<div class="form-group">
														<label class="col-md-2 control-label">Optional Date Range</label>
														<div class="col-sm-4">
															From
															<div class="form-group">
																<div class="input-group">
																	<input class="form-control" name="from_date" id="from_date" type="text" placeholder="Start Date">
																	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																</div>
															</div>
					
														</div>
														<div class="col-sm-4" style="margin-left: 5px">
															To
															<div class="form-group">
																<div class="input-group">
																	<input class="form-control" name="to_date" id="to_date" type="text" placeholder="Target Date">
																	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																</div>
															</div>
					
														</div>	
														<div class="col-sm-4" style="margin-left: 5px">&nbsp;</div>	
								
													</div>

											</fieldset>
				
				
																							
												<footer>
													<span style="flaot: left">Your Timezone:<br /> <?php echo $_SESSION['timezone'];?></span>
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