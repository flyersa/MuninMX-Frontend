<?php
if(php_sapi_name() != "cli")
{
	echo "no cli..\n";
	die;
}
chdir("..");
include("inc/startup.php");
$m = new MongoClient(MONGO_HOST,array('socketTimeoutMS' => '-1'));
$dbm = $m->selectDB(MONGO_DB);

$list = $dbm->listCollections();
foreach ($list as $collection) {
	    
	$colname = substr($collection,8,strlen($collection));
	$db->query("SELECT * FROM index_log WHERE colname = '$colname'");
	if($db->affected_rows > 0)
	{
		echo "Metrics - skipping index for $colname - already indexed\n";
	}
	else	
	{
		echo "Metrics - adding index to $colname \n ";
		flush();
		ob_flush();
		$c = new MongoCollection($dbm, $colname);
		$c->ensureIndex(array("graph" => 1, "plugin" => 1, 'recv' => 1),array('clustering' => true, 'background' => true, 'timeout' => 900000));
		$c->ensureIndex(array("recv" => 1), array('background' => true, 'timeout' => 900000));
		$c->ensureIndex(array("recv" => 1,"customId" => 1), array('background' => true, 'timeout' => 900000));
		$db->query("INSERT INTO index_log (colname) VALUES ('$colname')");
	}
}

// SERVICE CHECKS
$dbm = $m->selectDB(MONGO_DB_CHECKS);

$list = $dbm->listCollections();
foreach ($list as $collection) {
	    
	$colname = substr($collection,strlen(MONGO_DB_CHECKS)+1,strlen($collection));
	$db->query("SELECT * FROM index_log WHERE colname = '$colname'");
	if($db->affected_rows > 0)
	{
		echo "Checks - skipping index for $colname - already indexed\n";
	}
	else	
	{
		echo "Checks - adding index to $colname \n ";
		flush();
		ob_flush();
		$c = new MongoCollection($dbm, $colname);
		$c->ensureIndex(array("cid" => 1, "time" => 1),array('clustering' => true, 'background' => true, 'timeout' => 900000));
		$c->ensureIndex(array("cid" => 1, "time" => 1, "status" => 1), array('background' => true, 'timeout' => 900000));
		$db->query("INSERT INTO index_log (colname) VALUES ('$colname')");
	}
}

// add trackpkg index
$dbm = $m->selectDB(MONGO_DB_ESSENTIALS);
$c = new MongoCollection($dbm, "trackpkg");
$c->ensureIndex(array("package" => 1, "node" => 1),array('clustering' => true, 'background' => true, 'timeout' => 900000));
$c->ensureIndex(array("package" => 1, "node" => 1, "time" => 1),array('clustering' => true, 'background' => true, 'timeout' => 900000));
$c->ensureIndex(array("package" => 1),array('clustering' => true, 'background' => true, 'timeout' => 900000));
$c->ensureIndex(array("node" => 1, "time" => 1),array('background' => true, 'timeout' => 900000));

// add index to essential collections
$dbm = $m->selectDB(MONGO_DB_ESSENTIALS);

$list = $dbm->listCollections();
foreach ($list as $collection) {
    
	$colname = substr($collection,10,strlen($collection));
	if($colname == "trackpkg")
	{
		continue;
	}

	flush();
	ob_flush();
	$db->query("SELECT * FROM index_log WHERE colname = '$colname'");
	if($db->affected_rows > 0)
	{
		echo "skipping Essential index for $colname - already indexed\n";
	}
	else	
	{
		$c = new MongoCollection($dbm, $colname);
		echo "adding index to Essential collection: $colname \n ";
		$c->ensureIndex(array("time" => 1),array('background' => true, 'timeout' => 900000));
		$c->ensureIndex(array("time" => -1),array('background' => true, 'timeout' => 900000));
		$db->query("INSERT INTO index_log (colname) VALUES ('$colname')");
	}
}
