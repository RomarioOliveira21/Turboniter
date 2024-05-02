<?php

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Modelo_Usuario_Gestor');

		$this->load->library('form_validation');
	}

	public function index()
	{

		$this->load->view('pages/login/view-login');
	}

	/**
	 * signIn
	 *
	 */
	public function signIn()
	{
		if (isset($_SERVER['HTTP_ORIGIN'])) {

			$this->load->helper('cookie');
			set_cookie("HTTP_ORIGIN", $_SERVER['HTTP_ORIGIN'], "86400");
		}

		$email = $this->input->post('email', FILTER_SANITIZE_EMAIL);
		$senha = $this->input->post('senha');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('senha', 'Senha', 'required');

		if ($this->form_validation->run() == FALSE) {

			$this->load->view('pages/login/view-login');
		} else {

			// buscar o usuário na base de dados
			$dadosUsuario = $this->Modelo_Usuario_Gestor->getUsuario($email, $senha);

			if ($dadosUsuario) {

				$tempoPdraoLogin = 86400;	// 1 dia

				$this->session->set_tempdata('userId', $dadosUsuario['id'], $tempoPdraoLogin);
				$this->session->set_tempdata('userNome', $dadosUsuario['nome'], $tempoPdraoLogin);
				$this->session->set_tempdata('userEmail', $dadosUsuario['email'], $tempoPdraoLogin);
				$this->session->set_tempdata('nivel', $dadosUsuario['tipo'], $tempoPdraoLogin);

				$this->load->model('Modelo_Empresa_Gestor');

				if (count($dadosUsuario['empresas']) > 1) {

					foreach ($dadosUsuario['empresas'] as $empresa) {

						$ids_empresas[] = $empresa['id'];
					}

					$texto  = $this->Modelo_Empresa_Gestor->getGrupoEmpresa($ids_empresas);
				} else {

					$texto   = $dadosUsuario['empresas'][0]['cliente'];
				}

				$this->session->set_tempdata('empresas', $dadosUsuario['empresas'], $tempoPdraoLogin);
				$this->session->set_tempdata('tHeaderSidebar', $texto, $tempoPdraoLogin);
				$this->session->set_tempdata('loggedIn', TRUE, $tempoPdraoLogin);

				$this->load->model('Modelo_Fat_CPagamento');

				foreach ($dadosUsuario['empresas'] as $emp) {

					$dataUltimaExportacao = $this->Modelo_Fat_CPagamento->setChaveEmpresa($emp['cnpj'])->getDataExportacao();

					$datasEmpresas[] = [

						'data_movimento' => $dataUltimaExportacao,
						'cnpj'           => $emp['cnpj']
					];
				}

				$this->session->set_tempdata('movimentos', $datasEmpresas, $tempoPdraoLogin);

				redirect('dashboard');
			} else {

				$this->session->set_flashdata('msgError', 'Usuário ou senha inválidos');
				$this->load->view('pages/login/view-login');
			}
		}
	}

	/**
	 * signOut
	 *
	 */
	public function signOut()
	{
		session_destroy();           // destroy as sessions
		return redirect("login");
	}

	public function delete_session()
	{
		session_destroy();
		echo json_encode(true);
	}
}
