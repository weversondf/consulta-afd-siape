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
				<span class="input-group-btn">
					<!-- Split button -->
					<input pattern=".{2,}" required title="Mínimo de 2 caracteres!" type="text" class="form-control" placeholder="Pesquisar por..." name="search">
				</span>		
				<div class="btn-group">
				  <input type="submit" class="btn btn-primary" value="Pesquisar" id="btn_submit">
				  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
					<li><a href="update-unit.php">Atualizar</a></li>
					<li><a href="export-unit.php">Exportar</a></li>
				  </ul>
				</div>
			</div>			
		</form>
	</div>
	<div class="col-md-4"> </div>
	<?php 
	if (!empty($_GET['search'])) {
		$search = $_GET['search'];
		$query = $select->dadosUnidadesAfd($search);
		$records_per_page = 5;
		$max_links_page   = 5;
		$id_table = 'tb_unidades_afd';
		$caption = "Lista das unidades importadas do AFD";
		$newquery = $paginate->paging($query,$records_per_page);
		$paginate->dataViewDinamic($newquery,$id_table, $caption);
		$paginate->pagingLinkSearch($query,$records_per_page,$search,$max_links_page);
	}
	?>
	</div>	
</div>
</body>
</html>
