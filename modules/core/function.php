<?php


function getCurUrl()
{
	 $actual_link = PROTO."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	 return $actual_link;
}

function number_format_global($num)
{
	return number_format($num, 2, '.', '');
}

function startsWith($haystack,$needle,$case=true)
{
   if($case)
       return strpos($haystack, $needle, 0) === 0;

   return stripos($haystack, $needle, 0) === 0;
}

function endsWith($haystack,$needle,$case=true)
{
  $expectedPosition = strlen($haystack) - strlen($needle);

  if($case)
      return strrpos($haystack, $needle, 0) === $expectedPosition;

  return strripos($haystack, $needle, 0) === $expectedPosition;
}

function random_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

function getTimeStampFromStringForZone($string,$zone)
{
	date_default_timezone_set($zone);
	return strtotime($string);
}

function secureArray($arr)
{
	global $db;
	foreach($arr as $key => $value)
	{
		$arr[$key] = $db -> real_escape_string($value);
	}
	return $arr;
}

function getUserIP()
{
	if(! isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$client_ip = $_SERVER['REMOTE_ADDR'];
	}
	else
	{
		$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	return $client_ip;
}

function formatSeconds($secs) {

    if (!$secs = (int)$secs)
        return '0 seconds';

    $units = array(
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1
    );

    $strs = array();

    foreach($units as $name=>$int){
        if($secs < $int)
            continue;
        $num = (int) ($secs / $int);
        $secs = $secs % $int;
        $strs[] = "$num $name".(($num == 1) ? '' : 's');
    }

    return implode(', ', $strs);
}

function bytesToSize($bytes, $precision = 2)
{  
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
   
    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';
 
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';
 
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';
 
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';
 
    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}

function getFormatedLocalTimeByStr($time)
{
	$timestr = strtotime($time);
	return getFormatedLocalTime($timestr);
}

function getFormatedLocalTime($timestamp)
{

	//echo $_SESSION['timezone'];
	if(!isset($_SESSION['timezone']))
	{
		$tz = "UTC";
	}
	else
	{
		$tz = $_SESSION['timezone'];
	}
	$reset = date_default_timezone_get();
	date_default_timezone_set($tz);
	$dtStr = date("c", $timestamp);
	$date = new DateTime($dtStr, new DateTimeZone($tz));
    date_default_timezone_set($reset);
	return($date->format('D M j G:i:s  Y'));
}
?>