			
							<div class="alert alert-danger alert-block" style="margin: 12px">
								<a class="close" data-dismiss="alert" href="#">×</a>
								<h4 class="alert-heading">Really Delete?</h4>
								Do you really want to delete this Dashboard? 
								<p class="text-align-left">
									<br>
									<a href="dashboard.php?dashboard=<?php echo $board->id?>&deletefinal=true&token=<?php echo getToken()?>" class="btn btn-sm btn-default"><strong>Yes delete this dashboard</strong></a>
								</p>
							</div>
