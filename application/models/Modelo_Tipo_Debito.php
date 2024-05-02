<?php

class Modelo_Tipo_Debito extends CI_Model
{
	const tabela = 'tipo_debito';

	const CONFIG_RULES = [
		[
			'field' => 'id',
			'label' => 'Código Tipo de Débito',
			'rules' => 'required|integer'
		],
		[
			'field' => 'descricao',
			'label' => 'Descrição',
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
