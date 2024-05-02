<?php

class Modelo_Usuario_Empresa extends CI_Model
{

	const tabela = 'usuario_empresa';

	public function insert($id_empresa, $id_usuario)
	{
		return $this->db
			->set('id_usuario_empresa', $id_usuario)
			->set('id_empresa', $id_empresa)
			->insert(self::tabela);
	}

	public function delete($id_usuario)
	{
		return $this->db
			->where('id_usuario_empresa', $id_usuario)
			->delete(self::tabela);
	}

	public function get_descricao_empresa($id_usuario)
	{
		$this->load->model("Modelo_Empresa_Gestor");

		$empresas = [];

		$id_empresa = $this->db
			->select("id_empresa")
			->where("id_usuario_empresa", $id_usuario)
			->get(self::tabela)
			->result_array();



		foreach ($id_empresa as $id) {

			$dados = $this->Modelo_Empresa_Gestor->get_empresa($id['id_empresa']);
			array_push($empresas, $dados);
		}

		return $empresas;
	}
}
