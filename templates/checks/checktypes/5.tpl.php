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
	$decode = json_decode($check->json,true);
	foreach($decode['param'] as $param)
	{
		$t = explode("|##|",$param);
		$p[$t[0]] = $t[1];
	}	
}
?>
         
<fieldset>
	<div class="form-group">
		<label class="col-md-2 control-label">&nbsp;</label>
			<div class="col-md-10">									
				<div style="border-bottom: 1px solid #ccc; padding-bottom:3px">
		        <a href="#req" data-toggle="tab" class="btn btn-primary" style="padding: 5px"><i class="fa fa-asterisk"></i>Required Settings</a>
		   		</div>     
			</div>
     <div class="tab-content">
          <div class="tab-pane active" id="req">	
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Host:Port</label>
					<div class="col-md-10">
						<input type="text" name="param[c]" class="form-control validate[required] required" placehold="www.example.com:443" value="<?php echo $p['-c']?>" data-prompt-position="topLeft"/>
						<div class="note">
							Host and Port. NO URL. Use FQDN:PORT
						</div>
					</div>
				</div>
			</fieldset>	  
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Alert Condition</label>
					<div class="col-md-10">
						<input type="text" name="param[D]" class="form-control validate[required,custom[integer],max[65535]] required" placehold="30" value="<?php echo $p['-D']?>" data-prompt-position="topLeft"/>
						<div class="note">
							Send alert when certificate expires in X days
						</div>
					</div>
				</div>
			</fieldset>	  			        	 
          </div>

     </div>
     <script type="application/javascript">
		$("#rdrsel").select2();
		$("#rdrselt").select2();
		$("#rdrselc").select2();
     </script>

</div>
</div>
</fieldset>
 
