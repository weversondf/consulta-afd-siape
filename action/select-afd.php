<script src="assets/js/index.js"></script>
 <?php 
require_once("../config/dbconfig.php");
require_once("../classes/functions.php");
require_once("../classes/webservices-afd.php");
header('Content-Type: text/html; charset=utf-8');

$pdo = Conexao::getInstance();
$crud = Crud::getInstance($pdo, NULL);

$search = trim($_POST['search']);
if(isset( $_POST['search'] )) {
	if((strlen($search) < 7) || ($search != is_numeric($search))) {
		// echo "Digite uma matrícula válida com 7 ou 12 caracteres!";
?>
		<div class="alert alert-danger alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hsearchden="true">&times;</span></button>
			<span class="glyphicon glyphicon-thumbs-down" aria-hsearchden="true"></span>
			<strong>Erro!</strong> 
			<br>Digite uma matrícula válida com 7 ou 12 caracteres!
		</div>
<?php
		die();
	} else {
    // echo "Matrícula digitada: $search";
	$sql = "SELECT DISTINCT protocolo.protocolo_formatado AS matricula,
				   DATE_FORMAT(protocolo.dta_geracao,'%d/%m/%Y') AS dt_admissao,
				   INSERT( INSERT( INSERT( REPLACE ( REPLACE(protocolo.descricao, '-', ''), '.', '') , 10, 0, '-' ), 7, 0, '.' ), 4, 0, '.' ) AS cpf,
				   unidade.sigla AS nu_unidade,
				   unidade.descricao,
				   atividade.id_protocolo,
				   CASE WHEN atividade.id_protocolo IS NULL
					THEN ('QUEBRADA')
					ELSE ('CARREGADA')  
					END AS situacao
			FROM protocolo
			JOIN unidade ON protocolo.id_unidade_geradora = unidade.id_unidade
			LEFT JOIN atividade ON protocolo.id_protocolo = atividade.id_protocolo
			WHERE (protocolo_formatado = '{$search}'
			OR RIGHT(protocolo_formatado, 7) = '{$search}')
			AND atividade.id_protocolo IS NOT NULL"; 
	$dataAfd = $crud->getSQLGeneric($sql, NULL, TRUE); 
	}
}
	// Exibir os dados
	// die('<pre>'.print_r($dataAfd, 1));
	
	if(!empty($dataAfd)) {
?>
	<div class="table-responsive">
		<table id="tabela-afd" class="table table-bordered table-striped table-hover">
		<caption><strong>Dados do ambiente de produção do AFD<strong></caption>
		<thead>
			<tr class="info">
				<th>Matrícula</th>
				<th>Data de Admissão</th>
				<th>CPF</th>
				<th>Unidade</th>
				<th>Unidade descrição</th>
				<th>Sit. pasta</th>
			</tr>
		</thead>
		<tbody>
<?php
		foreach($dataAfd as $row) {
?>
		<tr>				
			<td><?php echo $row['matricula']; ?></td>
			<td><?php echo $row['dt_admissao']; ?></td>
			<td><?php echo $row['cpf']; ?></td>
			<td><?php echo $row['nu_unidade']; ?></td>
			<td><?php echo $row['descricao']; ?></td>
			<?php
			if ($row['situacao'] == 'QUEBRADA') {
			?>
				<td class="danger text-danger">
					<!-- button type="button" id="btn-delete" class="btn btn-danger btn-xs">Excluir pasta</button -->
					<strong><?php echo $row['situacao']; ?> </strong>
				</td>
			<?php 
			} else {
			?>
				<td class="text-info">
					<!-- button type="button" id="btn-insert" class="btn btn-success btn-xs">Inserir pasta</button -->
					<strong><?php echo $row['situacao']; ?> </strong>
				</td>
			<?php 
			}
			?>							
		</tr>
<?php	
            }
			// Desconectar
			$pdo = null;
?>
			</tbody>
		</table>		
<?php
	} else {
?>
		<div class="alert alert-info alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hsearchden="true">&times;</span></button>
			<span class="glyphicon glyphicon-exclamation-sign" aria-hsearchden="true"></span>
			<strong>Informação!</strong> 
			<br>Registro não encontrado nos dados do ambiente de produção AFD!
		</div>
<?php
	}

