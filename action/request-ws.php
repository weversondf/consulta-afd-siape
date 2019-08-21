<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Consulta dados do AFD e da extração SIAPE</title>

	<!-- Bootstrap -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<!-- script   src="http://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script -->
	<script src="../assets/js/jquery-3.1.1.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/index.js"></script>
</head>
<body>
<div class="container">

<!-- Serviços WS -->
<?php
require_once("../config/dbconfig.php");
require_once("../classes/functions.php");
require_once("../classes/webservices-afd.php");
header('Content-Type: text/html; charset=utf-8'); 

$pdo = Conexao::getInstance2();
$crud = Crud::getInstance($pdo, NULL);
$class = new Ws();

if(isset($_POST["searchWs"])) {
	$search      = $_POST["searchWs"];
	$action      = $_POST["action"];
	$matricula   = $_POST['matricula'];
}

if(!empty($_POST['orgaoUpag'])) {
	$orgaoUpag   = $_POST['orgaoUpag'];
	$dtIngrOrgao = $_POST['dtIngrOrgao'];
	$cpf         = $_POST['cpf'];
	$nome        = $_POST['nome'];
	$regAFD      = $_POST['regAFD'];
	$result = $class->requestWs($action, $orgaoUpag, $matricula, $dtIngrOrgao, $regAFD , $cpf, $nome);
} else {
	$result = $class->requestWs($action, null, $matricula, null, null, null, null);
}
// echo "search: $search | action: $action";

if(!empty($search)) {
?>			
	<!-- Modal -->
	<div class="modal-dialog" role="document" id="modalwindow">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closemodal"><span aria-hidden="true">&times;</button>
				<h4 class="modal-title">Informação!</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-info" role="alert">
					<?php echo $result; ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="closemodalbtn">Fechar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
<?php
	}
?>
	</div>
</body>
</html>