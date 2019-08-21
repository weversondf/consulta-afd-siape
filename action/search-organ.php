<?php
include_once("../config/dbconfig.php");
include_once("../classes/paging.php");
$pdo = Conexao::getInstance2();  
$paginate = new paginate($pdo);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Consulta nome ou CPF</title>

	<!-- Bootstrap -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<!-- script   src="http://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script -->
	<!-- script src="../assets/js/jquery-3.1.1.min.js"></script -->
	<script src="../assets/js/jquery-3.1.1.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/index.js"></script>
	<script>
	$(document).ready(function(){
		$(document).keypress(function(e) {
			if(e.which == 13) {
				$('#btn_submit').click();
			} 
		});
	});	
	</script>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<h4><strong>Consulta servidor/instituidor por Órgão ou UPAG - Extração SIAPE</strong></h4>
		</div>
		<div class="col-md-4">
			<p class="text-right">
				<a href="../index.php" class="btn btn-success" role="button">Página inicial</a>
			</p>
		</div>
    </div>
	
	<!-- Linha -->
	<fieldset>
		<legend> </legend>
	<fieldset>
	
	<!-- Form -->
	<div class="col-md-4"> </div>
	<div class="col-md-4">
		<form class="navbar-form" action="search-organ.php" method="get" name="formSearch">
			<div class="input-group">
				<!-- input pattern=".{2,}" required title="Mínimo de 2 caracteres!" type="text" class="form-control" placeholder="Pesquisar por..." name="search" -->
				<!-- input id="search" name="textinput" type="text" placeholder="Informe o Órgão ou UPAG!" class="form-control" pattern=".{5,}" required title="Mínimo de 5 caracteres!" maxlength="12" -->
				<input type="text" name="search" pattern="[0-9]+$" placeholder="Informe o Órgão ou UPAG!" class="form-control" required title="Mínimo de 5 caracteres numéricos!" maxlength="14" />
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
		
		if ($type == 'S'){
			$table = "tb_siape_servidor";		
		} elseif ($type == 'I') {
			$table = "tb_siape_instituidor";		
	    }
		// 26241000000054	
		$query = "SELECT nu_orgao_upag AS \"Órgão e UPAG\"
				, co_orgao_matr AS \"Matrícula\"
				, CASE 
					WHEN da_ocor_ingr_orgao_serv = '00000000' THEN '00/00/0000'
					ELSE TO_CHAR(da_ocor_ingr_orgao_serv::date, 'DD/MM/YYYY')
				END AS \"Dt. Ingr. Órgão\"
				, TO_CHAR(da_cadastramento_servidor::date, 'DD/MM/YYYY') AS \"Dt. Cadastro\"
				, TRIM(REPLACE(TO_CHAR(nu_cpf::bigint, '000:000:000-00'), ':', '.')) AS \"CPF\"
				, no_servidor AS \"Nome\"
				, regime_afd AS \"Reg. AFD\"
				, sg_regime_situacao \"Reg. Sit.\"
			FROM {$table}
			WHERE (nu_orgao_upag = '{$search}' OR nu_orgao_upag LIKE '{$search}%')
			ORDER BY no_servidor";
?>
		<div class="alert alert-success" role="alert">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		    <span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
			<strong>Filtro:</strong> 
			<?
				if((strlen($search)) == 5) {
					echo "Órgão {$search}";
				} else {
					echo "UPAG {$search}";
				}
			?>
		</div>
<?	
		$records_per_page = 5;
		$max_links_page   = 5;
		$id_table = 'tb_extracao_siape';
		$newquery = $paginate->paging($query,$records_per_page);
		$paginate->dataViewDinamic($newquery,$id_table);
		$paginate->paginglinksearch($query,$records_per_page,$search,$type,$max_links_page);
	}
	?>
	</div>	
</div>
</body>
</html>
