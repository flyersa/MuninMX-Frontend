<?php
if(isset($_POST['graph']))
{
	$selv = $_POST['graph'];
}
else
{
	$selv = false;
}
?>				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-bell"></i> </span>
									<h2>New Alert for Plugin: <?php echo htmlspecialchars($plugin->pluginname)?> on Host: <?php echo htmlspecialchars($node->hostname)?> (<?php echo htmlspecialchars($node->groupname)?>)</h2>

									
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
										 
										<form class="smart-form" name="addalertform" id="addalertform" action="<?php echo getCurUrl()?>" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Monitor Graph</label>
														<div class="col-md-10">
															<?php renderGraphsForPluginAndNode($_GET['node'],$plugin->pluginname,$selv,false);?>
															<div class="note">
															<strong>Info:</strong> Select the graph you want to monitor
															</div>
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Alert Condition Value</label>
														<div class="col-md-10">
															<input type="text" class="form-control" name="raise_value" id="raise_value" value="<?php echo $_POST['raise_value']?>">
															<div class="note">
															<strong>Info:</strong> Alert if this value is met with the Alert Condition. Example: If 200 is set and Alert Condition is set to Greater Then. Alert is raised if the graph value is higher then 200.
															</div>
														</div>
													</div>
												</fieldset>												
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Alert Condition</label>
														<div class="col-md-10">
															<select class="select2" name="condition" id="condition">
																<option value="eq" <?php if($_POST['condition'] == "eq") { echo ' selected';}?>>Equal</option>
																<option value="lt" <?php if($_POST['condition'] == "lt") { echo ' selected';}?>>Less then</option>
																<option value="gt" <?php if($_POST['condition'] == "gt") { echo ' selected';}?>>Greater then</option>
																<option value="gtavg" <?php if($_POST['condition'] == "gtavg") { echo ' selected';}?>>Greater then average</option>
																<option value="ltavg" <?php if($_POST['condition'] == "ltavg") { echo ' selected';}?>>Less then average</option>
															</select>
															<div class="note">
															<strong>Info:</strong> Select the condition to met until a alert is raised. (if graph value is: equal, lt, gt, gtavg, ltavg then alert)
															</div>
														</div>
													</div>
												</fieldset>
											  <div id="hideme" style="display: none">
													<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Number of Samples</label>
														<div class="col-md-10">
															<select class="select2" name="num_samples" id="num_samples">
																<option value="2" <?php if($_POST['num_samples'] == "2") { echo ' selected';}?>>2</option>
																<option value="3" <?php if($_POST['num_samples'] == "3") { echo ' selected';}?>>3</option>
																<option value="4" <?php if($_POST['num_samples'] == "4") { echo ' selected';}?>>4</option>
																<option value="5" <?php if($_POST['num_samples'] == "5") { echo ' selected';}?>>5</option>
																<option value="6" <?php if($_POST['num_samples'] == "6") { echo ' selected';}?>>6</option>
																<option value="7" <?php if($_POST['num_samples'] == "7") { echo ' selected';}?>>7</option>
																<option value="8" <?php if($_POST['num_samples'] == "8") { echo ' selected';}?>>8</option>
																<option value="9" <?php if($_POST['num_samples'] == "9") { echo ' selected';}?>>9</option>
																<option value="10" <?php if($_POST['num_samples'] == "10") { echo ' selected';}?>>10</option>
																<option value="11" <?php if($_POST['num_samples'] == "11") { echo ' selected';}?>>11</option>
																<option value="12" <?php if($_POST['num_samples'] == "12") { echo ' selected';}?>>12</option>
																<option value="13" <?php if($_POST['num_samples'] == "13") { echo ' selected';}?>>13</option>
																<option value="14" <?php if($_POST['num_samples'] == "14") { echo ' selected';}?>>14</option>
																<option value="15" <?php if($_POST['num_samples'] == "15") { echo ' selected';}?>>15</option>
															</select>
															<div class="note">
															<strong>Info:</strong> Select the number of samples for average calculation. Example: 5 samples = 5 munin runs on this plugin. If Query Interval is 5 Minutes, 5 samples = average of 25 minutes
															</div>
														</div>
													</div>
												</fieldset>
												</div>					
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Re-Alert Time</label>
														<div class="col-md-10">
															<select class="select2" name="alert_time" id="alert_time">
																<option value="0">Instant, no Alert Pause (not recommended)</option>
																<option value="5" selected>5 Minutes</option>
																<option value="10">10 Minutes</option>
																<option value="15">15 Minutes</option>
																<option value="20">20 Minutes</option>
																<option value="30">30 Minutes</option>
																<option value="45">45 Minutes</option>
																<option value="60">60 Minutes</option>	
																<option value="90">90 Minutes</option>	
																<option value="120">120 Minutes</option>
																<option value="1440">1 Day</option>																																
															</select>
															<div class="note">
															<strong>Info:</strong> Select a time to wait before resending an alert in case the condition stil matches. It is highly recommended to set this to at least 5 minutes.
															</div>
														</div>
													</div>
												</fieldset>				
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Alert Contacts</label>
														<div class="col-md-10">
															<?php 
																if($_SESSION['role'] == "admin") { renderContactDropDown(true); } else { renderContactDropDown(); }
															?>
															<div class="note">
															<strong>Info:</strong> Select contacts to notify if alert condition is met
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
			