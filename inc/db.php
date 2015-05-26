<?php
/* 
 * Copyright (c) 2010 by Clavain Technologies Ltd.
 * http://www.clavain.co.uk
 */
function get_link()
{
        $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
        if(mysqli_connect_errno() != 0)
        {
                echo $db->error;
                echo 'we are back ASAP please hold on...';
                die;
        }
        return($db);
}

?>
