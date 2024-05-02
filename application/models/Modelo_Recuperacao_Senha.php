<?php

class Modelo_Recuperacao_Senha extends CI_Model
{
	const tabela             = 'recuperacao_senha';
	const chave_tipo_usuario = 'tipo';
	const chave_token        = 'token';
	const chave_email        = 'email';
	const chave_status       = 'status';
	const campos_select      = [
		"created_at",
		"tipo",
		"id"
	];

	public function insert($data)
	{
		return $this->db->insert(self::tabela, $data);
	}

	public function atualiza_status_token($id, $status = 2)
	{
		$this->db
			->set("status", $status)
			->where("id", $id)
			->update(self::tabela);
	}

	public function check_token($token, $email)
	{
		$query = $this->db
			->select(self::campos_select)
			->where(self::chave_email, $email)
			->where(self::chave_token, $token)
			->where(self::chave_status, 1)
			->get(self::tabela);

		if ($query->num_rows() > 0) {

			return $query->row_array();
		} else {

			return false;
		}
	}

	public function check_link_exists($email)
	{
		$query = $this->db
			->select(self::campos_select)
			->where(self::chave_email, $email)
			->where(self::chave_status, 1)
			->get(self::tabela);

		if ($query->num_rows() > 0) {

			return true;
		} else {

			return false;
		}
	}
}
