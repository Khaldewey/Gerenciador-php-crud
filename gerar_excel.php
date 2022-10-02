<?php  


require 'config.php';
session_start();
ob_start();


$query_movimentos = "SELECT matricula, devolver, retirar, horario, datas FROM movimentos";


$result_movimentos = $pdo->prepare($query_movimentos); 



$result_movimentos->execute();


if(($result_movimentos) and ($result_movimentos->rowCount() != 0)) {
header('Content-Type: text/csv; charset-UTF-8');

header('Content-Disposition: attachment; filename=papafila.csv');

$resultado = fopen("php://output",'w'); 

$cabecalho = ['Matricula','Devolver','Retirar','Horario','Data'];

fputcsv($resultado, $cabecalho, ';');

while($row_movimentos = $result_movimentos->fetch(PDO::FETCH_ASSOC)){
	//extract($row_movimentos);
	fputcsv($resultado, $row_movimentos, ';');
}

fclose($resultado);

}else{

	$_SESSION['msg'] = "<p>Nenhum movimento encontrado</p>";
}












?>