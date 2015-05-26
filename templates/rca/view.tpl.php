				<!-- row -->
				<div class="row">
					<!-- NEW WIDGET START -->
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-search"></i> </span>
									<h2>Analysis Result</h2>

									
								</header>
								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body">
										 

<?php
foreach($tpl->json->results as $result)
{
	$ps[$result->nodeId][$result->pluginName][] = $result;
	//echo $result->nodeId;										
}
?>

<div class="panel-group smart-accordion-default" id="accordion">

<?php
$i = 0;										
foreach ($ps as $key => $value)
{
	$node = getNode($key);
	if(!$node)
	{
		continue;
	}
?>										
										
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $node->id?>"> <i class="fa fa-sitemap"></i> <i class="fa fa-sitemap"></i> [<?php echo htmlspecialchars($node->groupname)?>] <?php echo htmlspecialchars($node->hostname)?></a></h4>
												</div>
												<div id="<?php echo $node->id?>" class="panel-collapse collapse<?php if($i == 0) {echo ' in';}?>">
													<div class="panel-body padding"> 
												
													<?php foreach($value as $skey => $svalue) { ?>
												
														<h3><?php echo htmlspecialchars($skey)?></h3>
														
        												<a href="javascript:void(0);" onClick="$('#g<?php echo $node->id?><?php echo $skey?>').toggle()" class="btn btn-xs  btn-default" style="margin-bottom: 2px"><i class="fa fa-expand-o"></i> toggle graph</a>
        												<span id="g<?php echo $node->id?><?php echo $skey?>" style="display: none">
        													<?php renderSingleGraphByPluginNameForRCA($node->id,$skey,$tpl->start_time,$tpl->end_time)?>
        												</span>
														<table class="table table-bordered">
															<tr>
																<thead>
															<th style="width: 25%">Graph</th>
															<th style="width: 25%">Input Average</th>
															<th style="width: 25%">Average (last <?php echo $tpl->querydays?> days)</th>
															<th style="width: 25%">Deviation %</th>
															</thead>
															</tr>
															<?php 
															foreach($svalue as $tval) {
																if($tval->inputAverage < $tval->daysAverage) 
																{
																	$perc = "- " . $tval->percentage . ' %';	
																}	
																else
																{
																	$perc = $tval->percentage . ' %';		
																}
															?>
															<tr>
																<td><?php echo htmlspecialchars($tval->graphName)?></td>
																<td><?php echo htmlspecialchars($tval->inputAverage)?></td>
																<td><?php echo htmlspecialchars($tval->daysAverage)?></td>
																<td><?php echo $perc; ?></td>
															</tr>		
																	
															<?php }?>
														</table>	
														<hr>
													<?php } ?>
												
													</div>
												</div>
											</div>

										
<?php 
$i++;
}
?>
	

</div>

</div>
							</div>
					</article>
				</div>
				<!-- end row -->												
