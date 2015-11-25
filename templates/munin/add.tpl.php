<?php
if(!isset($_POST['port']))
{
	$_POST['port'] = 4949;
}
// required for addNode script
global $db;
$user = getUserObject($_SESSION['user_id']);
if(trim($user->apikey) == "" )
{
	$key = sha1($_SESSION['user_id'].microtime());
	$db->query("UPDATE users SET apikey = '$key' WHERE id = '$user->id'");		
	$user = getUserObject($_SESSION['user_id']);								
}
?>
									<!-- widget content -->
									<div class="widget-body">
																		
										<form class="smart-form" name="editForm" id="editForm" action="index.php?action=add" method="POST">
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Hostname</label>
														<div class="col-md-10">
															<input class="form-control" name="hostname" placeholder="Valid Hostname or IP" type="text" value="<?php echo (isset($_POST['hostname'])?$_POST['hostname']:'') ?>">
														</div>
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Contact Via Other Node?</label>
														<div class="col-md-10">
															<?php renderViaHostDropDown();?>
															<div class="note">
															<strong>Info:</strong> This is only required if another munin-node serves the plugin. As example for snmp. Leave on default if unsure
															</div>
														</div>
														
													</div>
												</fieldset>
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Port</label>
														<div class="col-md-10">
															<input class="form-control" name="port" placeholder="Munin Port" type="text" value="<?php echo (isset($_POST['port'])?$_POST['port']:'')?>">
														</div>
													</div>	
												</fieldset>	
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Optional Auth Password</label>
														<div class="col-md-10">
															<input class="form-control" name="authpw" placeholder="Leave empty for none" type="text" value="<?php echo (isset($_POST['authpw'])?$_POST['authpw']:'') ?>">
															<div class="note">
															<strong>Info:</strong> If a Auth Password is set the MuninMX collector will authenticate against a pseudo plugin before loading plugins. Leave empty if unsure
															</div>															
														</div>
													</div>	
												</fieldset>													
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Query Interval</label>
														<div class="col-md-10">
															<label class="select">
	
															<select name="query_interval">
																<option value="1">1 Minute</option>
																<option value="5" selected>5 Minutes</option>
																<option value="10">10 Minutes</option>
															</select>
															</label>
														</div>
													</div>		
												</fieldset>		
												<fieldset>
													<div class="form-group">
														<label class="col-md-2 control-label">Group</label>
														<div class="col-md-10">
															<input class="form-control" placeholder="Groupname" name="groupname" value="<?php echo (isset($_POST['groupname'])?$_POST['groupname']:'' )?>" type="text" data-autocomplete='[<?php echo getAutoCompleteGroups()?>]'>
														</div>
													</div>	
												</fieldset>														
												<footer>
												<button type="submit" class="btn btn-primary">
													Add Munin Node
												</button>
												</footer>
									
										</form>
									</div>
								

<div style="margin: 20px">
	

			<div id="tabs">
				<ul>
					<li>
						<a href="#tabs-a">Auto Agent Installation</a>
					</li>
					<li>
						<a href="#tabs-b">Manuall Agent Installation</a>
					</li>
				</ul>
				<div id="tabs-a">
					<p>
						Execute this on your Linux Server (Debian, Ubuntu, CentOS/Redhat) to auto install and configure munin and add the node to muninmx:<br /><br />
						<code>
							wget <?php echo BASEURL?>/autoAdd.php -O addnode.sh; bash ./addnode.sh <?php echo $user->apikey?>
						</code>
					</p>
				</div>
				<div id="tabs-b">
					<h2>Install Instructions (munin-node)</h2>
						<p>
							<b>You need to have the package <b></b>munin-node</b> installed and running on the system you want to graph. This package can be installed with  <b>yum install munin-node</b> (CentOS/RedHat) or <b>apt-get install munin-node</b> (Ubuntu/Debian)</b>
						</p>
						
						
						To allow connections from the MuninMX Collector add the following line to<br /> <b>/etc/munin/munin-node.conf:</b>
						<div class="well">
						<code>
							 allow <?php echo COLLECTOR_PRIMARY_IP?>
						</code>
						</div>
						
						<br /><br />
						<h2>Optional Password Authentication for MuninMX Collector to Munin-Node</h2>
						<p>
							you can add a simple shell plugin to your munin-node with a password. The MuninMX collector will not load plugins from that host if the password in the shellplugin does not match the optional auth password.
							To enable password authentication enter a authentication password in the form above and add the following plugin to munin as<br />
						</p>
						<b>/etc/munin/plugins/muninmxauth:</b>
						<div class="well">
							<code>
					#!/bin/bash<br />
					if [ "$1" = "config" ]; then<br />
					&nbsp;  &nbsp; &nbsp;       echo yourauthenticationpassword<br />
					fi
							</code>
						</div>
						<p>
							replace "yourauthenticationpassword" with the choosen password and enable execution with <b>chmod +x /etc/munin/plugins/muninmxauth</b> then restart munin-node
						</p>
				</div>
			</div>

	
	
	
</div>

				