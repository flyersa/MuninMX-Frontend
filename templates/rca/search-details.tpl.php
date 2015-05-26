				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-search"></i> </span>
									<h2>Analysis Query Details</h2>

									
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
									<table class="table table-bordered">
													<thead>
														<tr>
															<th style="width:50%"></th>
															<th style="width:50%"></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Start Time:</td>
															<td><?php echo getFormatedLocalTime($tpl->start_time)?></td>
														</tr>
														<tr>
															<td>End Time:</td>
															<td><?php echo getFormatedLocalTime($tpl->end_time)?></td>
														</tr>
														<tr>
															<td>Compare with:</td>
															<td>last <?php echo $tpl->querydays?> days</td>
														</tr>
														<tr>
															<td>Match Percentage</td>
															<td><?php echo $tpl->percentage?></td>
														</tr>
														<tr>
															<td>Ignore Threshold</td>
															<td><?php echo $tpl->threshold?></td>
														</tr>
														<tr>
															<td>Group Filter</td>
															<td><?php echo htmlspecialchars($tpl->groupname)?></td>
														</tr>
														<tr>
															<td>Category Filter</td>
															<td><?php echo htmlspecialchars($tpl->categoryfilter)?></td>
														</tr>														
													</tbody>
												</table>
										
									</div>	
							</div>
					</article>
				</div>
				<!-- end row -->	