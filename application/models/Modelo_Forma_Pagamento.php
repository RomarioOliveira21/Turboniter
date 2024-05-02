<?php

class Modelo_Forma_Pagamento extends CI_Model
{
	const tabela = 'forma_pagamento';

	const CONFIG_RULES = [
		[
			'field' => 'id',
			'label' => 'Código da Condição de Pagamento',
			'rules' => 'required|integer'
		],
		[
			'field' => 'descricao',
			'label' => 'Descrição',
			'rules' => 'required'
		],
		[
			'field' => 'tipo',
			'label' => 'Tipo',
			'rules' => 'required|integer'
		],
		[
			'field' => 'parcelas',
			'label' => 'Quantidade de Parcelas',
			'rules' => 'required|integer'
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
