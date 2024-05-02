<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modelo_Pagamento extends CI_Model
{
	const tabela = 'pagamento';

	const CONFIG_RULES = [
		[
			'field' => 'dt_movimento',
			'label' => 'Data de Faturamento',
			'rules' => 'required|exact_length[10]'
		],
		[
			'field' => 'id_tipo_debito',
			'label' => 'Código Tipo de Débito',
			'rules' => 'required|integer'
		],
		[
			'field' => 'saldo_vencido',
			'label' => 'Saldo Vencido',
			'rules' => 'required'
		],
		[
			'field' => 'saldo_vencer',
			'label' => 'Saldo a Vencer',
			'rules' => 'required'
		],
		[
			'field' => 'incluido',
			'label' => 'Incluído',
			'rules' => 'required'
		],
		[
			'field' => 'liquidado',
			'label' => 'Liquidado',
			'rules' => 'required'
		],
		[
			'field' => 'desconto',
			'label' => 'Desconto',
			'rules' => 'required'
		],
		[
			'field' => 'cancelado',
			'label' => 'Cencelado',
			'rules' => 'required'
		],
		[
			'field' => 'baixado',
			'label' => 'Baixado',
			'rules' => 'required'
		],
		[
			'field' => 'despesa',
			'label' => 'Despesa',
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

	public function getPagamentos($filtro, $intervalo = [])
	{
		$where = $this->montaIntervalo($filtro, "p.dt_movimento", $intervalo);

		$query = $this->db
			->select("
				td.descricao,
				IFNULL(SUM(p.saldo_vencido),0) AS saldo_vencido,
				IFNULL(SUM(p.saldo_vencer),0) AS saldo_vencer,
				IFNULL(SUM(p.incluido),0) AS incluido,
				IFNULL(SUM(p.liquidado),0) AS liquidado,
				IFNULL(SUM(p.desconto),0) AS desconto,
				IFNULL(SUM(p.cancelado),0) AS cancelado,
				IFNULL(SUM(p.baixado),0) AS baixado,
				IFNULL(SUM(p.despesa),0) AS despesa				
			")
			->from(self::tabela . ' AS p')
			->join(
				'tipo_debito AS td',
				'td.id = p.id_tipo_debito AND td.chave_empresa = p.chave_empresa'
			)
			->where(
				$where['query'],
				NULL,
				FALSE
			)
			->where('p.' . self::chaveEmpresa, $this->getChaveEmpresa())
			->group_by('p.id_tipo_debito')
			->order_by('td.descricao')
			->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();
		}

		return [];
	}
}
