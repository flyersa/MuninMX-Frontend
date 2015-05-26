<?php
	$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/list/ttsenabled");
	if(trim($json) == "true")
	{
		$ttsenabled = true;
	}
	else
	{
		$ttsenabled = false;	
	}
	$json = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/list/smsenabled");
	if(trim($json) == "true")
	{
		$smsenabled = true;
	}
	else
	{
		$smsenabled = false;	
	}		
?>
				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-male"></i> </span>
									<h2>Edit Contact</h2>

									
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
										 
										<form class="smart-form" name="contactform" id="contactform" action="<?php echo getCurUrl()?>" method="POST">
          									<div class="padded">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Contact Name</label>
														<div class="col-md-10">
															<input class="form-control" name="contact_name" type="text" value="<?php echo $tpl->contact_name?>">
															<div class="note">
															<strong>Info:</strong> Make this a descriptive name for the contact
															</div>
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">E-Mail</label>
														<div class="col-md-10">
															<input class="form-control" name="contact_email" type="text" value="<?php echo $tpl->contact_email?>">
															<div class="note">
															<strong>Info:</strong> Send Notifications to this e-mail address
															</div>
														</div>
													</div>
												</fieldset>

            

	      <div class="box-headernoback">
	        <ul class="nav nav-tabs nav-tabs-left">
	          <li class="active"><a href="#req" data-toggle="tab"><i class="icon-pushpin"></i>Optional Settings</a></li>
	        </ul>
	      </div>

    	 <div class="tab-content">
          <div class="tab-pane active" id="req">	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Mobile Number</label>
														<div class="col-md-10">
															<input class="form-control" name="contact_mobile_nr" type="text" value="<?php echo $tpl->contact_mobile_nr?>">
															<div class="note">
															<strong>Info:</strong> Used for SMS and Text2Speech Calls. Please necessarily use the international format Example: 0049160123456
															</div>
														</div>
													</div>
												</fieldset>          	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label"><img src="img/pushover.png"> Pushover User Key</label>
														<div class="col-md-10">
															<input class="form-control" name="pushover_key" id="pushover_key" type="text" value="<?php echo $tpl->pushover_key?>">
															  <span style="float: right; padding-top: 2px"><a onClick="sendPushOverTest()" class="btn btn-info btn-xs">Send Test Message</a></span>
															<div class="note">
															<strong>Info:</strong> Your User Key to receive notifications via PushOver.net
															</div>
														</div>
													</div>
												</fieldset>               
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Callback URL</label>
														<div class="col-md-10">
															<input class="form-control" name="contact_callback" type="text" value="<?php echo $tpl->contact_callback?>">
															<div class="note">
															<strong>Info:</strong> Receive JSON callback notifications from us via HTTP POST to a url
															</div>
														</div>
													</div>
												</fieldset>   

            
           </div>
     	</div> <!-- tab content -->
     	
     	<br />
     	
     	<div class="box-headernoback">
	        <ul class="nav nav-tabs nav-tabs-left">
	          <li class="active"><a href="#not" data-toggle="tab"><i class="icon-asterisk"></i>Notification Settings</a></li>
	        </ul>
	   </div>
	   
	   <div class="tab-content">
          <div class="tab-pane active" id="not">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Send E-Mail Notifications</label>
														<div class="col-md-10">
								          	          		<div class="checkbox">
																<label>
																	<input type="checkbox" name="email_active" value="1" class="checkbox style-0"  id="email_active" <?php if($tpl->email_active == 1) { echo 'checked';}?>>
																	 <span>Send E-Mail Notifications</span>
																</label>
															</div>
														</div>
													</div>
												</fieldset>             	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Send SMS</label>
														<div class="col-md-10">
								          	          		<div class="checkbox">
																<label>
																	<input type="checkbox" name="sms_active" id="sms_active" value="1" class="checkbox style-0" <?php if($tpl->sms_active == 1) { echo 'checked';}?> <?php if(!$smsenabled) { echo ' disabled';}?>>
																	 <span>Send SMS</span>
																</label>
															</div>
														</div>
													</div>
												</fieldset>     
 												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Text2Speech Phone Call</label>
														<div class="col-md-10">
								          	          		<div class="checkbox">
																<label>
																	<input type="checkbox" name="tts_active" id="tts_active" value="1" class="checkbox style-0" <?php if($tpl->tts_active == 1) { echo 'checked';}?> <?php if(!$ttsenabled) { echo ' disabled';}?>>
																	 <span>Text2Speech Phone Call</span>
																</label>
															</div>
														</div>
													</div>
												</fieldset>     
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Send Pushover.net Notifications</label>
														<div class="col-md-10">
								          	          		<div class="checkbox">
																<label>
																	<input type="checkbox" name="pushover_active" value="1" class="checkbox style-0" <?php if($tpl->pushover_active == 1) { echo 'checked';}?>>
																	 <span>Send Pushover.net Notifications</span>
																</label>
															</div>
														</div>
													</div>
												</fieldset>     
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Send JSON Callbacks</label>
														<div class="col-md-10">
								          	          		<div class="checkbox">
																<label>
																	<input type="checkbox" name="callback_active" value="1" class="checkbox style-0" <?php if($tpl->callback_active == 1) { echo 'checked';}?>>
																	 <span>Send JSON Callbacks</span>
																</label>
															</div>
														</div>
													</div>
												</fieldset>     																								         	
              
                                            
             </div>    
    	</div> <!-- tab content -->
    	
    	<br />
     	<div class="box-headernoback">
	        <ul class="nav nav-tabs nav-tabs-left">
	          <li class="active"><a href="#sched" data-toggle="tab"><i class="icon-calendar"></i>Notification Schedules</a></li>
	        </ul>
	   </div>    
	   	
    	 <div class="tab-content">
          <div class="tab-pane active" id="sched">	
          	<table class="table table-normal">
          		<tr>
          			<td>
                    	<b>Monday</b>
                   </td>
                   <td style="padding-left: 175px">
                   		<?php $s = explode(";",$tpl->s_mon)?>
						<?php renderTimeDropDown("s_mon_from",$s[0]); ?>
						to
                    	<?php renderTimeDropDown("s_mon_to",$s[1]); ?>          				
          			</td>
          			<td>
          					<div class="checkbox">
								<label>
									<input type="checkbox" name="s_mon_none" value="1" class="checkbox style-0"  id="s_mon_none" <?php if($tpl->s_mon == "disabled") { echo 'checked';}?>>
									 <span>Disable Notifications for this day</span>
								</label>
							</div>
          			</td>
          		</tr>
          		<tr>
          			<td>
                    	<b>Tuesday</b>
                   </td>
                   <td style="padding-left: 175px">
                   	    <?php $s = explode(";",$tpl->s_tue)?>
						<?php renderTimeDropDown("s_tue_from",$s[0]); ?>
						to
                    	<?php renderTimeDropDown("s_tue_to",$s[1]); ?>          				
          			</td>
          			<td>
          					<div class="checkbox">
								<label>
									<input type="checkbox" name="s_tue_none" value="1" class="checkbox style-0"  id="s_tue_none" <?php if($tpl->s_tue == "disabled") { echo 'checked';}?>>
									 <span>Disable Notifications for this day</span>
								</label>
							</div>          				
          			</td>          			
          		</tr>
         		<tr>
          			<td>
          				<b>Wednesday</b>	
          			</td>
          			<td style="padding-left: 175px">
          				<?php $s = explode(";",$tpl->s_wed)?>
						<?php renderTimeDropDown("s_wed_from",$s[0]); ?>
						to
                    	<?php renderTimeDropDown("s_wed_to",$s[1]); ?>          				
          			</td>  
          			<td>
          					<div class="checkbox">
								<label>
									<input type="checkbox" name="s_wed_none" value="1" class="checkbox style-0"  id="s_wed_none" <?php if($tpl->s_wed == "disabled") { echo 'checked';}?>>
									 <span>Disable Notifications for this day</span>
								</label>
							</div>              				
          			</td>          			        			
          		</tr> 
          		<tr>
          			<td>
                    	<b>Thursday</b>
                   </td>
                   <td style="padding-left: 175px">
                   		<?php $s = explode(";",$tpl->s_thu)?>
						<?php renderTimeDropDown("s_thu_from",$s[0]); ?>
						to
                    	<?php renderTimeDropDown("s_thu_to",$s[1]); ?>          				
          			</td>
          			<td>
           					<div class="checkbox">
								<label>
									<input type="checkbox" name="s_thu_none" value="1" class="checkbox style-0"  id="s_thu_none" <?php if($tpl->s_thu == "disabled") { echo 'checked';}?>>
									 <span>Disable Notifications for this day</span>
								</label>
							</div>                 				
          			</td>          			
          		</tr>
          		<tr>
          			<td>
                    	<b>Friday</b>:
                   </td>
                   <td style="padding-left: 175px">
                   		<?php $s = explode(";",$tpl->s_fri)?>
						<?php renderTimeDropDown("s_fri_from",$s[0]); ?>
						to
                    	<?php renderTimeDropDown("s_fri_to",$s[1]); ?>          				
          			</td>
          			<td>
           					<div class="checkbox">
								<label>
									<input type="checkbox" name="s_fri_none" value="1" class="checkbox style-0"  id="s_fri_none" <?php if($tpl->s_fri == "disabled") { echo 'checked';}?>>
									 <span>Disable Notifications for this day</span>
								</label>
							</div>            				
          			</td>          			
          		</tr>
         		<tr>
          			<td>
          				<b>Saturday</b>	
          			</td>
          			<td style="padding-left: 175px">
          				<?php $s = explode(";",$tpl->s_sat)?>
						<?php renderTimeDropDown("s_sat_from",$s[0]); ?>
						to
                    	<?php renderTimeDropDown("s_sat_to",$s[1]); ?>          				
          			</td> 
          			<td>
           					<div class="checkbox">
								<label>
									<input type="checkbox" name="s_sat_none" value="1" class="checkbox style-0"  id="s_sat_none" <?php if($tpl->s_sat == "disabled") { echo 'checked';}?>>
									 <span>Disable Notifications for this day</span>
								</label>
							</div>            				
          			</td>          			         			
          		</tr>  
         		<tr>
          			<td>
          				<b>Sunday</b>
          			</td>
          			<td style="padding-left: 175px">
          				<?php $s = explode(";",$tpl->s_sun)?>
						<?php renderTimeDropDown("s_sun_from",$s[0]); ?>
						to
                    	<?php renderTimeDropDown("s_sun_to",$s[1]); ?>          				
          			</td> 
          			<td>
           					<div class="checkbox">
								<label>
									<input type="checkbox" name="s_sun_none" value="1" class="checkbox style-0"  id="s_sun_none" <?php if($tpl->s_sun == "disabled") { echo 'checked';}?>>
									 <span>Disable Notifications for this day</span>
								</label>
							</div>            				
          			</td>          			         			
          		</tr>          		          		        		        		          		
          	</table>                      	          	       	
          </div>  <!-- tab content --> 	
    	
       </div><!-- end padded -->
     	
                        
          </div>
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