			<div id="logo-group">

				<!-- PLACE YOUR LOGO HERE -->
				<span id="logo"> <a href="index.php"><img src="img/muninmx-trans-small-single.png" alt="MuninMX"></a> </span>
				<!-- END LOGO PLACEHOLDER -->

			</div>
<?php if(isset($_COOKIE['lastseen'])) {
	 $c = explode(",",$_COOKIE['lastseen']);
?>
		<!-- projects dropdown -->
			<div id="project-context">

				<span class="label">Nodes:</span>
				<span id="project-selector" class="popover-trigger-element dropdown-toggle" data-toggle="dropdown">Recently viewed<i class="fa fa-angle-down"></i></span>

				<!-- Suggestion: populate this list with fetch and push technique -->
				<ul class="dropdown-menu">
					<?php foreach($c as $seen) { $n = explode("|",$seen); ?>
					<li>
						<a href="view.php?nid=<?php echo htmlspecialchars($n[0])?>"><?php echo htmlspecialchars($n[1])?></a>
					</li>
					<?php } ?>
				</ul>
				<!-- end dropdown-menu-->

			</div>
			<!-- end projects dropdown -->
<?php } ?>

<?php if(isset($_COOKIE['favorites'])) {
	 $f = explode(",",$_COOKIE['favorites']);
?>
		<!-- projects dropdown -->
			<div id="project-context">

				<span class="label">Nodes:</span>
				<span id="project-selector" class="popover-trigger-element dropdown-toggle" data-toggle="dropdown">Favorites<i class="fa fa-angle-down"></i></span>

				<!-- Suggestion: populate this list with fetch and push technique -->
				<ul class="dropdown-menu">
					<?php foreach($f as $fav) { $fn = explode("|",$fav); ?>
					<li>
						<?php if(getNode($fn[0]) != false) { ?>
							<a href="view.php?nid=<?php echo htmlspecialchars($fn[0])?>"><?php echo htmlspecialchars($fn[1])?></a>
						<?php } ?>
					</li>
					<?php } ?>
				</ul>
				<!-- end dropdown-menu-->

			</div>
			<!-- end projects dropdown -->
<?php } ?>

<?php if(DEMO_MODE == true) { ?>
<div style="position: absolute; left: 50%;"><p style="text-align: center; font-size: 25px; padding-top: 5px">DEMO VERSION</p></div>
<?php } ?>

			<!-- pulled right: nav area -->
			<div class="pull-right">

				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span> <a href="javascript:void(0);" onClick="minify()" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
				</div>
				<!-- end collapse menu -->

				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="login.php?logout=true" title="Sign Out"><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->

				<!-- search mobile button (this is hidden till mobile view port) -->
				<div id="search-mobile" class="btn-header transparent pull-right">
					<span> <a href="search.php" title="Search"><i class="fa fa-search"></i></a> </span>
				</div>
				<!-- end search mobile button -->

				<!-- input: search field -->
				<form action="search.php" method="POST" class="header-search pull-right">
					<input type="text" name="search" placeholder="Find Node" id="search-fld">
					<button type="submit">
						<i class="fa fa-search"></i>
					</button>
					<a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
				</form>
				<!-- end input: search field -->
				

			</div>
			<!-- end pulled right: nav area -->