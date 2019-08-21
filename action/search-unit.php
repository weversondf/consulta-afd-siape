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
	<title>Consulta unidades AFD</title>

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
			<h4><strong>Consulta unidades cadastradas no AFD</strong></h4>
		</div>
		<div class="col-md-2">
			<p class="text-right">
				<a href="../index.php" class="btn btn-success" role="button">Página inicial</a>
			</p>
		</div>
		<div class="col-md-2">
			<form class="text-left" action="update-unit.php" method="post" name="form-update">
						<input type="submit" class="btn btn-danger" value="Atualizar" id="btn_submit">
			</form>
		</div>
    </div>
	
	<!-- Linha -->
	<fieldset>
		<legend> </legend>
	<fieldset>
	
	<!-- Form -->
	<div class="col-md-4"> </div>
	<div class="col-md-4">
		<form class="navbar-form" action="search-unit.php" method="get" name="formSearch">
			<div class="input-group">
				<input pattern=".{2,}" required title="Mínimo de 2 caracteres!" type="text" class="form-control" placeholder="Pesquisar por..." name="search">
				<span class="input-group-btn">
					<input type="submit" class="btn btn-primary" value="Pesquisar" id="btn_submit">
				</span>
			</div>
		</form>
	</div>
	<div class="col-md-4"> </div>
	<div class="col-md-12">
	<?php 
	if (!empty($_GET['search'])) {
		$search = $_GET['search'];
		// echo "<br>Search: $search";
		$query = "SELECT id_unidade AS \"Id\",
					sigla AS \"Sigla\",
					descricao AS \"Descrição\"
					FROM tb_afd_unidade 
					WHERE (descricao LIKE UPPER('%{$search}%') OR sigla LIKE UPPER('{$search}%'))
					ORDER BY sigla";
		$records_per_page = 5;
		$max_links_page   = 5;
		$id_table = 'tb_unidades_afd';
		$newquery = $paginate->paging($query,$records_per_page);
		$paginate->dataViewDinamic($newquery,$id_table);
		$paginate->paginglink($query,$records_per_page,$search,$max_links_page);
	}
	?>
	</div>	
</div>
</body>
</html>
