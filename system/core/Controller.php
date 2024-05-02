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
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/userguide3/general/controllers.html
 */
class CI_Controller
{
	private $request;

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * CI_Loader
	 *
	 * @var	CI_Loader
	 */
	public $load;

	/**
	 * Variável responsável por todos os dados.
	 *
	 * @var array
	 */
	public $data;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance = &$this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class) {

			$this->$var = &load_class($class);
		}

		$this->load = &load_class('Loader', 'core');

		$this->load->initialize();

		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
	 * Função responsável por rendarizar as views dentro do template padrão
	 *
	 * @param string $page
	 * @return void
	 */
	protected function load_view($page = 'dashboard')
	{
		if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
			// Whoops, we don't have a page for that!
			show_404();
		}

		$this->load->view('templates/header', $this->data);
		$this->load->view('pages/' . $page, $this->data);
		$this->load->view('templates/footer', $this->data);
	}

	protected function setRequest()
	{
		$json = json_decode(file_get_contents("php://input"), true);

		if (!isset($json['data'])) {

			show_error("Falha ao processar dados da requisição", 401, "Ops! Parece que tivemos um erro!");
		}

		$this->request = $json['data'];
	}

	protected function getRequest() : array
	{
		return $this->request;
	}

	/**
	 * Válida a sessão do usuário
	 */
	protected function is_loged()
	{
		if (is_null($this->session->userdata('logged'))) {

			$this->load->helper('cookie');

			$cookie = get_cookie('url-logout');

			if (is_null($cookie)) {

				show_error("Ops! Permissão negada.", 404, "");
			} else {

				delete_cookie('url-logout');
				header('Location: ' . $cookie);
				exit;
			}
		}
	}

	protected function is_admin()
	{
		if (is_null($this->session->userdata('logged')) || is_null($this->session->userdata('is_admin'))) {
			redirect('dashboard');
		}
	}
}
