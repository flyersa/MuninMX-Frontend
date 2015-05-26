<?php
include("inc/settings.php");
if($_GET["graph"] && is_numeric($_GET["value"]) && !$_POST)
{
	$graph = $_GET['graph'];
	$value = $_GET['value'];
	
	

	if(is_numeric($_GET['timestamp']))
	{
		$timestamp = $_GET['timestamp'];
	}
	else 
	{
		$timestamp = time();
	}
	
	$m = new MongoClient( "mongodb://".MONGO_HOST );
	$dbm = $m->buckets;
	$col = $dbm->$graph;
	$document['value'] = $value;
	$document['timestamp'] = $timestamp;
	$r = $col->insert($document);
	if($r['err'] == null)
	{
		$ret['status'] = 200;
		$ret['msg'] = "stored";
	}
	else
	{
		$ret['status'] = 400;
		$ret['msg'] = "unable to store value";		
	}
	echo json_encode($ret);
}
elseif($_POST)
{
	if(!isset($_POST['graph']))
	{
		$arr['status'] = 400;
		$arr['msg'] = "no field named graph received";
		echo json_encode($arr);	
		die;			
	}	
	if(!is_numeric($_POST['value']))
	{
		$arr['status'] = 400;
		$arr['msg'] = "field named value must be numeric";
		echo json_encode($arr);	
		die;		
	}
	$graph = $_POST['graph'];
	$value = $_POST['value'];
	if(is_numeric($_POST['timestamp']))
	{
		$timestamp = $_POST['timestamp'];
	}
	else
	{
		$timestamp = time();
	}
	
	$m = new MongoClient( "mongodb://".MONGO_HOST );
	$dbm = $m->buckets;
	$col = $dbm->$graph;
	$document['value'] = $value;
	$document['timestamp'] = $timestamp;
	$r = $col->insert($document);
	if($r['err'] == null)
	{
		$ret['status'] = 200;
		$ret['msg'] = "stored";
	}
	else
	{
		$ret['status'] = 400;
		$ret['msg'] = "unable to store value";		
	}
	echo json_encode($ret);
}
else
{
	$arr['status'] = 400;
	$arr['msg'] = "no value and graph parameter given";
	echo json_encode($arr);		
}

