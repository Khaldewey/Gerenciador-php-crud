<!DOCTYPE html>
<html>
<head>
<?php  
require 'config.php'; 

session_start();
date_default_timezone_set('America/Sao_Paulo');

$lista = [];
$lista1 = [];
$lista2 = []; 
 
if(isset($_SESSION['msg'])){
	echo $_SESSION['msg'];
	unset($_SESSION['msg']); 

}

$sql = $pdo->query("SELECT *FROM dados");
if($sql->rowCount() > 0){
    $lista = $sql->fetchAll(PDO::FETCH_ASSOC);
} 

$sql2 = $pdo->query("SELECT *FROM cartoes");
if($sql2->rowCount() > 0){
	$lista1 = $sql2->fetchAll(PDO::FETCH_ASSOC);
}  

$sql1 = $pdo->query("SELECT *FROM movimentos ORDER BY id DESC");
if($sql1->rowCount() > 0){
	$lista2 = $sql1->fetchAll(PDO::FETCH_ASSOC);
} 
 


$matricula = filter_input(INPUT_POST,'matricula');
$quantidade = filter_input(INPUT_POST,'quantidade'); 

$flag = $quantidade;
$horario = date("H:i");
$data = date("d/m/Y"); 

foreach($lista1 as $cartoes): 
$qtd = $cartoes['quantidade'];
endforeach;

if(isset($_POST['retirar'])){
$quanti = $qtd - $quantidade;

$query_matricula = "SELECT id, matricula FROM matriculas WHERE matricula = :matricula LIMIT 1"; 
$result_matricula = $pdo->prepare($query_matricula); 
$result_matricula->bindParam(':matricula',$matricula, PDO::PARAM_STR); 
$result_matricula->execute();

if(($result_matricula) and ($result_matricula->rowCount() != 0)){

if($matricula && $quantidade){ 
	$sql1 = $pdo->prepare("INSERT INTO movimentos (matricula, retirar,horario,datas) VALUES (:matricula, :flag ,:horario , :data)");
	$sql1->bindValue(":flag",$flag);
	$sql1->bindValue(":matricula",$matricula);
	$sql1->bindValue(":horario",$horario); 
	$sql1->bindValue(":data",$data);
	$sql1->execute();

    $sql2 = $pdo->prepare("UPDATE cartoes SET quantidade = :quanti");
    $sql2->bindValue(":quanti",$quanti);
    $sql2->execute(); 
    header('Location: index.php');
}
}else{
    print '<script>alert("ERRO: Matrícula inválida");</script>';
} 
} 


if(isset($_POST['devolver'])){
$quanti = $qtd + $quantidade; 

$query_matricula = "SELECT id, matricula FROM matriculas WHERE matricula = :matricula LIMIT 1"; 
$result_matricula = $pdo->prepare($query_matricula); 
$result_matricula->bindParam(':matricula',$matricula, PDO::PARAM_STR); 
$result_matricula->execute();

if(($result_matricula) and ($result_matricula->rowCount() != 0)){

if($matricula && $quantidade){  
   $sql1 = $pdo->prepare("INSERT INTO movimentos (matricula, devolver, horario,datas) VALUES (:matricula, :flag, :horario, :data)");
	$sql1->bindValue(":flag",$flag);
	$sql1->bindValue(":matricula",$matricula);
	$sql1->bindValue(":horario",$horario); 
	$sql1->bindValue(":data",$data);
	$sql1->execute();

    $sql2 = $pdo->prepare("UPDATE cartoes SET quantidade = :quanti");
    $sql2->bindValue(":quanti",$quanti);
    $sql2->execute(); 
    header('Location: index.php');
} 
}else{
    print '<script>alert("ERRO: Matrícula inválida");</script>';
}
}


?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<title>Gerenciador</title>
</head>
<body > 
<nav class="navbar navbar-dark bg-dark fixed-top ">
  <div class="container-fluid">
    <a class="navbar-brand" href="https://israel-alves.netlify.app" target="_blank">Criado por Israel Alves</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Gerenciador de cartões</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="portal_adm.php">Painel administrador</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="gerar_excel.php">Gerar excel</a>
          </li>
          
        </ul>
      </div>
    </div>
  </div>
</nav>
<br>
 
 <div class="container mt-5 "> 

    <h1 class="display-2 text-center">Gerenciador de Cartões</h1>
    <form method="POST" action="" class="text-center" >
    	<input class="form-control" type="number" placeholder="Matrícula" aria-label="default input example" name="matricula" required> 
        <br>
    	<input class="form-control" type="number" placeholder="Quantidade" aria-label="default input example" name="quantidade" required>
    	<br>
      <button class="btn btn-success " type="submit" name="devolver">Devolver</button> 
      <button class="btn btn-success " type="submit" name="retirar">Retirar</button>

    </form> 


 	</div>
 
 
<div class="container text-center p-5"> 


<?php foreach($lista1 as $cartoes) : ?>
<h3>ESTOQUE:<?=$cartoes['quantidade'];?></h3>

<?php endforeach; ?>




<h1 class="mt-5"> Relatório </h1>
 <table class="table table-dark table-hover">
     <tr>
      
      <th scope="col">Matrícula</th>
      <th scope="col">Ação</th>
      <th scope="col">Data</th>
      <th scope="col">Horário</th>
     
    </tr>
<?php foreach($lista2 as $movimentos) : ?>

 
  <?php if($movimentos['retirar'] != null)  : ?>
     
     <tr>
      <td><?=$movimentos['matricula'];?></td>
      <td>-<?=$movimentos['retirar'];?></td>
      <td><?=$movimentos['datas'];?></td>
      <td><?=$movimentos['horario'];?></td>
     </tr>  
     <?php endif; ?>
     


     <?php if($movimentos['devolver'] != null)  : ?> 
      <tr>
      <td><?=$movimentos['matricula'];?></td>
      <td>+<?=$movimentos['devolver'];?></td>
      <td><?=$movimentos['datas'];?></td>
      <td><?=$movimentos['horario'];?></td>
     </tr> 

     <?php endif; ?>

<?php endforeach; ?>
</table>
</div>
  


<script type="text/javascript" src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>