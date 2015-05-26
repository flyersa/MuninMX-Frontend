<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
if(!is_numeric($_GET['gid']))
{
	die("gid (graph id missing)");
}

// check if we have this graph

$result = $db->query("SELECT * FROM custom_graphs WHERE id = $_GET[gid]");
if($db->affected_rows < 1)
{
	die("unknown graph id");
}

$tpl = $result->fetch_object();
// access?
if($_SESSION['role'] != "admin")
{
	if($tpl->user_id != $_SESSION['user_id'])
	{
			if(!accessToCustomGraph($tpl->id))
			{
				die("Permission Denied");
				return;				
			}
	}
}

$gid = $tpl;

// set chart type
if(!isset($_SESSION['stype']))
{
	$stype = "area";
}
else
{
	$stype = $_SESSION['stype'];
}

$result = $db->query("SELECT *,nodes.hostname FROM `custom_graph_items` LEFT JOIN nodes ON node_id = nodes.id WHERE custom_graph_id = $_GET[gid]");
if($db->affected_rows < 1)
{
	die("no graph items for this graph defined");
}

?>
<html>
    <head>
        <title>Munin Graph</title>
		<script src="js/libs/jquery-2.0.2.min.js"></script>
        <script src="js/highstock.js" type="text/javascript"></script>
        <script src="js/modules/exporting.js"></script>
    </head>
    <body>
    
        <div id="container" style="width: 100%; height: 100%"></div>
        
<?php

$tpl = $result->fetch_object();

$plugin = $tpl->plugin;
$host = $tpl->node_id;

$plugintext = getPluginText($host,$plugin);

$json  = json_decode(file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/$host/fetch/$plugin"));

if($_GET['period'])
{
	if($_GET['period'] == "tmonth")
	{
		$ptext = " last 3 months";	
	}
	else
	{
		$ptext = $_GET['period'];
	}
	$ttext = "/" . htmlspecialchars($ptext);	
}

if(!$_GET['start'] && !$_GET['end'])
{
	if($_GET['day'])
	{
		$start = strtotime($_GET['day']);
		$end = strtotime('+1 day', $start);
		$ttext = "/" . $_GET['day'];

	}
}

?>
<script>
Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
var chart; // global
$(document).ready(function() {
	<?php include("js/charttheme.js"); ?>
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            defaultSeriesType: '<?php echo $stype?>',
            zoomType: 'x',

        },
        title: {
            text: '<?php echo htmlspecialchars($gid->graph_name). " - " . $plugintext . $ttext?>'
        },
	plotOptions: {
		line: {
			marker: {
	    		enabled: false
	    	},
	      	lineWidth: 1
		},
       	area: {
        	lineWidth: 0.8,
            marker: {
            	enabled: false
            },
            states: {
            	hover: {
                	lineWidth: 1
                }
            },
            fillOpacity: 0.3
        }	
	},        
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 50,
            labels: {
                format: '{value:%d.%m %H:%M:%S}',
                align: 'right',
                rotation: -70
            }            
        }, 
        credits: {
            enabled: false  
        },
		exporting: {
            	enabled: true,
  				sourceWidth: 1280,
            	sourceHeight: 400
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
					return Highcharts.dateFormat('%d.%m.%y %H:%M', this.x) + '<br/><b>'+this.series.name+'</b>: ' + getReadableFileSizeString(this.y); 
				<?php } else { ?>
				if (this.series.name.indexOf("Bytes") !=-1 || chart.options.yAxis[0].title.text.indexOf("bytes") !=-1 ||
				    this.series.name.indexOf("bytes") !=-1 || chart.options.yAxis[0].title.text.indexOf("Bytes") !=-1 ||
				    chart.options.yAxis[0].title.text.indexOf("directory size") !=-1 || chart.options.title.text.indexOf("DFS Capacity") !=-1 ||
				    chart.options.yAxis[0].title.text.indexOf("Used memory") !=-1
				   ) {
					return Highcharts.dateFormat('%d.%m.%y %H:%M', this.x) + '<br/><b>'+this.series.name+'</b>: ' + getReadableFileSizeString(this.y); 
				}
				else
				{
					return Highcharts.dateFormat('%d.%m.%y %H:%M', this.x) + '<br/><b>'+this.series.name+'</b>: ' + Highcharts.numberFormat(this.y); 	
				}
				<?php } ?>
			} 
		},	        
        yAxis: {
            minPadding: 0.2,
            maxPadding: 0.2,
            /*floor: 0,*/
            title: {
                text: '<?php echo getVlabelForPlugin($host,$plugin)?>'
            }
        },
        series: [
    <?php
    	mysqli_data_seek($result, 0);
        while($tpl = $result->fetch_object())
		{
			$host = $tpl->node_id;
			$hostname = $tpl->hostname;
			if(endsWith($hostname, "unbelievable-machine.net"))
			{
				$hostname = substr($hostname,0,strpos($hostname,"unbelievable-machine.net") - 1);
			}
			$plugin = $tpl->plugin;
			$graph = $tpl->graph;
        	$period = false;
        	if($_GET['period'] == "24h")
			{
				$period = "- 1 day";	
			}
			elseif($_GET['period'] == "week")
			{
				$period = "-1 week";
			}
			elseif($_GET['period'] == "month")
			{
				$period = "-4 week";
			}
			elseif($_GET['period'] == "tmonth")
			{
				$period = "-3 month";
			}			
			else
			{
				$period = "- 1 year";	
			}	
			
					
			if(!$_GET['start'] && !$_GET['end'] && !$_GET['day'])
			{
        		$q = getChartDataArray($host,$plugin,$graph,$period);
			}
			elseif($_GET['day'])
			{
				$q = getChartDataArray($host,$plugin,$graph,true,$start,$end);		
			}
			else
			{
				$q = getChartDataArray($host,$plugin,$graph,true,$_GET['start'],$_GET['end']);	
			}
			$data = "";
			foreach($q as $item)
			{
				if($item == null)
				{
					continue;
				}
				$time = $item['recv'] * 1000;
				$val  = $item['value'];
				$data.= '['.$time.','.$val.'],';	
			}
			if(trim($g->str_GraphLabel) != "")
			{
				$g->str_GraphName = $g->str_GraphLabel;
			}
			$visible = returnGraphVisibility($plugin,$graph);
            echo "{ dataGrouping: { enabled: true, smoothed: false }, $visible name: ' ".$hostname." - ".$graph."', data: [ ".$data."]},";
        }
    ?>
    ],
    });        
});

function getReadableFileSizeString(fileSizeInBytes) {

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
