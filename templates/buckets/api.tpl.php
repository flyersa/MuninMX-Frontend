<?php
if(!isset($tpl->graphid))
{
	$tpl->graphid = 'examplegraphid';
}
?>

<h3>Bucket Stat API - Get Started</h3>
<div class="span12" style="margin-left: 0px">
    <div class="box">
      <div class="box-header">
        <span class="title"><i class="icon-th-list"></i> Basics</span>
      </div>
      <div class="box-content padded">
		To push values to your bucket you can use POST or GET requests to <b><?php echo BUCKETSTAT_BASE?>sapi.php</b> <br /><br />
		The following fields are required for both post or get:<br /><br />
		<table class="table table-normal">
			<thead>
			<tr>
				<th>Fieldname</th>
				<th>Value</th>
				<th>Required?</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>graph</td>
				<td>the graphid from your bucket, as it can be seen from the simplestats overview page</td>
				<td>YES</td>
			</tr>
			<tr>
				<td>value</td>
				<td>a numeric value for the graph. Can be positive or negative. Example: 19.8</td>
				<td>YES</td>
			</tr>	
			<tr>
				<td>timestamp</td>
				<td>a unixtimestamp to use for the value in the graph, if not given the servertime is used when inserting the data to the database</td>
				<td>NO</td>
			</tr>
			</tbody>						
		</table>   
		
		<br />
		Return Values:<br /><br />
		The API will return a json output with a msg and a status code. The status code is ether 200 for OK or 400 for failure:
		<pre><code>{"status":200,"msg":"stored"}</code></pre> 
		It is important, that for performance reasons the api does not verify the graphid and will even accept messages with a invalid graphid. So verify that you use the correct graphid in your querys.
      </div>
    </div>
</div>


<div class="span12" style="margin-left: 0px">
    <div class="box">
      <div class="box-header">
        <span class="title"><i class="icon-th-list"></i> Example: Store simple value via GET</span>
      </div>
      <div class="box-content padded">
<pre><code>sapi.php?graph=$1&value=$2
<?php echo BUCKETSTAT_BASE?>sapi.php?graph=<?php echo $tpl->graphid?>&value=$YOURVALUE

If you request <?php echo BUCKETSTAT_BASE?>sapi.php?graph=<?php echo $tpl->graphid?>&value=1.0

this would store the value 1.0 to the graph with graphid <?php echo $tpl->graphid?>
</code></pre>       
      </div>
    </div>
</div>

<div class="span12" style="margin-left: 0px">
    <div class="box">
      <div class="box-header">
        <span class="title"><i class="icon-th-list"></i> Example: Nginx Active Connections</span>
      </div>
      <div class="box-content padded">
<pre><code>
#!/bin/bash
# Retrieve the load average of the past 1 minute
STAT=`curl -s http://localhost/nginx_status`
CONNS=`echo ${STAT:19:4} | tr -d ' '`
GRAPHID="<?php echo $tpl->graphid?>"
wget -O- --quiet --post-data "graph=$GRAPHID&value=$CONNS" <?php echo BUCKETSTAT_BASE?>sapi.php > /dev/null
</code></pre>       
      </div>
    </div>
</div>

<div class="span12" style="margin-left: 0px">
    <div class="box">
      <div class="box-header">
        <span class="title"><i class="icon-th-list"></i> Example: Server Load Average</span>
      </div>
      <div class="box-content padded">
<pre><code>
#!/bin/bash
Load_AVG=`uptime | awk '{ print $10}' | cut -f1 -d,`
GRAPHID="<?php echo $tpl->graphid?>"
wget -O- --quiet --post-data "graph=$GRAPHID&value=$Load_AVG" <?php echo BUCKETSTAT_BASE?>sapi.php > /dev/null
</code></pre>    
      </div>
    </div>
</div>