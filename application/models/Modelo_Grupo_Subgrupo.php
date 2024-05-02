<?php

class Modelo_Grupo_Subgrupo extends CI_Model
{
	const tabela = 'grupo_subgrupo';

	const CONFIG_RULES = [
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
