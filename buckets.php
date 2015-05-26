<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = APP_NAME . " - Dashboard"; include("templates/core/head.tpl.php"); ?>
	</head>
	<body <?php if($_SESSION['minify'] == true) { echo 'class="desktop-detected pace-done minified"'; } else { echo 'class=""';} ?>>

		<!-- HEADER -->
		<header id="header">
		<?php include("templates/core/header.tpl.php"); ?>
		</header>
		<!-- END HEADER -->

		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<?php include("templates/nav/left.tpl.php"); ?>
		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">
			   <!-- breadcrumb -->
				<ol class="breadcrumb">
					<li>Home</li><li>Bucket Stats</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-bitbucket"></i> Dashboard <span>> Bucket Stats</span></h1>
					</div>
				</div>

				<?php
				if(!$_GET['action'] && !$_POST)
				{				
					$result = $db->query("SELECT * FROM buckets WHERE user_id = '$_SESSION[user_id]'");
					if($db->affected_rows < 1)
					{
						display_warning("NO BUCKET STATS FOUND", "<br /><br />Add a bucket stat to get started. You can feed values to a bucket stat with a single http call. Graph whatever you want.");
						include("templates/buckets/forms/new.tpl.php");
					}
					else
					{
						renderBucketStatTable();
					}				
				}	
				elseif($_GET['action'] == "api")
				{
					include("templates/buckets/api.tpl.php");
				}
				elseif($_GET['action'] == "add")
				{
					if(!$_POST)
					{
						include("templates/buckets/forms/new.tpl.php");	
					}
					else
					{
						$statid = sha1(uniqid($_SESSION['user_id'].time(),true));

						$_POST = secureArray($_POST);
						$db->query("INSERT INTO buckets (user_id,statname,statlabel,statid,groupname) VALUES ($_SESSION[user_id],'$_POST[statname]','$_POST[statlabel]','$statid','$groupname')");
						//echo "INSERT INTO simplestats (user_id,statname,statlabel,statid,shareid) VALUES ($_SESSION[user_id],'$_POST[statname]','$_POST[statlabel]','$statid','$shareid')";
						if($db->affected_rows > 0)
						{
							display_ok("Bucket created. You can now push values to this stat. Please note that additional firewall changes might be required to allow you access to the API");
							//	display some informations on how to get started with examples and so on
							// create mongo indexes
							global $m; 
							$dbm = $m->buckets;
							$colname = $statid;
							$collection = $dbm->$colname;
							$collection->ensureIndex(array("timestamp" => 1));
							$collection->ensureIndex(array("timestamp" => -1));
							
							$tpl->graphid = $statid;
							include("templates/buckets/api.tpl.php");
						}
						else
						{
							display_error("Storage Error", " Unable to save bucket stat. Please try again later");
						}
					}
				}
				// view
				elseif($_GET['action'] == "view" && is_numeric($_GET['bid']))
				{
					$bucket = returnBucket($_GET['bid']);
					if($bucket == false)
					{
						display_error("Error","Bucket not found");		
					}
					else
					{
						if(gotAccessToBucket($bucket->id))
						{
							if($_GET['delete'])
							{
								include("templates/buckets/delete.tpl.php");
							}
								
							include("templates/buckets/forms/graphBox.tpl.php");
						}
						else
						{
							display_error("Permission Denied","You do not have access to this bucket");
						}						
					}
					
				}
				// edit
				elseif($_GET['action'] == "edit" && is_numeric($_GET['bid']))
				{
					$bucket = returnBucket($_GET['bid']);
					if($bucket == false)
					{
						display_error("Error","Bucket not found");		
					}
					else
					{
						if($_SESSION['user_id'] != $bucket->user_id)
						{
							display_error("Permission Denied","This bucket does not belong to you");
						}
						else
						{
							if($_POST)
							{
								//print_r($_POST);
								$_POST = secureArray($_POST);
								if($_POST['groupname'] == "none")
								{
									$groupname = "";
								}
								else
								{
									$groupname = $_POST['groupname'];
								}
								
								$db->query("UPDATE buckets SET statname = '$_POST[statname]', statlabel = '$_POST[statlabel]', groupname = '$groupname' WHERE id = '$bucket->id'");
								display_ok("Changes Saved","Changes to bucket applied - Redirecting to Bucket in 3 seconds");
								echo '<meta http-equiv="refresh" content="3; URL=buckets.php?action=view&bid='.$bucket->id.'">';
							}	
							else
							{
								include("templates/buckets/forms/edit.tpl.php");		
							}
						}
					}
											
				}
				// delete
				if($_GET['action'] == "delete" && is_numeric($_GET['bid']))
				{
					$bucket = returnBucket($_GET['bid']);
					if($bucket == false)
					{
						display_error("Error","Bucket not found");		
					}
					else
					{
						if($_SESSION['user_id'] != $bucket->user_id)
						{
							display_error("Permission Denied","This bucket does not belong to you");
						}
						else
						{
							$db->query("DELETE FROM buckets WHERE id = '$bucket->id'");
							global $m;
							$dbm = $m->buckets;
							$colname = $bucket->statid;
							$dbm->$colname->drop();
							display_ok("Bucket Deleted","We purged this poor little stat from planet earth.");
						}
					}					
				}
				?>


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
		<script type="application/javascript">
		
			
			function loadnew(url,frame,field)
			{
				var date = $( "#"+field ).val();
				var dateObject = $('#'+field).datepicker("getDate");
				var dateString = $.datepicker.formatDate("dd.mm.yy", dateObject);
				window.frames[frame].location = url + "&day="+dateString;
			}
			
			function loadnewframe(url,frame)
			{
				window.frames[frame].location = url;
			}			
					
			// validate edit field
			var $addForm = $('#addForm').validate({
			// Rules for form validation
				rules : {
					statname : {
						required : true
					},
					statlabel : {
						required : true
					}				
				},
		
				// Messages for form validation
				messages : {
					statname : {
						required : 'Please enter a name for this graph'
					},	
					statlabel : {
						required : 'Please enter a label for this graph'
					}								
				},
		
				// Do not change code below
				//errorPlacement : function(error, element) {
				//	error.insertAfter(element.parent());
				//}
			});		
			</script>		
	</body>

</html>