<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Relatorio extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('loggedIn') == FALSE) {

			redirect('login');
		}
	}

	public function faturamento()
	{
		$this->load_view('relatorio/view-faturamento');
	}

	public function estoque()
	{
		$this->load_view('relatorio/view-estoque');
	}

	public function getDadosFaturamento()
	{
		$this->load->model('Modelo_Fat_CPagamento');

		$chaveEmpresa = $this->input->post('empresa');
		$dia          = $this->input->post('data');

		$data = $this->Modelo_Fat_CPagamento->setChaveEmpresa($chaveEmpresa)->getComparativoVendas($dia);

		echo json_encode($data);
	}

	public function linkRelatorio()
	{
		$this->load->helper('url_encurtada');

		$cnpj = $this->input->post('chave');

		$retorno = get_url_encurtada($cnpj, $_POST);

		if (isset($retorno['error'])) {

			echo json_encode($retorno);
		} else {

			echo json_encode(['error' => TRUE]);
		}
	}
}
