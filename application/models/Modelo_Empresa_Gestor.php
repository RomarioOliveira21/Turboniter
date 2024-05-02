<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modelo_Empresa_Gestor extends CI_Model
{
	private $__tabela       = 'cliente';
	private $__chave_codigo = 'id_cliente';
	private $__chave_cnpj   = 'cpfcnpj_cliente';
	private $__chave_status = 'status_cliente';

	private $conn;

	/**
	 *  Armazena o código do sistema a ser utilizado.
	 */
	const SISTEMA       = 42;
	/**
	 * Armazena a string de comparação de permissão
	 */
	const PERMISSAO     = 'S';

	/**
	 * Armazena os status em que uma empresa esta desabilitada.
	 */
	const STATUS        = ['6', '7'];

	const campos_select = [
		'id_cliente',
		'nome_cliente',
		'cpfcnpj_cliente'
	];

	public function __construct()
	{
		parent::__construct();
		$this->conn = $this->load->database("gestor", TRUE);
	}

	/**
	 * Verificando se a empresa esta habilitada.
	 */
	public function validaCnpj(string $codigo)
	{
		$query = $this->conn
			->select('c.cpfcnpj_cliente')
			->from($this->__tabela . ' as c')
			->join(
				'cliente_sistema_modulo as csm',
				'csm.id_cliente = c.' . $this->__chave_codigo
			)
			->where('csm.id_sistema', self::SISTEMA)
			->where('csm.permissao', self::PERMISSAO)
			->where('c.' . $this->__chave_codigo, $codigo)
			->where_not_in('c.' . $this->__chave_status, self::STATUS)
			->get();

		if ($query->num_rows() > 0) {

			return true;
		} else {

			return false;
		}
	}

	public function checkCnpj(string $cnpj_encryped)
	{
		$query = $this->conn
			->select('cpfcnpj_cliente, status_cliente')
			->where("SHA2($this->__chave_cnpj,256)", $cnpj_encryped)
			->get($this->__tabela);

		if ($query->num_rows() > 0) {

			return $query->row_array();
		} else {

			return false;
		}
	}

	public function getEmpresa(string $codigo)
	{
		return $this->conn
			->select(self::campos_select)
			->where($this->__chave_codigo, $codigo)
			->where_not_in($this->__chave_status, ['6', '7'])
			->get($this->__tabela)
			->row_array();
	}

	public function getEmpresaCnpj(string $cnpj)
	{
		return $this->conn
			->select(self::campos_select)
			->where($this->__chave_cnpj, $cnpj)
			->where_not_in($this->__chave_status, ['6', '7'])
			->get($this->__tabela)
			->row_array();
	}

	public function getGrupoEmpresa(array $codigos)
	{
		return $this->conn
			->select('g.descricao')
			->from('cliente_grupo as c')
			->join('grupo_cliente as g', 'g.id_grupo = c.id_grupo')
			->where_in($this->__chave_codigo, $codigos)
			->get()
			->row_array();
	}

	public function getCnpjsEmpresa(array $codigos)
	{
		return $this->conn
			->select(self::campos_select)
			->where_in($this->__chave_codigo, $codigos)
			->where_not_in($this->__chave_status, ['6', '7'])
			->get($this->__tabela)
			->result_array();
	}
}
