<ul id="myTab1" class="nav nav-tabs bordered">
	<li class="active">
		<a href="#s1" data-toggle="tab">Processes <span class="badge bg-color-blue txt-color-white"><?php echo sizeof($data['procs'])?></span></a>
	</li>
	<li>
		<a href="#s2" data-toggle="tab">Connections <span class="badge bg-color-blue txt-color-white"><?php echo sizeof($data['netstat'])?></span></a>
	</li>
	<li>
		<a href="#s4" data-toggle="tab">Memory Status</a>
	</li>	
	<li>
		<a href="#s3" data-toggle="tab">Logged in Users</a>
	</li>	
</ul>

<div id="myTabContent1" class="tab-content padding-10">
	<div class="tab-pane fade in active" id="s1">
		<div style="margin-top: 50px">						
										
		</div>
		<table id="procstable" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>PID</th>
				<th>USER</th>
				<th>COMMAND</th>
				<th>%CPU</th>
				<th>%MEM</th>
				<th>VSZ</th>
				<th>RSS</th>
				<th>TTY</th>
				<th>STAT</th>
				<th>START</th>
				<th>TIME</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach($data['procs'] as $proc)
			{
				//echo substr($proc['COMMAND'],1,2); die; 
				if(substr($proc['COMMAND'],1,2) == '\_')
				{
					$proc['COMMAND'] = substr($proc['COMMAND'],3,strlen($proc['COMMAND']));
				}
				echo '
				<tr>
					<td>'.htmlspecialchars($proc['PID']).'</td>
					<td>'.htmlspecialchars($proc['USER']).'</td>
					<td>'.htmlspecialchars($proc['COMMAND']).'</td>
					<td>'.htmlspecialchars($proc['%CPU']).'</td>
					<td>'.htmlspecialchars($proc['%MEM']).'</td>
					<td>'.htmlspecialchars($proc['VSZ']).'</td>
					<td>'.htmlspecialchars($proc['RSS']).'</td>
					<td>'.htmlspecialchars($proc['TTY']).'</td>
					<td>'.htmlspecialchars($proc['STAT']).'</td>
					<td>'.htmlspecialchars($proc['START']).'</td>
					<td>'.htmlspecialchars($proc['TIME']).'</td>
				</tr>';
			}
			?>
		</tbody>
		</table>
	</div>
	<div class="tab-pane fade" id="s2">
		<div style="margin-top: 50px">						
										
		</div>
		<table id="conntable" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>State</th>
				<th>Recv-Q</th>
				<th>Send-Q</th>
				<th>Local Address:Port</th>
				<th>Peer Address:Port</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach($data['netstat'] as $net)
			{
				echo '
				<tr>
					<td>'.htmlspecialchars($net['State']).'</td>
					<td>'.htmlspecialchars($net['Recv-Q']).'</td>
					<td>'.htmlspecialchars($net['Send-Q']).'</td>
					<td>'.htmlspecialchars($net['Local Address:Port']).'</td>
					<td>'.htmlspecialchars($net['Peer Address:Port']).'</td>	
				</tr>';
			}
			?>			
		</tbody>
		</table>
	</div>
	<div class="tab-pane fade" id="s3">
		<div style="margin-top: 50px">						
										
		</div>
		<table id="usertable" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>TTY</th>
				<th>Time</th>
				<th>Comment/Host</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach($data['who'] as $who)
			{
				echo '
				<tr>
					<td>'.htmlspecialchars($who['Name']).'</td>
					<td>'.htmlspecialchars($who['Line']).'</td>
					<td>'.htmlspecialchars($who['Time']).'</td>
					<td>'.htmlspecialchars($who['Comment']).'</td>
				</tr>';
			}
			?>			
		</tbody>
		</table>
	</div>	
	<div class="tab-pane fade" id="s4">

		<table id="memtable" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Stat</th>
				<th>Value</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				echo '
				<tr>
					<td style="width: 150px"><strong>Memory Total</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['MemTotal'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>Memory Free</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['MemFree'] * 1000)).'</td>
				</tr>			
				<tr>
					<td style="width: 150px"><strong>Buffers</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Buffers'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>Cached</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Cached'] * 1000)).'</td>
				</tr>					
				<tr>
					<td style="width: 150px"><strong>Swap Cached</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['SwapCached'] * 1000)).'</td>
				</tr>					
				<tr>
					<td style="width: 150px"><strong>Active</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Active'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>Inactive</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Inactive'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>Active (anon)</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Active(anon)'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>Active (file)</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Active(file)'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>Inactive (file)</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Inactive(file)'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>Unevictable</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Unevictable'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>Mlocked</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Mlocked'] * 1000)).'</td>
				</tr>			
				<tr>
					<td style="width: 150px"><strong>Swap Total</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['SwapTotal'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>Swap Free</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['SwapFree'] * 1000)).'</td>
				</tr>					
				<tr>
					<td style="width: 150px"><strong>Dirty</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Dirty'] * 1000)).'</td>
				</tr>					
				<tr>
					<td style="width: 150px"><strong>Writeback</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Writeback'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>AnonPages</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['AnonPages'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>Mapped</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Mapped'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>Shmem</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Shmem'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>Slab</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Slab'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>SReclaimable</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['SReclaimable'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>SUnreclaim</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['SUnreclaim'] * 1000)).'</td>
				</tr>			
				<tr>
					<td style="width: 150px"><strong>KernelStack</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['KernelStack'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>PageTables</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['PageTables'] * 1000)).'</td>
				</tr>					
				<tr>
					<td style="width: 150px"><strong>NFS_Unstable</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['NFS_Unstable'] * 1000)).'</td>
				</tr>					
				<tr>
					<td style="width: 150px"><strong>Bounce</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Bounce'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>WritebackTmp</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['WritebackTmp'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>CommitLimit</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['CommitLimit'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>Committed_AS</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Committed_AS'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>VmallocTotal</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['VmallocTotal'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>VmallocUsed</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['VmallocUsed'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>VmallocChunk</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['VmallocChunk'] * 1000)).'</td>
				</tr>			
				<tr>
					<td style="width: 150px"><strong>HardwareCorrupted</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['HardwareCorrupted'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>AnonHugePages</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['AnonHugePages'] * 1000)).'</td>
				</tr>					
				<tr>
					<td style="width: 150px"><strong>Dirty</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Dirty'] * 1000)).'</td>
				</tr>					
				<tr>
					<td style="width: 150px"><strong>HugePages_Total</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['HugePages_Total'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>HugePages_Free</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['HugePages_Free'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>HugePages_Rsvd</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['HugePages_Rsvd'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>HugePages_Surp</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['HugePages_Surp'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>Hugepagesize</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['Hugepagesize'] * 1000)).'</td>
				</tr>	
				<tr>
					<td style="width: 150px"><strong>DirectMap4k</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['DirectMap4k'] * 1000)).'</td>
				</tr>
				<tr>
					<td style="width: 150px"><strong>DirectMap2M</strong></td>
					<td>'.htmlspecialchars(bytesToSize($data['meminfo']['DirectMap2M'] * 1000)).'</td>
				</tr>																																		
				';

			?>			
		</tbody>
		</table>
	</div>		
</div>
