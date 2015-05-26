<tr>
	<td><a href="#<?php echo htmlspecialchars($tpl->pluginname)?>"><?php echo htmlspecialchars($tpl->pluginname)?></a></td>
	<td><?php echo $tpl->query_interval?> seconds</td>
	<td><?php echo $tpl->timerange?></td>
	<td><?php echo $tpl->retention?> days</td>
	<td><?php echo $tpl->crontab?></td>
	<td>
	<?php 
	if($_SESSION['role'] != "admin")
	{
		if($tpl->user_id == $_SESSION['user_id'])
		{
			echo '<a href="view.php?nid='.$tpl->node_id.'&action=customs&plugin='.$tpl->plugin_id.'&sub=delete&jobid='.$tpl->id.'&token='.getToken().'" class="btn btn-danger btn-sm">Delete</a></td>';		
		}
	}
	else
	{
		echo '<a href="view.php?nid='.$tpl->node_id.'&action=customs&plugin='.$tpl->plugin_id.'&sub=delete&jobid='.$tpl->id.'&token='.getToken().'" class="btn btn-danger btn-sm">Delete</a></td>';	
	}
	?>
	</td>
</tr>