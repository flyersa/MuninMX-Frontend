				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
									<h2>Add</h2>
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
										<form class="smart-form" name="addForm" id="addForm" action="customs.php?action=add" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Enter a name for this custom graph</label>
														<div class="col-md-10">
															<input class="form-control" id="graph_name" name="graph_name" placeholder="Example: CPU Usage all CUSTOMER-X App Servers" type="text">
														</div>														
													</div>
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Enter a more descriptive description</label>
														<div class="col-md-10">
															<input class="form-control" id="graph_desc" name="graph_desc"  placeholder="" type="text">
														</div>														
													</div>
												</fieldset>	
												<?php if($_SESSION['role'] == "admin") { ?>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Assign to Access Group</label>
														<div class="col-md-10">
															<select style="width:100%" name="groupname" class="select2">
																<option value="none">None</option>
																<?php echo getGroupOptions();?>
															</select>
															<div class="note">
															<strong>Info:</strong> If you assign a Access Group to this graph, users with access to this group can see this in the custom metric menu.
															</div>
														</div>
													</div>	
												</fieldset>	
												<?php } ?>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Assign to Custom Metric Group</label>
														<div class="col-md-10" id="newmetricgroup">
															<select  style="width:100%" name="assigntogroup" id="assigntogroup" class="select2">
																<option value="none">None</option>
																<option value="newgroup">Create a New Group</option>
																<?php
																	if($_SESSION['role'] == "admin")
																	{
																	 	echo getCustomMetricGroupOptions();
																	}
																	else
																	{
																		echo getCustomMetricGroupOptions(false,$_SESSION['user_id']);
																	}
																?>
															</select>
															<div class="note">
															<strong>Info:</strong> a custom metric group consists of multiple custom graphs. 
															</div>															
														</div>
													</div>	
												</fieldset>																																																
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Select Base Host</label>
														<div class="col-md-10">
															<?php renderHostDropDown();?>
														</div>														
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Select Hosts to include</label>
															<div class="col-md-10">
															<?php renderHostDropDownMulti()?>
															</div>														
													</div>
												</fieldset>												
												<div id="loadplugins" name="loadplugins">

												</div>		
												<div id="loadgraphs" name="loadgraphs">

												</div>	
												
												<div id="togglebutton" style="display: none">
												<footer>
												<button type="submit" class="btn btn-primary">
													Save Metric
												</button>
												</footer>
												</div>																					
										</form>
									</div>
								</div>
							</div>
					</article>
				</div>
				<!-- end row -->	
				<div id="preview">
					
				</div>									