<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modelo_Fat_Produto extends CI_Model
{
	const tabela = 'faturamento_produto';

	const CONFIG_RULES = [
		[
			'field' => 'id_produto',
			'label' => 'Código do Produto',
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

	public function getValorTotalEstoque($filtro, $intervalo = [])
	{
		$res = $this->montaIntervalo($filtro, 'dt_movimento', $intervalo);

		$query = $this->db
			->select('
				SUM(vlr_custo) AS vlr_custo
			')
			->where(
				$res['query'],
				NULL,
				FALSE
			)
			->where(self::chaveEmpresa, $this->getChaveEmpresa())
			->get(self::tabela);

		if ($query->num_rows() > 0) {

			return $query->row()->vlr_custo;
		}

		return 0;
	}
}
