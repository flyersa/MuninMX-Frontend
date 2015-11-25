<?php
function get_link()
{
		$db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
        $errno = mysqli_connect_errno();
        if($errno != 0)
        {
                echo $db->error;
                echo 'mysqli connect error: ' . $errno;
                die;
        }
        return($db);
}
?>
