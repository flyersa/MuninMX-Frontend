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
	<?php $tpl->title = APP_NAME . " - Package Tracking"; include("templates/core/head.tpl.php"); ?>
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
					<li><a href="index.php">Home</a></li><li>Package Tracking</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-bug"></i> Package Tracking</h1>
					</div>
				</div>



				<?php 
				if(!$_GET && !$_POST)
				{
					renderNodesWithSam();	
				}
				elseif($_GET['action'] == "view" && is_numeric($_GET['nid']))
				{
					if(!accessToNode($_GET['nid']))
					{
						header("Location: 403.php");
						die;
					}
					
					$node = getNode($_GET['nid']);
					renderPackageListForNode($node->id);
				}
				elseif($_GET['action'] == "allpackages")
				{
					renderAllPackageTable();
				}
				elseif($_GET['action'] == "packagedetail" && isset($_GET['package']))
				{
					renderInstalledPackageTable($_GET['package']);
				}
					
					
				//getLatestPackageCountForNode(5);
				?>
									



			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
		
		<script>
			$(document).ready(function() {
				var oTableSam = $('#samtablenodes').dataTable({
					"sPaginationType" : "bootstrap_full",				
					"iDisplayLength" : 25,
					"bProcessing": true,
					"oLanguage":      { sProcessing: "Please Wait. Loading Nodes..." },
			        "sAjaxSource": "dtaTableSAM.php"
				});
				<?php if(!$_GET && !$_POST) { ?>
				oTableSam.fnSort( [ [1,'asc'] ] );
				<?php } ?>
				
				var oTablePkgNode = $('#packagelistnode').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 50 
				});
				
				var oTablePkgAll = $('#allpackages').dataTable({
					"sPaginationType" : "bootstrap_full",				
					"iDisplayLength" : 50,
					"bProcessing": true,
					"oLanguage":      { sProcessing: "Please Wait. Loading Packages..." },
			        "sAjaxSource": "dtaTableSAMall.php"				
				});	

						
				var oTablePkgInstalled = $('#installedpkg').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 50 
				});	
									
			})	
		</script>
	</body>

</html>