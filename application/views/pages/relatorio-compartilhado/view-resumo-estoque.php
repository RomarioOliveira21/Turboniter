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
			<h5 id="titulo" class="fw-bold text-secondary">Relatório Estoque</h5>
		</div>
		<div class="text-end m-2 gap-1">
			<div id="dropdown-filtro" class="btn-group">
				<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="fa-solid fa-filter"></i> Filtros
				</button>
				<ul class="dropdown-menu" id="dropdown-resumo-estoque">
					<li><span class="dropdown-item filter" data-value="1">Custo Médio</span></li>
					<li><span class="dropdown-item filter" data-value="2">Custo Reposição</span></li>
					<li><span class="dropdown-item filter" data-value="3">Quantidade</span></li>
				</ul>
			</div>
		</div>
		<div id="alert-filtro-dia" style="display: none;">
			<div class="d-flex flex-column text-center justify-content-center">
				<div class="m-auto alert alert-warning" role="alert">
					<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado!
				</div>
				<div>
					<img width="20%" src="<?= site_url('public/assets/img/no-data.png') ?>" alt="alert">
				</div>
			</div>
		</div>
		<div id="tabelas">
			<div class="bg-light rounded p-2">
				<div class="table-responsive">
					<table id="tbl-resumo-estoque" class="table w-100 display nowrap table-striped">
						<thead>
							<tr>
								<th class="text-start">Grupo</th>
								<th class="text-end">Estoque Inicial</th>
								<th class="text-end">Entradas</th>
								<th class="text-end">Saídas</th>
								<th class="text-end">Estoque Final</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			<div class="bg-light rounded p-2 mt-2">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th colspan="4">Totalizador</th>
							</tr>
							<tr>
								<th class="text-end">Estoque Inicial</th>
								<th class="text-end">Entradas</th>
								<th class="text-end">Saídas</th>
								<th class="text-end">Estoque Final</th>
							</tr>
						</thead>
						<tbody id="total-resumo-estoque"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal-detalhes-estoque" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-detalhesLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl bg-light rounded">
			<div class="modal-content">
				<div class="modal-header">
					<div>
						<h1 class="fw-bold modal-title fs-5" id="modal-detalhesLabel"></h1>
						<p class="fw-bold text-secondary" id="modal-detalhes-grupo"></p>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="bg-light rounded p-2 mt-2">
						<div class="table-responsive">
							<table id="tbl-estoque-detalhes" class="table table-striped table-borderless">
								<thead>
									<tr>
										<th class="text-start">Cód.</th>
										<th class="text-start">Descrição</th>
										<th class="text-end">Quantidade</th>
										<th class="text-end">Custo Médio</th>
										<th class="text-end">Custo Reposição</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="mt-auto modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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

	<script src="<?= site_url("public/assets/js/relatorio-compartilhado/resumo-estoque.js") ?>"></script>

</body>

</html>