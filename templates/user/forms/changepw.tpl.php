<br /><br />
				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-lock"></i> </span>
									<h2>Change E-Mail / Password</h2>

									
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
										 
										<form class="smart-form" name="customform" id="customform" action="settings.php" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">E-Mail</label>
														<div class="col-md-10">
															<input class="form-control" name="email" type="text" value="<?php echo $tpl->email?>" autocomplete="off">
															<div class="note">
															</div>
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Current Password</label>
														<div class="col-md-10">
															<input class="form-control" name="password" type="password" value="" autocomplete="off">
															<div class="note">
															</div>
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">New Password</label>
														<div class="col-md-10">
															<input class="form-control" id="passwordn" name="passwordn" type="password" value="" autocomplete="off">
															<div class="note">
															</div>
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Repeat</label>
														<div class="col-md-10">
															<input class="form-control" id="repeat" name="repeat" type="password" value="" autocomplete="off">
															<div class="note">
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