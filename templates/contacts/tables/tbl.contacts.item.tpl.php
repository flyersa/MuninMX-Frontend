<?php
// get assigned checks
$a = getAssignedAlertsForContact($tpl->id);
$b = getAssignedCheckAlertsForContact($tpl->id);

$not = "";
// get list of active notifications
if($tpl->email_active == "1")
{
	$not.= '&nbsp; <i class="fa fa-envelope-o"></i> E-Mail';
}
if($tpl->sms_active == "1")
{
	$not.= '&nbsp; <i class="fa fa-mobile-phone"></i> SMS';
}
if($tpl->tts_active == "1")
{
	$not.= '&nbsp; <i class="fa fa-phone"></i> Phone Call';
}
if($tpl->pushover_active == "1")
{
	$not.= '&nbsp; <img src="img/pushover.png" style="vertical-align: middle"> Pushover';
}

if($tpl->callback_active == "1")
{
	$not.= '&nbsp; <i class="fa fa-cogs"></i> JSON Callback';
}
?>
<tr id="ordrrow-<?php echo $tpl->id?>">
  <td><strong><a href="alerts.php?action=contacts&sub=view&cid=<?php echo $tpl->id?>"><i class="fa fa-search"></i> <?php echo htmlspecialchars($tpl->contact_name)?></a></strong></td>
  <td><?php echo $tpl->contact_email?></td>
  <td><?php echo $not?></td>
  <td><?php echo $a?> Metrics, <?php echo $b?> Checks,</td>
  <td>
  	<a href="alerts.php?action=contacts&sub=view&cid=<?php echo $tpl->id?>" class="btn btn-info"><i class="fa fa-search"></i> Details</a> 
  	<a href="alerts.php?action=contacts&sub=edit&cid=<?php echo $tpl->id?>" class="btn btn-default"><i class="fa fa-edit"></i> Edit</a> 
  	<a href="alerts.php?action=contacts&sub=delete&cid=<?php echo $tpl->id?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>
  </td>
</tr>