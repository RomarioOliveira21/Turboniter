<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modelo_Fat_CPagamento extends CI_Model
{
	const tabela = 'faturamento_condicao_pag';

	const CONFIG_RULES = [
		[
			'field' => 'id_condicao_pag',
			'label' => 'Código da Condição de Pagamento',
			'rules' => 'required|integer'
		],
		[
			'field' => 'dt_movimento',
			'label' => 'Data de Faturamento',
			'rules' => 'required|exact_length[10]'
		],
		[
			'field' => 'vlr_venda',
			'label' => 'Valor Venda',
			'rules' => 'required'
		],
		[
			'field' => 'vlr_custo',
			'label' => 'Valor Custo',
			'rules' => 'required'
		]
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function getConfigRules()
	{
		return self::CONFIG_RULES;
	}

	/**
	 * .
	 */
	public function save(array $data)
	{

		$this->db->trans_begin();

		$this->db->insert_batch(self::tabela, $data);

		if ($this->db->trans_status() === FALSE) {
			// Se a transação falhar, desfaz as alterações
			$this->db->trans_rollback();

			return [

				'error'   => true,
				'message' => $this->db->error()
			];
		} else {
			// Se a transação for bem-sucedida, confirma as alterações
			$this->db->trans_commit();

			return [

				'error'   => false,
				'message' => 'Dados atualizados com sucesso!'
			];
		}
	}

	public function delete(string $dtMovimento)
	{
		$result = $this->db
			->where(self::chaveDtMovimento, $dtMovimento)
			->where(self::chaveEmpresa, $this->getChaveEmpresa())
			->delete(self::tabela);

		if ($result) {

			return TRUE;
		} else {

			return FALSE;
		}
	}

	public function getDataExportacao($min = false)
	{
		if ($min) {

			$this->db->select_min(self::chaveDtMovimento);
		} else {

			$this->db->select_max(self::chaveDtMovimento);
		}

		$query = $this->db->where(self::chaveEmpresa, $this->getChaveEmpresa())->get(self::tabela);

		if ($query->num_rows() > 0) {

			return $query->row()->{self::chaveDtMovimento};
		} else {

			return [];
		}
	}

	public function getVendas(int $filtro, array $intervalo = [])
	{
		$res = $this->montaIntervalo($filtro, "dt_movimento", $intervalo);

		$vendas = $this->db
			->select('
				SUM(fcp.vlr_venda) AS vlr_venda,
				fp.tipo,
				(
					SELECT MAX(sub.dt_movimento) 
					FROM faturamento_condicao_pag AS sub
					WHERE sub.chave_empresa = fcp.chave_empresa
				) AS ultimo_movimento
			')
			->from('faturamento_condicao_pag AS fcp')
			->join(
				'forma_pagamento AS fp',
				'fp.id = fcp.id_condicao_pag AND fp.chave_empresa = fcp.chave_empresa'
			)
			->where(
				$res['query'],
				NULL,
				FALSE
			)
			->where('fcp.chave_empresa', $this->getChaveEmpresa())
			->group_by('fp.tipo')
			->order_by('fcp.vlr_venda', 'DESC')
			->get()
			->result_array();

		return [
			'vendas'  => $vendas,
			'periodo' => $res['textoPeriodo']
		];
	}

	public function getComparativoVendas($dia)
	{
		$cnpj        = $this->getChaveEmpresa();
		$anoAnterior = date('Y', strtotime($dia)) - 1;
		$ano         = date('Y', strtotime($dia));
		$mes         = date('m', strtotime($dia));
		$objData     = new DateTime("$ano-$mes-01");
		$diaInicial  = $objData->format('Y-m-d');
		$mesAnterior = get_mes_anterior((int)date('m', strtotime($dia)));

		$query                = $this->db->query("CALL sp_relatorio_faturamento('$dia', '$cnpj')");
		$vendas_tipo_condicao = $query->result_array();
		mysqli_next_result($this->db->conn_id);
		$query->free_result();

		$query2 = "SELECT 
				fp.id_produto,
				p.descricao,
				CONCAT(LPAD(gp.id_grupo, 2, '0'), '-', gp.descricao) AS grupo,
				CONCAT(LPAD(sgp.id_subgrupo, 2, '0'), '-', sgp.descricao) AS subgrupo,
				(
					SELECT IFNULL(SUM(sb5.vlr_venda), 0) FROM faturamento_produto AS sb5
					JOIN produto AS p5 ON p5.id_produto = sb5.id_produto AND p5.chave_empresa = sb5.chave_empresa
					WHERE sb5.chave_empresa = fp.chave_empresa
					AND sb5.id_produto = fp.id_produto
					AND sb5.dt_movimento = '$dia'
				) AS dia,
				(
					SELECT IFNULL(SUM(sb1.vlr_venda), 0) FROM faturamento_produto AS sb1
					JOIN produto AS p1 ON p1.id_produto = sb1.id_produto AND p1.chave_empresa = sb1.chave_empresa
					WHERE sb1.chave_empresa = fp.chave_empresa
					AND sb1.id_produto = fp.id_produto
					AND sb1.dt_movimento BETWEEN '$diaInicial' AND '$dia'
				) AS mes,
				(
					SELECT IFNULL(SUM(vlr_venda), 0) FROM faturamento_produto AS sb2
					JOIN produto AS p2 ON p2.id_produto = sb2.id_produto AND p2.chave_empresa = sb2.chave_empresa
					WHERE sb2.chave_empresa = fp.chave_empresa
					AND sb2.id_produto = fp.id_produto
					AND YEAR(sb2.dt_movimento) = $anoAnterior AND MONTH(sb2.dt_movimento) = $mesAnterior 
				) AS mes_anterior,
				(
					SELECT IFNULL(SUM(vlr_venda), 0) FROM faturamento_produto AS sb3
					JOIN produto AS p3 ON p3.id_produto = sb3.id_produto AND p3.chave_empresa = sb3.chave_empresa
					WHERE sb3.chave_empresa = fp.chave_empresa
					AND sb3.id_produto = fp.id_produto
					AND sb3.dt_movimento BETWEEN '$diaInicial' AND '$dia'
				) AS ano,
				(
					SELECT IFNULL(SUM(vlr_venda), 0) FROM faturamento_produto AS sb4
					JOIN produto AS p4 ON p4.id_produto = sb4.id_produto AND p4.chave_empresa = sb4.chave_empresa
					WHERE sb4.chave_empresa = fp.chave_empresa
					AND sb4.id_produto = fp.id_produto
					AND YEAR(sb4.dt_movimento) = $anoAnterior
				) AS ano_anterior
			FROM faturamento_produto AS fp
			JOIN produto AS p ON p.id_produto = fp.id_produto AND p.chave_empresa = fp.chave_empresa
			JOIN grupo_subgrupo AS gp ON gp.id_grupo = p.id_grupo AND p.chave_empresa = gp.chave_empresa AND gp.id_subgrupo = 0
			JOIN grupo_subgrupo AS sgp ON sgp.id_subgrupo = p.id_subgrupo AND p.chave_empresa = sgp.chave_empresa AND sgp.id_grupo = gp.id_grupo
			AND fp.chave_empresa = '$cnpj'
			GROUP BY fp.id_produto
		";

		$vendas_grupo = $this->db->query($query2)->result_array();

		return [

			'vendas_tipo_condicao' => $vendas_tipo_condicao,
			'vendas_grupo'         => $vendas_grupo
		];
	}
}
