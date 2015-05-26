<?php
if(is_numeric($_GET['cid']))
{
	chdir("../../..");
	include("inc/startup.php");
	if(!isLoggedIn())
	{
		header('HTTP/1.0 401 Unauthorized');
		die;
	}
	$check = returnServiceCheck($_GET['cid']);	
	if($check->user_id != $_SESSION['user_id'] && $_SESSION['role'] != "admin")
	{
		header('HTTP/1.0 401 Unauthorized');
		die;
	}
	$p = json_decode($check->json);
	if($check->check_type == "1")
	{
			
	}
}
?>
	
            
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">IP or FQDN</label>
														<div class="col-md-10">
															 <input type="text" name="nonearg" value="<?php echo $p->nonearg?>" class="form-control validate[required]" data-prompt-position="topLeft"/>
															<div class="note">
																Please enter a IP or a Hostname
															</div>
														</div>
													</div>
												</fieldset>			