<?php 
$db_name = 'estoque';
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'admin';

$pdo = new PDO("mysql:dbname=".$db_name.";host=".$db_host, $db_user, $db_password); 

//$pdo = mysqli_connect ($db_host, $db_user, $db_password, $db_name); 

/* 
$host="localhost";

$login_bd="filial68";

$senha_bd="senhafilial";

$database="atacadao";

$db = mysqli_connect ($host, $login_bd, $senha_bd, $database);
date_default_timezone_set("Brazil/East");
*/

 ?> 

