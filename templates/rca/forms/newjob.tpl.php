<?php
display_info("About","This Analyzer Tool can help you to find deviations in metric data by performing simple average comparisations of metric data. For proper results it is important to filter your query as best as possible. Select short timeframes and filter by group/category");
?>
				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-plus-square"></i> </span>
									<h2>New Analyzer Job</h2>

									
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
										 
										<form class="smart-form" name="rcaform" id="rcaform" action="<?php echo getCurUrl()?>" method="POST">
          									<div class="padded">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Input Day</label>
														<div class="col-md-10">
																<input class="form-control" name="input_day" id="input_day" type="text" placeholder="Input Day" value="<?php echo date("m/d/Y")?>">
															<div class="note">
															<strong>Info:</strong> Select a input day. The input day will be compared with the averages of selected analysis days
															</div>
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Start Hour</label>
														<div class="col-md-10">
															<input class="form-control" name="start_hour" id="start_hour" type="text" placeholder="Select Start Time" value="">
															<div class="note">
															<strong>Info:</strong> Analyze between Start and End Hour
															</div>
														</div>
													</div>
												</fieldset>											
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">End Hour</label>
														<div class="col-md-10">
															<input class="form-control" name="end_hour" id="end_hour" type="text" placeholder="Select Start Time" value="">
															<div class="note">
															<strong>Info:</strong> Analyze between Start and End Hour
															</div>
														</div>
													</div>
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Analysis Days</label>
														<div class="col-md-10">
															<select class="select2" name="analysis_days">
																<option value="3" selected>3 Days</option>
																<option value="4">4 Days</option>
																<option value="5">5 Days</option>
																<option value="6">6 Days</option>
																<option value="7">1 Week</option>
																<option value="14">2 Weeks</option>
																<option value="31">1 Month</option>
															</select>
															<div class="note">
															<strong>Info:</strong> Input Day with start and end time will be compared against the averages of the past days set here in the same timeframe. 
															</div>
														</div>
													</div>
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Run on Nodes with Group</label>
														<div class="col-md-10">
															<select class="select2" name="group">
																<option value="allXXall">Run on ALL nodes, ignore group</option>
																<?php renderGroupDropDown() ?>
															</select>
															<div class="note">
															<strong>Info:</strong> select a group for this analysis. Other nodes will be ignored. Or run on all nodes (might take a long time)
															</div>
														</div>
													</div>
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Only Analyze Plugins with Category</label>
														<div class="col-md-10">
															<select class="select2" name="categoryfilter">
																<option value="allXXall">Include All Categorys</option>
																<?php renderPluginCategoryDropDown() ?>
															</select>
															<div class="note">
															<strong>Info:</strong> select a category to run analysis on. Only Plugins with the given category are analysed
															</div>
														</div>
													</div>
												</fieldset>																																
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Match Percentage</label>
														<div class="col-md-10">
															<select class="select2" name="percentage">
																<option value="10">10%</option>
																<option value="15">15%</option>
																<option value="20">20%</option>
																<option value="25">25%</option>
																<option value="30">30%</option>
																<option value="35">35%</option>
																<option value="40">40%</option>
																<option value="45">45%</option>
																<option value="50">50%</option>
																<option value="55">55%</option>
																<option value="60">60%</option>
																<option value="65">65%</option>
																<option value="70">70%</option>
																<option value="75">75%</option>
																<option value="80">80%</option>
																<option value="85">85%</option>
																<option value="90">90%</option>
																<option value="95">95%</option>		
																<option value="100" selected>100%</option>	
																<option value="120">120%</option>	
																<option value="140">140%</option>	
																<option value="160">160%</option>	
																<option value="180">180%</option>	
																<option value="200">200%</option>															
															</select>
															<div class="note">
															<strong>Info:</strong> The Analyser will add one result if the input day average in the start/end timeframe is higher or equal the % difference (negative or positive) of the average values from analysis days.
															</div>
														</div>
													</div>
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Ignore Threshold</label>
														<div class="col-md-10">
															<input class="form-control" name="threshold" id="threshold" type="text" placeholder="Enter a threshold value. 10.00 is recommended" value="10.00">
															<div class="note">
															<strong>Info:</strong> Ignore Input averages this or below this value. Example: 10.00 means ignore all results where the initial average used for comparisation is 10 or less then 10. 
															</div>
														</div>
													</div>
												</fieldset>																																				
										</div>
       										<footer>
												<button type="submit" class="btn btn-primary">
													Submit Job
												</button>
											</footer>												
											</form>
								</div>
							</div>
					</article>
				</div>
				<!-- end row -->												