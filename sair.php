<?php 
require 'config.php';
session_start(); 
ob_start();

unset($_SESSION['id']);
unset($_SESSION['usuario']);  
$_SESSION['msg'] = "<p style= ' text-align: center;'>Deslogado com sucesso!</p>";
header("Location: index.php");  

?>