<?php

function select_empresas($empresas)
{
	$options = "";

	foreach ($empresas as $empresa) {

		$options .= "<option class='fw-bold' value='" . $empresa['cnpj'] . "'>" . $empresa['cliente'] . "</option>";
	}

	return "
		<div class='input-group'>
			<label class='input-group-text bg-body-secondary fw-bold' for='empresa'><i class='fas fa-caret-square-right'></i></label>
			<select id='empresas' name='empresa' class='form-select form-select-sm'>
				$options
			</select>
		</div>";
}

function load_table($data)
{

	$tr = "";

	foreach ($data as $key => $value) {
		$status = $value['status'] === 'A' ? 'Ativa' : 'Desativada';
		$cor = $value['status'] === 'A' ? 'text-primary' : 'text-danger';
		$tr .= "<tr><th>" . $value['id'] . " </th><th>" . $value['nome'] . "</th><th class='$cor'>" . $status . "</th></tr>";
	}

	return $tr;
}

function load_value($value)
{
	if (empty($value)) {
		return "<small class='text-muted'>Não cadastrado.</small>";
	}

	return $value;
}

function load_produtor()
{
	if (!isset($_SESSION['unidades'])) {
		return $_SESSION['produtor'];
	}

	return $_SESSION['user'];
}

function getUltimos12Meses()
{
	$primeiroDiaMesAtual = date('Y-m-01');
	$ultimoDiaMesPassado = date('Y-m-d', strtotime('-1 day', strtotime($primeiroDiaMesAtual)));
	$data12MesesAtras = date("Y-m-d", strtotime(date("Y-m-d", strtotime($ultimoDiaMesPassado)) . "-12 month"));
	$datainit = date('Y-m-d', strtotime('+1 day', strtotime($data12MesesAtras)));
	return array($datainit, $ultimoDiaMesPassado);
}

function numeroParaMes($numero)
{
	$meses = array(
		1 => 'Jan',
		2 => 'Fev',
		3 => 'Mar',
		4 => 'Abr',
		5 => 'Mai',
		6 => 'Jun',
		7 => 'Jul',
		8 => 'Ago',
		9 => 'Set',
		10 => 'Out',
		11 => 'Nov',
		12 => 'Dez'
	);

	return isset($meses[$numero]) ? $meses[$numero] : '';
}

function obterNomeMesPtBr($numeroMes)
{
	$nomesMesesPtBr = array(
		'Janeiro',
		'Fevereiro',
		'Março',
		'Abril',
		'Maio',
		'Junho',
		'Julho',
		'Agosto',
		'Setembro',
		'Outubro',
		'Novembro',
		'Dezembro'
	);

	if ($numeroMes >= 0 && $numeroMes <= 11) {
		$nomeMesPtBr = $nomesMesesPtBr[$numeroMes];
		return $nomeMesPtBr;
	} else {
		return null;
	}
}

function formatCnpjCpf($value = '00000000000000')
{
	$CPF_LENGTH = 11;
	$cnpj_cpf = preg_replace("/\D/", '', $value);

	if (strlen($cnpj_cpf) === $CPF_LENGTH) {
		return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
	}

	return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

function getFirstAndLastDayOfMonth($year, $month)
{
	// Verifica se o mês está no intervalo de 1 a 12
	if ($month < 1 || $month > 12) {
		return false;
	}

	// Obtém o primeiro dia do mês
	$firstDay = date('Y-m-01', strtotime("$year-$month-01"));

	// Obtém o último dia do mês
	$lastDay = date('Y-m-t', strtotime("$year-$month-01"));

	return array($firstDay, $lastDay);
}

function get_icon_tipo_user()
{
	$span = '';

	if (!isset($_SESSION['tipo_user'])) {

		return $span = "<i style='font-size: 13px;' class='badge badge-ligh text-white'>-</i>";
	}

	switch ($_SESSION['tipo_user']) {
		case '1':
			$span = "<i style='font-size: 13px;' class='badge badge-success text-white'>P</i>";
			break;
		case '2':
			$span = "<i style='font-size: 13px;' class='badge badge-primary text-white'>UC</i>";
			break;
		case '3':
			$span = "<i style='font-size: 13px;' class='badge badge-warning text-white'>UCC</i>";
			break;
	}

	return $span;
}

function get_color_tipo_user()
{
	$span = '';

	if (!isset($_SESSION['tipo_user'])) {
		return $span = "";
	}

	switch ($_SESSION['tipo_user']) {
		case '1':
			$span = "text-success";
			break;
		case '2':
			$span = "text-primary";
			break;
		case '3':
			$span = "text-warning";
			break;
	}

	return $span;
}

function get_mes_anterior($mes)
{
	if ($mes >= 2 && $mes <= 12) {

		return $mes - 1;

	} elseif ($mes == 1) {

		return 12; // Janeiro é o mês 1, então o mês anterior é dezembro (12).

	} else {

		return false; // Número de mês inválido.
	}
}

function get_primeiro_ultimo_dia_mes_atual()
{
	$primeiroDia = date('Y-m-01');
	$ultimoDia = date('Y-m-t');

	return array($primeiroDia, $ultimoDia);
}

function get_primeiro_ultimo_dia_mes_anterior()
{
	$primeiroDia = date('Y-m-01', strtotime('first day of last month'));
	$ultimoDia = date('Y-m-t', strtotime('last day of last month'));

	return array($primeiroDia, $ultimoDia);
}

/**
 * Formata data no padrão pt-BR
 *
 * @param string $data
 * @return string
 */
function date_to_BR($data)
{
	return date('d/m/Y', strtotime($data));
}

function monta_texto_intervalo($dt_inicial, $dt_final)
{
	return "Período dos registros - " . date_to_BR($dt_inicial) . " a " .  date_to_BR($dt_final);
}

function mostra_opcoes_admin($is_admin)
{
	// 	<ul class="list-unstyled">
	// 	<li>
	// 		<a href="#" class="list-link link-arrow">
	// 			<span class="list-icon"><i class="fas fa-folder-plus bell"></i></span>
	// 			Cadastros
	// 		</a>

	// 		<!-- list items, second level -->
	// 		<ul class="list-unstyled list-hidden">
	// 			<li><a href="' . $usuarios . '" class="list-link">Usuários</a></li>
	// 		</ul>
	// 	</li>
	// </ul>

	// $usuarios    = base_url('usuarios');
	$bicos  = base_url('troca-de-precos');

	if ($is_admin) {
		return '
			<ul class="list list-unstyled list-bg-dark list-icon-red mb-0">

				<li class="list-item">

					<p class="list-title text-uppercase">Sistema</p>

					<ul class="list-unstyled">
						<li>
							<a href="#" class="list-link link-arrow">
								<span class="list-icon"><i class="fas fa-tools bell"></i></i></span>
								Manutenção
							</a>

							<!-- list items, second level -->
							<ul class="list-unstyled list-hidden">
								<li><a href="' . $bicos . '" class="list-link">Troca de preços</a></li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
		';
	}
}

function verifica_diferenca_horas($dataHoraString, $intervalo)
{
	// Converte a string de data e hora em um objeto DateTime
	$dataHora = new DateTime($dataHoraString);

	// Obtém a data e hora atual
	$agora = new DateTime();

	// Calcula a diferença em horas entre a data atual e a data fornecida
	$diferencaHoras = $agora->diff($dataHora)->h;

	// Se a diferença for maior ou igual a 24 horas, retorna verdadeiro, caso contrário, falso
	return $diferencaHoras >= $intervalo;
}

/**
 * Retorna a diferença entre duas datas.
 * As datas devem estar no formato YYYY-MM-DD.
 * Por padrão ela retorna a difrença em dias
 *
 * @param string $data_init data inicial
 * @param string $data_end data final
 * @return int
 */
function get_espaco_tempo($data_init, $data_end)
{
	// Converte a string de data e hora em um objeto DateTime
	$data_inicial = new DateTime(date('Y-m-d', strtotime($data_init . ' - 1 days')));
	$data_final   = new DateTime($data_end);

	// Calcula a diferença em dias entre a data final e a data inicial
	$diferenca = $data_final->diff($data_inicial)->days;
	return (int) $diferenca;
}

/**
 * mensagem
 *
 * @return string
 */
function mensagem()
{
	$texto = '';

	if (isset($_SESSION['msgError'])) {

		$texto .= '
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>' . $_SESSION['msgError'] . '</strong>
            </div>
        ';
	}

	if (isset($_SESSION['msgSuccess'])) {

		$texto .= '
            <div class="alert alert-success" role="alert">
                <i class="fas fa-thumbs-up"></i> <strong>' . $_SESSION['msgSuccess'] . '</strong>
            </div>
        ';
	}

	return $texto;
}
