<?php
$host="novadb1.coayalyvndh4.us-east-1.rds.amazonaws.com"; // Host name 
$username="webserverUser"; // Mysql username 
$password="6wTqNkGK794s"; // Mysql password 

// Connect to server and select databse.
//$con=mysqli_connect("$host", "$username", "$password")or die("cannot connect"); 

$con = mysqli_init();
mysqli_options($con, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
mysqli_options($con, MYSQLI_OPT_CONNECT_TIMEOUT, 10);
$con->ssl_set(null, null, '../connection/rds-combined-ca-bundle.pem', null, null);
mysqli_real_connect($con,"$host", "$username", "$password", NULL, 3306, NULL, MYSQLI_CLIENT_SSL)or die(header('Location: /maintenance')); 
mysqli_set_charset($con, "utf8");
?>