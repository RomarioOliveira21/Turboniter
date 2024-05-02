<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RelatorioCompartilhado extends CI_Controller
{
	private $filtro;

	private $intervalo;

	private $chave;

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$hash = $this->input->get('filtro');

		$this->load->helper('openssl');

		$filtro = (json_decode(decrypt(rawurldecode($hash))));

		if ($filtro == NULL) {

			show_error('URL inválida ', 500, 'Oopss!');
		}

		$this->load->helper('cookie');

		set_cookie('filtro', $hash, '3600');

		$view = '';

		switch ($filtro->tipo) {

			case 'venda':
				$view = 'pages/relatorio-compartilhado/view-vendas';
				break;

			case 'estoque':
				$view = 'pages/relatorio-compartilhado/view-resumo-estoque';
				break;

			case 'pagamento':
				$view = 'pages/relatorio-compartilhado/view-pagamento';
				break;

			case 'recebimento':
				$view = 'pages/relatorio-compartilhado/view-recebimento';
				break;

			default:
				show_404();
				break;
		}

		$this->load->model('Modelo_Empresa_Gestor');

		$empresa = $this->Modelo_Empresa_Gestor->getEmpresaCnpj($filtro->chave);

		$this->load->view($view, ['empresa' =>  $empresa['nome_cliente']]);
	}

	private function processaHash()
	{
		$this->load->helper(['cookie', 'openssl']);

		$data = (json_decode(decrypt(get_cookie('filtro'))));

		if ($data == NULL) {

			show_404();
		}

		$intervalo = empty($data->dia) ? [$data->periodoInicial, $data->periodoFinal] : [$data->dia, $data->dia];
		$filtro    = !empty($data->dia) && $data->filtro == 6 ? 7 : (int) $data->filtro;

		return $this->setFiltro($filtro)
			->setIntervalo($intervalo)
			->setChave($data->chave);
	}

	public function getResumoEstoque()
	{
		$this->processaHash();

		$this->load->model('Modelo_Estoque');

		$resumoEstoque = $this->Modelo_Estoque->setChaveEmpresa($this->getChave())
			->getResumoEstoque($this->getFiltro(), $this->getIntervalo());

		echo json_encode([

			'filtro'         => $this->Modelo_Estoque->montaIntervalo($this->getFiltro(), '', $this->getIntervalo())['textoPeriodo'],
			'resumo_estoque' => $resumoEstoque
		]);
	}

	public function getVendas()
	{
		$this->processaHash();

		$this->load->model('Modelo_Fat_CPagamento');

		$data = $this->Modelo_Fat_CPagamento->setChaveEmpresa($this->getChave())
			->getVendas($this->getFiltro(), $this->getIntervalo());

		echo json_encode([

			'filtro' => $this->Modelo_Fat_CPagamento->montaIntervalo($this->getFiltro(), '', $this->getIntervalo())['textoPeriodo'],
			'vendas' => $data['vendas']
		]);
	}

	public function getContasAPagar()
	{
		$this->processaHash();

		$this->load->model('Modelo_Pagamento');

		$data = $this->Modelo_Pagamento->setChaveEmpresa($this->getChave())
			->getPagamentos($this->getFiltro(), $this->getIntervalo());

		echo json_encode([

			'filtro'     => $this->Modelo_Pagamento->montaIntervalo($this->getFiltro(), '', $this->getIntervalo())['textoPeriodo'],
			'pagamentos' => $data,
		]);
	}

	public function getContasAReceber()
	{
		$this->processaHash();

		$this->load->model('Modelo_Recebimento');

		$data = $this->Modelo_Recebimento->setChaveEmpresa($this->getChave())
			->getRecebimentos($this->getFiltro(), $this->getIntervalo());

		echo json_encode([

			'filtro'       => $this->Modelo_Recebimento->montaIntervalo($this->getFiltro(), '', $this->getIntervalo())['textoPeriodo'],
			'recebimentos' => $data,
		]);
	}

	public function saidasDetalhadas()
	{
		$this->processaHash();
		$grupo = $this->input->post('grupo');


		$this->load->model('Modelo_Estoque');

		$data = $this->Modelo_Estoque->setChaveEmpresa($this->getChave())
			->getMovimentoDetalhado($this->getFiltro(), $this->getIntervalo(), $grupo, 'S');

		echo json_encode($data);
	}

	public function entradasDetalhadas()
	{
		$this->processaHash();
		$grupo = $this->input->post('grupo');

		$this->load->model('Modelo_Estoque');

		$data = $this->Modelo_Estoque->setChaveEmpresa($this->getChave())
			->getMovimentoDetalhado($this->getFiltro(), $this->getIntervalo(), $grupo);

		echo json_encode($data);
	}

	/**
	 * Get the value of filtro
	 */
	public function getFiltro()
	{
		return $this->filtro;
	}

	/**
	 * Set the value of filtro
	 *
	 * @return  self
	 */
	public function setFiltro($filtro)
	{
		$this->filtro = $filtro;

		return $this;
	}

	/**
	 * Get the value of intervalo
	 */
	public function getIntervalo()
	{
		return $this->intervalo;
	}

	/**
	 * Set the value of intervalo
	 *
	 * @return  self
	 */
	public function setIntervalo($intervalo)
	{
		$this->intervalo = $intervalo;

		return $this;
	}

	/**
	 * Get the value of chave
	 */
	public function getChave()
	{
		return $this->chave;
	}

	/**
	 * Set the value of chave
	 *
	 * @return  self
	 */
	public function setChave($chave)
	{
		$this->chave = $chave;

		return $this;
	}
}
