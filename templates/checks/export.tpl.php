							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-check" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"> 
								<header>
									<span class="widget-icon"> <i class="fa fa-download"></i> </span>
									<h2>Data Export: <?php echo htmlspecialchars($check->check_name)?> - <?php echo $check->check_desc?></h2>		
								</header>
			
				
								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body" id="exportbody">
										<div>
									   <table class="table table-normal">
									      <thead>
									      <tr>
									        <td>Stored Check Results</td>
									        <td>Stored Debug Traces</td>
									        <td>Storage Size</td>
									      </tr>
									      </thead>
									      <tbody>
									      	<tr>
									      		<td><?php echo $tpl->stats['count']?></td>
												<td><?php echo $tpl->statst['count']?></td>
												<td><?php echo bytesToSize($tpl->totalsize) ?></td>
									      	</tr>
									      </tbody>
									      </table>	
									      </div>	
									      
									      

		
			        <?php display_info("About Exports","You can export service check data <strong>once per hour and check</strong>. You receive a zip archive with two json files, one for the reports and one for the debug traces. You can run your own
			        metrics and magic tools on them. And best of all you can push the received data right into a mongoDB instance using mongoimport."); ?>
			  


									      	  <div align="center" style="margin-top: 25px; margin-bottom: 25px" id="genexport">
										  	 	 <button class="btn btn-xl btn-info" onClick="genExport()">Generate and Download Export</button>
										  	  </div>									
									</div>
								</div>
							</div>
							
<script type="application/javascript">
	function genExport()
	{
		$( "#genexport" ).html('<img src="img/loading.gif"> Exporting Data... please wait this can take a while');
		$( "#genexport" ).load( "ajax/checks/export.php?cid=<?php echo $check->id?>&token=<?php echo getToken()?>" );
	}
</script>
