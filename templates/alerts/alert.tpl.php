<div class="alert alert-<?php echo $tpl->alerttype?> fade in">
	<?php if($tpl->close) { ?>
	<button class="close" data-dismiss="alert">
	×
	</button>
	<?php } ?>
	<i class="fa-fw fa fa-<?php echo $tpl->icon?>"></i>
	<strong><?php echo $tpl->title?></strong> <?php echo $tpl->text?>
</div>
