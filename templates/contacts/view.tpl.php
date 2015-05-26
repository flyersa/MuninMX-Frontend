<?php
$not = "";
// get list of active notifications
if($tpl->email_active == "1")
{
	$not.= '&nbsp; <i class="fa fa-envelope-o"></i> E-Mail';
}
if($tpl->sms_active == "1")
{
	$not.= '&nbsp; <i class="fa fa-mobile-phone"></i> SMS';
}
if($tpl->tts_active == "1")
{
	$not.= '&nbsp; <i class="icon-comment-alt"></i> Phone Call';
}
if($tpl->pushover_active == "1")
{
	$not.= '&nbsp; <img src="img/pushover.png" style="vertical-align: middle"> Pushover';
}
if($tpl->callback_active == "1")
{
	$not.= '&nbsp; <i class="fa fa-cogs"></i> JSON Callback';
}
?>

				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-male"></i> </span>
									<h2>Contact Details</h2>
									<a href="alerts.php?action=contacts&sub=edit&cid=<?php echo $tpl->id?>" style="float: right" class="btn btn-default"><i class="fa fa-edit"></i> Edit Contact</a>
									
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
					<!-- start row -->
			
					<div class="row">
						<h2 class="row-seperator-header"><i class="fa fa-search"></i> <?php echo htmlspecialchars($tpl->contact_name)?></h2>
						
						<div class="col-sm-12">
							
							<!-- well -->
							<div class="well">
								<!-- row -->
								<div class="row">
									<!-- col -->
									<div class="col-sm-12">
										<!-- row -->
										<div class="row">
				
											<div class="col-md-6">
												<h2>
													<i class="fa fa-male"></i> Contact Details
												</h2>
				
												<table class="table table-bordered">
													<thead>
														<tr>
															<th style="width:50%">Type</th>
															<th style="width:50%">Value</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																<i class="fa fa-envelope-o"></i> E-Mail
															</td>
															<td><?php echo htmlspecialchars($tpl->contact_email)?></td>
														</tr>
														<tr>
															<td>
																<i class="fa fa-mobile-phone"></i> Mobile
															</td>
															<td> <?php echo htmlspecialchars($tpl->contact_mobile_nr)?></td>
														</tr>
														<tr>
															<td>
																<img src="img/pushover.png"> Pushover
															</td>
															<td><?php echo htmlspecialchars($tpl->pushover_key)?></td>
														</tr>
														<tr>
															<td>
																<i class="fa fa-cogs"></i> Callback
															</td>
															<td><?php echo htmlspecialchars($tpl->contact_callback)?></td>
														</tr>
														<tr>
															<td>
																Enabled Notifications
															</td>
															<td><?php echo $not?></td>
														</tr>
													</tbody>
												</table>
											</div>
				
											<div class="col-md-6">
												<h2>
													<i class="fa fa-calendar-o"></i> Schedule ( TZ: <?php echo htmlspecialchars($tpl->timezone)?>)
												</h2>
												<table class="table table-bordered">
													<thead>
														<tr>
															<th style="width:50%">Weekday</th>
															<th style="width:50%">Time</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Monday</td>
															<td><?php if($tpl->s_mon == "disabled") { echo '<span class="badge bg-color-red">Disabled</span>';} else { echo str_replace(";","-",$tpl->s_mon); }?></td>
														</tr>
														<tr>
															<td>
																Tuesday
															</td>
															<td><?php if($tpl->s_tue == "disabled") { echo '<span class="badge bg-color-red">Disabled</span>';} else { echo str_replace(";","-",$tpl->s_tue); }?></td>
														</tr>
														<tr>
															<td>
																Wednesday
															</td>
															<td><?php if($tpl->s_wed == "disabled") { echo '<span class="badge bg-color-red">Disabled</span>';} else { echo str_replace(";","-",$tpl->s_wed); }?></td>
														</tr>
														<tr>
															<td>
																Thursday
															</td>
															<td><?php if($tpl->s_thu == "disabled") { echo '<span class="badge bg-color-red">Disabled</span>';} else { echo str_replace(";","-",$tpl->s_thu); }?></td>
														</tr>
														<tr>
															<td>
																Friday
															</td>
															<td><?php if($tpl->s_fri == "disabled") { echo '<span class="badge bg-color-red">Disabled</span>';} else { echo str_replace(";","-",$tpl->s_fri); }?></td>
														</tr>
														<tr>
															<td>
																Saturday
															</td>
															<td><?php if($tpl->s_sat == "disabled") { echo '<span class="badge bg-color-red">Disabled</span>';} else { echo str_replace(";","-",$tpl->s_sat); }?></td>
														</tr>
														<tr>
															<td>
																Sunday
															</td>
															<td><?php if($tpl->s_sun == "disabled") { echo '<span class="badge bg-color-red">Disabled</span>';} else { echo str_replace(";","-",$tpl->s_sun); }?></td>
														</tr>														
													</tbody>
												</table>
											</div>
				
										</div>
										<!-- end row -->
									</div>
									<!-- end col -->
								</div>
								<!-- end row -->
							</div>
							<!-- end well -->
				
						</div>
				
					</div>
				
					<!-- end row -->
      </div>
							
									</div>
								</div>
							</div>
							
							<?php renderAlertTableForContact($tpl->id);?>
							
							<?php renderCheckAlertTableForContact($tpl->id); ?>
					</article>
				</div>
				<!-- end row -->					
