<?php
class Ws
{   
	private $client = null;
	
	public function requestWs($action, $orgaoUpag, $protocoloFormatado, $dataAutuacao, $tipoProcedimento, $especificacao, $interessados){
		$client = Conexao::getSOAP(SOAP);
		$result = array();
		try {
			$siglaSistema = 'IPF@AFD';
			$identificacaoServico = 'ServicoIPF@AFD';
			
			if ($action == 'insert') {
				$assuntos = 701; // 1. - Provisão da Força de Trabalho
				$nivelAcesso = 1; // Restrito
				$client->incluirPasta($siglaSistema, $identificacaoServico, $orgaoUpag, $protocoloFormatado, $dataAutuacao, $tipoProcedimento, $especificacao, $assuntos, $interessados, $nivelAcesso);
			} elseif ($action == 'delete') {
				$client->deletarProcedimento($siglaSistema, $identificacaoServico, $protocoloFormatado);
			}
			$result = "<strong>Resposta do WebService:</strong> {$client->__getLastResponse()}";
			return $result;
		} catch (SoapFault $e) {
			return printf( "O seguinte erro ocorreu ao enviar os dados: Erro[ %s ]: %sn" , $e->getCode() , $e->getMessage() );
		}
	}	
}