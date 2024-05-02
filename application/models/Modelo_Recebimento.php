<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modelo_Recebimento extends CI_Model
{
	const tabela = 'recebimento';

	const CONFIG_RULES = [
		[
			'field' => 'dt_movimento',
			'label' => 'Data de Faturamento',
			'rules' => 'required|exact_length[10]'
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
			'field' => 'receita',
			'label' => 'Receita',
			'rules' => 'required'
		],
		# tipos de documento: 1 - Duplicatas; 2 - Cupons; 3 - Notas; 4 - Cartao; 5 - Cheques
		[
			'field' => 'id_tipo_documento',
			'label' => 'Tipo de Docucmento',
			'rules' => 'required|in_list[1,2,3,4,5]'
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

	public function getRecebimentos($filtro, $intervalo = [])
	{
		$where = $this->montaIntervalo($filtro, "r.dt_movimento", $intervalo);

		$query = $this->db
			->select("
			
				r.id_tipo_documento AS tipo_documento,
				IFNULL(`p`.`descricao`, 0) AS `portador`,
				IFNULL(SUM(r.saldo_vencido), 0) AS saldo_vencido,
				IFNULL(SUM(r.saldo_vencer), 0) AS saldo_vencer,
				IFNULL(SUM(r.incluido), 0) AS incluido,
				IFNULL(SUM(r.liquidado), 0) AS liquidado,
				IFNULL(SUM(r.desconto), 0) AS desconto,
				IFNULL(SUM(r.cancelado), 0) AS cancelado,
				IFNULL(SUM(r.baixado), 0) AS baixado,
				IFNULL(SUM(r.receita), 0) AS receita				
			")
			->from(self::tabela . ' AS r')
			->join(
				'portador AS p',
				'p.id = r.id_portador AND p.chave_empresa = r.chave_empresa',
				'left'
			)
			->where(
				$where['query'],
				NULL,
				FALSE
			)
			->where('r.' . self::chaveEmpresa, $this->getChaveEmpresa())
			->group_by('r.id_tipo_documento, r.id_portador')
			->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();
		}

		return [];
	}
}
