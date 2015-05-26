				<!-- row -->
				<div class="row" style="margin: 12px">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
									<h2>Edit Dashboard: <?php echo htmlspecialchars($board->dashboard_name)?></h2>
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
										 
										<form class="smart-form" name="dashform" id="dashform" action="dashboard.php?dashboard=<?php echo $board->id?>&edit=true" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Dashboard Name</label>
														<div class="col-md-10">
															<input class="form-control" name="dashboard_name" id="dashboard_name" type="text" value="<?php echo $board->dashboard_name?>">
															<div class="note">
															<strong>Info:</strong> Enter a descriptive title for this dashboard.
															</div>
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Group</label>
														<div class="col-md-10">
															<select name="groupname" class="select2">
															<option value="XXnoneXX">No Group</option>
															<?php renderGroupDropDown($board->groupname) ?>
															</select>
															<div class="note">
															<strong>Info:</strong> Enter a group to share this dashboard
															</div>
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Refresh Metrics every</label>
														<div class="col-md-10">
															<select name="global_refresh" class="select2">
															<option value="1"<?php if($board->global_refresh == 1) { echo ' selected';}?>>1 Minute</option>
															<option value="5"<?php if($board->global_refresh == 5) { echo ' selected';}?>>5 Minutes</option>
															<option value="10"<?php if($board->global_refresh == 10) { echo ' selected';}?>>10 Minutes</option>
															<option value="15"<?php if($board->global_refresh == 15) { echo ' selected';}?>>15 Minutes</option>
															<option value="30"<?php if($board->global_refresh == 30) { echo ' selected';}?>>30 Minutes</option>
															</select>
															<div class="note">
															<strong>Info:</strong> Metrics will reload every x minutes in this dashboard
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