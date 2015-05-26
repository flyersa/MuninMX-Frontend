<?php
if(php_sapi_name() != "cli")
{
	die;
}
chdir("..");
include("inc/startup.php");
$m = new MongoClient(MONGO_HOST);
$db = $m->selectDB(MONGO_DB);

$old = 1399295802;

$list = $db->listCollections();
foreach ($list as $collection) {

	$colname = substr($collection,8,strlen($collection));
    echo "dropping before $old : $colname.. \n ";
	$c = new MongoCollection($db, $colname);
	$c->remove(array('recv' => array('$lt' => $old)));
	//$c->ensureIndex(array('recv' => 1, 'recv' => -1));
	
}

