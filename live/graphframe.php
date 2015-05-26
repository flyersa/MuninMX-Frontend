<?php
chdir("..");
include("inc/startup.php");
if(!isLoggedIn())
{
	header("Location: login.php");
	die;
}
if(!accessToNode($_GET['node']))
{
        display_error("Access denied for this host");
        die;
}
if(!$_GET['plugin'])
{
	die;
}

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

$node = getNode($_GET['node']);
$host = $node->hostname;
$plugin = $_GET['plugin'];
$plugindesc = $_GET['plugindesc'];
$plugintext = getPluginText($node->id,$plugin);
?>
<html>
        <head>
                <title>Munin Livegraphing by MLD</title>
				<script src="/js/libs/jquery-2.0.2.min.js"></script>
       			<script src="/js/highstock.js" type="text/javascript"></script>
        </head>
        <body>

                <div id="container" style="width: 100%; height: 100%"></div>
<?php
$json  = json_decode(file_get_contents("http://".MLD_HOST.":".MLD_PORT."/node/$node->id/fetch/$plugin"));
$jsonc = json_decode(file_get_contents("http://".MLD_HOST.":".MLD_PORT."/config/query.sleep"));
$sleeptime = $jsonc[0];
if($sleeptime < 1)
{
        $sleeptime = 1000;
}

$vlabel = getVlabelForPlugin($node->id,$plugin);
?>
<script>
var chart; // global
$(document).ready(function() {
	Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
	<?php include("js/charttheme.js"); ?>
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            defaultSeriesType: '<?php echo $stype?>',
                        zoomType: 'x',
            events: {
            load: function() {
                      setInterval(function() {  
                        $.getJSON("/live/jsonpipe.php?node=<?php echo $node->id?>&plugin=<?php echo $plugin?>", function(data){
                                json = eval(data);
                                        var x = Math.round((new Date()).getTime() / 1000) * 1000;
                                        y = json[0].bd_GraphValue;
                                shift = chart.series[0].data.length > 20; 
                                chart.series[0].addPoint([x, y], true, shift);
                                        <?php
                                                $i = 0;
                                                foreach ($json as $g)
                                                {
                                                        if($i > 0)
                                                        {
                                                                $var = "b" . uniqid();
																if($g->b_isNegative == true)
																{
                                                                echo "var $var = 0 - json[$i].bd_GraphValue
";																	
																}
																else
																{
                                                                echo "var $var = json[$i].bd_GraphValue
";
																}
                                                                echo "chart.series[$i].addPoint([x, $var], true, shift);
";
                                                        }
                                                        $i++;
                                                }
                                        ?>      
                        chart.redraw();
                        })
                      }, <?php echo $sleeptime?>);
                    }

                }
        },
        title: {
            text: '<?php echo $node->hostname . " - " . $plugintext . " - LIVE"?>'
        },
		plotOptions: {
			line: {
				marker: {
		    		enabled: false
		    	},
		      	lineWidth: 1
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
            tickPixelInterval: 150,
            /*maxZoom: 20 * 1000,*/
            text: ''
        },
        credits: {
                        enabled: false
                },
		tooltip: {
			shared: false,
            crosshairs: true,          
			formatter: function() {
				<?php if($plugin == "fusionio_io") { ?>
					return Highcharts.dateFormat('%d.%m.%y %H:%M', this.x) + '<br/><b>'+this.series.name+'</b>: ' + getReadableFileSizeString(this.y); 
				<?php } else if ($vlabel == "bits in (-) / out (+) per second") { ?>
					return Highcharts.dateFormat('%d.%m.%y %H:%M', this.x) + '<br/><b>'+this.series.name+'</b>: ' + getReadableFileSizeStringFromBits(this.y); 	
				<?php } else if ($vlabel == "Bytes/second") { ?>
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
            title: {
                text: '<?php echo $vlabel?>',
                margin: 80
            }
        },
        series: [
        <?php
                foreach ($json as $g)
                {
                		$visible = returnGraphVisibility($plugin,$g->str_GraphName);
						if($g->str_GraphLabel == "bps" && $vlabel == "bits in (-) / out (+) per second")
						{
							$g->str_GraphLabel= "sent";
						}
                        echo "{ name: '".$g->str_GraphLabel."', $visible data: []},";
                }
        ?>
        ],
    });        
});

function getReadableFileSizeStringFromBits(fileSizeInBytes) {
    //var fileSizeInBytes = fileSizeInBytes / 8;
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