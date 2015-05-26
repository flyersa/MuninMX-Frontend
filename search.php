<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}

if($_POST['search'])
{
	$sword = $db->real_escape_string($_POST['search']);
	$dosearch = false;
}
else
{
	$sword = "none";
	$dosearch = true;
}

if($_SESSION['role'] == "admin")
{
	$result = $db->query("SELECT id FROM nodes WHERE hostname LIKE '$sword%'");
}
elseif($_SESSION['role'] == "userext")
{
	$result = $db->query("SELECT id FROM nodes WHERE user_id = '$_SESSION[user_id]' AND hostname LIKE '$sword%'");	
}
else
{
	$result = $db->query("SELECT id FROM nodes WHERE hostname LIKE '$sword%' AND groupname = '$_SESSION[accessgroup]'");	
}
if($db->affected_rows == 1)
{
	$tpl = $result->fetch_object();
	header("Location: view.php?nid=$tpl->id");
	die;
}

if($db->affected_rows > 0)
{
	$dosearch = true;
}

?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = APP_NAME . " - Search"; include("templates/core/head.tpl.php"); ?>
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
					<li>Home</li><li>Search</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-search"></i> Search <span>> <?php echo htmlspecialchars($sword)?></span></h1>
					</div>
				</div>

				
				<section id="widget-grid" class="">

				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-search"></i> </span>
									<h2>Search Results</h2>
								</header>
								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body no-padding">
										<div class="widget-body-toolbar">
										</div>

										<?php
											if($dosearch)
											{
												 renderNodeTable($sword); 	
											}
											else
											{
												display_warning("No Results", "Sorry the hamsters tried hard, but did not find anything for your search");
											}
										?>
										
									</div>
								</div>
							</div>
					</article>
				</div>
				</section>


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->


		<!--================================================== -->
		<?php include("templates/core/scripts.tpl.php"); ?>
		<?php if($dosearch) { ?>
				<script type="application/javascript">
			$(document).ready(function() {
				var oTable = $('#nodetable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});
				oTable.fnSort( [ [1,'asc'] ] );
			})
		</script>	
		<?php } ?>
	</body>

</html>