<?php
chdir("../..");
include("inc/startup.php");
if(!isLoggedIn())
{
	header('HTTP/1.0 401 Unauthorized');
	die;
}
if(!is_numeric($_GET['cid']))
{
	die;
}
checkToken();

$check = returnServiceCheck($_GET['cid']);

if(!accessToCheck($check->id))
{
	header('HTTP/1.0 401 Unauthorized');
	die;	
}
if(cvdPauseCheck($check->id))
{
	$db->query("UPDATE service_checks SET is_active = 0 WHERE id = $check->id");
?>
	    	<button class="btn btn-danger btn-xl dropdown-toggle" data-toggle="dropdown">PAUSED <span class="caret"></span></button>
	    	<ul class="dropdown-menu">
	    		<li><a href="#" onClick='$("#activetoggle<?php echo $check->id?>").load("ajax/checks/continue.php?cid=<?php echo $check->id?>&token=<?php echo getToken()?>"); return false;'>Continue Check</a></li>
	         </ul>    
<?php } else { ?>
	    	<button class="btn btn-success btn-xl dropdown-toggle" data-toggle="dropdown">ACTIVE <span class="caret"></span></button>
	    	<ul class="dropdown-menu">
	    		<li><a href="#" onClick='$("#activetoggle<?php echo $check->id?>").load("ajax/checks/pause.php?cid=<?php echo $check->id?>&token=<?php echo getToken()?>"); return false;'>Pause Check</a></li>
	         </ul>
<?php } ?>
