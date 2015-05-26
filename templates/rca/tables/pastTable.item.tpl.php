<tr>
												<td><a href="rca.php?view=<?php echo $tpl->rcaId?>"><?php echo $tpl->rcaId?></a></td>
												<td><?php echo $tpl->results?></td>
												<td><?php echo getFormatedLocalTime($tpl->start_time)?></td>
												<td><?php echo getFormatedLocalTime($tpl->end_time)?></td>
												<td><?php echo $tpl->querydays?></td>
												<td><?php echo $tpl->threshold?></td>
												<td><?php echo $tpl->percentage?></td>
												<td><?php echo htmlspecialchars($tpl->groupname)?></td>
												<td><?php echo htmlspecialchars($tpl->categoryfilter)?></td>
												<td><?php echo getFormatedLocalTime($tpl->last_change_ts)?></td>
											</tr>