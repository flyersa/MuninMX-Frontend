<?php
define("DEMO_MODE",false);
//error_reporting( E_ALL ); 
if (isset($_ENV["HOSTNAME"]))
{
    $MachineName = $_ENV["HOSTNAME"];
}
else if  (isset($_ENV["COMPUTERNAME"]))
{
    $MachineName = $_ENV["COMPUTERNAME"];
}
else 
{
	$MachineName = $_SERVER['SERVER_NAME'];
} 


header("X-Webf: $MachineName");

// UTF-8
// load settings
require_once("inc/settings.php");


// maintainance?
if(MAINTAINANCE > 0)
{
	echo '<div align="center"><b>We are performing maintainance work atm. We are back shortly</b></div>';
	die;
}

// lets go
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['SCRIPT_URL'] != '/api.php') { // do not start a session for api calls
	session_start();
}


if(isset($_SESSION['timezone']))
{
	date_default_timezone_set($_SESSION['timezone']);
}
else
{
	date_default_timezone_set('UTC');		
}


// open mysql connection
require_once("inc/db.php");
global $db;
$db = get_link();


// open mongodb connection
global $m;
try {
	$m = new MongoClient( "mongodb://".MONGO_HOST );
}
catch(Exception $e) {
	// fail gracefully - this way the frontend is "usable" even without a running mongodb backend
	//error_log("Cannot access mongodb backend: " . $e);
}



// load modules
if ($handle = opendir('modules')) {
    while (false !== ($file = readdir($handle))) {
        if($file != "." && $file != "..")
        {
          if(is_file("modules/$file/function.php"))
          {
            include("modules/$file/function.php");
          }
        }
       
    }
    closedir($handle);
}


if(isset($_COOKIE['scs_key']) && isset($_COOKIE['scs_user']))
{
  $db = get_link();
  $key = $db->real_escape_string($_COOKIE['scs_key']);
  $user = $db->real_escape_string($_COOKIE['scs_user']);
  $result = $db->query("SELECT * FROM users WHERE id = '$user' AND password = '$key'");
  if($db->affected_rows > 0)  
  {
      $tpl = $result->fetch_object();
      if (session_id() == "") session_start();
	  $_SESSION['login'] = true;
	  $_SESSION['username'] = htmlspecialchars($tpl->username);
	  $_SESSION['user_id'] = $tpl->id;
      $_SESSION['role'] = $tpl->userrole;
	  $_SESSION['accessgroup'] = $tpl->accessgroup;
	  setcookie("login", "yes");	
	  setcookie("random",uniqid(microtime()));		
      $path = isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:'';       
      $uip = getUserIP();
      $db->query("UPDATE users SET last_login = NOW(), last_login_ip = '$uip' WHERE id = $tpl->id");
	  
  }
}



// CSRF Protection
require_once("inc/csrf.php");
require_once("inc/csrf_get.php");
$csrf_protect = new CsrfProtect();
$csrf_protect->enable();    
?>
