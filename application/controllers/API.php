<?php

class API extends CI_Controller
{
	private $cnpj;

	const KEY_HEADER = 'HTTP_EMPRESA';

	const models = [

		'Modelo_Fat_CPagamento',
		'Modelo_Fat_Produto',
		'Modelo_Estoque',
		'Modelo_Pagamento',
		'Modelo_Recebimento'
	];

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');

		$this->load->model(self::models);

		$this->load->model([

			'Modelo_Produto',
			'Modelo_Forma_Pagamento',
			'Modelo_Tipo_Debito',
			'Modelo_Portador',
			'Modelo_Grupo_Subgrupo',
			'Modelo_Empresa_Gestor',
			'Modelo_Pagamento',
			'Modelo_Recebimento',
			'Modelo_Log_Acesso'
		]);

		$this->checkPermissao();
	}

	/**
	 * Função que valida os registros do json e retorna os dados para inserção
	 *
	 * @param string $model
	 */
	private function get_request_processada(string $model)
	{
		try {

			$this->setRequest();

			$data    = $this->getRequest();

			$dataset = [];

			$config_rules = $this->{$model}->getConfigRules();

			foreach ($data as $key => $item) {

				$item['chave_empresa']  = $this->cnpj;

				$this->form_validation->set_data($item);

				$this->form_validation->set_rules($config_rules);

				if ($this->form_validation->run() == FALSE) {

					echo json_encode([

						'code'      => 1,
						'error'     => true,
						'message'   => validation_errors('* ', ' *'),
						'key_array' => "Índice do array onde houve o erro : $key"
					]);
					exit;
				}

				$dataset[] = $item;
			}

			return $dataset;
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	private function setErrorResponse($msg)
	{
		$resposta['error']   = true;
		$resposta['code']    = 1;
		$resposta['message'] = $msg;
		http_response_code(401);
		echo json_encode($resposta);
		exit;
	}

	private function checkPermissao()
	{
		$empresa = '';

		switch ($this->input->server(self::KEY_HEADER)) {
			case '':
				$this->setErrorResponse('Permissão negada!');
				break;

			case null:
				$this->setErrorResponse('Permissão negada!');
				break;

			default:
				$empresa = $this->input->server(self::KEY_HEADER);
				break;
		}

		$cnpj = $this->Modelo_Empresa_Gestor->checkCnpj($empresa);

		if ($cnpj) {

			if (in_array($cnpj['status_cliente'], ['6', '7'])) {

				$this->setErrorResponse('CNPJ inativo!');
			} else {

				$this->cnpj = $cnpj['cpfcnpj_cliente'];
				$this->Modelo_Log_Acesso->save($cnpj['cpfcnpj_cliente']);
			}
		} else {

			$this->setErrorResponse('CNPJ inválido!');
		}
	}

	/**
	 * Método genérico para salvar os registros no banco de dados. 
	 *
	 * @param string $model 
	 * @param array $data
	 * @param string $msg
	 * @return void
	 */
	private function save($model, $data, $msg)
	{
		if ($this->{$model}->saveRows($data)) {

			$retorno['error']   = false;
			$retorno['message'] = $msg;
		} else {

			$retorno['error']   = true;
			$retorno['code']    = 1;
			$retorno['message'] = 'Erro ao tentar gravar os registros!';
		}

		echo json_encode($retorno);
	}

	private function deleteModel($dataMovimento)
	{
		try {

			foreach (self::models as $value) {

				if ($this->{$value}->setChaveEmpresa($this->cnpj)->delete($dataMovimento) == FALSE) {

					throw new Exception("Falha ao tentar deletar registros no modelo Modelo_Fat_CPagamento do período " . $dataMovimento);
				}
			}
		} catch (\Throwable | \Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function movimentos()
	{
		try {

			$this->setRequest();

			$data = $this->getRequest();

			$dataset = [];

			foreach ($data as $key => $value) {

				$dataMovimento        = isset($value['dt_movimento']) ? $value['dt_movimento'] : [];
				$fatCondicaoPagamento = isset($value['faturamento_condicao_pag']) ? $value['faturamento_condicao_pag'] : [];
				$fatProduto           = isset($value['faturamento_produto']) ? $value['faturamento_produto'] : [];
				$estoque              = isset($value['estoque']) ? $value['estoque'] : [];
				$recebimento          = isset($value['recebimento']) ? $value['recebimento'] : [];
				$pagamento            = isset($value['pagamento']) ? $value['pagamento'] :  [];

				$rulesModelo_Fat_CPagamento = $this->Modelo_Fat_CPagamento->getConfigRules();

				foreach ($fatCondicaoPagamento as $key => $itemFCP) {

					$itemFCP['dt_movimento']   = $dataMovimento;
					$itemFCP['chave_empresa']  = $this->cnpj;

					$this->form_validation->set_data($itemFCP);

					$this->form_validation->set_rules($rulesModelo_Fat_CPagamento);

					if ($this->form_validation->run() == FALSE) {

						echo json_encode([

							'code'      => 1,
							'error'     => true,
							'message'   => validation_errors('* ', ' *'),
							'key_array' => "Índice do array onde houve o erro : $key"
						]);
						exit;
					}

					$dataset['Modelo_Fat_CPagamento'][] = $itemFCP;
				}

				$rulesModelo_Fat_Produto = $this->Modelo_Fat_Produto->getConfigRules();

				foreach ($fatProduto as $key => $itemFP) {

					$itemFP['dt_movimento']  = $dataMovimento;
					$itemFP['chave_empresa'] = $this->cnpj;

					$this->form_validation->set_data($itemFP);

					$this->form_validation->set_rules($rulesModelo_Fat_Produto);

					if ($this->form_validation->run() == FALSE) {

						echo json_encode([

							'code'      => 1,
							'error'     => true,
							'message'   => validation_errors('* ', ' *'),
							'key_array' => "Índice do array onde houve o erro : $key"
						]);
						exit;
					}

					$dataset['Modelo_Fat_Produto'][] = $itemFP;
				}

				$rulesModelo_Estoque = $this->Modelo_Estoque->getConfigRules();

				foreach ($estoque as $key => $itemEstoque) {

					$itemEstoque['dt_movimento'] = $dataMovimento;
					$itemEstoque['chave_empresa']  = $this->cnpj;

					$this->form_validation->set_data($itemEstoque);

					$this->form_validation->set_rules($rulesModelo_Estoque);

					if ($this->form_validation->run() == FALSE) {

						echo json_encode([

							'code'      => 1,
							'error'     => true,
							'message'   => validation_errors('* ', ' *'),
							'key_array' => "Índice do array onde houve o erro : $key"
						]);
						exit;
					}

					$dataset['Modelo_Estoque'][] = $itemEstoque;
				}

				$rulesModelo_Pagamento = $this->Modelo_Pagamento->getConfigRules();

				foreach ($pagamento as $key => $itemPagamento) {

					$itemPagamento['dt_movimento'] = $dataMovimento;
					$itemPagamento['chave_empresa']  = $this->cnpj;

					$this->form_validation->set_data($itemPagamento);

					$this->form_validation->set_rules($rulesModelo_Pagamento);

					if ($this->form_validation->run() == FALSE) {

						echo json_encode([

							'code'      => 1,
							'error'     => true,
							'message'   => validation_errors('* ', ' *'),
							'key_array' => "Índice do array onde houve o erro : $key"
						]);
						exit;
					}

					$dataset['Modelo_Pagamento'][] = $itemPagamento;
				}

				$rulesModelo_Recebimento = $this->Modelo_Recebimento->getConfigRules();

				foreach ($recebimento as $key => $itemRecebimento) {

					$itemRecebimento['dt_movimento'] = $dataMovimento;
					$itemRecebimento['chave_empresa']  = $this->cnpj;

					$this->form_validation->set_data($itemRecebimento);

					$this->form_validation->set_rules($rulesModelo_Recebimento);

					if ($this->form_validation->run() == FALSE) {

						echo json_encode([

							'code'      => 1,
							'error'     => true,
							'message'   => validation_errors('* ', ' *'),
							'key_array' => "Índice do array onde houve o erro : $key"
						]);
						exit;
					}

					$dataset['Modelo_Recebimento'][] = $itemRecebimento;
				}

				$this->deleteModel($dataMovimento);
			}

			foreach ($dataset as $key => $value) {

				$result = $this->{$key}->save($value);

				if ($result['error']) {

					$this->setErrorResponse($result['message']);
				}
			}

			echo json_encode([

				'code'      => 0,
				'error'     => false,
				'message'   => "Dados inseridos com sucesso!",
				'key_array' => ""
			]);
		} catch (\Throwable | \Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function estoque()
	{
		try {

			$data = $this->get_request_processada('Modelo_Estoque');

			foreach ($data as $value) {

				$this->Modelo_Estoque->setChaveEmpresa($this->cnpj)->delete($value['dt_movimento']);
			}

			if ($this->Modelo_Estoque->save($data)) {

				echo json_encode([

					'code'    => 0,
					'error'   => false,
					'message' => "Dados inseridos com sucesso!",
				]);
			} else {

				echo json_encode([

					'code'    => 0,
					'error'   => true,
					'message' => "Falha ao tentar inserir registros!",
				]);
			}
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function pagamentos()
	{
		try {

			$data = $this->get_request_processada('Modelo_Pagamento');

			foreach ($data as $value) {

				$this->Modelo_Pagamento->setChaveEmpresa($this->cnpj)->delete($value['dt_movimento']);
			}

			if ($this->Modelo_Pagamento->save($data)) {

				echo json_encode([

					'code'    => 0,
					'error'   => false,
					'message' => "Dados inseridos com sucesso!",
				]);
			} else {

				echo json_encode([

					'code'    => 0,
					'error'   => true,
					'message' => "Falha ao tentar inserir registros!",
				]);
			}
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function recebimentos()
	{
		try {

			$data = $this->get_request_processada('Modelo_Recebimento');

			foreach ($data as $value) {

				$this->Modelo_Recebimento->setChaveEmpresa($this->cnpj)->delete($value['dt_movimento']);
			}

			if ($this->Modelo_Recebimento->save($data)) {

				echo json_encode([

					'code'    => 0,
					'error'   => false,
					'message' => "Dados inseridos com sucesso!",
				]);
			} else {

				echo json_encode([

					'code'    => 0,
					'error'   => true,
					'message' => "Falha ao tentar inserir registros!",
				]);
			}
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function grupo_subgrupo()
	{
		try {

			$data = $this->get_request_processada('Modelo_Grupo_Subgrupo');

			$this->Modelo_Grupo_Subgrupo->setChaveEmpresa($this->cnpj)->delete();

			$this->save(
				'Modelo_Grupo_Subgrupo',
				$data,
				'Grupos e Subgrupos inseridos com sucesso!'
			);
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function forma_pagamento()
	{
		try {

			$data = $this->get_request_processada('Modelo_Forma_Pagamento');

			$this->Modelo_Forma_Pagamento->setChaveEmpresa($this->cnpj)->delete();

			$this->save(
				'Modelo_Forma_Pagamento',
				$data,
				'Forma(s) de Pagamento(s) inseridas com sucesso!'
			);
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function tipo_debito()
	{
		try {

			$data = $this->get_request_processada('Modelo_Tipo_Debito');

			$this->Modelo_Tipo_Debito->setChaveEmpresa($this->cnpj)->delete();

			$this->save(
				'Modelo_Tipo_Debito',
				$data,
				'Dados atualizados com sucesso!'
			);
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function portador()
	{
		try {

			$data = $this->get_request_processada('Modelo_Portador');

			$this->Modelo_Portador->setChaveEmpresa($this->cnpj)->delete();

			$this->save(
				'Modelo_Portador',
				$data,
				'Dados atualizados com sucesso!'
			);
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function produto()
	{
		try {

			$data = $this->get_request_processada('Modelo_Produto');

			$delete_sucess = $this->Modelo_Produto->setChaveEmpresa($this->cnpj)->delete();

			if ($delete_sucess) {

				$this->save(
					'Modelo_Produto',
					$data,
					'Produtos inseridos com sucesso!'
				);
			} else {

				$this->setErrorResponse("Falha ao tentar limpar registros do CNPJ: " . $this->cnpj . ", na tabela produto.");
			}
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}



	/**
	 * 
	 *    ========      GET       =========
	 * 
	 */

	public function data_ultima_exportacao()
	{
		try {

			$result = $this->Modelo_Fat_CPagamento
				->setChaveEmpresa($this->cnpj)
				->getDataExportacao();

			echo json_encode([

				'code'    => 0,
				'error'   => false,
				'message' => empty($result) ? "Nenhuma data foi encontrada." : "Data encontrada com sucesso.",
				'data'    => $result
			]);
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}

	public function data_primeira_exportacao()
	{
		try {

			$result = $this->Modelo_Fat_CPagamento->setChaveEmpresa($this->cnpj)->getDataExportacao(true);

			echo json_encode([

				'code'    => 0,
				'error'   => false,
				'message' => empty($result) ? "Nenhuma data foi encontrada." : "Data encontrada com sucesso!",
				'data'    => $result
			]);
		} catch (\Exception $e) {

			$this->setErrorResponse($e->getMessage());
		}
	}
}
