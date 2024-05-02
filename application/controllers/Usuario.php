<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->is_admin();

		$this->load->model("Modelo_Usuario");
		$this->load->model("Modelo_Empresa_Gestor");
		$this->load->model("Modelo_Usuario_Empresa");
	}

	private function set_empresas()
	{
		$codigos  = $this->session->userdata('usuario')['empresas'];
		$empresas = [];


		if (is_array($codigos)) {

			foreach ($codigos as $key => $value) {

				$empresa = $this->Modelo_Empresa_Gestor->get_empresa($value);
				array_push($empresas, $empresa);
			}
		} else {

			$empresa = $this->Modelo_Empresa_Gestor->get_empresa($codigos);
			array_push($empresas, $empresa);
		}

		$this->data['empresas'] = $empresas;
	}

	public function get_empresa()
	{
		$this->set_empresas();
		echo json_encode($this->data['empresas']);
	}

	public function index()
	{
		$this->load_view('usuario/view-list-usuario');
	}

	public function form()
	{
		$this->set_empresas();
		$this->load_view('usuario/view-form-usuario');
	}

	public function save()
	{
		$this->set_empresas();
		$this->load->library('form_validation');

		$this->form_validation->set_rules(
			array(
				array(
					'field' => 'nome',
					'label' => 'Nome',
					'rules' => 'required|min_length[5]|max_length[50]'
				),
				array(
					'field' => 'senha',
					'label' => 'Senha',
					'rules' => 'required|min_length[8]|max_length[20]|matches[confirmacao_senha]',
				),
				array(
					'field' => 'confirmacao_senha',
					'label' => 'Confirmação de senha',
					'rules' => 'required|min_length[8]|max_length[20]'
				),
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'required|valid_email|is_unique[usuario.email]'
				),
				array(
					'field' => 'empresas[]',
					'label' => 'Empresa(s)',
					'rules' => 'required'
				)
			)
		);

		$this->form_validation->set_error_delimiters('<div class="error text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> ', '</div>');
		$this->form_validation->set_message('min_length', '{field} precisa ter no mínimo {param} caractéres.');
		$this->form_validation->set_message('max_length', '{field} precisa ter no máximo {param} caractéres.');
		$this->form_validation->set_message('required', '{field} é obrigátorio.');
		$this->form_validation->set_message('matches', 'Senhas não coincidem.');
		$this->form_validation->set_message('valid_email', 'Informe um email válido.');
		$this->form_validation->set_message('is_unique', 'Este email já foi cadastrado em outro usuário.');

		if ($this->form_validation->run() == FALSE || $this->input->method() == 'get') {

			$this->load_view('usuario/view-form-usuario');
		} else {

			if ($this->insert()) {

				$this->session->set_flashdata(
					'mensagem',
					"<div class='alert alert-success fw-bold' role='alert'><i class='fas fa-check'></i> Usuário cadastrado com sucesso!</div>"
				);
				redirect("usuarios");
			} else {

				$this->session->set_flashdata(
					'mensagem',
					"<div class='alert alert-danger fw-bold' role='alert'><i class='fas fa-exclamation-triangle'></i> Falha ao cadastrar usuário!</div>"
				);
				redirect("usuarios");
			}
		}
	}

	public function insert()
	{
		$empresas = $this->input->post("empresas");

		$usuario = [
			"nome"          => $this->input->post("nome"),
			"email"         => $this->input->post("email"),
			"senha"         => md5($this->input->post("senha")),
			"status"        => $this->input->post("status"),
			"data_cadastro" => date('Y-m-d H:i:s')
		];

		$id_usuario = $this->Modelo_Usuario->insert($usuario);

		if ($id_usuario) {

			foreach ($empresas as $empresa) {
				$this->Modelo_Usuario_Empresa->insert($empresa, $id_usuario);
			}

			return true;
		} else {

			return false;
		}
	}

	public function update()
	{
		if ($this->input->method() != 'post') {

			echo (json_encode([
				"message" => "Dados inválidos!",
				"error"   => true
			]));

			return;
		}

		$id       = $this->input->post("id");
		$empresas = $this->input->post("empresas") ?? [];

		$usuario = [
			"nome"    => $this->input->post("nome"),
			"email"   => $this->input->post("email"),
			"status"  => $this->input->post("status"),
		];

		if ($this->input->post("senha")) {

			if (!empty($this->input->post("senha"))) {

				$usuario["senha"] = md5($this->input->post("senha"));
			}
		}


		if ($this->Modelo_Usuario->update($usuario, $id)) {

			$this->Modelo_Usuario_Empresa->delete($id);

			foreach ($empresas as $empresa) {
				$this->Modelo_Usuario_Empresa->insert($empresa, $id);
			}

			echo (json_encode([
				"message" => "Usuário alterado com sucesso!",
				"error"   => false
			]));
		} else {

			echo (json_encode([
				"message" => "Erro ao tentar alterar usuário, tente novamente mais tarde.",
				"error"   => true
			]));
		}
	}

	public function get_usuario()
	{
		$data['data'] = $this->Modelo_Usuario->get_usuario();
		echo json_encode($data);
	}
}
