<tr>
	<td>
	<?php 
		echo getFormatedLocalTime($tpl->event_start);
		if($tpl->event_end > 0)
		{
			echo " - " .  getFormatedLocalTime($tpl->event_end);
		}
	?>
	</td>
	<td><a href="view.php?nid=<?php echo $tpl->node ?>"><?php echo htmlspecialchars($tpl->hostname)?></a></td>
	<td><?php echo htmlspecialchars($tpl->nodegroup)?></td>
	<td style="color: <?php echo $tpl->color?>"><?php echo htmlspecialchars(utf8_encode($tpl->event_title))?></td>
</tr>