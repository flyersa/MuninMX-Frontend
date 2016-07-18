		<aside id="left-panel">
			<!-- User info -->
			<div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 		
					<a href="<?php if($_SESSION['role'] == "admin") { echo 'javascript:void(0);'; } else { echo 'settings.php';}?>">
						<img src="img/avatars/noav.jpeg" alt="me" class="online" /> 
						<span>
							<?php echo htmlspecialchars($_SESSION['username'])?> 
						</span>
					</a> 
					
				</span>
			</div>
			<!-- end user info -->

			<!-- NAVIGATION : This navigation is also responsive

			To make this navigation dynamic please make sure to link the node
			(the reference to the nav > ul) after page load. Or the navigation
			will not initialize.
			-->
			<nav>
				<!-- NOTE: Notice the gaps after each icon usage <i></i>..
				Please note that these links work a bit different than
				traditional hre="" links. See documentation for details.
				-->
				<ul>
					<li<?php if($_SERVER['REQUEST_URI'] == "/index.php") { echo ' class="active"';}?>>
						<a href="index.php" title="Your Nodes"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Your Nodes</span></a>
					</li>					
					<?php if($_SESSION['role'] == "userext" || $_SESSION['role'] == "admin") { ?>
					<li<?php if($_SERVER['REQUEST_URI']  == "/index.php?action=add") { echo ' class="active"'; }?>>
						<a href="index.php?action=add" title="Add Nodes"><i class="fa fa-lg fa-fw fa-plus"></i> <span class="menu-item-parent">Add Node</span></a>
					</li>	
					<li<?php if($_SERVER['REQUEST_URI']  == "/index.php?action=groups") { echo ' class="active"'; }?>>
						<a href="index.php?action=groups" title="View Groups"><i class="fa fa-lg fa-fw fa-group"></i> <span class="menu-item-parent">Groups</span></a>
					</li>					
					<?php } ?>		
					<?php if(getDashboardCount() > 0) { ?>
					<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/dashboard.php")) { echo ' class="active"';}?>>
						<a href="dashboard.php" title="Dashboards"><i class="fa fa-lg fa-fw fa-dashboard"></i> <span class="menu-item-parent">Dashboards</span></a>
					</li>							
					<?php } ?>
					<?php if(eventsAvail()) { ?>
					<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/events.php")) { echo ' class="active"';}?>>
						<a href="events.php" title="Events"><i class="fa fa-lg fa-fw fa-tags"></i> <span class="menu-item-parent">Event Log</span></a>
					</li>							
					<?php } ?>													
					<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/customs.php")) { echo ' class="active"'; }?>>
						<a href="#"><i class="fa fa-lg fa-fw fa-magic"></i> <span class="menu-item-parent">Custom Metrics</span></a>
						<ul>
							<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/customs.php")){ echo ' class="active"'; }?>>
								<a href="customs.php">Your Grouped Metrics</a>
							</li>
							<li<?php if($_SERVER['REQUEST_URI'] == "/customs.php?vgroup=true") { echo ' class="active"'; }?>>
								<a href="customs.php?vgroup=true">Grouped Metric Groups</a>
							</li>	
							<li <?php if($_SERVER['REQUEST_URI'] == "/customs.php?action=add") { echo ' class="active"'; }?>>
								<a href="customs.php?action=add">Create Grouped Graph</a>
							</li>														
						</ul>
					</li>	
					<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/alerts.php")) { echo ' class="active"'; }?>>
						<a href="#"><i class="fa fa-lg fa-fw fa-bell"></i> <span class="menu-item-parent">Alert Notifications</span></a>
						<ul>
							<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/alerts.php?action=contacts")){ echo ' class="active"'; }?>>
								<a href="alerts.php?action=contacts">Your Contacts</a>
							</li>								
							<li<?php if($_SERVER['REQUEST_URI'] == "/alerts.php" || startsWith($_SERVER['REQUEST_URI'],"/alerts.php?action=alerts")) { echo ' class="active"'; }?>>
								<a href="alerts.php">Metric Notifications</a>
							</li>	
							<li<?php if($_SERVER['REQUEST_URI'] == "/alerts.php" || startsWith($_SERVER['REQUEST_URI'],"/alerts.php?action=logmetrics")) { echo ' class="active"'; }?>>
								<a href="alerts.php?action=logmetrics">Metric Notifications Log</a>
							</li>																							
						</ul>
					</li>	
					<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/checks.php")) { echo ' class="active"'; }?>>
						<a href="#"><i class="fa fa-lg fa-fw fa-medkit"></i> <span class="menu-item-parent">Service Checks</span></a>
						<ul>
							<li<?php if($_SERVER['REQUEST_URI'] == "/checks.php" || startsWith($_SERVER['REQUEST_URI'],"/checks.php?action=view")) { echo ' class="active"'; }?>>
								<a href="checks.php">Your Service Checks</a>
							</li>								
							<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/checks.php?action=add")){ echo ' class="active"'; }?>>
								<a href="checks.php?action=add">New Service Check</a>
							</li>																							
						</ul>
					</li>						
					<?php if(trackPkgDataAvailable()) { ?>
					<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/sam.php")) { echo ' class="active"'; }?>>
						<a href="#"><i class="fa fa-lg fa-fw fa-bug"></i> <span class="menu-item-parent">Package Tracking</span></a>
						<ul>
							<li<?php if($_SERVER['REQUEST_URI'] == "/sam.php" || startsWith($_SERVER['REQUEST_URI'],'/sam.php?action=packagedetail') || startsWith($_SERVER['REQUEST_URI'],'/sam.php?action=view')) { echo ' class="active"'; }?>>
								<a href="sam.php">Overview</a>
							</li>								
							<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/sam.php?action=allpackages")){ echo ' class="active"'; }?>>
								<a href="sam.php?action=allpackages">All Packages</a>
							</li>									
						</ul>
					</li>						
					<?php } ?>	
					<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/rca.php")) { echo ' class="active"'; }?>>
						<a href="#"><i class="fa fa-lg fa-fw fa-stethoscope"></i> <span class="menu-item-parent">Analyzer Tool</span></a>
						<ul>
							<li<?php if($_SERVER['REQUEST_URI'] == "/rca.php") { echo ' class="active"'; }?>>
								<a href="rca.php">New Job</a>
							</li>								
							<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/rca.php?view")){ echo ' class="active"'; }?>>
								<a href="rca.php?viewpast=true">Past Results</a>
							</li>									
						</ul>
					</li>									
					<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/buckets.php")) { echo ' class="active"'; }?>>
						<a href="#"><i class="fa fa-lg fa-fw fa-bitbucket"></i> <span class="menu-item-parent">Bucket Stats</span></a>
						<ul>
							<li<?php if(startsWith($_SERVER['REQUEST_URI'],"/buckets.php")) { echo ' class="active"'; }?>>
								<a href="buckets.php">Your Bucket Stats</a>
							</li>
							<li <?php if($_SERVER['REQUEST_URI'] == "/buckets.php?action=add") { echo ' class="active"'; }?>>
								<a href="buckets.php?action=add">Create Bucket Stat</a>
							</li>	
							<li<?php if($_SERVER['REQUEST_URI'] == "/buckets.php?action=api") { echo ' class="active"'; }?>>
								<a href="buckets.php?action=api">API Help</a>
							</li>																					
						</ul>
					</li>																
					<?php if($_SESSION['role'] == "admin") { ?>					
					<li<?php if($_SERVER['REQUEST_URI']  == "/users.php") { echo ' class="active"'; }?>>
						<a href="users.php" title="Manage Users"><i class="fa fa-lg fa-fw fa-user"></i> <span class="menu-item-parent">Manage Users</span></a>
					</li>						
					<li<?php if($_SERVER['REQUEST_URI']  == "/apistatus.php") { echo ' class="active"'; }?>>
						<a href="#"><i class="fa fa-lg fa-fw fa-info"></i> <span class="menu-item-parent">MuninMX Status</span></a>
						<ul>
							<li>
								<a href="apistatus.php?action=noplugins">Nodes without Plugins</a>
							</li>
							<li>
								<a href="apistatus.php?action=joblist">Job Scheduler Status</a>
							</li>	
							<li>
								<a href="apistatus.php?action=customjoblist">Custom Job Scheduler Status</a>
							</li>													
						</ul>
					</li>												
					<?php } ?>
					<li<?php if($_SERVER['REQUEST_URI'] == "/apidoc.php" || $_SERVER['REQUEST_URI'] == "/apisetting.php") { echo ' class="active"'; }?>>
						<a href="#"><i class="fa fa-lg fa-fw fa-gears"></i> <span class="menu-item-parent">MuninMX API</span></a>
						<ul>
							<li<?php if($_SERVER['REQUEST_URI'] == "/apisetting.php") { echo ' class="active"'; }?>>
								<a href="apisetting.php">API Key</a>
							</li>
							<li <?php if($_SERVER['REQUEST_URI'] == "/apidoc.php") { echo ' class="active"'; }?>>
								<a href="apidoc.php">API Documentation</a>
							</li>																					
						</ul>
					</li>
					<?php if($_SESSION['role'] != "admin") { ?>
					<li<?php if($_SERVER['REQUEST_URI'] == "/settings.php") { echo ' class="active"'; }?>>	
						<a href="#"><i class="fa fa-lg fa-fw fa-lock"></i> <span class="menu-item-parent">Account Settings</span></a>
						<ul>
							<li<?php if($_SERVER['REQUEST_URI'] == "/settings.php") { echo ' class="active"'; }?>>
								<a href="settings.php">Your Account</a>
							</li>																				
						</ul>
					</li>
					<?php } ?>						
				</ul>
			</nav>
			<span class="minifyme" onClick="minify()"> <i class="fa fa-arrow-circle-left hit"></i> </span>

		</aside>