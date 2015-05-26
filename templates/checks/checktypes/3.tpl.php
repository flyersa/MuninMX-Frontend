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
		        <a href="#opt" data-toggle="tab" class="btn btn-default" style="padding: 5px"><i class="fa fa-adjust"></i> <span>Optional Settings</span></a>
		   		</div>     
			</div>
     <div class="tab-content">
          <div class="tab-pane active" id="req">	
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">HOST</label>
					<div class="col-md-10">
						<input type="text" name="param[H]" class="form-control validate[required] required" placehold="8.8.8.8" value="<?php echo $p['-H']?>" data-prompt-position="topLeft"/>
						<div class="note">
							FQDN or IP Address
						</div>
					</div>
				</div>
			</fieldset>	  
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Port</label>
					<div class="col-md-10">
						<input type="text" name="param[p]" class="form-control validate[required,custom[integer],max[65535]] required" placehold="8.8.8.8" value="<?php echo $p['-p']?>" data-prompt-position="topLeft"/>
						<div class="note">
							Port to check
						</div>
					</div>
				</div>
			</fieldset>	  			        	 
          </div>
          <div class="tab-pane" id="opt">	
          	
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Send String</label>
					<div class="col-md-10">
						<input type="text" name="param[s]" value="<?php echo $p['-s']?>" class="form-control" placeholder="" data-prompt-position="topLeft"/>
						<div class="note">
							String to send after connect
						</div>
					</div>
				</div>
			</fieldset>	
          
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Expect String</label>
					<div class="col-md-10">
						<input type="text" name="param[e]" value="<?php echo $p['-e']?>" class="form-control" placeholder="" data-prompt-position="topLeft"/>
						<div class="note">
							String to expect in return
						</div>
					</div>
				</div>
			</fieldset>							          	
   
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Use SSL?</label>
					<div class="col-md-10">
						<input type="checkbox" class="icheck" id="icheck1" name="param[S]" value=" "> 
						<div class="note">
							Check to Activate SSL
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
 
