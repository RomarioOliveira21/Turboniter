<?php

class Modelo_Link_Compartilhado extends CI_Model
{
	const tabela = 'link_relatorio_compartilhado';

	public function get_url($id)
	{
		$query = $this->db
			->select('url_orginal')
			->where('id', $id)
			->get(self::tabela);

		if($query->num_rows() > 0){

			return $query->row()->url_orginal;
		}

		return FALSE;
	}

	public function save($data)
	{
		return $this->db->insert(self::tabela, $data);
	}
}
