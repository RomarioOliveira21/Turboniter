<?php

class Modelo_Usuario_Gestor extends CI_Model
{
	const tabela = 'usuario_web_cliente';

	const campos_select = [
		'id',
		'nome',
		'email',
		'id_cliente as empresa'
	];

	private $conn;

	const chave_primaria = 'id';
	const chave_status   = 'status';
	const chave_email    = 'email';
	const chave_senha    = 'senha';

	public function __construct()
	{
		parent::__construct();
	}

	public function getUsuario($email, $senha)
	{
		$query = $this->conn
			->select('
				u.id,
				u.nome,
				u.email,
				u.tipo,
				upe.id_empresa,
				cliente.nome_cliente,
				cliente.cpfcnpj_cliente
			')
			->from(self::tabela . ' AS u')
			->join(
				'usuario_permissao_empresa AS upe',
				'upe.id_usuario = u.id'
			)
			->join(
				'usuario_permissao_sistema AS us',
				'us.id_usuario = u.id'
			)
			->join(
				'cliente',
				'cliente.id_cliente = upe.id_empresa'
			)
			->join(
				'cliente_sistema_modulo AS cs',
				'cs.id_cliente = upe.id_empresa'
			)
			->where('u.' . self::chave_email, trim($email))
			->where('u.' . self::chave_senha, md5($senha))
			->where('u.' . self::chave_status, 1)
			->where('cs.id_sistema', CODIGO_WEB_GERENCIAL)
			->where('us.id_sistema', CODIGO_WEB_GERENCIAL)
			->where('cs.permissao', 'S')
			->where_not_in('cliente.status_cliente', ['6', '7'])
			->get();

		if ($query->num_rows() > 0) {

			foreach ($query->result_array() as $usuario) {

				$empresas[] = [

					'id'      => $usuario['id_empresa'],
					'cnpj'    => $usuario['cpfcnpj_cliente'],
					'cliente' => $usuario['nome_cliente']
				];
			}

			$user             = $query->first_row('array');
			$user['empresas'] = $empresas;

			return $user;
		} else {

			return false;
		}
	}

	public function update_senha($senha, $email)
	{
		return $this->conn
			->set('senha', $senha)
			->where(self::chave_email, $email)
			->update(self::tabela);
	}

	public function check_senha_usuario($senha_atual, $email)
	{
		$query = $this->conn
			->select('id')
			->where(self::chave_email, $email)
			->where(self::chave_senha, $senha_atual)
			->get(self::tabela);

		if ($query->num_rows() > 0) {

			return true;
		} else {

			return false;
		}
	}

	public function check_email_usuario($email)
	{
		$query = $this->conn
			->select('id')
			->where(self::chave_email, $email)
			->get(self::tabela);

		if ($query->num_rows() > 0) {

			return true;
		} else {

			return false;
		}
	}
}
