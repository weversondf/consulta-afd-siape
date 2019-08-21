<script src="assets/js/index.js"></script>
<?php 
require_once("../config/dbconfig.php");
require_once("../classes/functions.php");
header('Content-Type: text/html; charset=utf-8'); 
 
$pdo = Conexao::getInstance();  
$crud = Crud::getInstance($pdo, NULL);  

$search = trim($_POST['search']);
$action = trim($_POST['action']);

if(isset( $search )) {
	if((strlen($search) < 7) || ($search != is_numeric($search))) {
		// echo "Digite uma matrícula válida com 7 ou 12 caracteres!";
		die();
	} else {
		$id = "tabela-afd";
		$caption = "Dados do Ambiente de Produção do AFD";				
		$dataAfd = $crud->searchProductionAfd($search, $id, $caption, $action);
		// Exibir os dados
		// die('<pre>'.print_r($dataSiape, 1));
	}

	// Desconectar
	$pdo = null;
}
?>

