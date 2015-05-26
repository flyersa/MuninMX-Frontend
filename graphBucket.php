<html>
    <head>
        <title>Munin Graph</title>
		<script src="js/libs/jquery-2.0.2.min.js"></script>
        <script src="js/highstock.js" type="text/javascript"></script>
         <script src="js/modules/exporting.js"></script>
    </head>
    <body>
    
        <div id="graph" style="width: 100%; height: 100%"></div>
<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header('HTTP/1.0 401 Unauthorized');
	die;
}
//checkToken();

if(!is_numeric($_GET['bid']))
{
	die;
}
else
{
	if(!gotAccessToBucket($_GET['bid']))
	{
		die("access denied");
	}
}

$bucket = returnBucket($_GET['bid']);

$is_compare = false;
if(is_numeric($_GET['compare']))
{
	$compare = returnBucket($_GET['compare']);
	if($compare->user_id != $_SESSION['user_id'])
	{
		echo "bucket user_id mismatch with compare id";
		die;
	}
	$is_compare = true;
}

global $m; 
$dbm = $m->buckets;
$colname = $bucket->statid;
$collection = $dbm->$colname;


// check if we have data at all
$res = $collection->find()->limit(1);


if($res->count() < 1)
{
	echo '<br />';
	display_info("No stats yet", "There are no data available yet for this stat. Please check back in a few minutes or submit data to this stat");
	die;
}

if(isset($_GET['day']))
{
	$start = strtotime($_GET['day']);
	$end = strtotime('+1 day', $start);
	$tlabel = htmlspecialchars($_GET['day']);
}
else
{
        	if($_GET['period'] == "24h")
			{
				$start = strtotime("- 1 day");
				$tlabel = "24 hours";	
			}
			elseif($_GET['period'] == "week")
			{
				$start = strtotime("-1 week");
				$tlabel = "1 week";
			}
			elseif($_GET['period'] == "month")
			{
				$start = strtotime("-4 week");
				$tlabel = "1 month";
			}
			elseif($_GET['period'] == "tmonth")
			{
				$start = strtotime("-3 month");
				$tlabel = "3 months";
			}			
			else
			{
				$start = strtotime("- 1 year");
				$tlabel = "1 year";	
			}		
			$end = time();
}




$ytitle = $bucket->statname;

$setymax = false;
	

$res = $collection->find(array('timestamp' => array('$gt' => $start, '$lt' => $end)))->sort(array('timestamp' => 1));

$graphdata = "";
	
foreach($res as $avg)
{
	$time = $avg['timestamp'] * 1000;
	$graphdata.= '['.$time.','.number_format_global($avg['value']).'],';	
	//$graphdata.= '['.$time.','.$avg['value'].'],';	
	
}
$graphdata = substr($graphdata,0,-1);	


if($is_compare)
{
	$colname = $compare->statid;
	$collection = $dbm->$colname;
	$res = $collection->find(array('timestamp' => array('$gt' => $start, '$lt' => $end)))->sort(array('timestamp' => 1));
	
	$comparedata = "";
		
	foreach($res as $avg)
	{
		$time = $avg['timestamp'] * 1000;
		$comparedata.= '['.$time.','.number_format_global($avg['value']).'],';	
	}
	$comparedata = substr($comparedata,0,-1);			
	$compare_series.= '{ type: \'line\', dataGrouping: { enabled: true, smoothed: false }, name: \''.htmlspecialchars($compare->statname).'\', data: ['.$comparedata.'], tooltip: {valueSuffix: \' '.htmlspecialchars($db->real_escape_string($compare->statlabel)).'\' } }, ';	
}

?>

<script type="application/javascript">
$(document).ready(function() {
    
Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    <?php include("javascripts/charttheme.js"); ?>
        $(function () {
        $('#graph').highcharts({
            chart: {
                zoomType: 'x',
                spacingRight: 20,
                animation: false,
                shadow: false
            },
            credits: {
	            enabled: false
	        },	 
            title: {
                text: '<?php echo htmlspecialchars($bucket->statname)?> - <?php echo $tlabel?>',
                x: -20 //center
            },
			plotOptions: {
				line: {
					marker: {
			    		enabled: false
			    	},
			      	lineWidth: 0.8
			   },
			   series: {
			   	turboThreshold: 0
			   },
          	   area: {
                    lineWidth: 0.8,
                    marker: {
                        enabled: false
                    },
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }			   
			},  
			<?php if(!$is_compare) { ?>
			tooltip: {
			    formatter: function() {
					return Highcharts.dateFormat('%A %d.%m.%y %H:%M:%S', this.x) + '<br/><b>'+this.series.name+'</b>: ' + Highcharts.numberFormat(this.y); 	
			     }			    
			},	
			<?php } else { ?>	
			tooltip: {
			    formatter: function() {
					return Highcharts.dateFormat('%A %d.%m.%y %H:%M:%S', this.x) + '<br/><b>'+this.series.name+'</b>: ' + Highcharts.numberFormat(this.y); 	
			     }			    
			},					
			<?php } ?>             
            xAxis: {
            	type: 'datetime',
            	tickPixelInterval: 100,
            	ordinal: false,
                labels: {
                	format: '{value:%d.%m.%y %H:%M} ',
                    align: 'right',
                    rotation: -70
                },
   				events:{
                	setExtremes: function(e) {
                    	var xMin = e.min;
                        var xMax = e.max; 
                        var from = Math.round(xMin/ 1000);
                        var to = Math.round(xMax/ 1000);
                        console.log("from: " + from + " to: " + to);
                        //reRenderTables(from,to);
                    }
                }                
            },
            yAxis: {
            	<?php if(	$setymax ) { ?>
            	max: <?php echo $ymax?>,
            	<?php } ?>
            	min: 0,
                title: {
                    text: '<?php echo $ytitle?>'
                },
            },
            series: [
                    {
                        type: '<?php if($is_compare) { echo 'line'; } else { echo 'area';}?>',
                        name: ' <?php echo htmlspecialchars($db->real_escape_string($bucket->statlabel))?>',
		                dataGrouping: {
		                    enabled: true,
		                    smoothed: false
		                },   
	                    fillColor: {
	                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
	                        stops: [
	                            [0, Highcharts.getOptions().colors[0]],
	                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
	                        ]
	                    },		                                    
                        data: [<?php echo $graphdata?>],
                        tooltip: {
                        	valueSuffix: ' <?php echo htmlspecialchars($db->real_escape_string($bucket->statlabel))?>'
                        }
                    },
                    <?php if($is_compare) { echo $compare_series; } ?>                         
            ]
        });
    });

})
    


function getReadableFileSizeStringFromBits(fileSizeInBytes) {
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