<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}

if(!is_numeric($_GET['node']))
{
	die("No Node given");
}

$node = getNode($_GET['node']);
if(!$node)
{
	die("Invalid Node");
}

if(!accessToNode($node->id))
{
	die("Access denied");	
}


// no match given, use current timestamp
if(!is_numeric($_GET['match']))
{
	$match = time();
}
else
{
	$match = $_GET['match'];
}

$ss = getEssentialSystemSnapShot($node->id,$match,true);
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = htmlspecialchars($node->hostname) . " - System Snapshots"; include("templates/core/head.tpl.php"); ?>
	</head>
	<body style="overflow: scroll;">

		<!-- END HEADER -->


		<!-- MAIN PANEL -->
		<div id="maindnm" role="maindnm">

			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<!-- row -->
				<div class="row">
						<!-- NEW WIDGET START -->
						<article class="col-sm-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-white" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					
								<header>
									<?php
										$pnstatus = getSystemSnapShotPrevNextStatus($node->id,$match);
									?>
									<?php if($pnstatus->dataprev) { ?>
									<span style="float: left; line-height: 0px; margin-bottom: 5px"><a href="essframe.php?node=<?php echo $node->id?>&match=<?php echo $pnstatus->prevtime?>" class="btn btn-default"><i class="fa fa-angle-double-left"></i> Prev</a></span>
									<?php } ?>
									<?php if($pnstatus->datanext) { ?>
  									<span style="float: right; line-height: 0px; margin-bottom: 5px"><a href="essframe.php?node=<?php echo $node->id?>&match=<?php echo $pnstatus->nexttime?>" class="btn btn-default">Next <i class="fa fa-angle-double-right"></i></a></span>
									<?php } ?>
									<span class="widget-icon"> <i class="fa fa-search"></i> </span>
									<h2><strong><?php echo htmlspecialchars($node->groupname)?></strong> <i><?php echo htmlspecialchars($node->hostname)?></i> - System Snapshots </h2>
									<!--
									<div class="widget-toolbar">
										maybe usefull...
									</div>
									-->
								</header>
								

								<!-- widget div-->
								<div>
									<!-- end widget edit box -->
									
					
									<!-- widget content -->
									<div class="widget-body">
									<?php
									// data available at all?
									if(!EssentialDataAvailable($node->id))
									{
										display_error("No Data","There is no System Snapshot Data available for this node. Please install the muninmx_essentials plugin on this host to use this feature");
									}
									else
									{
										if($ss != false)
										{
											echo '<span class="label label-info">'.getFormatedLocalTime($ss['time']).'</span><br /><br />';
											$data = buildEssentialArrayFromString($ss['data']);
											
											//echo $ss['data'];
											include("templates/essentials/resultTabs.tpl.php");
										}	
										else
										{
											display_error("Snapshot not available","No Snapshot found for given timestamp");
										}
									}
									?>
									</div>
							    </div>
							</div>
						</article>
				</div>
				<!-- end row -->


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
		
		<script>
			$(document).ready(function() {
			
				var oTableProcs = $('#procstable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 50 
				});
				oTableProcs.fnSort( [ [3,'desc'] ] );
				var oTableCon = $('#conntable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 50 
				});
				var oTableWho = $('#usertable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 50 
				});			
			})
		</script>
	</body>

</html>