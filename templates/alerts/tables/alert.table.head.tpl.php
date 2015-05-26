<!-- row -->
<?php
if($node != false && $plugin != false)
{
	$tpl->title = "Your Metric Alert Notifications for this Host and Plugin";
}
else
{
	$tpl->title = "Your Metric Alert Notifications";
}
?>
				<div class="row">
						<!-- NEW WIDGET START -->
						<article class="col-sm-12">
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-x" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					
								<header>
									<span class="widget-icon"> <i class="fa fa-bell"></i> </span>
									<h2><?php echo $tpl->title?></h2>
								</header>
								<!-- widget div-->
								<div>
				
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
				
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body no-padding">
										<div class="widget-body-toolbar">
										</div>
<table id="alertTable" class="table table-striped table-bordered table-hover">
<thead>
	<tr>
		<th>Hostname</th>
		<th>Plugin</th>
		<th>Graph</th>
		<th>Condition</th>	
		<th>Alert Value</th>	
		<th>Contacts</th>	
		<th>Action</th>			
	</tr>
</thead>
<tbody>
