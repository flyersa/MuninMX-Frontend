<?php
if($_GET['plugin'] == "mxinvalidmx" || $_GET['plugin'] == "undefined")
{
	die;
}
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
if(!$_GET['graphs'] && !$_GET['plugin'] && !$_GET['nodes'])
{
	die("fdata missing");
}


// given data example in GET:
//Array ( [graphs] => user,idle [plugin] => cpu [nodes] => 646,2 [base] => 646 )

// check if we have this graph

$nodes = explode(",",$_GET['nodes']);
$graphs = explode(",",$_GET['graphs']);
array_push($nodes, $_GET['base']);

$nodes = array_unique ( $nodes );


foreach($nodes as $node)
{
	if(!accessToNode($node))
	{
		die("access to node denied, you specified a node that you have no access to");
	}	
}

$plugin = $_GET['plugin'];



// set chart type
if(!isset($_SESSION['stype']))
{
	$stype = "area";
}
else
{
	$stype = $_SESSION['stype'];
}


?>
<html>
    <head>
        <title>Munin Graph</title>
		<script src="js/libs/jquery-2.0.2.min.js"></script>
        <script src="js/highstock.js" type="text/javascript"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
    </head>
    <body>
    
        <div id="container" style="width: 100%; height: 100%"></div>
        
<?php


$node = getNode($_GET['base']);

$host = $node->id;

$graphname = "Custom Graph PREVIEW";

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
            text: '<?php echo htmlspecialchars($graphname). " - " . $plugintext . $ttext?>'
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
    	foreach($nodes as $inode)
		{
			$tpl = getNode($inode);
			$host = $tpl->id;
			$hostname = $tpl->hostname;
			
			foreach($graphs as $graph)
			{
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
					$period = "- 1 day";	
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
					$time = $item['recv'] * 1000;
					$val  = $item['value'];
					$data.= '['.$time.','.$val.'],';	
				}
	
				$visible = returnGraphVisibility($plugin,$graph);
	            echo "{ dataGrouping: { enabled: true, smoothed: false }, $visible name: ' ".$hostname." - ".$graph."', data: [ ".$data."]},";
			}
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
