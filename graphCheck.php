<?php
include("inc/startup.php");
if(!isLoggedIn())
{
	header('HTTP/1.0 401 Unauthorized');
	die;
}
//checkToken();

if(!is_numeric($_GET['cid']))
{
	die;
}
else
{
	$check = returnServiceCheck($_GET['cid']);	
	if($check)
	{
		if(!accessToCheck($check->id))
		{
			display_error("Access Denied","You have no access to this service check");
			die;
		}
	}
	else
	{
		display_error("Not Found","check not found");
	}
}

global $m; 
$dbname = MONGO_DB_CHECKS;
$dbm = $m->$dbname;
$cid = $_GET['cid'];
$user_id = $check->user_id;
$colname = $user_id."cid".$cid;
$collection = $dbm->$colname;

// check if we have data at all
$res = $collection->find(array('cid' => new MongoInt32($cid)))->limit(1);


if($res->count() < 1)
{
	echo '<br />';
	display_info("Propagation in Progress", "There is no data available yet for this check. Please check back in a few minutes");
	die;
}

if(!is_numeric($_GET['timeframe']))
{
	$start = time() - 86400;
	$tlabel = "24 hours";
}
else
{
	$start = time() - $_GET['timeframe'];
	$tlabel = formatSeconds($_GET['timeframe']);
}
$end = time() - 60;


if($_GET['day'])
{
	$_GET['day'] = str_replace("/",".",$_GET['day']);
	$start = strtotime($_GET['day']);
	$end = strtotime('+1 day', $start);
	$tlabel = htmlspecialchars($_GET['day']);
}



$ytitle = "Response Time";	
$hnum = 3;
$ymax = 20;
if($check->check_type == 1)
{
	$label = "rta";
	$labelh = "ms";
	$cut   = -3;
	$ymax = 10000;
	$setymax = false;
}
elseif($check->check_type == 5)
{
	$ytitle = "Days to expire";
	$label = "days";
	$labelh = " days";
	$cut   = -3;
	$ymax = 10000;
	$setymax = false;	
}
else
{
	$label = "time";
	$labelh = "s";
	$cut   = -4;
	$ymax = 12;
	$setymax = false;
}	
	

	$res = $collection->find(array('cid' => new MongoInt32($cid), 'time' => array('$gt' => new MongoInt32($start), '$lt' => new MongoInt32($end))))->sort(array('time' => 1));
	
	$res = returnAverageMedianFrom($res,$label,$check->cinterval);
	
	$graphdata = "";
	
	foreach($res as $avg)
	{
		$time = $avg['time'] * 1000;

		if($avg['avg'] != -10)
		{
			$graphdata.= '['.$time.','.number_format_global($avg['avg']).'],';
		}

		
	}
	$graphdata = substr($graphdata,0,-1);	
	$multigraph = false;

?>

<script type="application/javascript">
$(document).ready(function() {
    
    
Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    <?php include("js/charttheme.js"); ?>
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
                text: '<?php echo htmlspecialchars($check->check_name)?> <?php echo $check->check_desc?>',
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
			exporting: {
            	enabled: true,
  				sourceWidth: 1280,
            	sourceHeight: 400,
            	filename: '<?php echo htmlspecialchars($check->check_name)."_".$check->check_desc?>'
       		},			
			tooltip: {
			    formatter: function() {
			    	if(this.point.customTooltip == 1)
			    	{
			    		return Highcharts.dateFormat('%d.%m.%y %H:%M', this.x) + '<br/> DOWNTIME REPORTED';
			        }
			        else
			       	{
			       		if(this.series.type == "flags")
			       		{
			       			return Highcharts.dateFormat('%d.%m.%y %H:%M', this.x) + '<br/> DOWNTIME REPORTED';
			       		}
			       		else
			       		{
			       			return Highcharts.dateFormat('%d.%m.%y %H:%M', this.x) + '<br/><b>'+this.series.name+'</b>: ' + Highcharts.numberFormat(this.y, <?php echo $hnum?>) + '<?php echo $labelh?>'; 
			       		}
			       	}
			    }
			},		             
            subtitle: {
                text: 'Results from: <?php echo $tlabel?>',
                x: -20
            },
            xAxis: {
            	type: 'datetime',
            	tickPixelInterval: 100,
            	ordinal: false,
                labels: {
                	format: '{value:%d.%m.%y %H:%M} ',
                    align: 'right',
                    rotation: -70
                },
                <?php renderDowntimes($check->id); ?>
   				events:{
                	setExtremes: function(e) {
                    	var xMin = e.min;
                        var xMax = e.max; 
                        var from = Math.round(xMin/ 1000);
                        var to = Math.round(xMax/ 1000);
                        console.log("from: " + from + " to: " + to);
                        reRenderTables(from,to);
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
                }
            },
            series: [
            		<?php if(!$multigraph) { ?>
                    {
                        type: 'area',
                        name: '<?php echo $ytitle?>',
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
                        	valueSuffix: '<?php echo $labelh?>'
                        }
                    },
                    <?php } else { echo $multigraph_series; }?>                                                  
            ]
        });
    });

})
    
console.log("start: <?php echo $start?> end: <?php echo $end?> ");

reRenderCheckTable(<?php echo $start ?>,<?php echo $end?>);

</script>