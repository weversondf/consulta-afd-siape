<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);
include_once("../config/dbconfig.php");
require_once("../classes/functions.php");
include_once("../classes/webservices.php");
include_once("../assets/layout/header.php");
$pdo = Conexao::getInstance2(); 
$crud = Crud::getInstance($pdo, NULL);
$webservice = new Ws();
header('Content-Type: text/html; charset=utf-8');
?>
<div class="row">
	<?php
	if(isset($_POST['submit'])) { 
		$request = $webservice->listarUnidades(1);
		$result = $crud->updateUnitAfd($request);
	}
	?>
	<!-- Modal -->
	<div class="modal-dialog" role="document" id="modalwindow">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closemodal"><span aria-hidden="true">&times;</button>
				<h4 class="modal-title">Informação!</h4>
			</div>
			<div class="modal-body">
				<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" name="form-update">
					<div class="text-center"> 
						<input type="submit" name="submit" value="Atualizar Unidades do AFD na Consulta" class="btn btn-primary">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="closemodalbtn">Fechar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->	
</div>
</body>
</html>