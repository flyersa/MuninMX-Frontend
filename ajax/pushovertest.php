<?php
chdir("..");
include("inc/startup.php");
if(!isLoggedIn())
{
	header('HTTP/1.0 401 Unauthorized');
	die;
}
checkToken();

$pkey = $_GET['userKey'];

if(trim($pkey) != "")
{
	//open connection
	$ch = curl_init();
	$title = urlencode("Test Message");
	$msg = urlencode("This is test message from MuninMX via pushover");
	$url = MCD_HOST.":".MCD_PORT."/pushovertest/$pkey/$title/$msg";
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt( $ch, CURLOPT_ENCODING, "UTF-8" );  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
	//execute post
	$result = curl_exec($ch);
	
	//close connection
	curl_close($ch);
	echo $result;
	echo $url;

}
else
{
	header('HTTP/1.0 401 Unauthorized');
	die;		
}
