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
?>
<fieldset>
	<div class="form-group">
		<label class="col-md-2 control-label">Select Hosts to include</label>
			<div class="col-md-10">
			<?php renderGraphsForPluginAndNode($_GET['node'],$_GET['plugin']);?>
			</div>														
	</div>
</fieldset>

<script>
	$( "#otherhosts" ).select2();
				// add magic
			$('#graphs').on('change', function() {
			//alert( this.value ); // or $(this).val()	
			  
			  //$( "#loadplugins" ).load( "ajax/customPluginLoad.php?node="+this.value+"&token=<?php echo getToken()?>" );
			});		
</script>