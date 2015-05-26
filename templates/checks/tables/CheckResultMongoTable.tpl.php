<?php $ctype = $check->check_type; ?>
<?php if(checkGotErrors($check->id)) { $errortab = true; $tracetab = true; } ?>
<!--<btn class="btn btn-red" onClick="oTable.fnReloadAjax('ajax/checks/mongo_dtable_tracer.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>');">Test Me</btn>-->


							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-check" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"> 
								<!-- widget options:
									usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
									
									data-widget-colorbutton="false"	
									data-widget-editbutton="false"
									data-widget-togglebutton="false"
									data-widget-deletebutton="false"
									data-widget-fullscreenbutton="false"
									data-widget-custombutton="false"
									data-widget-collapsed="true" 
									data-widget-sortable="false"
									
								-->
								<header>
									<span class="widget-icon"> <i class="fa fa-eye"></i> </span>
									<h2><strong><?php echo htmlspecialchars($check->check_name)?></strong> <i><?php echo $check->check_desc?></i></h2>	
										
									<ul id="widget-tab-1" class="nav nav-tabs pull-right">
										    <li class="active"><a href="#records" data-toggle="tab">Check Records</a></li>
								          <?php if($errortab) { ?>
								          	<li><a href="#errors" data-toggle="tab">Errors Only</a></li>
								          <?php } ?>
								          <?php if(checkSupportsTrace($ctype) && $tracetab) { ?>
								          	<li><a href="#traces" data-toggle="tab">Debug Traces</a></li>
								           <?php } ?>
								           <?php if(checkGotDowntimeDurations($check->id)) { ?>
								           		<li><a href="#downtimes" data-toggle="tab">Verified Downtimes</a></li>
								           <?php } ?>
								           <?php if(checkGotNotifications($check->id)) { ?>
								           	<li><a href="#nots" data-toggle="tab">Notification Log</a></li>
								           <?php } ?>
									</ul>	
																			
			
								</header>
				
								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body no-padding" id="graph" style="min-height: 400px">
												<div class="widget-body-toolbar">
											
										</div>
						<div class="span12" style="margin-left: 0px">
								<div class="box">
								  <div class="box-header">
						     
								   </div>
								   
							  <div class="box-content">
						        <div class="tab-content">
						          <div class="tab-pane active" id="records">
									<table cellpadding="0" cellspacing="0" border="0" id="checkrtable" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
											<th width="50%">Output</th>
											<th width="30px">Return Code</th>
											<th>Check Time</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
									</div>
									<?php if($errortab) { ?>
									 <div class="tab-pane" id="errors">
										<table cellpadding="0" cellspacing="0" border="0" id="errortable" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
												<th width="70%">Output</th>
												<th width="30px">Return Code</th>
												<th>Check Time</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									 </div>				
									<?php } ?>
									<?php if(checkSupportsTrace($ctype) && $tracetab) { ?>
									 <div class="tab-pane" id="traces">
										<table cellpadding="0" cellspacing="0" border="0" id="tracertable" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>	
												<th>Check Time</th>										
												<th>Return Code</th>
												<th>Output</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									 </div>
									 <?php } ?>
									 <?php if(checkGotDowntimeDurations($check->id)) { ?>
									 <div class="tab-pane" id="downtimes">
										<table cellpadding="0" cellspacing="0" border="0" id="downtable" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
												<th>Duration</th>
												<th>Start</th>
												<th>End</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$result = $db->query("SELECT * FROM `downtimes_durations` WHERE check_id = $check->id ORDER BY id DESC");
													while($tpl = $result->fetch_object())
													{
														$dur = $tpl->up_at - $tpl->down_at;
														$dur = formatSeconds($dur);
														echo '
														<tr>
															<td>'.$dur.'</td>
															<td>'.getFormatedLocalTime($tpl->down_at).'</td>
															<td>'.getFormatedLocalTime($tpl->up_at).'</td>
														</tr>
														';
													}
												?>
											</tbody>
										</table>
									 </div>			  	
									 <?php } ?>									 
									 <?php if(checkGotNotifications($check->id)) { ?>
									 <div class="tab-pane" id="nots">
										<table cellpadding="0" cellspacing="0" border="0" id="notstable" class="table table-striped table-bordered table-hover">
											<thead>
												<tr>
												<th>Type</th>
												<th>Message</th>
												<th>Send at</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$rn = getNotificationResult($check->id);
													while($tn = $rn->fetch_object())
													{
														echo '
														<tr>
															<td>'.$tn->msg_type.'</td>
															<td>'.$tn->msg.'</td>
															<td>'.getFormatedLocalTime($tn->unixtime).'</td>
														</tr>
														';
													}
												?>
											</tbody>
										</table>
									 </div>			  	
									 <?php } ?>
								</div>
						
						</div></div></div>

</div>

								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->

<script type="application/javascript">

	function reRenderTables(from,to)
	{
		if(isNaN(from))
		{
			console.log("reRendering Tables to default because of zoom-out");
			oTable.fnReloadAjax('ajax/checks/mongo_dtable_checkr.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>');
		    <?php if(checkSupportsTrace($ctype) && $tracetab) { ?>
			otraceTable.fnReloadAjax('ajax/checks/mongo_dtable_tracer.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>');
			<?php } ?>
	    	<?php if($errortab) { ?>
			errorTable.fnReloadAjax('ajax/checks/mongo_dtable_checkerrors.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>');
			<?php } ?>			
		}
		else
		{
			console.log("reRendering Tables because of zoom");
			oTable.fnReloadAjax('ajax/checks/mongo_dtable_checkr.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>&from='+from+'&to='+to);
			<?php if(checkSupportsTrace($ctype) && $tracetab) { ?>
			otraceTable.fnReloadAjax('ajax/checks/mongo_dtable_tracer.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>&from='+from+'&to='+to);
			<?php } ?>
	    	<?php if($errortab) { ?>
			errorTable.fnReloadAjax('ajax/checks/mongo_dtable_checkerrors.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>&from='+from+'&to='+to);
			<?php } ?>				
		}
	}
	
	
	function reRenderCheckTable(from,to)
	{
		if(isNaN(from))
		{
			console.log("reRendering Tables to default because of zoom-out");
			oTable.fnReloadAjax('ajax/checks/mongo_dtable_checkr.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>');		
		}
		else
		{
			console.log("reRendering Tables because of zoom");
			oTable.fnReloadAjax('ajax/checks/mongo_dtable_checkr.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>&from='+from+'&to='+to);			
		}
	}	

	$(document).ready(function() {
	$.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource ) {
	    if ( typeof sNewSource != 'undefined' )
	    oSettings.sAjaxSource = sNewSource;
	     
	    this.fnClearTable( this );
	    this.oApi._fnProcessingDisplay( oSettings, true );
	    var that = this;
	     
	    $.getJSON( oSettings.sAjaxSource, null, function(json) {
	    /* Got the data - add it to the table */
	    for ( var i=0 ; i<json.aaData.length ; i++ ) {
	    that.oApi._fnAddData( oSettings, json.aaData[i] );
	    }
	     
	    oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
	    that.fnDraw( that );
	    that.oApi._fnProcessingDisplay( oSettings, false );
	    });
	}
		
   oTable = $('#checkrtable').dataTable( {
        "bProcessing": false,
        "bServerSide": true,
        "bJQueryUI": false,
        "bAutoWidth": false,
        "iDisplayLength": 25,
        "sAjaxSource": "ajax/checks/mongo_dtable_checkr.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>",
        "aoColumns": [
        	{"mDataProp":"hread"},
        	{"mDataProp":"status"},
        	{"mDataProp":"time"},
        ]
    } );
    oTable.fnSort( [ [2,'desc'] ] );
    
 <?php if(checkSupportsTrace($ctype) && $tracetab) { ?>
 otraceTable = $('#tracertable').dataTable( {
        "bProcessing": false,
        "bServerSide": true,
        "bJQueryUI": false,
        "bAutoWidth": false,
        "iDisplayLength": 10,
        "sPaginationType": "bootstrap_full",
        "sAjaxSource": "ajax/checks/mongo_dtable_tracer.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>",
        "aoColumns": [
       		{"mDataProp":"time"},
       		{"mDataProp":"status"},
        	{"mDataProp":"output"},
        ]
    } );
    otraceTable.fnSort( [ [0,'asc'] ] );
    <?php } ?>
 <?php if($errortab) { ?>
 errorTable = $('#errortable').dataTable( {
        "bProcessing": false,
        "bServerSide": true,
        "bJQueryUI": false,
        "bAutoWidth": false,
        "iDisplayLength": 25,
        "sPaginationType": "bootstrap_full",
        "sAjaxSource": "ajax/checks/mongo_dtable_checkerrors.php?token=<?php echo getToken()?>&cid=<?php echo $check->id?>",
        "aoColumns": [
        	{"mDataProp":"hread"},
        	{"mDataProp":"status"},
        	{"mDataProp":"time"},
        ]
    } );
    errorTable.fnSort( [ [2,'desc'] ] );
    <?php } ?>    
    
} );

 	<?php if(checkGotNotifications($check->id)) { ?>
				// nots
				var notTable = $('#notstable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});		
				notTable.fnSort( [ [2,'desc'] ] );
	<?php } ?>
	
	 <?php if(checkGotDowntimeDurations($check->id)) { ?>
				// nots
				var downTable = $('#downtable').dataTable({
					"sPaginationType" : "bootstrap_full",
					"iDisplayLength" : 25 
				});	
				
				
				downTable.fnSort( [ [2,'desc'] ] );
	<?php } ?>
    $.fn.dataTableExt.sErrMode = 'throw';
</script>
