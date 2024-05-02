<div>
	<div class="row">
		<div class="col-12">
			<h5 class="ms-2 mt-2"><i class="fa-solid fa-box cl-orange"></i> Relatório Estoque</h5>
			<hr>
		</div>
	</div>
	<div class="shadow rounded p-2">
		<div class="row mb-4 gap-2">
			<div class="col-12">
				<h5 class="ms-2 mt-2"><i class="fa-solid fa-filter"></i> Filtros</h5>
			</div>
			<div class="col-auto">
				<?= select_empresas($this->session->userdata('empresas')) ?>
			</div>
			<div class="col-auto mb-2">
				<div class="input-group input-group-sm">
					<span class="input-group-text bg-body-secondary fw-bold">Data</span>
					<input type="date" name="data" min="2023-01-01" id="data" class="form-control">
				</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-12 text-center">
				<button id="btn_limpar_filtro" onclick="limpar_filtro()" class="btn btn-sm btn-secondary">Limpar filtros</button>
				<button id="btn_buscar" class="btn btn-sm btn-secondary">Buscar</button>
			</div>
		</div>
	</div>

	<div class="shadow rounded p-2 mt-3" id="content-cards-tipo-condicao" style="display: none;">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true"><i class="fas fa-file cl-orange"></i> Relatórios</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false"><i class="fas fa-chart-line cl-orange"></i> Gráficos</button>
			</li>
		</ul>
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active p-2" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="1">
				<ul class="nav nav-tabs" id="myTipo" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="r-tipo-con-tab" data-bs-toggle="tab" data-bs-target="#r-tipo-con-tab-pane" type="button" role="tab" aria-controls="r-tipo-con-tab-pane" aria-selected="true">Tipo de Condição de Pagamento</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="r-grupo-tab" data-bs-toggle="tab" data-bs-target="#r-grupo-tab-pane" type="button" role="tab" aria-controls="r-grupo-tab-pane" aria-selected="false">Grupos</button>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade show active" id="r-tipo-con-tab-pane" role="tabpanel" aria-labelledby="r-tipo-con-tab" tabindex="0">
						<div class="row p-2" id="cards-tipo-condicao"></div>
					</div>
					<div class="tab-pane fade" id="r-grupo-tab-pane" role="tabpanel" aria-labelledby="r-grupo-tab" tabindex="0">
						<div class="row gap-2 p-2" id="accordions-grupo"></div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade p-2" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="2">
				<div class="text-end">
					<div id="dropdown-filtro" class="btn-group m-2">
						<button class="btn btn-secondary btn-sm dropdown-toggle" id="btn-filtro-chart-tcp" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="fa-solid fa-filter"></i> Campos
						</button>
						<ul class="dropdown-menu" id="dropdown-chart-tcp">
							<li><span class="dropdown-item filter" data-value="1">Vendas Dia</span></li>
							<li><span class="dropdown-item filter" data-value="2">Vendas Mês</span></li>
							<li><span class="dropdown-item filter" data-value="3">Vendas Mês Anterior</span></li>
							<li><span class="dropdown-item filter" data-value="4">Vendas Ano</span></li>
							<li><span class="dropdown-item filter" data-value="5">Vendas Ano Anterior</span></li>
						</ul>
					</div>
					<hr>
				</div>
				<div id="content-charts">
					<div class="row p-2">
						<div class="col-12 text-center">
							<span class="fw-bold">Tipos de Condição de Pagamento</span>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="align-self-center w-100">
								<div class="row d-flex justify-content-center align-items-center">
									<div id="content-chart-tipo-condicao">
										<canvas id="chart-tipo-condicao"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="w-100 text-center">
								<span class="fst-italic">Legenda</span>
							</div>
							<div class="table-responsive">
								<table id="table-grafic-pie" class="table mb-0 table-hover" style="display: none;">
									<tbody id="tbody-grafic-pie"></tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="px-4">
						<hr>
					</div>
					<div class="row p-2">
						<div class="col-12 mb-2 text-center">
							<span class="fw-bold">Grupos de Produtos</span>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="align-self-center w-100">
								<div class="row d-flex justify-content-center">
									<div id="content-chart-grupos">
										<canvas id="chart-grupos"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="w-100 text-center">
								<span class="fst-italic">Legenda</span>
							</div>
							<div class="table-responsive">
								<table id="table-grupos" class="table mb-0 table-hover" style="display: none;">
									<tbody id="tbody-grupos"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?= site_url('public/assets/js/charts/chart-grupo-produtos.js') ?>"></script>
<script src="<?= site_url('public/assets/js/charts/chart-tipo-condicao-pg.js') ?>"></script>
<script src="<?= site_url('public/assets/js/relatorios/faturamento.js') ?>"></script>
