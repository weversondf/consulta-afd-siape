<?php
header ('Content-type: text/html; charset=UTF-8');
// URL: 10.209.9.131/afd/controlador-ws/listar-unidades-afd.php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// Servidor 131 Postgres
try {
	$pdoConnect = new PDO('pgsql:host=10.209.9.131;port=5432;dbname=carga_afd_abr2016;user=cgdms;password=senhacgdms'); // Postgres
	$pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// print "Conexão Efetuada com sucesso!";
} catch (PDOException $exc) {
	echo 'Falha ao conectar no banco de dados: '.$exc->getMessage();
	exit();
}

// Consumir o serviço e incluir em tabela no servidor 131
listarUnidadesComInclusaoTabela($pdoConnect);

function listarUnidadesComInclusaoTabela($pdoConnect) {
	// include_once("dbconfig.php");
		
	// Desenvolvimento local
	// $cliente = new SoapClient('http://10.209.9.219/sei/controlador_ws.php?servico=sei', array('trace' => true));

	// Produção com acesso pelo servidor 131
    // $wsdl = "https://afd.planejamento.gov.br/sei/controlador_ws.php?servico=sei";
    // $cliente = new SoapClient($wsdl, array('trace' => true));
	$cliente = new SoapClient('https://afd.planejamento.gov.br//sei//controlador_ws.php?servico=sei',
		array(
			"trace" => true,
			"stream_context" => stream_context_create(
				array(
					'ssl' => array(
						'verify_peer'       => false,
						'verify_peer_name'  => false,
					)
				)
			)
		) 
	);	
	
	// Produção com acesso pelo servidor 219
	// $cliente = new SoapClient('https://afd.planejamento.gov.br//sei//controlador_ws.php?servico=sei',
		// array(
			// "trace" => true,
			// "stream_context" => stream_context_create(
				// array(
					// 'ssl' => array(
						// 'verify_peer'       => false,
						// 'verify_peer_name'  => false,
					// )
				// )
			// )
		// ) 
	// );
	
	try {
		$siglaSistema = 'IPF@AFD';
		$identificacaoServico = 'ServicoIPF@AFD';
		$idSerie = 1;
		$result = $cliente->listarUnidades($siglaSistema, $identificacaoServico, $idSerie);
	} catch (SoapFault $e) {
		// $result[$data->nu_matr_siape] = $e;
		printf( "O seguinte erro ocorreu ao enviar os dados: Erro[ %s ]: %sn" , $e->getCode() , $e->getMessage() );
	}
	// var_dump( $cliente->__getLastResponse() );
	// echo "Resposta do WS: {$cliente->__getLastResponse()}</br>";
	// die('<pre>' . print_r($result, 1));
	
	// Lista os dados na tela
	// foreach($result as $key => $data){
		// echo 'Unidade:'.$data->IdUnidade.' | '.$data->Sigla.' | '.$data->Descricao.'<br>';
	// }
	// die;
	
	// Resolver o ERROR:  duplicate key value violates unique constraint "uk_afd_unidade"
	$pdoConnect->exec("TRUNCATE TABLE tb_afd_unidade");
	echo "<style>
			.arred{
				width: 375px;
				height: 76px;
				background: #31b0d5;
				border-radius: 30px;
				border:1px solid #31b0d5;
			}
			p{
				color: #fff;
				font-family: arial, helvetica, sans-serif;
				font-size: 14px;
				font-weight: bold;
				text-align: center;
			}
		</style>
		<div class=\"arred\"><p>TRUNCATE executado com sucesso!</p>";

	// Inserir os registros no bd carga_afd_abr2016 do servidor 131
	// Início da consulta
	$pdoQuery = "INSERT INTO tb_afd_unidade(id_unidade, sigla, descricao, dt_inclusao_registro) VALUES";

	// Para cada elemento $data, faça:
	foreach ($result as $key => $data) {
		$idUnidade = $data->IdUnidade;
		$sigla = $data->Sigla;
		$descricao = $data->Descricao;
		// Monta o value de cada unidade
		$pdoQuery .= " ('{$idUnidade}', '{$sigla}', '{$descricao}', current_timestamp),";
	}
	// Tira a última vírgula do values
	$pdoQuery = substr($pdoQuery, 0, -1);
	// echo $pdoQuery;
	
	$countRows = $pdoConnect->exec($pdoQuery);
	echo "<p>{$countRows} linhas incluídas!</p></div>";
	echo "<input type=\"button\" value=\"Voltar\" onClick=\"history.go(-1)\"> ";
	
	$pdoConnect = null;        // Disconnect
}
// die('<pre>' . print_r($cliente, 1));

// Consumir o serviço
// listarUnidades(1);

function listarUnidades($idSerie) {
	// Desenvolvimento local
	// $cliente = new SoapClient('http://10.209.9.219/sei/controlador_ws.php?servico=sei', array('trace' => true));

	// Produção com acesso pelo servidor 131
    $wsdl = "https://afd.planejamento.gov.br/sei/controlador_ws.php?servico=sei";
    $cliente = new SoapClient($wsdl, array('trace' => true));
	
	// Produção com acesso pelo servidor 219
	// $cliente = new SoapClient('https://afd.planejamento.gov.br//sei//controlador_ws.php?servico=sei',
		// array(
			// "trace" => true,
			// "stream_context" => stream_context_create(
				// array(
					// 'ssl' => array(
						// 'verify_peer'       => false,
						// 'verify_peer_name'  => false,
					// )
				// )
			// )
		// ) 
	// );

	try {
		$siglaSistema = 'IPF@AFD';
		$identificacaoServico = 'ServicoIPF@AFD';
		$result = $cliente->listarUnidades($siglaSistema, $identificacaoServico, $idSerie);
	} catch (SoapFault $e) {
		// $result[$data->nu_matr_siape] = $e;
		printf( "O seguinte erro ocorreu ao enviar os dados: Erro[ %s ]: %sn" , $e->getCode() , $e->getMessage() );
	}
	// var_dump( $cliente->__getLastResponse() );
	// echo "Resposta do WS: {$cliente->__getLastResponse()}</br>";
	
	foreach($result as $key => $data){
		echo 'Unidade:'.$data->IdUnidade.' | '.$data->Sigla.' | '.$data->Descricao.'<br>';
	}
	// die('<pre>' . print_r($result, 1));
}
?>
