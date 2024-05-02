<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="Veredas Tecnologia e Informação">
	<link href="<?= site_url('favicon.ico') ?>" rel="icon">
	<title>
		Gerencial
	</title>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

	<link href="<?= site_url('public/assets/vendor/fontawesome-free/css/all.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?= site_url('public/assets/css/style.css') ?>" rel="stylesheet">
	<link href="<?= site_url('public/assets/css/virtual-select.min.css') ?>" rel="stylesheet">

	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

	<script src="<?= site_url('public/assets/js/helpers.js?') . time() ?>"></script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<style>
		body {
			height: 100vh;
			display: flex;
			flex-direction: column;
		}
	</style>
</head>

<body>
	<nav class="navbar navbar-light bg-navbar">
		<div class="container-fluid">
			<div class="d-flex align-items-center">
				<img width="50px" src="<?= site_url('public/assets/img/logo.png') ?>" alt="logo Gerencial">
				<h5 class="fw-bold text-light ms-1 my-0">Gerencial</h5>
			</div>
		</div>
	</nav>

	<div class="container-fluid mt-2">
		<div class="p-2 mb-4 bg-body-tertiary rounded-3">
			<div class="container-fluid text-secondary">
				<h5 id="nome-empresa" class="fw-bold m-0"><i class="fa-solid fa-bookmark text-primary"></i> <?= $empresa ?></h5>
				<p id="filtro_aplicado"></p>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="p-2 mb-4 bg-body-tertiary rounded-3">
			<h5 id="titulo" class="fw-bold text-secondary">Relatório Vendas</h5>
		</div>
		<div id="alert-filtro" style="display: none;">
			<div class="d-flex flex-column text-center justify-content-center">
				<div class="m-auto alert alert-warning" role="alert">
					<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado!
				</div>
				<div>
					<img width="20%" src="<?= site_url('public/assets/img/no-data.png') ?>" alt="alert">
				</div>
			</div>
		</div>
		<div id="grafico">
			<div class="p-2">
				<div class="row justify-content-center p-2">
					<div class="shadow rounded col-lg-8 p-2 d-flex flex-column gap-2" id="chart-condicao-pg"></div>
				</div>
			</div>
			<div class="shadow rounded p-2 mt-3" id="totalizadores">
				<h5 class="fw-bold text-secondary"><i class="fa-solid fa-calculator cl-orange"></i> Somatório Geral </h5>
				<div class="d-flex align-items-center" id="content_totais">
					<h6 class="fw-bold text-wrap">Total vendas no período</h6>
					<span class="badge text-bg-success ms-auto"></span>
				</div>
			</div>
		</div>
	</div>
	<div class="bg-white p-1 mt-auto">
		<div class="copyright text-center">
			<span>copyright &copy;
				<script>
					document.write(new Date().getFullYear());
				</script> - desenvolvido por
				<b><a href="https://veredastecnologia.com.br" target="_blank">
						<img src="<?= site_url("public/assets/img/vti.png") ?>" width="90px" alt="logo VTI">
					</a></b>
			</span>
		</div>
	</div>

	<script src="<?= site_url("public/assets/js/relatorio-compartilhado/vendas.js") ?>"></script>

</body>

</html>