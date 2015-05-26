</tbody>
</table>
												</div>
								</div>
							</div>
						</article>
				</div>
				<!-- end row -->
	<?php 
	if($node == false && $plugin == false)
	{
		if($_GET['sub'] != "delete")
		{
			display_info("Add Metric Alert","To add new metric alert notifications click the 'Settings' Tab of a plugin in the node detail view and select 'Notifications'");
		}
	}
	?>							
						