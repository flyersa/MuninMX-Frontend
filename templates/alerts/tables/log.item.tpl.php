<tr>
	<td><a href="view.php?nid=<?php echo $tpl->node_id?>"><?php echo htmlspecialchars($tpl->node->hostname)?></a></td>
	<td><a href="alerts.php?action=contacts&sub=view&cid=<?php echo $tpl->contact_id?>"><?php echo htmlspecialchars($tpl->contact_name)?></a></td>
	<td><?php echo htmlspecialchars($tpl->msg)?></td>
	<td><?php echo $tpl->msg_type?></td>
	<td><?php echo htmlspecialchars($tpl->pluginname)?></td>
	<td><?php echo htmlspecialchars($tpl->graphname)?></td>
	<td><?php echo $tpl->created_at?></td>
</tr>