<?php
class select
{
	public function dadosCadastraisServidoresNomeCpf($search, $type){
		if ($type == 'S'){
			$query = "SELECT co_matsiape AS \"Matrícula\",
					   fn_formatar_cpf(co_cpf) AS \"CPF\",
					   CASE
						   WHEN co_dataentrada = '00000000' THEN '00/00/0000'
						   ELSE to_char(co_dataentrada::date,'DD/MM/YYYY')
					   END AS \"Dt. Entrada\",
					   co_regimejur AS \"Regime Jur.\",
					   co_situacaoservidor  AS \"Situação\",
					   ds_nomeservidor  AS \"Nome\",
					   co_upag  AS \"Upag\",
					   oco_exc  AS \"Ocorrência\",
					   ds_identific_unica_titular  AS \"Identif. Única\",
					   to_char(dt_inclusao_registro,'DD/MM/YYYY HH24:MI:SS') AS \"Dt. Importação Reg.\"
					FROM siape_cadastro.tp_6_dados_cadastrais_servidores
		            WHERE (ds_nomeservidor LIKE UPPER(fn_remove_acento(REPLACE('{$search}',' ','%')))
						   OR co_cpf LIKE fn_remove_pontos_cpf('{$search}%')
						   OR co_matsiape LIKE '%{$search}')
					ORDER BY ds_nomeservidor;";
		} elseif ($type == 'I') {
			$query = "SELECT siape_inst AS \"Matrícula Inst.\"
							, siape_ben AS \"Matrícula Ben.\"
							, nome_ben AS \"Nome Ben.\"
							, fn_formatar_cpf(cpf_ben) AS \"Cpf Ben.\"
							, uorg_contr_ben AS \"Uorg Contr. Ben.\"
							, uorg_local_ben AS \"Uorg local Ben.\"
							, oco_excl_pen AS \"Ocorrência Pen.\"
							, to_char(dt_inclusao_registro,'DD/MM/YYYY HH24:MI:SS') AS \"Dt. Importação Reg.\"
                      FROM siape_cadastro.tp_7_dados_cadastrais_de_pensionistas
					WHERE (nome_ben LIKE UPPER(fn_remove_acento(REPLACE('{$search}',' ','%')))
						   OR cpf_ben LIKE fn_remove_pontos_cpf('{$search}%')
						   OR siape_ben LIKE '%{$search}'
						   OR siape_inst LIKE '%{$search}')
                    ORDER BY nome_ben";
	    }
		return $query;
	}
	
	public function dadosOrgao($search){
		$query = "SELECT co_orgao AS \"Upag\"
					, ds_orgao  AS \"Nome do Órgão\"
					, ds_sigla  AS \"Sigla\"
					, sit_orgao AS \"Situação\"
					, to_char(dt_inclusao_reg,'DD/MM/YYYY HH24:MI:SS') AS \"Dt. Importação Reg.\"
				FROM siape_cadastro.tp_2_dados_orgaos
				WHERE (co_orgao = '{$search}'
					   OR ds_orgao LIKE upper(fn_remove_acento('{$search}%')))
				ORDER BY ds_orgao";	
		return $query;
	}
	
	public function dadosUpag($search){
		$query = "SELECT co_orgao AS \"Órgão\"
						, LPAD(co_upag,9,'0')::varchar(9) AS \"Upag\"
						, CASE
                          WHEN descricao IS NULL THEN '<span class=\"label label-danger\">Excluída no SIAPE ou não cadastrada no AFD!</span>'
                          ELSE descricao
                          END AS \"Upag/Unidade AFD\" 
						, fn_field_mask(ds_cep, 'cep') AS \"Cep\"
						, ds_cidade AS \"Cidade\"
						, lower(ds_email) AS \"E-mail\"
						, ds_telefone AS \"Telefone\"
						, ds_logradouro AS \"Logradouro\"
				  FROM  siape_cadastro.tp_3_dados_upags up
				  LEFT JOIN tb_afd_unidade un ON up.co_orgao || up.co_upag = un.sigla
					WHERE (co_orgao || co_upag = '{$search}'
						   OR co_upag = '{$search}'
						   OR descricao LIKE upper(fn_remove_acento('{$search}%')))
				  ORDER BY co_orgao, co_upag";
		return $query;
	}
	
	public function dadosOrgaoUpag($search){
		$query = "SELECT co_orgao AS \"Órgão\"
						, LPAD(co_upag,9,'0')::varchar(9) AS \"Upag\"
						, CASE
                          WHEN descricao IS NULL THEN '<span class=\"label label-danger\">Excluída no SIAPE ou não cadastrada no AFD!</span>'
                          ELSE descricao
                          END AS \"Upag/Unidade AFD\" 
						, fn_field_mask(ds_cep, 'cep') AS \"Cep\"
						, ds_cidade AS \"Cidade\"
						, lower(ds_email) AS \"E-mail\"
						, ds_telefone AS \"Telefone\"
						, ds_logradouro AS \"Logradouro\"
				  FROM  siape_cadastro.tp_3_dados_upags up
				  LEFT JOIN tb_afd_unidade un ON up.co_orgao || up.co_upag = un.sigla
					WHERE (co_orgao = '{$search}'
						   OR descricao LIKE upper(fn_remove_acento('{$search}%')))
				  ORDER BY co_orgao, co_upag";
		return $query;
	}
	
	public function dadosCadastraisServidoresInstituidoresOrgaoUpag($search, $type){
		$query = "SELECT nu_orgao_upag AS \"Órgão e Upag\"
					, co_orgao_matr AS \"Matrícula\"
					, CASE
						   WHEN da_ocor_ingr_orgao_serv = '00000000' THEN '00/00/0000'
						   ELSE to_char(da_ocor_ingr_orgao_serv::date,'DD/MM/YYYY')
					   END AS \"Data de Admissão\"
					, fn_formatar_cpf(nu_cpf) AS \"CPF\"
					, no_servidor AS \"Nome\"
					, regime_afd AS \"Reg. Afd\"
					, sg_regime_situacao AS \"Reg. Sit.\"
					, tipo AS \"Tipo\"
					, to_char(dt_inclusao_registro,'DD/MM/YYYY HH24:MI:SS') AS \"Dt. Importação Reg.\"
				  FROM tb_siape_servidor_instituidor ";
		if ($type == 'O'){
				$query .= "WHERE LEFT(nu_orgao_upag, 5) = '{$search}' ";
		} elseif ($type == 'U') {
				$query .= "WHERE (nu_orgao_upag = '{$search}'
				           OR nu_orgao_upag LIKE '%{$search}') ";
	    }
		$query .= "ORDER BY co_orgao_matr";
		return $query;
	}	

	public function dadosCadastraisServidores($search_in, $search_out){
		// Com ocorrência de exclusão
		$query = "SELECT co_matsiape AS \"Matrícula\",
					   fn_formatar_cpf(co_cpf) AS \"Cpf\",
					   CASE
						   WHEN co_dataentrada = '00000000' THEN '00/00/0000'
						   ELSE to_char(co_dataentrada::date,'DD/MM/YYYY')
					   END AS \"Dt. Entrada\",
					   co_regimejur AS \"Regime Jur.\",
					   co_situacaoservidor  AS \"Situação\",
					   ds_nomeservidor  AS \"Nome\",
					   co_upag  AS \"Upag\",
					   oco_exc  AS \"Ocorrência\",
					   ds_identific_unica_titular  AS \"Identif. Única\",
					   to_char(dt_inclusao_registro,'DD/MM/YYYY HH24:MI:SS') AS \"Dt. Importação Reg.\"
				FROM siape_cadastro.tp_6_dados_cadastrais_servidores
				WHERE co_dataentrada BETWEEN to_char('{$search_in}'::date,'YYYYMMDD') AND to_char('{$search_out}'::date,'YYYYMMDD')
				ORDER BY ds_nomeservidor;";
		return $query;	
	}

	public function dadosUnidadesAfd($search){
		$query = "SELECT id_unidade AS \"Id\",
					sigla AS \"Sigla\",
					descricao AS \"Descrição\"
					FROM tb_afd_unidade 
					WHERE (descricao LIKE UPPER('%{$search}%') OR sigla LIKE UPPER('{$search}%'))
					ORDER BY sigla";
		return $query;
	}
	
	public function dadosRegimeSituacaoAfd($search){
	    $query = "SELECT
				regime_situacao_siape AS \"Regime e Situação SIAPE\"
				, descricao_siape AS \"Descrição Regime e Situação SIAPE\"
				, regime_afd AS \"Regime AFD\"
				, to_char(dt_inclusao_registro,'DD/MM/YYYY HH24:MI:SS') AS \"Dt. Importação Reg.\"
			  FROM correlacao_regime_siape_afd
			  WHERE (descricao_siape LIKE UPPER('%{$search}%')
					OR regime_situacao_siape LIKE UPPER('{$search}%')
					OR regime_afd LIKE UPPER('{$search}%'))
			  ORDER BY regime_situacao_siape";
        return $query;
	}
	
	public function dadosAnexosExcluidos($search, $filter){
	    $query = "SELECT protocolo AS \"Protocolo\", 
                         sigla_unidade AS \"Órgão e Upag/Unidade\", 
                         desc_unidade AS \"Descrição\", 
                         nome_arquivo AS \"Nome do arquivo\"
                  FROM sustentacao.tb_afd_exclusao_documentos ";
		if ($filter == true) {
			$query .= "WHERE protocolo = {$search}";
		}
        return $query;
	}
	
	public function datasetServidorInstituidor($table, $search){
		$query = "SELECT nu_orgao_upag AS \"Órgão e UPAG\",
					co_orgao_matr AS \"Matrícula\",
					CASE
						WHEN da_ocor_ingr_orgao_serv = '00000000' THEN '00/00/0000'
						ELSE TO_CHAR(da_ocor_ingr_orgao_serv::date,'DD/MM/YYYY') 
					END AS \"Dt. Ingr. Órgão\",
					CONCAT(SUBSTR(nu_cpf,1,3),'.',SUBSTR(nu_cpf,4,3),'.',SUBSTR(nu_cpf,7,3),'-',SUBSTR(nu_cpf,10,2)) AS \"CPF\",
					no_servidor AS \"Nome\",
					regime_afd AS \"Reg. AFD\",
					sg_regime_situacao AS \"Reg. Sit.\"
				FROM {$table}
				WHERE co_orgao_matr = '{$search}'
				OR RIGHT(co_orgao_matr, 7) = '{$search}'";
        return $query;
	}
	
	public function datasetWebserviceServidorInstituidor($table, $search){
		$query = "SELECT 
					nu_orgao_upag,
					co_orgao_matr,
					CASE
						WHEN da_ocor_ingr_orgao_serv = '00000000' THEN '0000-00-00'
						ELSE TO_CHAR(da_ocor_ingr_orgao_serv::date,'YYYY-MM-DD') 
					END AS da_ocor_ingr_orgao_serv,
					nu_cpf,
					no_servidor,
					regime_afd
				FROM {$table}
				WHERE co_orgao_matr = '{$search}'
				OR RIGHT(co_orgao_matr, 7) = '{$search}'";
		// echo $query ; die;
        return $query;
	}
	
	public function datasetProducaoAfd($table, $search){
		$query = "SELECT DISTINCT protocolo.protocolo_formatado AS Matrícula,
					INSERT( INSERT( INSERT( REPLACE ( REPLACE(protocolo.descricao, '-', ''), '.', '') , 10, 0, '-' ), 7, 0, '.' ), 4, 0, '.' ) AS CPF,
					DATE_FORMAT(protocolo.dta_geracao,'%d/%m/%Y') AS \"Data de Admissão\",
					contato.nome AS Nome,			   
					CONCAT(unidade.sigla, ' ', unidade.descricao) AS Unidade,
					CASE
						WHEN atividade.id_protocolo IS NULL THEN ('QUEBRADA')
						ELSE ('CARREGADA')
					END AS Situação
				FROM protocolo
				JOIN unidade ON protocolo.id_unidade_geradora = unidade.id_unidade
				LEFT JOIN atividade ON protocolo.id_protocolo = atividade.id_protocolo
				JOIN participante ON protocolo.id_protocolo = participante.id_protocolo
				JOIN contato ON participante.id_contato = contato.id_contato
				WHERE (protocolo_formatado = '{$search}'
				OR RIGHT(protocolo_formatado, 7) = '{$search}')
				AND atividade.id_protocolo IS NOT NULL";
		return $query;
	}
}