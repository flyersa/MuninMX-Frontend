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
		<label class="col-md-2 control-label">Select Plugin</label>
			<div class="col-md-10">
			<?php renderPluginsForNode($_GET['node'],$selv);?>
			</div>														
	</div>
</fieldset>

<script>
	$( "#plugin" ).select2();
				// add magic
			$('#plugin').on('change', function() {
			  $( "#preview" ).html("");
			  if(this.value != "mxinvalidmx")
			  {
			   	 $( "#loadgraphs" ).load( "ajax/customGraphLoad.php?node="+$( "#basehost" ).val()+"&plugin="+this.value+"&token=<?php echo getToken()?>" );			  
			  }
			
			});		
</script>