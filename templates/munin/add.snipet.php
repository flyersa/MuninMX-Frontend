											<?php if(!$_GET && !$_POST) { ?>
												<?php if($_SESSION['role'] == "admin" || $_SESSION['role'] == "userext") { ?>										
												<div class="btn-group" style="margin: 10px">
													<a href="index.php?action=add" class="btn dropdown-toggle btn-primary">
															<i class="fa fa-plus"></i> Add new Node 
													</a>
												</div>
												<?php } ?>
												
												<div class="well well-sm" style="margin-top: 10px">Legend: <span class="label label-danger">Alert Notifications configured</span> &nbsp; <span class="label label-default">Custom Intervals configured</span></div>		
											<?php } ?>	