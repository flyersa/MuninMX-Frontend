<?php
 $user = getUserObject($_SESSION['user_id']);
 $db->query("SELECT * FROM service_checks WHERE user_id = $user->id");
 $checks_in_use = $db->affected_rows;
?>
				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-plus"></i> </span>
									<h2>New Service Check</h2>
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
											   <span class="title"><i class="fa fa-info-circle"></i> &nbsp; <b><?php echo $checks_in_use; ?> of <?php echo $user->max_checks; ?></b> allowed service check slots in use</span>
										</div>
										
										<form class="smart-form" name="newCheckForm" id="newCheckForm" action="checks.php?action=add" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Name of Check</label>
														<div class="col-md-10">
															<input class="form-control" name="checkname" placeholder="Descriptive Name (Example: www.example.com https check)"  type="text" value="<?php echo $_POST['checkname']?>">
															<div class="note">
															<strong>Info:</strong> Make this a descriptive name for the service check. This is the name that will help you identify the check
															</div>
														</div>
													</div>
												</fieldset>
												
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Check Interval</label>
														<div class="col-md-10">
															<select name="interval" class="select2">
																<option value="1">Every minute</option>
																<option value="5" selected>Every 5 minutes</option>
																<option value="10">Every 10 minutes</option>
																<option value="15">Every 15 minutes</option>
																<option value="20">Every 20 minutes</option>
																<option value="30">Every 30 minutes</option>
																<option value="60">Every 60 minutes</option>
																<option value="720">2x per day</option>
																<option value="1440">Once a day</option>
															</select>
															<div class="note">
															<strong>Info:</strong> Check Interval. Example: If you select 10 minutes, MuninMX will run the check once every 10 minutes.
															</div>
														</div>
													</div>
												</fieldset>	

												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Check Tags</label>
														<div class="col-md-10">
															<input class="form-control" name="tags" id="tags" class="form-control" placeholder="linux,webserver,customername"  type="text" value="<?php echo $_POST['tags']?>">
															<div class="note">
															<strong>Info:</strong> Tags will help you later to batch add contacts or to perform other batch operations
															</div>
														</div>
													</div>
												</fieldset>	
												
												<?php if($_SESSION['role'] == "admin") { ?>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Access Group</label>
														<div class="col-md-10">
															<select name="accessgroup" class="select2 form-control">
																<option value="">None</option>
																<?php renderGroupDropDown()?>
															</select>
															<div class="note">
															<strong>Info:</strong> Will give Users with the assigned access group Read-Only access to this service check
															</div>
														</div>
													</div>													
												</fieldset>
												<?php } ?>
												
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Check Type</label>
														<div class="col-md-10">
															<select name="checktype" id="checktype" class="select2 checktype">
																<?php
																$result = $db->query("SELECT * FROM check_types");
																while($tpl = $result->fetch_object())
																{
																	echo '<option value="'.$tpl->id.'">'.$tpl->check_name.'</option>';	
																}
																?>
															</select>
															<div class="note">
															<strong>Info:</strong> Type of Service Check. PING would do a icmp check, Website a website check and so on
															</div>
														</div>
													</div>
												</fieldset>													
													
													
												<div id="subform">
												</div>													
										

												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Verify</label>
														<div class="col-md-10">
															<button type="button" id="tcbtn" class="btn btn-primary" onclick="testCheck()" style="padding: 5px">Test Check</button>  	
															<div class="note" id="testOutput">
															
															</div>
														</div>
													</div>
												</fieldset>											
		
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Notify Contacts</label>
														<div class="col-md-10">
															<?php 
																if($_SESSION['role'] == "admin")
																{
																	renderContactDropDown(true,true);
																}
																else
																{
																	renderContactDropDown();	
																}
															
															?>	
															<div class="note">
																Select contacts for notifications. Leave empty if you do not want to send notifications.
															</div>
														</div>
													</div>
												</fieldset>				
												<?php
												if($tpl->editmode)
												{
													chdir("../../..");
													include("inc/startup.php");
													if(!isLoggedIn())
													{
														header('HTTP/1.0 401 Unauthorized');
														die;
													}
													
													$check = returnServiceCheck($_GET['cid']);	
													
													if($check->user_id != $_SESSION['user_id'])
													{
														header('HTTP/1.0 401 Unauthorized');
														die;
													}
													$p = json_decode($check->json);
													if(!isset($p->notifydown))
													{
														$p->notifydown = 5;
													}
													if(!isset($p->notifyagain))
													{
														$p->notifyagain = 0;
													}
													if(!isset($p->notifyifup))
													{
														$p->notifyifup = 0;
													}		
													if(!isset($p->notifyflap))
													{
														$p->notifyflap = 0;
													}	
												}
												else
												{
													$p->notifydown = 5;
													$p->notifyagain = 0;
													$p->notifyifup = 1;
													$p->notifyflap = 0;
												}
												?>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Notify When Down</label>
														<div class="col-md-10">
															<input type="hidden" name="notifydown" id="notifydown" value="5">	
															<div id="slider"></div>
															<span id="notifydowndisp" style="float: right"><?php echo $p->notifydown?> Minutes</span>
														</div>
													</div>
												<script>
											  $(function() {
											  	var slideone = $('#slider');
											     slideone.slider({
											      value:<?php echo $p->notifydown?>,
											      min: 0,
											      max: 60,
											      step: 1,
											    });
											    
										        slideone.slider().on('slide', function (ev) {
										            slideone.slider('setValue', ev.value);
										            console.log("notify down set to " + ev.value);
										            $( "#notifydown" ).val(ev.value );
										            if(ev.value == "0")
										            {
										            	$( "#notifydowndisp" ).html(" Instant");	
										            }
										            else
										           	{
														$( "#notifydowndisp" ).html(ev.value + " minutes");
													}
										        });
											  });
											  
												  </script>
												</fieldset>		
								
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Repeat Notification Every</label>
														<div class="col-md-10">
															<input type="hidden" name="notifyagain" id="notifyagain" value="0">	
															<div id="slider2"></div>
															<span id="notifyagaindisp" style="float: right"><?php if($p->notifyagain == 0) { echo 'Never'; } else { echo $p->notifyagain . " down cycles";}?></span>
														</div>
													</div>
												<script>
											  $(function() {
											  	var slidetwo = $('#slider2');
											     slidetwo.slider({
											      value:<?php echo $p->notifyagain?>,
											      min: 0,
											      max: 60,
											      step: 1,
											    });
											    
										        slidetwo.slider().on('slide', function (ev) {
										            slidetwo.slider('setValue', ev.value);
										            console.log("notify again set to " + ev.value);
										            $( "#notifyagain" ).val(ev.value );
													if(ev.value == "0")
										            {
										            	$( "#notifyagaindisp" ).html(" Never");	
										            }
										            else
										           	{
														$( "#notifyagaindisp" ).html(ev.value + "  down cycles");
													}										            
													
										        });
											  });
											  
												  </script>
												  <input type="hidden" name="notifyflap" id="notifyflap" value="0">	
												</fieldset>									

												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Notify if up?</label>
														<div class="col-md-10">
															<div>
																<input type="radio" name="notifyifup" class="iradio_square-blue" <?php if($p->notifyifup == 1) { echo "checked";}?> id="notifyifupyes" value="1">
																<label for="notifyifup">Yes send UP notifications</label><br />
																<input type="radio" name="notifyifup" class="iradio_square-blue" id="notifyifupno" <?php if($p->notifyifup != 1) { echo "checked";}?> value="0">
																<label for="notifyifup">No, only downtime notifications</label>
															</div>
														</div>
													</div>
												</fieldset>	
												
												<footer>
												<button type="submit" class="btn btn-primary">
													Add Service Check
												</button>
												</footer>
										</form>

									
								</div>
							</div>
					</article>
				</div>
	
				
