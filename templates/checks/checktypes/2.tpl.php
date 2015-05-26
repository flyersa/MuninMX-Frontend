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
	if($check->check_type == "2")
	{
		$decode = json_decode($check->json,true);
		foreach($decode['param'] as $param)
		{
			 $t = explode("|##|",$param);
		     $p[$t[0]] = $t[1];
		}
				
		if(in_array('-S|##| ',$decode['param']))
		{
			$url = "https://";
		}
		else
		{
			$url = "http://";
		}
		
		if(isset($p['-a']))
		{
			$url.= $p['-a'] . "@";
			$auth = explode(":",$p['-a']);
		}
		
		$url.= $p['-H'];
		
		if(isset($p['-p']))
		{
			$url.= ":".$p['-p'];
		}
		
		if(isset($p['-u']))
		{
			$url.= $p['-u'];
		}		
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
				<label class="col-md-2 control-label">URL</label>
					<div class="col-md-10">
						<input type="text" name="uri" class="form-control validate[required,custom[url]]" placehold="http://www.example.com" value="<?php echo $url?>" data-prompt-position="topLeft"/>
						<div class="note">
							Use full URL, http://example.com if you need a custom port use http://example.com:8080
						</div>
					</div>
				</div>
			</fieldset>	          	 
          </div>
          <div class="tab-pane" id="opt">	
          	
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Username</label>
					<div class="col-md-10">
						<input type="text" name="user" value="<?php echo $auth[0]?>" class="form-control" placeholder="username" data-prompt-position="topLeft"/>
						<div class="note">
							Username for http auth if required
						</div>
					</div>
				</div>
			</fieldset>	
          
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Password</label>
					<div class="col-md-10">
						<input type="text" name="pass" value="<?php echo $auth[1]?>" class="form-control" placeholder="password" data-prompt-position="topLeft"/>
						<div class="note">
							Password for http auth if required
						</div>
					</div>
				</div>
			</fieldset>							          	
   
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Expect String</label>
					<div class="col-md-10">
						<input type="text" name="param[s]" value="<?php echo $p['-s']?>" class="form-control" data-prompt-position="topLeft"/>
						<div class="note">
							String to expect in the content
						</div>
					</div>
				</div>
			</fieldset>		 
 
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Search Page</label>
					<div class="col-md-10">
						<input type="text" name="param[r]" value="<?php echo $p['-r']?>" class="form-control" data-prompt-position="topLeft"/>
						<div class="note">
							String to expect in the content
						</div>
					</div>
				</div>
			</fieldset>	
			
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Set Useragent</label>
					<div class="col-md-10">
						<input type="text" name="param[A]" value="<?php echo $p['-A']?>" class="form-control" data-prompt-position="topLeft"/>
						<div class="note">
							String to be sent in http header as User Agent
						</div>
					</div>
				</div>
			</fieldset>	

          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Follow Redirects?</label>
					<div class="col-md-10">
						 <select name="param[f]" id="rdrsel" class="select2 form-control">
						<option value="ok" <?php if($p['f'] == "ok") { echo 'selected'; } ?>>Do nothing (ok)</option>
						<option value="critical" <?php if($p['-f'] == "critical") { echo 'selected'; } ?>>trigger error, critical</option>
						<option value="follow" <?php if($p['-f'] == "follow") { echo 'selected'; } ?>>follow the redirect</option>
						<option value="sticky" <?php if($p['-f'] == "sticky") { echo 'selected'; } ?>>follow but stick to IP</option>
						</select>
						<div class="note">
							what todo if a redirect happens?
						</div>
					</div>
				</div>
			</fieldset>					             	
		
          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Timeout</label>
					<div class="col-md-10">
		               <select name="param[t]" id="rdrselt" class="select2 form-control">
								<option value="10" <?php if($p['-t'] == "10") { echo 'selected'; } ?>>10 Seconds</option>
								<option value="15" <?php if($p['-t'] == "15") { echo 'selected'; } ?>>15 Seconds</option>
								<option value="20" <?php if($p['-t'] == "20") { echo 'selected'; } ?>>20 Seconds</option>
						</select>
						<div class="note">
							maximum timeout for service check?
						</div>
					</div>
				</div>
			</fieldset>					             	

          	<fieldset>
				<div class="form-group">
				<label class="col-md-2 control-label">Critical Time</label>
					<div class="col-md-10">
		               <select name="param[c]" id="rdrselc" class="select2 form-control">
							<option value="10" <?php if($p['-c'] == "10") { echo 'selected'; } ?>>10 Seconds</option>
							<option value="15" <?php if($p['-c'] == "15") { echo 'selected'; } ?>>15 Seconds</option>
							<option value="20" <?php if($p['-c'] == "20") { echo 'selected'; } ?>>20 Seconds</option>
						</select>
						<div class="note">
							critical if loading time is...
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
 
