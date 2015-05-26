<?php
chdir("..");
include("inc/startup.php");

if(isset($_POST['mail']))
{
	$email = $db->real_escape_string($_POST['mail']);
	$result = $db->query("SELECT * FROM users WHERE email = '$email'");
	if($db->affected_rows > 0)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$tpl = $result->fetch_object(); 
			$rkey = random_password(14);
			$rkey = sha1($rkey);
			$db->query("UPDATE users SET rkey = '$rkey' WHERE id = '$tpl->id' ");
			$headers = 'From: '.MAIL_ADDR . "\r\n";
			$msg = "Hello,\n\nWe received a password recovery request for your MuninMX Account (".BASEURL.") account from IP: ".getUserIP()."\n\nTo receive a new password please open: ".BASEURL.'/login.php?rkey='.$rkey;
			mail ( $tpl->email, "MuninMX Password Recovery" , $msg ,$headers);		
		}
		else
		{
			echo "filter fail";
		}
	}
}
else
{
	echo "not set";
}


