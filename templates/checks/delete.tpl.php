			
							<div class="alert alert-danger alert-block">
								<a class="close" data-dismiss="alert" href="#">×</a>
								<h4 class="alert-heading">Really Delete?</h4>
								Do you really want to delete this service check? We will delete all associated database entrys and dequeue the check.
								<p class="text-align-left">
									<br>
									<a href="checks.php?action=delete&cid=<?php echo $check->id?>&dowhatisay=yes&token=<?php echo getToken()?>" class="btn btn-sm btn-default"><strong>Yes delete this check</strong></a>
								</p>
							</div>
