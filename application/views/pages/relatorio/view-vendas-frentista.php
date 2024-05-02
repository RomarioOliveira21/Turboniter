<div>
	<div class="row">
		<div class="col-12">
			<h5 class="ms-2 mt-2"><i class="fas fa-id-badge"></i> Relatório Vendas Frentista</h5>
			<hr>
		</div>
	</div>
	<div class="shadow rounded p-2">
		<div class="row mb-4">
			<div class="col-12">
				<h5 class="ms-2 mt-2"><i class="fa-solid fa-filter"></i> Filtros</h5>
			</div>
			<div class="col">
				<div class="col-lg-6 col-md-10">
					<?= select_unidades($this->session->userdata('empresas')) ?>
				</div>
			</div>
		</div>
		<div class="row mb-2 align-items-end">

			<div class="col-lg-6 col-md-12">
				<div id="select_frentistas" class="input-group input-group-sm" style="display: none;"></div>
			</div>
			<div class="col-lg-6 col-md-12">
				<span class="fw-bold">Período</span>
				<div class="input-group">
					<span class="m-1 badge rounded text-bg-secondary">de</span>
					<input type="date" name="data_inicial" min="2023-01-01" id="data_inicial" class="form-control form-control-sm rounded">
					<span class="m-1 badge rounded text-bg-secondary">até</span>
					<input type="date" min="2023-01-01" name="data_final" id="data_final" class="form-control form-control-sm rounded">
				</div>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col-12 text-center">
				<button id="btn_limpar_filtro" onclick="limpar_filtro()" class="btn btn-sm btn-secondary">Limpar filtros</button>
				<button id="btn_buscar" class="btn btn-sm btn-secondary">Buscar</button>
			</div>
		</div>
	</div>

	<div class="shadow rounded p-2 mt-3">
		<h5 class="fw-bold text-secondary">Filtro aplicado:</h5>
		<div class="row justify-content-center">
			<div class="col-auto">
				<div id="filtro_aplicado"></div>
			</div>
		</div>
	</div>

	<div class="shadow rounded p-2 mt-3" id="totalizadores" style="display: none;">
		<h5 class="fw-bold text-secondary">Totalizadores: </h5>
		<div id="content_totais"></div>
	</div>

	<div class="shadow rounded p-2 mt-3">
		<h5 class="fw-bold text-secondary">Vendas por frentista: </h5>
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" disabled aria-selected="true">
					<h4><i class="fas fa-table"></i></h4>
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" disabled aria-selected="false">
					<h4><i class="fas fa-chart-pie"></i></h4>
				</button>
			</li>
		</ul>
		<div class="tab-content" id="myTabContent">

			<div class="tab-pane fade show active py-2" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

				<div id="alert_accordions" class="alert alert-warning" role="alert" style="display: none;">
					<strong><i class="fa-solid fa-triangle-exclamation"></i> Não encontramos registros!</strong>
				</div>
				<div class="row p-2 mt-3 gap-2" id="accordion_grupos"></div>

			</div>
			<div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="1">

				<div class="row d-flex align-items-center">
					<div class="col">
						<span class="badge fs-6 text-bg-light" id="filtro_selecionado"></span>
					</div>
					<div class="col text-end mb-1">
						<div class="btn-group m-2">
							<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="fa-solid fa-filter"></i> Filtros
							</button>
							<ul class="dropdown-menu">
								<li><span class="dropdown-item filter" data-value="1">Valor Combustíveis</span></li>
								<li><span class="dropdown-item filter" data-value="3">Valor Outros</span></li>
								<li><span class="dropdown-item filter" data-value="4">Valor Combustíveis + Valor Outros</span></li>
								<li><span class="dropdown-item filter" data-value="2">Nº Abastecimentos</span></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div style="height: 400px;" id="content_frentista"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<script src="<?= site_url('public/vendor/virtual-select/virtual-select.min.js') ?>"></script>
<script src="<?= site_url('public/js/charts/chart-vendas-frentista.js') ?>"></script>
<script src="<?= site_url('public/js/relatorios/vendas-frentista.js') ?>"></script>
