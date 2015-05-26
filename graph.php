<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}


if($_GET['nodata'])
{
	echo '<div align="center" style="margin-top: 100px"><img src="img/nodata.jpg" align="center"></div>';
	die;
}

if(!is_numeric($_GET['node']) && !$_GET['plugin'])
{
	die("host and plugin parameter missing");
}

// check if user got permission to this node
$node = getNode($_GET['node']);

if(!accessToNode($_GET['node']))
{
	die("access denied");	
}

if(!$node)
{
	die("unknown node");
}

if(!$_GET['stype'])
{
	// set chart type
	if(!isset($_SESSION['stype']))
	{
		$stype = "area";
	}
	else
	{
		$stype = $_SESSION['stype'];
		if($stype == "areastack")
		{
			$stype = "area";
			$stacking = true;
		}
		else
		{
			$stacking = false;
		}
	}
	// overwrite
	$lmode = getPluginLinemode($_GET['node'],$_GET['plugin']);
	if($lmode != "default")
	{
		switch($lmode)
		{
			case "area":
				$stype = "area";
				break;
			case "line":
				$stype = "line";
				break;
			case "column":
				$stype = "column";
				break;
			case "areastack":
				$stype = "area";
				$stacking = true;
			default:
				$stype = "area";			
		}
	}
}
else
{
	$stacking = false;
	switch($_GET['stype'])
	{
		case "area":
			$stype = "area";
			break;
		case "line":
			$stype = "line";
			break;
		case "column":
			$stype = "column";
			break;
		case "areastack":
			$stype = "area";
			$stacking = true;
		default:
			$stype = "area";
	}
}

$host = $_GET['node'];
$plugin = $_GET['plugin'];


if(!$_GET['dateformat'])
{
	$dff = '{value:%d.%m %H:%M:%S}';	
	$dfr = -70;
}
elseif($_GET['dateformat'] == "short")
{
	if(endsWith($_GET['period'], "hour") || endsWith($_GET['period'], "min"))
	{
		$dff = '{value:%H:%M}';		
	}
	else
	{
		$dff = '{value:%d.%m %H:%M}';		
	}
	
	$dfr = -30;		
}

$notext = false;
if($_GET['notitle'])
{
	$notext = true;
}

if(!dataAvailable($host,$plugin))
{
	echo '<div align="center" style="margin-top: 100px"><img src="img/nodata.jpg" align="center"></div>';
	die;
}


?>
<html>
    <head>
        <title>Munin Graph</title>
		<script src="js/libs/jquery-2.0.2.min.js"></script>
        <script src="js/highstock.js" type="text/javascript"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/modules/exporting.js"></script>
        <?php if(is_numeric($_GET['refresh'])) { ?>
        	<meta http-equiv="refresh" content="<?php echo $_GET['refresh']*60?>; URL=<?php echo getCurUrl()?>">
       	<?php } ?>
    </head>
    <body>
    
        <div id="container" style="width: 100%; height: 100%"></div>
        
<?php

$plugintext = htmlspecialchars(getPluginText($host,$plugin));

$json  = json_decode(file_get_contents("http://".MCD_HOST.":".MCD_PORT."/node/$host/fetch/$plugin"));

$gapsize = 450;
if($_GET['period'])
{
	if($_GET['period'] == "week")
	{
		$gapsize = 10;	
		$ptext = " last week";
	}

	if($_GET['period'] == "month")
	{
		$gapsize = 10;	
		$shownav = false;
		$ptext = " last month";
	}	
	
	if($_GET['period'] == "1hour")
	{
		$ptext = " last hour";
	}

//

	
	if($_GET['period'] == "tmonth")
	{
		$ptext = " last 3 months";	
		$gapsize = 10;
		$shownav = true;
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
		$shownav = false;
	}
}

if(!$_GET['period'])
{
	$gapsize = 10;
	$shownav = true;
}



$vlabel = strip_tags(getVlabelForPlugin($host,$plugin)); 
?>
<script>
var lstamp = 0;
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
            events: {
                click: function(event) {
                	
                	stamp = event.xAxis[0].value / 1000;               	
                	<?php if(EssentialDataAvailable($node->id)) { ?>
                	$('#snapBtn<?php echo $plugin?>', window.parent.document).show();
                	$('#snapBtn<?php echo $plugin?>', window.parent.document).unbind('click').click(function() {
					  window.open('essframe.php?node=<?php echo $node->id?>&match='+stamp,null,"height=700,width=1124,status=no,toolbar=no,menubar=no,location=no");
					});
					<?php } ?>
					lstamp = stamp;
                    var jsDate = new Date(event.xAxis[0].value);
                    var localeSpecificTime = jsDate.toLocaleTimeString();
   					var time = localeSpecificTime.replace(/:\d+ /, ' ');

                    	$('#sdataLnk<?php echo $plugin?>', window.parent.document).html('(~ '+time+')');
                    	$('#sdataBtn<?php echo $plugin?>', window.parent.document).show();
						$('#sdataBtn<?php echo $plugin?>', window.parent.document).unbind('click').click(function() {
						  window.open('singledata.php?node=<?php echo $node->id?>&plugin=<?php echo htmlspecialchars($plugin)?>&match='+lstamp,null,"height=700,width=1124,status=no,toolbar=no,menubar=no,location=no");
						  console.log ('btn click action for sdataBtn');
						});     					
   					
                    console.log("time: " + time + " from input: " + lstamp);
                    <?php if(EssentialDataAvailable($node->id)) { ?>
                    $('#snapLnk<?php echo $plugin?>', window.parent.document).html('(~ '+time+')');
	                $('#snapBtn<?php echo $plugin?>', window.parent.document).show();
	                $('#snapBtn<?php echo $plugin?>', window.parent.document).unbind('click').click(function() {
					  window.open('essframe.php?node=<?php echo $node->id?>&match='+stamp,null,"height=700,width=1124,status=no,toolbar=no,menubar=no,location=no");
					  console.log ('btn click action for snapbtn');
					});   	
					<?php } ?>				
                    if(clickDetected) {
                        console.log ('time: '+ event.xAxis[0].value);
                        stamp = event.xAxis[0].value / 1000;
                        //window.parent.$("#essentialModal").toggle();
                        //$("#frameEssential", parent.window.document).attr('src','essframe.php?node=<?php echo $node->id?>&match='+stamp);
                        //$("#essentialModal", parent.window.document).modal('toggle');
                        <?php if(EssentialDataAvailable($node->id) && DCLICK_ACTION == "snapshot") { ?>
                        window.open('essframe.php?node=<?php echo $node->id?>&match='+stamp,null,"height=700,width=1124,status=no,toolbar=no,menubar=no,location=no");
                        <?php } ?>
                        clickDetected = false;
                    } else {
                        clickDetected = true;
                        setTimeout(function() {
                            clickDetected = false;
                        }, 500);
                    }
                }
            },         	
            renderTo: 'container',
            defaultSeriesType: '<?php echo $stype?>',
            zoomType: 'x',
            animation: false,
            shadow: false            
        },
         <?php if($shownav) { ?>
		    navigator: {
				enabled: true,
				adaptToUpdatedData: false
			}, 
		    rangeSelector: {
	            enabled: true,
		    	selected: 0,
				buttons: [{
					type: 'all',
					text: 'All'
				}]		    	
		    },			
			<?php } ?>         
        title: {
        	<?php if(!$notext) { ?>
            text: '<?php echo $node->hostname . " (".htmlspecialchars($node->groupname).") - " . htmlspecialchars($plugintext) . $ttext?>'
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
		series: {
			turboThreshold: 0,
			connectNulls: true,
			gapSize: <?php echo $gapsize?>,
			point: {
            	events: {
                	click: function () {
                    	console.log('single click value: ' + this.x / 1000);
                    	var stamp = this.x / 1000;
                    	lstamp = stamp;
                    	var jsDate = new Date(this.x);
                    	var localeSpecificTime = jsDate.toLocaleTimeString();
   						var time = localeSpecificTime.replace(/:\d+ /, ' ');
   						
                    	console.log(time);
                    	$('#sdataLnk<?php echo $plugin?>', window.parent.document).html(' (~ '+time+')');
                    	$('#sdataBtn<?php echo $plugin?>', window.parent.document).show();
						$('#sdataBtn<?php echo $plugin?>', window.parent.document).click(function() {
						  window.open('singledata.php?node=<?php echo $node->id?>&plugin=<?php echo htmlspecialchars($plugin)?>&match='+lstamp,null,"height=700,width=1124,status=no,toolbar=no,menubar=no,location=no");
						});  
						                    	
                    	<?php if(EssentialDataAvailable($node->id)) { ?>
                    	$('#snapLnk<?php echo $plugin?>', window.parent.document).html('(~ '+time+')');
	                	$('#snapBtn<?php echo $plugin?>', window.parent.document).show();
	                	$('#snapBtn<?php echo $plugin?>', window.parent.document).click(function() {
						  window.open('essframe.php?node=<?php echo $node->id?>&match='+lstamp,null,"height=700,width=1124,status=no,toolbar=no,menubar=no,location=no");
						});  
						<?php } ?>                  	
						if(clickDetected) {
	                        console.log ('time: '+ this.x / 1000);
	                        stamp = this.x / 1000;
	                        //window.parent.$("#essentialModal").toggle();
	                        //$("#frameEssential", parent.window.document).attr('src','essframe.php?node=<?php echo $node->id?>&match='+stamp);
	                        //$("#essentialModal", parent.window.document).modal('toggle');
	                        <?php if(EssentialDataAvailable($node->id)) { ?>
	                        window.open('essframe.php?node=<?php echo $node->id?>&match='+lstamp,null,"height=700,width=1124,status=no,toolbar=no,menubar=no,location=no");
	                        <?php } ?>
	                        clickDetected = false;
	                    } else {
	                        clickDetected = true;
	                        setTimeout(function() {
	                            clickDetected = false;
	                        }, 500);
	                    }                    	
                    }
                }
           }
		},		
       	area: {
       		<?php if($stacking) { ?>stacking: 'normal',<?php } ?>
        	lineWidth: 0.8,
            shadow: false,
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
                format: '<?php echo $dff?>',
                align: 'right',
                rotation: <?php echo $dfr?>
            } 
            <?php renderEvents($node);?>           
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
    	$fresults = 0;
        foreach ($json as $g)
        {
        	$period = false;
        	if($_GET['period'] == "24h")
			{
				$period = "- 1 day";	
			}
			elseif($_GET['period'] == "1hour")
			{
				$period = "- 1 hour";	
			}	
			elseif($_GET['period'] == "2hour")
			{
				$period = "- 2 hour";	
			}	
			elseif($_GET['period'] == "4hour")
			{
				$period = "- 4 hour";	
			}	
			elseif($_GET['period'] == "24hour")
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
			elseif($_GET['period'] == "30min")
			{
				$period = "- 30 minutes";
			}		
			else
			{
				$period = "- 1 year";	
			}	
			
					
			if(!$_GET['start'] && !$_GET['end'] && !$_GET['day'])
			{
        		$q = getChartDataArray($host,$plugin,$g->str_GraphName,$period);
			}
			elseif($_GET['day'])
			{
				$q = getChartDataArray($host,$plugin,$g->str_GraphName,true,$start,$end);	
			}
			else
			{
				$q = getChartDataArray($host,$plugin,$g->str_GraphName,true,$_GET['start'],$_GET['end']);	
			}
			
			// switch to avg?
			if(sizeof($q) > SWITCH_TO_AVG_PEAK)
			{
				$q = returnAverageValue($q);
			}
			
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
				$val  = $item['value'];
				// negative?
				if($g->b_isNegative == true)
				{
					$val = 0 - $val;
				}
				$data.= '['.$time.','.$val.'],';	
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
            echo "{ dataGrouping: { enabled: true, smoothed: false }, $visible name: '".htmlspecialchars($g->str_GraphName)."', data: [ ".$data."]},";
        }
		if($fresults == 0)
		{
			header("Location: graph.php?nodata=true");
			die;
		}
    ?>
    ],
    });        
});

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
