<?php
if(trim($tpl->luptime) != "N/A")
{
	if(trim($tpl->luptime) == "100 %" || trim($tpl->luptime) == "100.00 %")
	{
		$ucol = "success";
	}
	else
	{
		$ft = substr($tpl->luptime,0,2);
		if($ft >= 90 )
		{
			$ucol = "warning";
		}
		if($ft >= 95)
		{
			$ucol = "success";
		}
		if($ft <= 90)
		{
			$ucol = "danger";	
		}
	}
}
else
{
	$ucol = "info";
}

$disabled = "";
if($tpl->user_id != $_SESSION['user_id'])
{
	$disabled = "disabled";
}
if($_SESSION['role'] == "admin")
{
	$disabled = "";
}
?>
<tr>
  <td>
 	<div class="btn-group <?php echo $disabled?>" id="activetoggle<?php echo $tpl->id?>">
 		<?php if($tpl->is_active == 1) { ?>
	    	<button class="btn-success btn btn-xl dropdown-toggle <?php echo $disabled?>" data-toggle="dropdown">ACTIVE <span class="caret"></span></button>
	    	<ul class="dropdown-menu">
	    		<li><a href="#" onClick='$("#activetoggle<?php echo $tpl->id?>").load("ajax/checks/pause.php?cid=<?php echo $tpl->id?>&token=<?php echo getToken()?>"); return false;'>Pause Check</a></li>
	         </ul>
         <?php } else { ?> 
	    	<button class="btn btn-danger btn-xl dropdown-toggle <?php echo $disabled?>" data-toggle="dropdown">PAUSED <span class="caret"></span></button>
	    	<ul class="dropdown-menu">
	    		<li><a href="#" onClick='$("#activetoggle<?php echo $tpl->id?>").load("ajax/checks/continue.php?cid=<?php echo $tpl->id?>&token=<?php echo getToken()?>"); return false;'>Continue Check</a></li>
	         </ul>         	
        <?php } ?>
    </div>
  </td>
  <td><?php echo  getSparkLine($tpl->id,$tpl->user_id)?></td>
  <td><strong><a href="checks.php?action=view&cid=<?php echo $tpl->id?>"><i class="icon-search"></i> <?php echo htmlspecialchars($tpl->check_name)?></a></strong></td>
  <td><?php echo $tpl->check_desc_name?></td>
  <td><?php echo $tpl->cinterval?></td>
  <td><?php echo getTagsForCheck($tpl->id)?></td>
  <td class="center"><span class="label label-<?php echo $ucol?>" style="width: 50px"><?php echo $tpl->luptime?></span></td>
  <td><?php echo htmlspecialchars($tpl->username)?></td>
  <td>
  	<div class="btn-group">
    	<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Tasks <span class="caret"></span></button>
    	<ul class="dropdown-menu">
    		<li><a href="checks.php?action=view&cid=<?php echo $tpl->id?>"><i class="icon-search"></i> Details</a></li>
    		<li><a href="checks.php?action=export&cid=<?php echo $tpl->id?>"><i class="icon-download"></i> Export</a></li>
    		<?php if($disabled == "") { ?>
         	<li><a href="checks.php?action=edit&cid=<?php echo $tpl->id?>"><i class="icon-edit"></i> Edit</a></li>
			<li class="divider"></li>
            <li><a href="checks.php?action=delete&cid=<?php echo $tpl->id?>"><i class="icon-trash"></i> Delete</a></li>
            <?php } ?>
         </ul>
    </div>
  </td>
</tr>