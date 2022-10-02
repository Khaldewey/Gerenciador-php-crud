<?php   
//'".$dados['usuario']."' LIMIT 1";
require 'config.php';

session_start(); 
ob_start(); 

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if(!empty($dados['send-login'])){
	$query_usuario = "SELECT id, usuario, senha FROM usuarios WHERE usuario = :usuario LIMIT 1";
	    $result_usuario = $pdo->prepare($query_usuario);
	    $result_usuario->bindParam(':usuario',$dados['usuario'], PDO::PARAM_STR); 
	    $result_usuario->execute();

	    if(($result_usuario) and ($result_usuario->rowCount() != 0)){
	    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
	    if(password_verify($dados['senha_usuario'], $row_usuario['senha'])){
	    	$_SESSION['id']=$row_usuario['id'];
	    	$_SESSION['usuario']=$row_usuario['usuario'];
	    	header("Location:dashboard.php");
	    }else{
	    $_SESSION['msg'] = "ERRO: Senha inválida!";	
	    }
	}else{
      $_SESSION['msg'] = "ERRO: Usuário ou senha inválida!";

	} 

}
if(isset($_SESSION['msg'])){
	echo $_SESSION['msg'];
	unset($_SESSION['msg']);
} 


?> 

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<title>Login</title>
</head>
<body class="bg-secondary">
<nav class="navbar bg-dark ">
  <div class="container-fluid">
    <a class="navbar-brand" href="https://israel-alves.netlify.app" style="color:white;" target="_blank">Criado por Israel Alves</a>
  </div>
</nav>
<div class="container bg-light p-5 mt-5">
<form method="POST" action="" class="text-center"> 
	<div class="row mb-3">
	 <label for="usuario" class="col-sm-2 col-form-label" >Usuário</label>
	  <div class="col-sm-10">
	  <input type="text" name="usuario" placeholder="Digite o usuario" value="<?php if(isset($dados['usuario'])){echo $dados['usuario'];}?>" class = "form-control">
	  </div> 
	</div> 
    
    <div class="row mb-3">
	<label for="senha_usuario" class="col-sm-2 col-form-label">Senha</label> 
	<div class="col-sm-10"> 
	<input type="password" name="senha_usuario" placeholder="Digite a senha" value="<?php if(isset($dados['usuario'])){echo $dados['usuario'];}?>" class="form-control">
    </div> 
	</div> 
	<br><br>
	<input type="submit" name="send-login" value="Acessar" class="btn btn-dark"> 
	<a href="index.php" class="btn btn-dark">Voltar</a> 
</form> 
</div> 


<script type="text/javascript" src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>