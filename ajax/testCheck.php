<?php
chdir("..");
include("inc/startup.php");
if(!isLoggedIn())
{
	header('HTTP/1.0 401 Unauthorized');
	die;
}
if($_GET['checktype'])
{
	$_POST['checktype'] = $_GET['checktype'];
}
$result = $db->query("SELECT * FROM check_types WHERE id = '$_POST[checktype]'");
if($db->affected_rows < 1)
{
	 display_error("Undefined Check", '<br />Check Type not defined');
	 		echo '
		 <script type="application/javascript">
			$("#tcbtn").attr("disabled", false);
		</script>
		 ';
	 die;
}

// build json
$tpl = $result->fetch_object();
$_POST['command'] = $tpl->executable;
$json = postCheckToJson($_POST);


$url = CVD_URI."/testcheck/dummy";


//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt( $ch, CURLOPT_ENCODING, "UTF-8" );  
curl_setopt($ch,CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

$ret = json_decode($result,true);
if(!$ret)
{
	 display_error("Unknown Response", '<br />We received giberish. Please try again. Maybe some parameters make no sense.');
	 		echo '
		 <script type="application/javascript">
			$("#tcbtn").attr("disabled", false);
		</script>
		 ';
	 die;	
}
else
{
	$retVal = $ret['returnValue'];
	$retTxt = $ret['output'][0];
	if($retVal == 0)
	{
		display_ok($retTxt);
	}
	elseif($retVal == 1)
	{
		display_warning($retTxt);
	}
	elseif($retVal == 2)
	{
		display_error($retTxt);
	}
	elseif($retVal == 3)
	{
		display_error("Something is not correct with your parameters.");
	}		
}
		echo '
		 <script type="application/javascript">
			$("#tcbtn").attr("disabled", false);
		</script>
		 ';


