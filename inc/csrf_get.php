<?php

function getToken()
{
  return sha1(session_id() . ':csrf');
}

function checkToken()
{
  if($_GET && !$_POST)
  {
    if(!$_GET['token'])
    {
        die('no csrf token received');
     
    }
    else
    {
      if($_GET['token'] != getToken())
      {
          die('invalid csrf token, hit back and try again');
       
      }
     
    }
  }
  elseif(!$_GET)
  {
         die('no csrf token received');
  }
}
?>
