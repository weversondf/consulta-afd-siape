<?php
define('SIGLA_SISTEMA', 'IPF@AFD');
define('IDENTIFICACAO_SERVICO', 'ServicoIPF@AFD');
header ('Content-type: text/html; charset=UTF-8');
date_default_timezone_set('America/Sao_Paulo');

class Ws
{
	private $soap = null;
	
	public function __construct() {  
		$client = new SoapClient('https://afd.planejamento.gov.br/sei/controlador_ws.php?servico=sei', 
		array(
			'trace' => 1, 
			'exceptions' => true,
			'cache_wsdl' => WSDL_CACHE_NONE, 
			'features' => SOAP_SINGLE_ELEMENT_ARRAYS
			)
		);
		$this->soap = $client;
	} 
	
	public function incluirPasta($orgaoUpag, $protocoloFormatado, $dataAutuacao, $tipoProcedimento, $especificacao, $interessados){
		$result = array();
		try {
			$siglaSistema         = SIGLA_SISTEMA;
			$identificacaoServico = IDENTIFICACAO_SERVICO;
			$assuntos = 701; // 1. - Provisão da Força de Trabalho
			$nivelAcesso = 1; // Restrito
			$result[$protocoloFormatado] = $this->soap->incluirPasta($siglaSistema, $identificacaoServico, $orgaoUpag, $protocoloFormatado, $dataAutuacao, $tipoProcedimento, $especificacao, $assuntos, $interessados, $nivelAcesso);

			foreach($result as $key => $value){
				// Gravar log em arquivo texto
				$log = "Matrícula: {$key} | Resposta do WS: ".strip_tags($this->soap->__getLastResponse()).PHP_EOL;
				file_put_contents("../log/webservices.php.log", $log, FILE_APPEND);
			}
			// die('<pre>' . print_r($values, 1));

			$result = strip_tags($this->soap->__getLastResponse());
			// echo $result.'<br>';
			if (strpos($result, 'Procedimento') !== false) {
				$result = '<div class="alert alert-success">
							  <strong>Atenção!</strong><br>Pasta incluída com sucesso!
							</div>';
			} elseif (strpos($result, 'fk_atributo_andamento_atividad') !== false) {
				$result = '<div class="alert alert-danger">
							  <strong>Atenção!</strong><br>Pasta do servidor/instituidor não pode ser excluída por ter documento anexo no AFD!
							</div>';			
			} elseif (strpos($result, '1062 Duplicate entry') !== false) {
				$result = '<div class="alert alert-danger">
							  <strong>Atenção!</strong><br>Pasta do servidor/instituidor já cadastrada no AFD!
							</div>';
			} elseif (strpos($result, 'OrgaoUpagUpag') !== false) {
				$result = '<div class="alert alert-danger">
							  <strong>Atenção!</strong><br>Unidade não cadastrada no AFD!
							</div>';				
			// echo $result; die;
			}
			return $result;
		} catch (SoapFault $e) {
			return printf( "O seguinte erro ocorreu ao enviar os dados: Erro[ %s ]: %s".PHP_EOL , $e->getCode() , $e->getMessage() );
		}
	}
	
	public function incluirServidor($SiglaUnidade, $TipoAssentamento, $Interessado, $CPF, $DataIngressoCargo, $Assentamento){
		$result = array();
		try {
			$siglaSistema         = SIGLA_SISTEMA;
			$identificacaoServico = IDENTIFICACAO_SERVICO;
			$this->soap->incluirServidor($siglaSistema, $identificacaoServico, $SiglaUnidade, $TipoAssentamento, $Interessado, $CPF, $DataIngressoCargo, $Assentamento);
			$result = "<strong>Resposta do WebService:</strong> {$this->soap->__getLastResponse()}";
			return $result;
		} catch (SoapFault $e) {
			return printf( "O seguinte erro ocorreu ao enviar os dados: Erro[ %s ]: %s".PHP_EOL , $e->getCode() , $e->getMessage() );
		}
	}	
	
	public function excluirPasta($protocoloFormatado){
		$result = array();
		try {
			$siglaSistema         = SIGLA_SISTEMA;
			$identificacaoServico = IDENTIFICACAO_SERVICO;
			$this->soap->deletarProcedimento($siglaSistema, $identificacaoServico, $protocoloFormatado);
			$result = strip_tags($this->soap->__getLastResponse());
			// echo $result.'<br>';
			if (strpos($result, 'deletada') !== false) {
				$result = '<div class="alert alert-success">
							  <strong>Atenção!</strong><br>Pasta excluída com sucesso!
							</div>';
			} elseif (strpos($result, 'fk_atributo_andamento_atividad') !== false) {
				$result = '<div class="alert alert-danger">
							  <strong>Atenção!</strong><br>Pasta do servidor/instituidor não pode ser excluída por ter anexo!
							</div>';
			} elseif (strpos($result, 'errorIdProtocolo') !== false) {
				$result = '<div class="alert alert-danger">
							  <strong>Atenção!</strong><br>Pasta do servidor/instituidor não pode ser encontrada!
							</div>';				
			// echo $result; die;
			}
			return $result;
		} catch (SoapFault $e) {
			return printf( "O seguinte erro ocorreu ao enviar os dados: Erro[ %s ]: %s".PHP_EOL , $e->getCode() , $e->getMessage() );
		}
	}
	
	public function listarUnidades($idSerie) {
		$result = array();
		try {
			$siglaSistema         = SIGLA_SISTEMA;
			$identificacaoServico = IDENTIFICACAO_SERVICO;
			$result = $this->soap->listarUnidades($siglaSistema, $identificacaoServico, $idSerie);
			return $result;
		} catch (SoapFault $e) {
			return printf( "O seguinte erro ocorreu ao enviar os dados: Erro[ %s ]: %s".PHP_EOL , $e->getCode() , $e->getMessage() );
		}
	}
}