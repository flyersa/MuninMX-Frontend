<?php
chdir("..");
include("inc/startup.php");
if(!isLoggedIn())
{
	$_SESSION['REAL_REFERRER'] = $_SERVER['REQUEST_URI'];
	header("Location: login.php");
	die;
}
checkToken();
if(!is_numeric($_GET['node']))
{
	die;	
}

if(!accessToNode($_GET['node']))
{
	die;	
}

if(!$_GET['plugin'])
{
	die;
}

if($_GET['selv'])
{
	$selv = $_GET['selv'];
}
else
{
	$selv = false;
}
?>
<fieldset>
	<div class="form-group">
		<label class="col-md-2 control-label">Select Graph(s)</label>
			<div class="col-md-10">
			<?php renderGraphsForPluginAndNode($_GET['node'],$_GET['plugin'],$selv);?>
			</div>														
	</div>
</fieldset>

<script>
	$( "#graphs" ).select2();
				// add magic
			$('#graphs').on('change', function() {
			//alert( $( "#graphs" ).val() ); 
		    // if(!$_GET['graphs'] && !$_GET['plugin'] && !$_GET['nodes'])
				$( "#preview" ).html('<iframe name="frame" id="frame" width="100%" src="custGraphPreview.php?graphs='+$( "#graphs" ).val()+'&plugin='+$( "#plugin" ).val()+'&nodes='+$( "#otherhosts" ).val()+'&base='+$( "#basehost" ).val()+'" scrolling="no" height="500px" frameborder="0" ></iframe>');
				if($( "#plugin" ).val() != "undefined" && $( "#plugin" ).val() != "mxinvalidmx")
				{
					$( "#togglebutton" ).show();
				}
				else
				{
					$( "#togglebutton" ).hide();	
				}
			});		
</script>