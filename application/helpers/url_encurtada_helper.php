<?php

function get_url_encurtada(string $cnpj, array $data)
{

	$url  = 'https://login.veredastecnologia.com.br/API/link_relatorio';
	$cnpj = hash('sha256', $cnpj);

	// Configuração do cabeçalho
	$headers = array(
		
		'Content-Type: application/x-www-form-urlencoded', // Definindo o tipo de conteúdo como JSON
		'empresa: ' . $cnpj, // Adicionando o CNPJ ao cabeçalho
	);

	// Inicializa a sessão cURL
	$curl = curl_init();

	// Define as opções da requisição
	curl_setopt_array($curl, array(

		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_URL            => $url,
		CURLOPT_RETURNTRANSFER => true, // Retorna a resposta da solicitação como uma string
		CURLOPT_CUSTOMREQUEST  => 'POST', // Método da requisição
		CURLOPT_POSTFIELDS     => http_build_query($data), // Dados a serem enviados no corpo da solicitação
		CURLOPT_HTTPHEADER     => $headers, // Cabeçalhos da requisição
	));

	// Executa a requisição e obtém a resposta
	$response = curl_exec($curl);

	// Verifica se houve algum erro durante a requisição
	if (curl_errno($curl)) {

		echo 'Erro ao realizar a requisição: ' . curl_error($curl);
		exit;
	}

	// Fecha a sessão cURL
	curl_close($curl);

	// Converte a resposta JSON em um array associativo
	return json_decode($response, true);
}
