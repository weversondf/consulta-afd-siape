<?php  
// header('Content-Type: text/html; charset=utf-8');

class Files{

	// Atributo para guardar uma conexão PDO
	private $pdo = null;

	// Atributo onde será guardado o nome da table
	private $table = null;

	// Atributo estático que contém uma instância da própria classe
	private static $files = null;

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
		if(!isset(self::$files)):
			try {
				self::$files = new Files($connect, $table);
			} catch (Exception $e) {
				echo "Erro " . $e->getMessage(); 
			}
		endif;

		return self::$files;
	}

	public function setTableName($table){
		if(!empty($table)){
			$this->table = $table;
		}
	}
	
	public function selectUnitAfd(){
		try {
			ob_end_clean();
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment;filename=exporta-lista-unidades-afd.csv");
			header("Pragma: no-cache");

   			$query = "SELECT id_unidade, sigla, descricao, dt_inclusao_registro FROM tb_afd_unidade;";
			$results = $this->pdo->query($query);
			$results->setFetchMode(PDO::FETCH_ASSOC);
   
            $delimiter = ';';
            $enclosure = '"';
   
            $out = fopen( 'php://output', 'w' );
			// Header file
			fputcsv($out, array('ID UNIDADE', 'SIGLA', 'DESCRIÇÃO', 'DATA INCLUSÃO'), $delimiter, $enclosure);
			// Body file
            foreach ( $results as $result ) {
                fputcsv( $out, $result, $delimiter, $enclosure );
            }
            fclose( $out );			
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