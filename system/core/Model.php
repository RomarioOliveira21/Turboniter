<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019 - 2022, CodeIgniter Foundation
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @copyright	Copyright (c) 2019 - 2022, CodeIgniter Foundation (https://codeigniter.com/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/userguide3/libraries/config.html
 */
class CI_Model
{
	const chaveDtMovimento = 'dt_movimento';
	const chaveEmpresa     = 'chave_empresa';
	private $chaveEmpresa;

	/**
	 * Class constructor
	 *
	 * @link	https://github.com/bcit-ci/CodeIgniter/issues/5332
	 * @return	void
	 */
	public function __construct()
	{
	}

	/**
	 * __get magic
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string	$key
	 */
	public function __get($key)
	{
		// Debugging note:
		//	If you're here because you're getting an error message
		//	saying 'Undefined Property: system/core/Model.php', it's
		//	most likely a typo in your model code.
		return get_instance()->$key;
	}

	/**
	 * Get the value of chave_empresa
	 */
	public function getChaveEmpresa()
	{
		return $this->chaveEmpresa;
	}

	/**
	 * Set the value of chave_empresa
	 *
	 * @return  self
	 */
	public function setChaveEmpresa($chaveEmpresa)
	{
		$this->chaveEmpresa = $chaveEmpresa;
		return $this;
	}

	public function montaIntervalo(int $filtro, string $campo, $intervalo = [])
	{
		$this->load->model('Modelo_Fat_CPagamento');

		$ultimoDia = $this->Modelo_Fat_CPagamento->setChaveEmpresa($this->chaveEmpresa)->getDataExportacao();
		$query     = "";
		$texto     = "";

		switch ((int) $filtro) {

			case 1:
				$primeiro_dia_ano_atual = date('Y') . '-01-01';
				$query                  = "$campo BETWEEN '$primeiro_dia_ano_atual' AND '$ultimoDia'";
				$texto                  = 'Ano Atual <i class="fas fa-long-arrow-alt-right"></i> ' . date('Y');
				break;
			case 2:
				$query = "YEAR($campo) = YEAR(NOW()) - 1";
				$texto = 'Ano Anterior <i class="fas fa-long-arrow-alt-right"></i> ' . (((int)date('Y')) - 1);
				break;
			case 3:
				$primeiro_dia_mes_atual = '01-' . date('m') . '-' . date('Y');
				$query                  = "$campo BETWEEN '$primeiro_dia_mes_atual' AND '$ultimoDia'";
				$texto                  = 'Mês Atual <i class="fas fa-long-arrow-alt-right"></i> ' . obterNomeMesPtBr(date('m') - 1);
				break;
			case 4:
				$mesAnterior = date("d-m-Y", strtotime("first day of previous month"));
				$query       = "MONTH($campo) = MONTH('$mesAnterior') AND YEAR($campo) = YEAR('$mesAnterior')";
				$texto       = 'Mês Anterior <i class="fas fa-long-arrow-alt-right"></i> ' . obterNomeMesPtBr(date("m", strtotime($mesAnterior)) - 1);
				break;
			case 5:
				$texto = 'Último Dia <i class="fas fa-long-arrow-alt-right"></i> ' . date_format(new DateTime($ultimoDia), 'd-m-Y');
				$query = "$campo = '$ultimoDia'";
				break;
			case 6:
				$dataInicial = $intervalo[0];
				$dataFinal   = $intervalo[1];
				$texto       = 'De ' . date_format(new DateTime($dataInicial), 'd-m-Y') . ' até ' . date_format(new DateTime($dataFinal), 'd-m-Y');
				$query       = "$campo BETWEEN '$dataInicial' AND '$dataFinal'";
				break;
			case 7:
				$data  = $intervalo[0];
				$texto = 'Dia selecionado <i class="fas fa-long-arrow-alt-right"></i> ' . date_format(new DateTime($data), 'd-m-Y');
				$query = "$campo = '$data'";
				break;
		}

		return [

			'query'        => $query,
			'textoPeriodo' => $texto
		];
	}
}
