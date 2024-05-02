<?php

class Modelo_Produto extends CI_Model
{
	const tabela = 'produto';

	const CONFIG_RULES = [
		[
			'field' => 'id_produto',
			'label' => 'Código da Condição de Pagamento',
			'rules' => 'required|integer'
		],
		[
			'field' => 'descricao',
			'label' => 'Descrição',
			'rules' => 'required'
		],
		[
			'field' => 'id_produto_str',
			'label' => 'Código do produto padrão SIG',
			'rules' => 'required'
		],
		[
			'field' => 'id_grupo',
			'label' => 'Código Grupo',
			'rules' => 'required|integer'
		],
		[
			'field' => 'id_subgrupo',
			'label' => 'Código Subgrupo',
			'rules' => 'required|integer'
		],
		[
			'field' => 'custo_medio',
			'label' => 'Custo Médio',
			'rules' => 'required'
		],
		[
			'field' => 'unidade_venda',
			'label' => 'Unidade Venda',
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

	public function delete()
	{
		return $this->db
			->where(self::chaveEmpresa, $this->getChaveEmpresa())
			->delete(self::tabela);
	}

	public function saveRows($data)
	{
		return $this->db->insert_batch(self::tabela, $data);
	}
}
