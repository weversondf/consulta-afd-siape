<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

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
	<p class="text-center text-info">Pesquisa documentos excluídos que deverão ser recadastrados no AFD</p>
	<div class="col-md-4"> </div>
	<div class="col-md-4">
		<form class="navbar-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="get" name="form-search">
			<div class="input-group col-xs-12">
				<input type="text" pattern="[0-9]+$" title="Somente números!" maxlength="6" class="form-control" placeholder="Em branco ou protocolo" name="search">
				<span class="input-group-btn">
					<input type="submit" class="btn btn-primary" value="Pesquisar" id="btn_submit">
				</span>
			</div>
		</form>
	</div>
	<div class="col-md-12">
	<?php
	if(isset($_GET['search'])) {
		$search = $_GET['search'];
		if (!empty($_GET['search'])) {
			$query = $select->dadosAnexosExcluidos($search, 1);
		} else {	
			$query = $select->dadosAnexosExcluidos($search, 0);
		}
		$records_per_page = 5;
		$max_links_page   = 5;
		$id_table = 'tb_siape_servidor';
		$caption = "Lista dos documentos excluídos que deverão ser recadastrados no AFD";
		$newquery = $paginate->paging($query,$records_per_page);
		$paginate->dataViewDinamic($newquery,$id_table,$caption);
		$paginate->pagingLinkSearch($query,$records_per_page,$search,$max_links_page);
	}
	?>
	</div>
</div>
</body>
</html>
