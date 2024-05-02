<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modelo_Estoque extends CI_Model
{
	const tabela = 'estoque';

	const CONFIG_RULES = [
		[
			'field' => 'id_produto',
			'label' => 'Código do Produto',
			'rules' => 'required'
		],
		[
			'field' => 'dt_movimento',
			'label' => 'Data de Faturamento',
			'rules' => 'required|exact_length[10]'
		],
		[
			'field' => 'estoque_inicial_customedio',
			'label' => 'Estoque Inicial Custo Médio',
			'rules' => 'required'
		],
		[
			'field' => 'entrada_customedio',
			'label' => 'Entrada Custo Médio',
			'rules' => 'required'
		],
		[
			'field' => 'saida_customedio',
			'label' => 'Saída Custo Média',
			'rules' => 'required'
		],
		[
			'field' => 'estoque_final_customedio',
			'label' => 'Estoque Final Custo Médio',
			'rules' => 'required'
		],
		[
			'field' => 'estoque_inicial_reposicao',
			'label' => 'Estoque Inicial Reposição',
			'rules' => 'required'
		],
		[
			'field' => 'entrada_reposicao',
			'label' => 'Entrada Reposição',
			'rules' => 'required'
		],
		[
			'field' => 'saida_reposicao',
			'label' => 'Saída Reposição',
			'rules' => 'required'
		],
		[
			'field' => 'estoque_final_reposicao',
			'label' => 'Estoque Final Reposição',
			'rules' => 'required'
		],
		[
			'field' => 'estoque_inicial_quantidade',
			'label' => 'Estoque Inicial Quantidade',
			'rules' => 'required'
		],
		[
			'field' => 'entrada_quantidade',
			'label' => 'Entrada Quantidade',
			'rules' => 'required'
		],
		[
			'field' => 'saida_quantidade',
			'label' => 'Saída Quantidade',
			'rules' => 'required'
		],
		[
			'field' => 'estoque_final_quantidade',
			'label' => 'Estoque Final Quantidade',
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

	public function getResumoEstoque($filtro, $intervalo = [])
	{
		$sPrincipal = $this->montaIntervalo($filtro, "e.dt_movimento", $intervalo);

		return $this->db
			->select("
				e.id_produto,
				UPPER(`p`.`descricao`) AS descricao,
				CONCAT(LPAD(g.id_grupo, 2, '0'), '-', g.descricao) AS grupo,
				CONCAT(LPAD(sbg.id_subgrupo, 2, '0'), '-', sbg.descricao) AS subgrupo,
				e.estoque_inicial_customedio,
				e.estoque_inicial_reposicao,
				e.estoque_inicial_quantidade,
				e.entrada_customedio,
				e.entrada_reposicao,
				e.entrada_quantidade,
				e.saida_customedio,
				e.saida_reposicao,
				e.saida_quantidade,
				e.estoque_final_customedio,
				e.estoque_final_reposicao,
				e.estoque_final_quantidade
			")
			->from('estoque AS e')
			->join(
				'produto AS p',
				'p.id_produto = e.id_produto AND e.chave_empresa = p.chave_empresa'
			)
			->join(
				'grupo_subgrupo AS g',
				'g.id_grupo = p.id_grupo AND g.chave_empresa = e.chave_empresa AND g.id_subgrupo = 0'
			)
			->join(
				'grupo_subgrupo AS sbg',
				'sbg.id_subgrupo = p.id_subgrupo AND sbg.chave_empresa = e.chave_empresa AND sbg.id_subgrupo <> 0'
			)
			->where(
				$sPrincipal['query'],
				NULL,
				FALSE
			)
			->where('e.chave_empresa', $this->getChaveEmpresa())
			->group_by('e.id_produto')
			->order_by('p.descricao')
			->get()
			->result_array();
	}

	public function getMovimentoDetalhado($filtro, $intervalo = [], $grupo, $tipo = "E")
	{
		$sPrincipal = $this->montaIntervalo($filtro, "e.dt_movimento", $intervalo);

		if ($tipo === "E") {

			$this->db->select("
				e.id_produto,
				UPPER(`p`.`descricao`) AS descricao,
				e.entrada_customedio,
				e.entrada_reposicao,
				e.entrada_quantidade
			");
		} else {

			$this->db->select("
				e.id_produto,
				UPPER(`p`.`descricao`) AS descricao,
				e.saida_customedio,
				e.saida_reposicao,
				e.saida_quantidade
			");
		}

		return $this->db->from('estoque AS e')
			->join(
				'produto AS p',
				'p.id_produto = e.id_produto AND e.chave_empresa = p.chave_empresa'
			)
			->where(
				$sPrincipal['query'],
				NULL,
				FALSE
			)
			->where($tipo === 'E' ? 'e.entrada_quantidade >' : 'e.saida_quantidade >', 0)
			->where('p.id_grupo', (int) $grupo)
			->where('e.chave_empresa', $this->getChaveEmpresa())
			->group_by('e.id_produto')
			->order_by('p.descricao')
			->get()
			->result_array();
	}
}
