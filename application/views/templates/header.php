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
		Web Gerencial
	</title>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

	<link href="<?= site_url('public/assets/css/sidebar.css?') . time() ?>" rel="stylesheet">
	<link href="<?= site_url('public/assets/css/perfect-scrollbar.css?') . time() ?>" rel="stylesheet">
	<link href="<?= site_url('public/assets/css/sidebar.menu.css?') . time() ?>" rel="stylesheet">
	<link href="<?= site_url('public/assets/vendor/fontawesome-free/css/all.css?') . time() ?>" rel="stylesheet" type="text/css">
	<link href="<?= site_url('public/assets/css/style.css?') . time() ?>" rel="stylesheet">
	<link href="<?= site_url('public/assets/css/virtual-select.min.css?') . time() ?>" rel="stylesheet">
	<script src="<?= site_url('public/assets/js/helpers.js?') . time() ?>"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>


<body class="bg-light">
	<header>
		<nav class="navbar navbar-expand-md navbar-light bg-navbar fixed-top">
			<div class="container-fluid">

				<!-- title -->
				<a class="navbar-brand" href="<?= base_url('/dashboard') ?>">
					<img class="shadow-lg rounded bg-light" src="<?= site_url("public/assets/img/icon-vti.png") ?>" width="30px" height="30px" alt="ícone vti">
				</a>

				<!-- sidebar toggle -->
				<button class="navbar-toggler btn-sm btn btn-link border-0" type="button" id="sidebar" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			</div>
		</nav>
	</header>

	<div class="d-flex wrappers wrapper-navbar-used wrapper-navbar-fixed">
		<nav role="navigation" class="sidebar sidebar-bg-dark sidebar-rounded-top-right shadow-sm" id="navigation">

			<!-- sidebar -->
			<div class="sidebar-menu">

				<!-- menu fixed -->
				<div class="sidebar-menu-fixed">
					<!-- menu scrollbar -->
					<div class="scrollbar scrollbar-use-navbar scrollbar-bg-dark">
						<!-- menu -->
						<ul class="list list-unstyled list-bg-dark mb-5 shadow rounded-2">

							<li class="list-item">

								<!-- list title -->
								<p class="list-title text-uppercase">Empresa / Grupo</p>

								<!-- list items -->
								<ul class="list-unstyled">
									<li>
										<div class="ms-3 text-light h6">
											<span class="list-icon"><i class="fas fa-thumbtack text-primary"></i></span>
											<?= $this->session->userdata('tHeaderSidebar') ?>
										</div>
									</li>
								</ul>
							</li>
						</ul>
						<ul class="list list-unstyled list-bg-dark mb-0">

							<li class="list-item">
								<a href="<?= base_url('/dashboard') ?>" class="list-link">
									<span class="list-icon"><i class="fas fa-chart-pie bell"></i>
									</span>Dashboard
								</a>
							</li>

						</ul>
						<ul class="list list-unstyled list-bg-dark mb-0">

							<li class="list-item">

								<p class="list-title text-uppercase">Relatórios</p>

								<ul class="list list-unstyled list-bg-dark mb-0">

									<li class="list-item">
										<a href="#" class="list-link">
											<span class="list-icon"><i class="fas fa-box bell"></i>
											</span>Estoque
										</a>
										<a href="<?= site_url('relatorios/faturamento') ?>" class="list-link">
											<span class="list-icon"><i class="fa-solid fa-calculator bell"></i>
											</span>Faturamento
										</a>
										<a href="#" class="list-link">
											<span class="list-icon"><i class="fas fa-arrow-circle-up bell"></i>
											</span>A Pagar
										</a>
										<a href="#" class="list-link">
											<span class="list-icon"><i class="fas fa-arrow-circle-down bell"></i>
											</span>A Receber
										</a>
									</li>
								</ul>
							</li>
						</ul>
						<ul class="list list-unstyled list-bg-dark mb-0">

							<li class="list-item">

								<p class="list-title text-uppercase">Lançamentos</p>

								<ul class="list list-unstyled list-bg-dark mb-0">

									<li class="list-item">
										<a href="#" class="list-link">
											<span class="list-icon"><i class="fas fa-receipt bell"></i>
											</span>A Pagar
										</a>
										<a href="#" class="list-link">
											<span class="list-icon"><i class="fas fa-wallet bell"></i>
											</span>A Receber
										</a>
									</li>
								</ul>
							</li>
						</ul>
						<ul class="list list-unstyled list-bg-dark mb-0">

							<li class="list-item">

								<p class="list-title text-uppercase">Usuário</p>

								<ul class="list-unstyled">
									<li><a href="#" class="list-link link-arrow text-uppercase">
											<span class="list-icon"><i class="fas fa-user bell"></i></span><?= $this->session->userdata('userNome') ?></a>

										<ul class="list-unstyled list-hidden">
											<li><a href="<?= base_url('/minha-conta') ?>" class="list-link">Perfil</a></li>
											<li><a id="btn-logout" type="button" class="list-link">Logout <i class="fas fa-sign-in-alt ms-1 text-danger"></i></a></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>

					</div>
				</div>
			</div>
		</nav>

		<div class="container-fluid mt-3 d-flex flex-column justify-content-between">
			<main role="main">
