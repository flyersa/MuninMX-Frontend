<?php
ini_set('max_execution_time', 300);
chdir("../..");
include("inc/startup.php");
if(!isLoggedIn())
{
	header('HTTP/1.0 401 Unauthorized');
	die;
}
if(!is_numeric($_GET['cid']))
{
	die;
}
checkToken();

$check = returnServiceCheck($_GET['cid']);

if(!accessToCheck($check->id))
{
	header('HTTP/1.0 401 Unauthorized');
	die;	
}

$reports = $check->user_id."cid".$check->id;
$traces = $check->user_id."traces".$check->id;

// check if we are stil running

if(is_file(EXPORTDIR.'/'.$check->id."_reports.json"))
{
	$ctime = filemtime(EXPORTDIR.'/'.$check->id."_reports.json");
	if($ctime == false || $ctime < strtotime ("-1 hour"))
	{
		unlink(EXPORTDIR.'/'.$check->id."_reports.json");
		unlink(EXPORTDIR.'/'.$check->id."_traces.json");
	}
	else
	{
		display_error("Export stil running","There is stil a export running. Please try again later");
		die;
	}
}
else
{
	$ctime = filemtime(EXPORTDIR.'/'.$check->id.".zip");
	if($ctime == false || $ctime < strtotime ("-1 hour"))
	{
		unlink(EXPORTDIR.'/'.$check->id.".zip");
		// create new export
		$command = EXPORT_BIN." -h " . MONGO_HOST . " -d ".MONGO_DB_CHECKS." -c " . $reports . " --jsonArray -o ". EXPORTDIR.'/'.$check->id."_reports.json";
		exec ( $command, $out, $ret );
		if($ret != 0)
		{
			unlink(EXPORTDIR.'/'.$check->id."_reports.json");	
			display_error("Export Error", " Please try again later");
			die;
		}
		else {
			$command = EXPORT_BIN." -h " . MONGO_HOST . " -d ".MONGO_DB_CHECKS." -c " . $traces . " --jsonArray -o ". EXPORTDIR.'/'.$check->id."_traces.json";
			exec ( $command, $out, $ret );	
			if($ret != 0)
			{
				unlink(EXPORTDIR.'/'.$check->id."_reports.json");	
				unlink(EXPORTDIR.'/'.$check->id."_traces.json");	
				display_error("Export Error", " Please try again later");
				die;				
			}
			// all ok until here, now zip
			$command = "/usr/bin/zip -j ".EXPORTDIR.'/'.$check->id.".zip ".EXPORTDIR.'/'.$check->id."_reports.json ".EXPORTDIR.'/'.$check->id."_traces.json";
			exec ( $command, $out, $ret );
			if($ret != 0)
			{
				unlink(EXPORTDIR.'/'.$check->id."_reports.json");	
				unlink(EXPORTDIR.'/'.$check->id."_traces.json");	
				unlink(EXPORTDIR.'/'.$check->id.".zip");
				display_error("Export Error", " Please try again later");
				die;							
			}
			else
			{
				unlink(EXPORTDIR.'/'.$check->id."_reports.json");	
				unlink(EXPORTDIR.'/'.$check->id."_traces.json");					
				echo '
					<a href="checks.php?action=export&cid='.$check->id.'&download=true&token='.getToken().'" class="btn btn-xl btn-success">Download '.bytesToSize(filesize(EXPORTDIR.'/'.$check->id.".zip")).'</a>
				';	
			}	
		}
	}
	else
	{
		echo '
			<a href="checks.php?a=export&cid='.$check->id.'&download=true&token='.getToken().'" class="btn btn-xl btn-success">Download '.bytesToSize(filesize(EXPORTDIR.'/'.$check->id.".zip")).'</a>
		';	
	}
}
//sleep(10);
?>

