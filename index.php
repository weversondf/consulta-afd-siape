<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>AFD e SIAPE CADASTRO</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- script src="http://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script -->
	<!-- Bootstrap v3.3.7 -->
	<script src="assets/js/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/index.js"></script>
</head>
<body>
<div class="container">
    <header class="row">
		<nav class="navbar navbar-inverse">
		  <div class="container-fluid">
			<div class="navbar-header">
			  <a class="navbar-brand" href="#">Consulta</a>
			</div>
			<ul class="nav navbar-nav">
			  <li class="active"><a href="index.php">AFD</a></li>
			  <li><a href="app/search-name-cpf.php">Nome ou CPF</a></li>
			  <li><a href="app/search-org-upag.php">Órgão ou UPAG</a></li>
			  <li><a href="app/search-interval.php">Período (Data)</a></li>
			  <li><a href="app/search-regime.php">Regimes</a></li>
			  <li><a href="app/search-unit.php">Unidades AFD</a></li>
			  <li><a href="app/search-report.php">Relatórios</a></li>
			</ul>
		  </div>
		</nav>        
    </header>
    <div class="row">
		<!-- Form -->
		<div class="col-md-4"> </div>
		<div class="col-md-4">
			<form class="navbar-form">
				<div class="input-group">
					<input id="search" pattern=".{2,}" required title="Mínimo de 7 caracteres!" type="text" class="form-control" placeholder="Matrícula SIAPE" name="search" maxlength="12">
					<span class="input-group-btn">
						<button type="button" class="btn btn-primary" name="btn-submit" id="btn-submit">Pesquisar</button>
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
    </div>
	<div class="row">
		<div class="col-md-12">
			<!-- Loading -->
			<div id="loading" class="row" style="display:none;">
				<div class="col-md-3"></div>
				<div class="col-md-6" style="text-align:center">
					<img id="img-loader" src="assets/imgs/ajax-loader.gif" alt="Loading"/>
					<p><strong>Processando os dados do ambiente de produção do AFD...</strong></p>
				</div>
			</div>
			<div id="loading-siape" class="row" style="display:none;">
				<div class="col-md-3"></div>
				<div class="col-md-6" style="text-align:center">
					<img id="img-loader" src="assets/imgs/ajax-loader.gif" alt="Loading"/>
					<p><strong>Processando os dados do arquivo SIAPE CADASTRO...</strong></p>
				</div>
			</div>

			<!-- Listagem dos dados da consulta -->
			<div id="data-afd" class="container"></div>
			<div id="data-siape" class="container"></div>
		</div>
	</div>
    <footer class="row">
		<? include_once("assets/layout/footer.php"); ?>
    </footer>
</div>
</body>
</html>