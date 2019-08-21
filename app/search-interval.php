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
date_default_timezone_set('America/Sao_Paulo');
?>
<div class="row">
	<!-- Form -->
	<div class="col-md-3"> </div>
	<div class="col-md-6">
	    <form class="form-inline" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="get" name="form-search">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">Início</div>
                <input type="date" required title="Formato: dd/mm/yyyy"  maxlength="10" pattern="[0-9]{2}\/[0-9]{2}\/[0-9]{4}$" class="form-control" name="date_in"/>
                <div class="input-group-addon">Fim</div>
                <input type="date" required title="Formato: dd/mm/yyyy"  maxlength="10" pattern="[0-9]{2}\/[0-9]{2}\/[0-9]{4}$" class="form-control" name="date_out"/>
              </div>
            </div>
            <input type="submit" class="btn btn-primary" value="Pesquisar" id="btn_submit">
        </form>
	</div>
	<div class="col-md-12">
	<?php
	if (!empty($_GET['date_in'])) {
		$search_in = $_GET['date_in'];
		$search_out = $_GET['date_out'];
		$query = $select->dadosCadastraisServidores($search_in, $search_out);
		$records_per_page = 5;
		$max_links_page   = 5;
		$id_table = 'tb_unidades_afd';
		$caption = "Dados de servidores do arquivo SIAPE CADASTRO por período(data) de entrada";
		$newquery = $paginate->paging($query,$records_per_page);
		$paginate->dataViewDinamic($newquery,$id_table,$caption);
		$paginate->pagingLinkSearchDate($query,$records_per_page,$search_in,$search_out,$max_links_page);
	}
	?>
	</div>	
</div>
</body>
</html>
