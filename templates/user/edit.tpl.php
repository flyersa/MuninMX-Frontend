				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
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
														<label class="col-md-2 control-label">Username</label>
														<div class="col-md-10">
															<input class="form-control" name="username" placeholder="Username" type="text" value="<?php echo $tpl->username?>" disabled>
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">E-Mail</label>
														<div class="col-md-10">
															<input class="form-control" name="email" placeholder="E-Mail" type="text" value="<?php echo $tpl->email?>" autocomplete="off">
														</div>
													</div>
												</fieldset>												
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Password</label>
														<div class="col-md-10">
															<input class="form-control" name="password" placeholder="Password, leave empty if you want to keep the old one" type="password" value="" autocomplete="off">
														</div>
													</div>	
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Autologin Key (<a href="#" onClick="genAKey(); return false"><i class="fa fa-magic"></i> Generate</a>)</label>
														<div class="col-md-10">
															<input class="form-control" name="autologinkey" id="akey" placeholder="Auto Login Key" type="text" value="<?php echo $tpl->autologinkey?>">
															<div class="note">
															<strong>Info:</strong> a autologin key can be used to autologin as this user with a special crafted URL which is usefull for 3rd party integration. See Documentation. Leave on default (unset) to disable
															</div>
														</div>
													</div>
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">API Key (<a href="#" onClick="genApiKey(); return false"><i class="fa fa-magic"></i> Generate</a>)</label>
														<div class="col-md-10">
															<input class="form-control" name="apikey" id="apikey" placeholder="API Key" type="text" value="<?php echo $tpl->apikey?>">
															<div class="note">
															<strong>Info:</strong> With a API Key users can receive node listings, plugin values, create bucket stats and so on. Admins can add users, nodes and so on.
															</div>
														</div>
													</div>
												</fieldset>												
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Data Retention Period</label>
														<div class="col-md-10">
															<select name="retention" class="select2">
																<option value="0"<?php if($tpl->retention == 0) { echo ' selected';}?>>Unlimited</option>
																<option value="1"<?php if($tpl->retention == 1) { echo ' selected';}?>>1 Month</option>
																<option value="2"<?php if($tpl->retention == 2) { echo ' selected';}?>>2 Months</option>
																<option value="3"<?php if($tpl->retention == 3) { echo ' selected';}?>>3 Months</option>
																<option value="4"<?php if($tpl->retention == 4) { echo ' selected';}?>>4 Months</option>
																<option value="5"<?php if($tpl->retention == 5) { echo ' selected';}?>>5 Months</option>
																<option value="6"<?php if($tpl->retention == 6) { echo ' selected';}?>>6 Months</option>
																<option value="7"<?php if($tpl->retention == 7) { echo ' selected';}?>>7 Months</option>
																<option value="8"<?php if($tpl->retention == 8) { echo ' selected';}?>>8 Months</option>
																<option value="9"<?php if($tpl->retention == 9) { echo ' selected';}?>>9 Months</option>
																<option value="10"<?php if($tpl->retention == 10) { echo ' selected';}?>>10 Months</option>
																<option value="11"<?php if($tpl->retention == 11) { echo ' selected';}?>>11 Months</option>
																<option value="12"<?php if($tpl->retention == 12) { echo ' selected';}?>>1 Year</option>
															</select>
															<div class="note">
															<strong>Info:</strong> Delete Snapshot and Metric Data which is older then this.
															</div>
														</div>
													</div>
												</fieldset>													
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Maximum Custom Intervals</label>
														<div class="col-md-10">
															<?php if(!isset($_POST[max_customs])) { $_POST[max_customs] = 0 ;} ?>
															<input class="form-control" name="maxcustoms" id="maxcustoms" placeholder="0" type="text" value="<?php echo $tpl->max_customs?>">
															<div class="note">
															<strong>Info:</strong> The maximum number of custom intervals this user can add.  custom intervals are second based intervals and range intervals on a per plugin base. 0 = not allowed, only applys to user and userext
															</div>
														</div>
													</div>
												</fieldset>		
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Maximum Nodes</label>
														<div class="col-md-10">
															<?php if(!isset($_POST[max_nodes])) { $_POST[max_nodes] = 0 ;} ?>
															<input class="form-control" name="max_nodes" id="max_nodes" placeholder="0" type="text" value="<?php echo $tpl->max_nodes?>">
															<div class="note">
															<strong>Info:</strong> The maximum number of nodes this user can add (only applys to userext role)
															</div>
														</div>
													</div>
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Maximum Service Checks</label>
														<div class="col-md-10">
															<?php if(!isset($_POST[max_checks])) { $_POST[max_checks] = 0 ;} ?>
															<input class="form-control" name="max_checks" id="max_checks" placeholder="0" type="text" value="<?php echo $tpl->max_checks?>">
															<div class="note">
															<strong>Info:</strong> The maximum number of service checks this user can add
															</div>
														</div>
													</div>
												</fieldset>													
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">SMS Tickets</label>
														<div class="col-md-10">
															<?php if(!isset($_POST[sms_tickets])) { $_POST[sms_tickets] = 0 ;} ?>
															<input class="form-control" name="sms_tickets" id="sms_tickets" placeholder="0" type="text" value="<?php echo $tpl->sms_tickets?>">
															<div class="note">
															<strong>Info:</strong> The number of SMS Tickets left to this user. If 0 user cannot send SMS Notifications, applys to ALL Roles
															</div>
														</div>
													</div>
												</fieldset>		
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">TTS Tickets</label>
														<div class="col-md-10">
															<?php if(!isset($_POST[tts_tickets])) { $_POST[tts_tickets] = 0 ;} ?>
															<input class="form-control" name="tts_tickets" id="tts_tickets" placeholder="0" type="text" value="<?php echo $tpl->tts_tickets?>">
															<div class="note">
															<strong>Info:</strong> The number of Text2Speech Tickets left to this user. If 0 user cannot send Text2Speech Notifications, applys to ALL Roles
															</div>
														</div>
													</div>
												</fieldset>																																																																								
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">User Role</label>
														<div class="col-md-10">
															<label class="select">
	
															<select name="userrole">
																<option value="admin" <?php if($tpl->userrole == "admin") { echo 'selected';}?>>Admin (ALL ACCESS)</option>
																<option value="user" <?php if($tpl->userrole == "user") { echo 'selected';}?>>User</option>
																<option value="userext" <?php if($tpl->userrole == "userext") { echo 'selected';}?>>User Extended</option>
															</select>
															</label>
															<div class="note">
															<strong>Info:</strong> Users can only view metrics in there assigned Access Groups (see below) and create custom and bucket metrics. Admins can see,edit and remove anything. User extended can add nodes themself and view OWN nodes, Access Groups are ignored
															</div>
														</div>
													</div>		
												</fieldset>		
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Access Groups</label>
														<div class="col-md-10">
															<select multiple style="width:100%" name="accessgroup[]" class="select2">
																<?php echo getGroupOptions($tpl->accessgroup);?>
															</select>
															<div class="note">
															<strong>Info:</strong> If the user got ADMIN as role. Access Groups have no effect. ADMIN Users can see everything. Access Groups are defined by adding a node (nodes define groups)
															</div>																
														</div>
													</div>	
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Events API</label>
														<div class="col-md-10">
															<select name="eventsallowed" class="select2">
																<option value="0"<?php if($tpl->eventsallowed == 0) { echo ' selected';}?>>Disallow</option>	
																<option value="1"<?php if($tpl->eventsallowed == 1) { echo ' selected';}?>>Allowed</option>	
															</select>
															<div class="note">
															<strong>Info:</strong> For users in the normal USER role. Allow to add events to assigned hosts?
															</div>
														</div>
													</div>
												</fieldset>															
												<footer>
												<a href="users.php?username=<?php echo htmlspecialchars($_GET['username'])?>&delete=true" class="btn btn-danger" style="float: left">
													Delete this user
												</a>													
												<button type="submit" class="btn btn-primary">
													Save Changes
												</button>
											</footer>
									
										</form>

									</div>
								
							</div>
					</article>
				</div>
				<!-- end row -->
<script type="application/javascript">
	function genAKey()
	{
	    var text = "";
	    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	
	    for( var i=0; i < 40; i++ )
	        text += possible.charAt(Math.floor(Math.random() * possible.length));
	
	    $('#akey').val(text);
	}
	function genApiKey()
	{
	    var text = "";
	    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	
	    for( var i=0; i < 40; i++ )
	        text += possible.charAt(Math.floor(Math.random() * possible.length));
	
	    $('#apikey').val(text);
	}	
</script>

				