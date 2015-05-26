				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
									<h2>Edit Bucket: <?php echo htmlspecialchars($bucket->statname)?></h2>
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
										<form class="smart-form" name="addForm" id="addForm" action="buckets.php?action=edit&bid=<?php echo $bucket->id?>" method="POST">
										<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Enter a name for this graph</label>
														<div class="col-md-10">
															<input class="form-control" id="statname" name="statname" placeholder="Example: Votes received on custom application A" type="text" value="<?php echo htmlspecialchars($bucket->statname)?>">
														</div>														
													</div>
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Enter a label for the yAxis</label>
														<div class="col-md-10">
															<input class="form-control" id="statlabel" name="statlabel"  placeholder="Example: Requests/second" type="text" value="<?php echo htmlspecialchars($bucket->statlabel)?>">
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
																<?php echo getGroupOptions($bucket->groupname);?>
															</select>
															<div class="note">
															<strong>Info:</strong> If you assign a Access Group to this bucket stat, users with access to this group can see this in the buckets menu.
															</div>
														</div>
													</div>	
												</fieldset>	
											<?php } ?>	
												<footer>
												<button type="submit" class="btn btn-primary">
													Save Bucket Stat
												</button>
												</footer>																																	
										</form>
									</div>
								</div>
							</div>
					</article>
				</div>											