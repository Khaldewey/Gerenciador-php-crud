<?php  

require 'config.php';
session_start(); 
ob_start();
if((!isset($_SESSION['id'])) AND (!isset($_SESSION['usuario']))){
header("Location: portal_adm.php"); 
$_SESSION['msg'] = "<p> Erro: Necessário realizar o login para acessar essa página</p>";

} 

$sql = $pdo->query("SELECT *FROM matriculas");
if($sql->rowCount() > 0){
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
} 

$sql1 = $pdo->query("SELECT *FROM movimentos ORDER BY id DESC"); 
$matricula = filter_input(INPUT_POST,'matricula');

if(isset($_POST['resetar'])){
$sql1 = $pdo->prepare("DELETE FROM movimentos");
$sql1->execute(); 
header("Location: index.php"); 

} 

if(isset($_POST['cadastrar'])){
	$sql = $pdo->prepare("INSERT INTO matriculas (matricula) VALUES (:matricula)");
	$sql->bindValue(":matricula",$matricula);
	$sql->execute();
header("Location: dashboard.php");
} 

$quantidade = filter_input(INPUT_POST,'estoque');
if(isset($_POST['editar'])){
	$sql = $pdo->prepare("UPDATE cartoes SET quantidade = :quantidade");
	$sql->bindValue(":quantidade",$quantidade);
	$sql->execute();
header("Location: dashboard.php");
} 


?> 

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<title>Administrador</title>
</head>
<body>  

	<nav class="navbar bg-dark ">
  <div class="container-fluid">
    <a class="navbar-brand" href="https://israel-alves.netlify.app" style="color:white;" target="_blank">Criado por Israel Alves</a> 

    <a href="sair.php" class="btn btn-warning">Sair</a>
  </div>
</nav>
	<br><br>
	<div class="container">
	  <form method="POST" action="" class="text-center" >
      <input type="number" name="matricula" placeholder="Matrícula"  class = "form-control" required>
      <br>
      <button class="btn btn-success " type="submit" name="cadastrar">Cadastrar matrícula</button>
     
      </form>  
    <br><br>
    <form method="POST" class="text-center"> 
    <input type="number" name="estoque" placeholder="Quantidade" class = "form-control" required>
    <br>
    <button class="btn btn-success " type="submit" name="editar">Definir estoque</button>
    </form>  

     <br><br><br>
     <form method="POST" class="text-center">
   <button class="btn btn-danger " type="submit" name="resetar">Excluir relatório</button>
    </form> 

    </div> 
    
    <br><br><br>
    <div class="container text-center">
    	 <table class="table table-dark table-hover">
     <tr>
      
      <th scope="col">Matrículas cadastradas</th> 
      <th scope="col">Ação</th>
     
    </tr>
    <?php foreach($lista as $matriculas) : ?> 
    
     <tr>
      <td><?=$matriculas['matricula'];?></td>
        <td>
       <a href="excluir.php?id=<?=$matriculas['id'];?>" class="btn btn-danger">Excluir</a>

      </td>
     </tr>
    <?php endforeach; ?>



    </div> 


  





 
<script type="text/javascript" src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>