<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');
include_once("../config/dbconfig.php");
require_once("../classes/files.php");
include_once("../assets/layout/header.php");
$pdo = Conexao::getInstance2(); 
$files = Files::getInstance($pdo, NULL);
?>
<div class="row">
	<?php
	if(isset($_POST['submit'])) {
		$files->selectUnitAfd();
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
						<input type="submit" name="submit" value="Exportar Unidades do AFD na Consulta" class="btn btn-primary">
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