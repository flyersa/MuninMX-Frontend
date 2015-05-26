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

if(!isset($_GET['plugin']))
{
	die("Plugin required");
}
$plugin = $_GET['plugin'];

// no match given, use current timestamp
if(!is_numeric($_GET['match']))
{
	$match = time();
}
else
{
	$match = $_GET['match'];
}

if(!dataAvailable($node->id,$plugin))
{
	echo '<div align="center" style="margin-top: 100px"><img src="img/nodata.jpg" align="center"></div>';
	die;
}

$host = $node->id;
$plugintext = htmlspecialchars(getPluginText($host,$plugin));

$a = file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/$host/fetch/$plugin");
$b = $a;
$singlejson  = json_decode($a);
$json = json_decode($b);
				
													
$vlabel = strip_tags(getVlabelForPlugin($host,$plugin)); 

$interval = $node->query_interval * 60;
$start = $match - $interval;
$end = $match + $interval;

// get time
foreach ($singlejson as $g)
{
	$q = getChartDataArray($host,$plugin,$g->str_GraphName,true,$start,$end);	
	foreach($q as $item)
	{
		//print_r($item);
		if($item == null)
		{
			$val = "0";
		}
		else
		{
			$time = $item['recv'];
		}
		break;
	}
}
$ttext = getFormatedLocalTime($time);

?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php $tpl->title = htmlspecialchars($node->hostname) . " - Single Data Pinpoint"; include("templates/core/head.tpl.php"); ?>
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
									<span class="widget-icon"> <i class="fa fa-search"></i> </span>
									<h2><strong><?php echo htmlspecialchars($node->groupname)?></strong> <i><?php echo htmlspecialchars($node->hostname)?></i> - Single Data Pinpoint </h2>
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
										<?php echo '<span class="label label-info">'.getFormatedLocalTime($time).'</span><br /><br />'; ?>
										<h2 style="margin-top: 1px"><?php echo htmlspecialchars($plugin)?> (<?php echo htmlspecialchars($plugintext)?>)</h2>
									<table id="table" class="table table-striped table-bordered table-hover">
										<thead>
										<?php
										//reset($json);
										foreach ($json as $g)
										{
											if(trim($g->str_GraphLabel) != "")
											{
												$g->str_GraphName = $g->str_GraphLabel;
												// fix for plugin shit by munin
												if($g->str_GraphName == "bps" && $vlabel == "bits in (-) / out (+) per second")
												{
													$g->str_GraphName = "sent";
												}
											}
											echo '<th>'.htmlspecialchars($g->str_GraphName).'</th>';
										}
										?>		
										</thead>
										<tbody>
											<tr>
										<?php
										reset($json);
										$json  = json_decode(file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/$host/fetch/$plugin"));
										foreach ($json as $gg)
										{
											$val = "No Data";
											echo '<td>';
											$q = getChartDataArray($host,$plugin,$gg->str_GraphName,true,$start,$end);	
											foreach($q as $item)
											{
												//print_r($item);
												if($item == null)
												{
													$val = "0";
												}
												else
												{
													$time = $item['recv'];
													$val  = $item['value'];
													// negative?
													if($gg->b_isNegative == true)
													{
														$val = 0 - $val;
													}
												}
											
												echo $val;
												break;
											}
											echo '</td>';
										}
										?>
										</tr>
										</tbody>
									</table>
								
									<hr /><br />
									<div id="chart">
										loading...
									</div>							
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
		<script src="js/highstock.js" type="text/javascript"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/modules/exporting.js"></script>
<script>

Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
var chart; // global
var clickDetected = false;
$(document).ready(function() {
	<?php include("js/charttheme.js"); ?>
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'chart',
            defaultSeriesType: 'column',
            zoomType: 'x',
            animation: true,
            shadow: true,                
        },  
        title: {
        	<?php if(!$notext) { ?>
            text: '<?php echo $node->hostname . " (".htmlspecialchars($node->groupname).") - " . htmlspecialchars($plugintext) . " - " . $ttext?>'
            <?php } else { ?>
            text: ''	
            <?php } ?>
        },
	plotOptions: {	
		line: {
			marker: {
	    		enabled: false
	    	},
	      	lineWidth: 1
		},		
 		pie: {
        	allowPointSelect: false,
            cursor: 'pointer',
            dataLabels: {
            	enabled: true
            },
            showInLegend: true
        },		
		series: {
			turboThreshold: 0,
			connectNulls: true, 

		},
	}, 		
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 50,          
        }, 
        credits: {
            enabled: false  
        },
		exporting: {
            	enabled: true,
  				sourceWidth: 1280,
            	sourceHeight: 400,
            	filename: '<?php echo htmlspecialchars($node->groupname)."_".$node->hostname."_".htmlspecialchars($plugin)?>'
       	},	
		tooltip: {
			shared: false,
            crosshairs: true,
	      /*  positioner: function(boxWidth, boxHeight, point) {
	            return {
	                x: point.plotX + 90,
	                y: point.plotY
	            };
	        },*/            
			formatter: function() {
				<?php if($plugin == "fusionio_io") { ?>
					return Highcharts.dateFormat('%A %d.%m.%y %H:%M:%S', this.x) + '<br/><b>'+this.series.name+'</b>: ' + getReadableFileSizeString(this.y); 
				<?php } else if ($vlabel == "bits in (-) / out (+) per second") { ?>
					return Highcharts.dateFormat('%A %d.%m.%y %H:%M:%S', this.x) + '<br/><b>'+this.series.name+'</b>: ' + getReadableFileSizeStringFromBits(this.y); 	
				<?php } else if ($vlabel == "Bytes/second") { ?>
					return Highcharts.dateFormat('%A %d.%m.%y %H:%M:%S', this.x) + '<br/><b>'+this.series.name+'</b>: ' + getReadableFileSizeString(this.y); 
				<?php } else { ?>
				if (this.series.name.indexOf("Bytes") !=-1 || chart.options.yAxis[0].title.text.indexOf("bytes") !=-1 ||
				    this.series.name.indexOf("bytes") !=-1 || chart.options.yAxis[0].title.text.indexOf("Bytes") !=-1 ||
				    chart.options.yAxis[0].title.text.indexOf("directory size") !=-1 || chart.options.title.text.indexOf("DFS Capacity") !=-1 ||
				    chart.options.yAxis[0].title.text.indexOf("Used memory") !=-1
				   ) {
					return Highcharts.dateFormat('%A %d.%m.%y %H:%M:%S', this.x) + '<br/><b>'+this.series.name+'</b>: ' + getReadableFileSizeString(this.y); 
				}
				else
				{
					return Highcharts.dateFormat('%A %d.%m.%y %H:%M:%S', this.x) + '<br/><b>'+this.series.name+'</b>: ' + Highcharts.numberFormat(this.y); 	
				}
				<?php } ?>
			} 
		},	        
        yAxis: {
            minPadding: 0.2,
            maxPadding: 0.2,
            /*floor: 0,*/
            title: {
                text: '<?php echo $vlabel?>'
            }
        },
        series: [
    <?php
    	$timeforall = 0;
    	$fresults = 0;
        foreach ($singlejson as $g)
        {
        	$q = getChartDataArray($host,$plugin,$g->str_GraphName,true,$start,$end);	
			$fresults = $fresults + sizeof($q);
			$data = "";
			$lasttime = 0;
			foreach($q as $item)
			{
				if($item == null)
				{
					continue;
				}
				$time = $item['recv'] * 1000;
				if($timeforall == 0)
				{
					$timeforall = $time;
				}
				$val  = $item['value'];
				// negative?
				if($g->b_isNegative == true)
				{
					$val = 0 - $val;
				}
				$data.= '['.$timeforall.','.$val.'],';
				break;	
			}
			if(trim($g->str_GraphLabel) != "")
			{
				$g->str_GraphName = $g->str_GraphLabel;
				// fix for plugin shit by munin
				if($g->str_GraphName == "bps" && $vlabel == "bits in (-) / out (+) per second")
				{
					$g->str_GraphName = "sent";
				}
			}
			$visible = returnGraphVisibility($plugin,$g->str_GraphName);
            echo "{ dataGrouping: { enabled: false, smoothed: false }, $visible name: '".htmlspecialchars($g->str_GraphName)."', data: [ ".$data."]},";				
		}
     ?>
    ],
    });                 
     	               			                    		
})

function getReadableFileSizeStringFromBits(fileSizeInBytes) {
	if(fileSizeInBytes < 0)
	{
		fileSizeInBytes = Math.abs(fileSizeInBytes);
	}
    //fileSizeInBytes = fileSizeInBytes * 0.125;
    var i = -1;
    var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
    do {
        fileSizeInBytes = fileSizeInBytes / 1024;
        i++;
    } while (fileSizeInBytes > 1024);

    return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
}

function getReadableFileSizeString(fileSizeInBytes) {
	if(fileSizeInBytes < 0)
	{
		fileSizeInBytes = Math.abs(fileSizeInBytes);
	}
    var i = -1;
    var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
    do {
        fileSizeInBytes = fileSizeInBytes / 1024;
        i++;
    } while (fileSizeInBytes > 1024);

    return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
};

</script>
	</body>

</html>