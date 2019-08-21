<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once("../config/dbconfig.php");
require_once("../classes/functions.php");
require_once("../classes/webservices.php");
include_once("../assets/layout/header.php");
header('Content-Type: text/html; charset=utf-8'); 

$pdo = Conexao::getInstance2();
$crud = Crud::getInstance($pdo, NULL);
$class = new Ws();

// Serviços WS
if(isset($_POST["matricula"])) {
	$matricula   = $_POST["matricula"];
}

if(!empty($_POST['orgao-upag'])) {
	$orgaoUpag   = $_POST['orgao-upag'];
	$dtIngrOrgao = $_POST['dt-ingr-orgao'];
	$cpf         = $_POST['cpf'];
	$nome        = $_POST['nome'];
	$regimeAfd   = $_POST['regime-afd'];
	$result = $class->incluirPasta($orgaoUpag, $matricula, $dtIngrOrgao, $regimeAfd , $cpf, $nome);
	
	// $SiglaUnidade      = $_POST['orgao-upag'];
	// $TipoAssentamento  = $_POST['regime-afd'];
	// $Interessado       = $_POST['nome'];
	// $CPF               = $_POST['cpf'];
	// $DataIngressoCargo = $_POST['dt-ingr-orgao'];
	// $Assentamento      = $_POST['matricula'];
	// $result = $class->incluirServidor($SiglaUnidade, $TipoAssentamento, $Interessado, $CPF, $DataIngressoCargo, $Assentamento);
} else {
	// echo $matricula;
	$result = $class->excluirPasta($matricula);
}

if(!empty($matricula)) {
?>
    <div class="container">
        <!-- Modal -->
        <div class="modal-dialog" role="document" id="modalwindow">
        	<div class="modal-content">
        		<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closemodal"><span aria-hidden="true">&times;</button>
        			<class="modal-title">Resposta do Webservice</h4>
        		</div>
        		<div class="modal-body">
					<?php echo $result; ?>
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