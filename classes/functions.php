<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once("select.php");
// $select = new select();
// header('Content-Type: text/html; charset=utf-8');

class Crud{

	// Atributo para guardar uma conexão PDO
	private $pdo = null;

	// Atributo onde será guardado o nome da table
	private $table = null;

	// Atributo estático que contém uma instância da própria classe
	private static $crud = null;

	private function __construct($connect, $table=NULL){ 
		if (!empty($connect)):
			$this->pdo = $connect;
		else:
			echo "<h3>Conexão inexistente!</h3>";
			exit();
		endif;

		if (!empty($table)) $this->table =$table;
	}

	public static function getInstance($connect, $table=NULL){

		// Verifica se existe uma instância da classe
		if(!isset(self::$crud)):
			try {
				self::$crud = new Crud($connect, $table);
			} catch (Exception $e) {
				echo "Erro " . $e->getMessage(); 
			}
		endif;

		return self::$crud;
	}

	public function setTableName($table){
		if(!empty($table)){
			$this->table = $table;
		}
	}
	
	public function getSQLGeneric($sql, $arrayParams=null, $fetchAll=TRUE){
		try {
			// Passa a instrução para o PDO
			$stm = $this->pdo->prepare($sql);

			// Verifica se existem condições para carregar os parâmetros 
			if (!empty($arrayParams)):

			  // Loop para passar os dados como parâmetro cláusula WHERE
			$count = 1;
			foreach ($arrayParams as $value):
				$stm->bindValue($count, $value);
				$count++;
			endforeach;

			endif;

			// Executa a instrução SQL 
			$stm->execute();

			// Verifica se é necessário retornar várias linhas  
			if($fetchAll):
				// $data = $stm->fetchAll();
				$data = $stm->fetchAll(PDO::FETCH_ASSOC);
			else:
				$data = $stm->fetch();
			endif;

			return $data;
		
		} catch (PDOException $e) {
			echo "Erro: " . $e->getMessage();
		}
	}
	
	public function getQueryInTableHtml($query, $id, $caption) {
		try {
			$result = $this->pdo->query($query);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			// Trata o Warning: Invalid argument supplied for foreach() in /var/www/html/afd/consulta-afd-siape/classes/functions.php on line 97
			if(!empty($row)) {	
				echo "<div class=\"row\">
						<table id=\"{$id}\"class=\"table table-condensed table-bordered table-striped table-hover\">
							<caption>
								<strong>{$caption}<strong>
							</caption>
						<thead>";
				echo "<tr class=\"info\">";
					foreach ($row as $field => $value){
						echo "<th>$field</th>";
					}
				echo "</tr>
				</thead>
				<tbody>";
				
				$data = $this->pdo->query($query);
				$data->setFetchMode(PDO::FETCH_ASSOC);
				foreach($data as $row){
					echo "<tr>";
					foreach ($row as $name=>$value){
						echo " <td>$value</td>";
					}
				echo "</tr>";
				}
				echo "</tbody>
				</table>
				</div>";
			} else {
?>
				<div class="alert alert-info alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hsearchden="true">&times;</span></button>
					<span class="glyphicon glyphicon-exclamation-sign" aria-hsearchden="true"></span>
					<strong>Informação!</strong> 
					<br>Registro não encontrado!
				</div>
<?php
			}
			
			return $row;
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	}
	
	public function getPublicServiceEmployee($search, $id, $caption, $action) {
		$select = new select();
		
		try {
			if ($action == 'S'){
				$table = "tb_siape_servidor";
			} else if ($action == 'I'){
				$table = "tb_siape_instituidor";
			}
			$query = $select->datasetServidorInstituidor($table, $search);
			$result = $this->pdo->query($query);
			// die('<pre>'.print_r($result, 1));
			$rowCount = $result->rowCount();

			// Trata o Warning: Invalid argument supplied for foreach() in /var/www/html/afd/consulta-afd-siape/classes/functions.php on line 97
			if($rowCount <> 0) {
				$row = $result->fetch(PDO::FETCH_ASSOC);
?>
				<div class="table-responsive">
					<table id="<? echo $id ?>"class="table table-bordered table-striped table-hover">
						<caption>
							<strong><? echo $caption ?><strong>
						</caption>
					<thead>	
				<tr class="info">
<?
				foreach ($row as $field => $value){
					echo "<th>$field</th>";
					// die('<pre>'.print_r($value, 1));
				}				
?>
				<th colspan="2">Serviços WS do AFD</th>
				</tr>
				</thead>
				<tbody>
<?
				$data = $this->pdo->query($query);
				$data->setFetchMode(PDO::FETCH_ASSOC);
				foreach($data as $row){
					echo "<tr>";
					foreach ($row as $name=>$value){
						echo "<td>$value</td>";
					}
				}
?>
				<!-- Serviços WS --> 
				<td class="text-center">
					<form action="app/request-ws.php" method="post">
						<input type="hidden" name="search-ws" value="<?php echo $search ?>"/>
<?php
						$query = $select->datasetWebserviceServidorInstituidor($table, $search);
						$dataInsert=$this->pdo->query($query);
						$dataInsert->setFetchMode(PDO::FETCH_ASSOC);
						foreach($dataInsert as $row){
						// die('<pre>'.print_r($row, 1));
?>
							<input type="hidden" name="orgao-upag" value="<? echo $row['nu_orgao_upag'] ?>"/>
							<input type="hidden" name="matricula"  value="<? echo $row['co_orgao_matr'] ?>"/>
							<input type="hidden" name="dt-ingr-orgao" value="<? echo $row['da_ocor_ingr_orgao_serv'] ?>"/>
							<input type="hidden" name="cpf" value="<? echo $row['nu_cpf'] ?>"/>
							<input type="hidden" name="nome" value="<?php echo $row['no_servidor'] ?>"/>
							<input type="hidden" name="regime-afd" value="<?php echo $row['regime_afd'] ?>"/>
<?php
						} 
?>
						<input name="btnInsert" type="submit" value="Inserir"  class="btn btn btn-success btn-sm"/>
					</form>
				</td>
				<td class="text-center">
					<form action="app/request-ws.php" method="post">
						<input type="hidden" name="search-ws" value="<?php echo $search ?>"/>
<?php
						$stmt = $this->pdo->prepare($query);
						$stmt->execute();
						$dataDelete = $stmt->fetch();
						// echo $dataDelete['co_orgao_matr']; die;
?>
						<input type="hidden" name="matricula" value="<? echo $row['co_orgao_matr'] ?>"/>
						<input name="btnDelete" type="submit" value="Excluir"  class="btn btn-danger btn-sm"/>
					</form>
				</td>
				</tr>
				</tbody>
				</table>
				</div>
<?
				return $row;
			} else {
?>
				<div class="alert alert-info alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hsearchden="true">&times;</span></button>
					<span class="glyphicon glyphicon-exclamation-sign" aria-hsearchden="true"></span>
					<strong>Informação!</strong> 
					<br>Registro não encontrado nos dados da extração SIAPE do mês e ano selecionado!
				</div>
<?php
			}
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	}

	public function searchProductionAfd($search, $id, $caption, $action) {
		$select = new select();
		
		try {
			if ($action == 'S'){
				$table = "tb_siape_servidor";
			} else if ($action == 'I'){
				$table = "tb_siape_instituidor";
			}
			$query = $select->datasetProducaoAfd($table, $search);
			$result = $this->pdo->query($query);
			// die('<pre>'.print_r($result, 1));
			$rowCount = $result->rowCount();

			// Trata o Warning: Invalid argument supplied for foreach() in /var/www/html/afd/consulta-afd-siape/classes/functions.php on line 97
			if($rowCount <> 0) {
				$row = $result->fetch(PDO::FETCH_ASSOC);
?>
				<div class="table-responsive">
					<table id="<? echo $id ?>"class="table table-bordered table-striped table-hover">
						<caption>
							<strong><? echo $caption ?><strong>
						</caption>
					<thead>	
				<tr class="info">
<?
				foreach ($row as $field => $value){
					echo "<th>$field</th>";
					// die('<pre>'.print_r($value, 1));
				}				
?>
				</tr>
				</thead>
				<tbody>
<?
				$data = $this->pdo->query($query);
				$data->setFetchMode(PDO::FETCH_ASSOC);
				foreach($data as $row){
					echo "<tr>";
					foreach ($row as $name=>$value){
						if($value == 'QUEBRADA') {
							echo "<td class=\"text-danger danger\"><strong>QUEBRADA</strong></td>";
						} elseif ($value == 'CARREGADA'){
							echo "<td class=\"text-success success\"><strong>CARREGADA</strong></td>";
						} else {
							echo "<td>$value</td>";
						}
					}
				}
?>
				</tr>
				</tbody>
				</table>
				</div>
<?
				return $row;
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
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	}
	
	public function updateUnitAfd($result){
		try {
			$this->pdo->exec("TRUNCATE TABLE tb_afd_unidade");
			
			// INSERT dinâmico do resultado do serviço listarUnidades
			$query = "INSERT INTO tb_afd_unidade(id_unidade, sigla, descricao, dt_inclusao_registro) VALUES";
			// Para cada elemento $values, faça:
			foreach($result as $key => $values){
				$idUnidade = $values->IdUnidade;
				$sigla = $values->Sigla;
				$descricao = $values->Descricao;
				$query .= " ('{$idUnidade}', '{$sigla}', '{$descricao}', current_timestamp),";
			}
			// Tira a última vírgula do values
			$query = substr($query, 0, -1);
			
			$stmt = $this->pdo->exec($query);
			// echo "Total de registros importados: {$stmt}";
?>
			<!-- Modal -->
			<div class="modal-dialog" role="document" id="modalwindow">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closemodal"><span aria-hidden="true">&times;</button>
						<h4 class="modal-title">Informação!</h4>
					</div>
					<div class="modal-body">
						<div class="alert alert-info" role="alert">
							<?php echo "Total de registros importados: <strong>{$stmt}</strong>"; ?>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" id="closemodalbtn">Fechar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
			<?php die; ?>
<?php
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	}
}  