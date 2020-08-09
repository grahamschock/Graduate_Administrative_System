<?php

require_once('connectvars.php');

$sql = file_get_contents('ADS_Schema.sql');

$mysqli = new mysqli('localhost', 'user', 'password', 'db');
if (mysqli_connect_errno()) { /* check connection */
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* execute multi query */
if ($mysqli->multi_query($sql)) {
   header("location:admin.php");
} else {
   echo "error";
}

?>
