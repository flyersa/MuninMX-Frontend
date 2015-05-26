<tr>
	<td><a href="view.php?nid=<?php echo $tpl->node_id?>"><?php echo $tpl->hostname?></a></td>
	<td><a href="view.php?nid=<?php echo $tpl->node_id?>#<?php echo htmlspecialchars($tpl->pluginname)?>"><?php echo htmlspecialchars($tpl->pluginname)?></a></td>
	<td><?php echo htmlspecialchars($tpl->graphname)?></td>
	<td><?php echo htmlspecialchars($tpl->condition)?></td>
	<td><?php echo $tpl->raise_value?></td>
	<td><?php echo $tpl->contacts?></td>
	<td style="width: 170px">
		<a href="alerts.php?action=alerts&sub=edit&aid=<?php echo $tpl->id?>" class="btn btn-default"><i class="fa fa-edit"></i> Edit</a>
		<a href="alerts.php?action=alerts&sub=delete&aid=<?php echo $tpl->id?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
	</td>
</tr>