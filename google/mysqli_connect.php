<?php
ini_set('memory_limit', '-1');

ini_set('max_execution_time', 0);
$host="novadbtest.coayalyvndh4.us-east-1.rds.amazonaws.com"; // Host name 
$username="novaUser"; // Mysql username 
$password="TTD4121"; // Mysql password 

// Connect to server and select databse.
//$con=mysqli_connect("$host", "$username", "$password")or die("cannot connect"); 

$con = mysqli_init();
mysqli_options($con, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
$con->ssl_set(null, null, 'rds-ca-2015-root.pem', null, null);
mysqli_real_connect($con,"$host", "$username", "$password", NULL, 3306, NULL, MYSQLI_CLIENT_SSL)or die("cannot connect"); 
mysqli_set_charset($con, "utf8");

?>