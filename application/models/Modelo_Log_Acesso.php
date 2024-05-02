<?php

class Modelo_Log_Acesso extends CI_Model
{
	const tabela = 'log_acesso';

	public function __construct()
	{
		parent::__construct();
	}

	public function save($cnpj)
	{
		date_default_timezone_set('America/Sao_Paulo');

		$this->db->insert(self::tabela, [

			'chave_empresa'  => $cnpj,
			'data_interacao' => date('Y-m-d H:i:s')
		]);
	}
}
