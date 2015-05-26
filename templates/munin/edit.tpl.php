				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
									<h2><?php echo $node->hostname?></h2>

									
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
										
										<form class="smart-form" name="editForm" id="editForm" action="<?php echo getCurUrl() ?>" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Hostname</label>
														<div class="col-md-10">
															<input class="form-control" name="hostname" placeholder="Valid Hostname or IP" type="text" value="<?php echo $node->hostname?>">
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Contact Via Other Node?</label>
														<div class="col-md-10">
															<?php renderViaHostDropDown($node->via_host);?>
															<div class="note">
															<strong>Info:</strong> This is only required if another munin-node serves the plugin. As example for snmp. Leave on default if unsure
															</div>
														</div>
														
													</div>
												</fieldset>												
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Port</label>
														<div class="col-md-10">
															<input class="form-control" name="port" placeholder="Munin Port" type="text" value="<?php echo $node->port?>">
														</div>
													</div>	
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Optional Auth Password</label>
														<div class="col-md-10">
															<input class="form-control" name="authpw" placeholder="Leave empty for none" type="text" value="<?php echo $node->authpw?>">
															<div class="note">
															<strong>Info:</strong> If a Auth Password is set the MuninMX collector will authenticate against a pseudo plugin before loading plugins. Leave empty if unsure
															</div>															
														</div>
													</div>	
												</fieldset>													
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Query Interval</label>
														<div class="col-md-10">
															<label class="select">
	
															<select name="query_interval">
																<option value="1" <?php if($node->query_interval == "1") { echo 'selected';}?>>1 Minute</option>
																<option value="5" <?php if($node->query_interval == "5") { echo 'selected';}?>>5 Minutes</option>
																<option value="10" <?php if($node->query_interval == "10") { echo 'selected';}?>>10 Minutes</option>
															</select>
															</label>
														</div>
													</div>		
												</fieldset>		
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Group</label>
														<div class="col-md-10">
															<input class="form-control" placeholder="Groupname" value="<?php echo $node->groupname?>" name="groupname" type="text" data-autocomplete='[<?php echo getAutoCompleteGroups()?>]'>
														</div>
													</div>	
												</fieldset>		
												
												<footer>
												<a href="view.php?nid=<?php echo $node->id?>&action=delete" class="btn btn-danger" style="float: left">
													Delete this Node
												</a>																
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


				