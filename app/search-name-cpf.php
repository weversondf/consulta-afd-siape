<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

include_once("../config/dbconfig.php");
include_once("../classes/paging.php");
include_once("../classes/select.php");
include_once("../assets/layout/header.php");
$pdo = Conexao::getInstance2();  
$select = new select();
$paginate = new paginate($pdo);
header('Content-Type: text/html; charset=utf-8');
?>
<div class="row">
	<!-- Form -->
	<div class="col-md-4"> </div>
	<div class="col-md-4">
		<form class="navbar-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="get" name="form-search">
			<div class="input-group">
				<input pattern=".{2,}" required title="Mínimo de 2 caracteres!" type="text" class="form-control" placeholder="Pesquisar por..." name="search">
				<span class="input-group-btn">
					<input type="submit" class="btn btn-primary" value="Pesquisar" id="btn_submit">
				</span>
			</div>
			<div class="input-group">
				<label class="radio-inline">
				  <input type="radio" name="type" value="S" checked>Servidor
				</label>
				<label class="radio-inline">
				  <input type="radio" name="type" value="I">Instituidor
				</label>
			</div>
		</form>
	</div>
	<div class="col-md-4"> </div>
	<div class="col-md-12">
	<?php 
	if (!empty($_GET['search'])) {
		$search = $_GET['search'];
		$type = $_GET['type'];
		$query = $select->dadosCadastraisServidoresNomeCpf($search, $type);
		$records_per_page = 5;
		$max_links_page   = 5;
		$id_table = 'tb_siape_servidor';
		$caption = "Lista de Instituidores por nome ou por CPF ou por matrícula";
		$newquery = $paginate->paging($query,$records_per_page);
		$paginate->dataViewDinamic($newquery,$id_table, $caption);
		$paginate->pagingLinkSearchType($query,$records_per_page,$search,$type,$max_links_page);
	}
	?>
	</div>
</div>
</body>
</html>
