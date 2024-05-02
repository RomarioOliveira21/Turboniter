<div class="px-2" id="content-cards" style="display: none;">
	<div class="row p-2 mb-3">
		<div class="col-auto">
			<?= select_empresas($this->session->userdata('empresas')) ?>
		</div>
		<div class="col m-1 z-3 d-flex justify-content-end align-items-center">
			<div data-bs-nivel="principal" class="prev-next p-1 rounded d-flex flex-nowrap bg-card-light">
				<button title="Dia anterior" id="prev" class="mr-2 btn-sm btn-orange"><i class="fas fa-long-arrow-alt-left"></i></i></button>
				<div class="text-center overflow-x-hidden" style="min-width: 107px;">
					<p class="text-nowrap surge-da-esquerda m-0 fw-bold"></p>
				</div>
				<button title="Dia posterior" id="next" class="btn-sm btn-orange"><i class="fas fa-long-arrow-alt-right"></i></button>
			</div>
		</div>
		<div class="col-12">
			<hr>
			<div class="d-flex justify-content-between">
				<p class="p-0 m-0 animate__animated animate__bounceInRight">
					<span id="data-movimento" class="badge badge-light"></span>
					<span id="filtro-geral" class="badge cl-orange"></span>
				</p>

				<div id="dropdown-filtro" class="btn-group">
					<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fa-solid fa-filter"></i> Filtros
					</button>
					<ul class="dropdown-menu" id="dropdown-principal">
						<li><span class="dropdown-item filter" data-value="1">Ano atual</span></li>
						<li><span class="dropdown-item filter" data-value="2">Ano anterior</span></li>
						<li><span class="dropdown-item filter" data-value="3">Mês atual</span></li>
						<li><span class="dropdown-item filter" data-value="4">Mês anterior</span></li>
						<li><span class="dropdown-item filter" data-value="5">Último dia</span></li>
						<li><span class="dropdown-item filter" data-value="6">Período personalizado</span></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="row d-flex align-items-stretch" id="content-cards-analytics">
		<div class="col-lg-3 col-md-6 col-sm-6 mb-2">
			<div id="card-vendas" data-bs-toggle="modal" data-bs-target="#modal-relatorio-vendas" class="cookie-card cookie-card__height d-flex flex-column hover-animation">
				<div>
					<div class="d-flex justify-content-between">
						<span class="title"><i class="fas fa-ruler-vertical"></i> Vendas</span>
						<i class="fa-solid fa-caret-down cl-orange"></i>
					</div>
					<div class="text-animation">
						<p>Mais informações <i class="fa-solid fa-arrow-up-right-from-square cl-orange"></i></p>
					</div>
				</div>
				<div class="text-end mt-auto">
					<span id="card-vlr-total-vendas" class="description fs-4">R$ 0,00</span>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6 mb-2">
			<div data-bs-toggle="modal" data-bs-target="#modal-relatorio-estoque" class="cookie-card cookie-card__height d-flex flex-column hover-animation">
				<div>
					<div class="d-flex justify-content-between">
						<span class="title"><i class="fas fa-box"></i> Estoque</span>
						<i class="fa-solid fa-caret-down cl-orange"></i>
					</div>
					<div class="text-animation">
						<p>Mais informações <i class="fa-solid fa-arrow-up-right-from-square cl-orange"></i></p>
					</div>
				</div>
				<div class="mb-1 mt-auto d-flex flex-column gap-2">
					<div class="d-flex align-items-center justify-content-between">
						<span class="description">Reposição</span><span id="custo-reposicao" class="badge text-bg-secondary">R$ 0,00</span>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<span class="description">Médio</span><span id="custo-medio" class="badge text-bg-secondary">R$ 0,00</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6 mb-2">
			<div data-bs-toggle="modal" data-bs-target="#modal-pagamento" class="cookie-card cookie-card__height d-flex flex-column hover-animation">
				<div>
					<div class="d-flex justify-content-between">
						<span class="title"><i class="fas fa-long-arrow-alt-up"></i> Pagamentos</span>
						<i class="fa-solid fa-caret-down cl-orange"></i>
					</div>
					<div class="text-animation">
						<p>Mais informações <i class="fa-solid fa-arrow-up-right-from-square cl-orange"></i></p>
					</div>
				</div>
				<div class="mb-1 mt-auto d-flex flex-column gap-2">
					<div class="d-flex align-items-center justify-content-between">
						<span class="description">A Vencer</span><span id="cp-a-vencer" class="badge text-bg-warning">R$ 0,00</span>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<span class="description">Vencido</span><span id="cp-vencido" class="badge text-bg-danger">R$ 0,00</span>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<span class="description">Total</span><span id="cp-total" class="badge text-bg-secondary">R$ 0,00</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6 mb-2">
			<div data-bs-toggle="modal" data-bs-target="#modal-recebimento" class="cookie-card cookie-card__height d-flex flex-column hover-animation">
				<div>
					<div class="d-flex justify-content-between">
						<span class="title"><i class="fas fa-long-arrow-alt-down"></i> Recebimentos</span>
					</div>
					<div class="text-animation">
						<p>Mais informações <i class="fa-solid fa-arrow-up-right-from-square cl-orange"></i></p>
					</div>
				</div>
				<div class="mb-1 mt-auto d-flex flex-column gap-2">
					<div class="d-flex align-items-center justify-content-between">
						<span class="description">A Vencer</span><span id="cr-a-vencer" class="badge text-bg-warning">R$ 0,00</span>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<span class="description">Vencido</span><span id="cr-vencido" class="badge text-bg-danger">R$ 0,00</span>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<span class="description">Total</span><span id="cr-total" class="badge text-bg-secondary">R$ 0,00</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row d-flex justify-content-center">
	<div class="col-12 mt-2">
		<div class="card">
			<div class="row card-header border-0">
				<div class="col">
					<h5 class="card-title mb-0">Comparativo de vendas por período</h5>
					<small class="badge badge-custom-light mb-3 text-wrap font-italic" id="periodo">Comparativo de valores brutos de vendas em relação ao mesmo período no ano anterior </small>
				</div>
				<div class="col-auto text-end mb-1">
					<div id="dropdown-filtro" class="btn-group">
						<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="fa-solid fa-filter"></i> Filtros
						</button>
						<ul class="dropdown-menu">
							<li><span class="dropdown-item filter" data-value="1">Ano atual</span></li>
							<li><span class="dropdown-item filter" data-value="3">Mês anterior</span></li>
							<li><span class="dropdown-item filter" data-value="2">Mês atual</span></li>
							<li><span class="dropdown-item filter" data-value="4">Último dia</span></li>
							<li><span class="dropdown-item filter" data-value="5">Período personalizado</span></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="align-self-center w-100">
					<div class="row d-flex justify-content-center">
						<div id="content-litragem" style="height: 400px;">
							<canvas id="chart_vendas"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-relatorio-vendas" tabindex="-1" aria-labelledby="modal-relatorio-vendasLabel" aria-hidden="true">
	<div class="modal-dialog modal-md bg-light modal-dialog-scrollable rounded">
		<div class="modal-content border-0">
			<div class="modal-header">
				<div>
					<h1 class="modal-title fs-5" id="modal-relatorio-vendasLabel"><i class="fa-solid fa-arrow-right-arrow-left cl-orange"></i> Vendas por tipo de condição de pagamento</h1>
					<span id="filtro-geral" class="badge badge-gray"></span>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
					<div class="p-2 text-end">
						<button id="btn-compartilhar" data-relatorio="venda" type="button" class="btn btn-sm btn-outline-success me-auto">
							<span class="fw-bold">Compartilhar <i class="fa-solid fa-share-nodes"></i></span>
						</button>
					</div>
					<div data-bs-nivel="venda" class="prev-next rounded d-flex flex-nowrap bg-card-light p-1">
						<button title="Dia anterior" id="prev" class="mr-2 btn-sm btn-orange"><i class="fas fa-long-arrow-alt-left"></i></i></button>
						<div class="text-center overflow-x-hidden" style="min-width: 107px;">
							<p class="text-nowrap surge-da-esquerda m-0 fw-bold"></p>
						</div>
						<button title="Próxino dia" id="next" class="btn-sm btn-orange"><i class="fas fa-long-arrow-alt-right"></i></button>
					</div>
				</div>
				<div id="alert">
					<div class="d-flex flex-column text-center justify-content-center">
						<div class="m-auto alert alert-warning" role="alert">
							<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado com o filtro aplicado!
						</div>
						<div>
							<img width="20%" src="<?= site_url('public/assets/img/no-data.png') ?>" alt="alert">
						</div>
					</div>
				</div>
				<div id="content-modal-body">
					<div class="row rounded justify-content-center">
						<div class="shadow rounded col p-2 d-flex flex-column gap-2" id="chart-condicao-pg"></div>
					</div>
					<div class="shadow row rounded p-2 mt-3" id="totalizadores">
						<h5 class="fw-bold text-secondary"><i class="fa-solid fa-calculator cl-orange"></i> Somatório Geral </h5>
						<div class="d-flex align-items-center col" id="content_totais">
							<h6 class="fw-bold text-wrap">Total vendas no período</h6>
							<span class="badge text-bg-success ms-auto"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-relatorio-estoque" tabindex="-1" aria-labelledby="modal-relatorio-estoqueLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl bg-light modal-dialog-scrollable rounded">
		<div class="modal-content border-0">
			<div class="modal-header header-modal-estoque">
				<div>
					<h1 class="modal-title fs-5" id="modal-relatorio-estoqueLabel"><i class="fas fa-clipboard cl-orange"></i> Resumo Estoque</h1>
					<span id="filtro-geral" class="badge badge-gray"></span>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="alert">
					<div class="d-flex flex-column text-center justify-content-center">
						<div class="m-auto alert alert-warning" role="alert">
							<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado com o filtro aplicado!
						</div>
						<div>
							<img width="20%" src="<?= site_url('public/assets/img/no-data.png') ?>" alt="alert">
						</div>
					</div>
				</div>
				<div id="content-modal-body">
					<div class="d-flex flex-wrap align-items-center text-end m-2 gap-1">
						<button id="btn-compartilhar" data-relatorio="estoque" type="button" class="btn btn-sm btn-outline-success me-auto">
							<span class="fw-bold">Compartilhar <i class="fa-solid fa-share-nodes"></i></span>
						</button>
						<div data-bs-nivel="estoque" class="prev-next rounded d-flex flex-nowrap bg-card-light p-1">
							<button title="Dia anterior" id="prev" class="mr-2 btn-sm btn-orange"><i class="fas fa-long-arrow-alt-left"></i></i></button>
							<div class="text-center overflow-x-hidden" style="min-width: 107px;">
								<p class="text-nowrap surge-da-esquerda m-0 fw-bold"></p>
							</div>
							<button title="Próxino dia" id="next" class="btn-sm btn-orange"><i class="fas fa-long-arrow-alt-right"></i></button>
						</div>
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
								<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado com o filtro aplicado!
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
											<th class="text-center">Estoque Inicial</th>
											<th class="text-center">Entradas</th>
											<th class="text-center">Saídas</th>
											<th class="text-center">Estoque Final</th>
										</tr>
									</thead>
									<tbody id="total-resumo-estoque"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-pagamento" tabindex="-1" aria-labelledby="modal-relatorio-pagamentoLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl bg-light modal-dialog-scrollable rounded">
		<div class="modal-content border-0">
			<div class="modal-header">
				<div>
					<h1 class="modal-title fs-5" id="modal-relatorio-pagamentoLabel"><i class="fas fa-long-arrow-alt-up cl-orange"></i> Contas a Pagar</h1>
					<span id="filtro-geral" class="badge badge-gray"></span>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="body-modal-pagamento">
				<div id="alert">
					<div class="d-flex flex-column text-center justify-content-center">
						<div class="m-auto alert alert-warning" role="alert">
							<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado com o filtro aplicado!
						</div>
						<div>
							<img width="20%" src="<?= site_url('public/assets/img/no-data.png') ?>" alt="alert">
						</div>
					</div>
				</div>
				<div id="content-modal-body">
					<div class="d-flex flex-wrap align-items-center justify-content-between">
						<div class="d-flex flex-wrap align-items-center text-end m-2 gap-1">
							<button id="btn-compartilhar" data-relatorio="pagamento" type="button" class="btn btn-sm btn-outline-success me-auto">
								<span class="fw-bold">Compartilhar <i class="fa-solid fa-share-nodes"></i></span>
							</button>
						</div>
						<div data-bs-nivel="pagamento" class="prev-next rounded d-flex flex-nowrap bg-card-light p-1">
							<button title="Dia anterior" id="prev" class="mr-2 btn-sm btn-orange"><i class="fas fa-long-arrow-alt-left"></i></i></button>
							<div class="text-center overflow-x-hidden" style="min-width: 107px;">
								<p class="text-nowrap surge-da-esquerda m-0 fw-bold" id="input-filtro-dia-pagamento"></p>
							</div>
							<button title="Próxino dia" id="next" class="btn-sm btn-orange"><i class="fas fa-long-arrow-alt-right"></i></button>
						</div>
					</div>
					<div id="alert-dia" style="display: none;">
						<div class="d-flex flex-column text-center justify-content-center">
							<div class="m-auto alert alert-warning" role="alert">
								<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado com o filtro aplicado!
							</div>
							<div>
								<img width="20%" src="<?= site_url('public/assets/img/no-data.png') ?>" alt="alert">
							</div>
						</div>
					</div>
					<div id="tabelas">
						<div class="bg-light rounded p-2">
							<div class="table-responsive">
								<table id="tbl-pagamento" class="table w-100 display nowrap table-striped">
									<thead>
										<tr>
											<th class="text-start">Tipo de Débito</th>
											<th class="text-end">Inclusões</th>
											<th class="text-end">Liquidações</th>
											<th class="text-end">Descontos</th>
											<th class="text-end">Cancelamentos</th>
											<th class="text-end">Baixa Contábil</th>
											<th class="text-end">Despesas Financeiras</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<div class="bg-light rounded p-2 mt-2">
							<div class="table-responsive">
								<table id="tbl-totalizador-pagamento" class="table table-striped">
									<thead>
										<tr>
											<th colspan="6">Totalizador</th>
										</tr>
										<tr>
											<th class="text-end">Inclusões</th>
											<th class="text-end">Liquidações</th>
											<th class="text-end">Descontos</th>
											<th class="text-end">Cancelamentos</th>
											<th class="text-end">Baixa Contábil</th>
											<th class="text-end">Despesas Financeiras</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<div class="bg-light rounded d-flex justify-content-center gap-2 p-2 mt-2">
							<h6 class="rounded p-2 text-uppercase text-bg-warning"><b>a vencer</b> <span id="cp-a-vencer">R$ 0,00</span></h6>
							<h6 class="rounded p-2 text-uppercase text-bg-danger"><b>vencido</b> <span id="cp-vencido">R$ 0,00</span></h6>
						</div>
						<div class="bg-light rounded d-flex justify-content-center mt-1">
							<h6 class="rounded p-2 text-uppercase text-bg-secondary"><b>total</b> <span id="cp-total">R$ 0,00</span></h6>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-recebimento" tabindex="-1" aria-labelledby="modal-relatorio-recebimentoLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl bg-light modal-dialog-scrollable rounded">
		<div class="modal-content border-0">
			<div class="modal-header">
				<div>
					<h1 class="modal-title fs-5" id="modal-relatorio-recebimentoLabel"><i class="fas fa-long-arrow-alt-down cl-orange"></i> Contas a Receber</h1>
					<span id="filtro-geral" class="badge badge-gray"></span>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="body-modal-recebimento">
				<div id="alert">
					<div class="d-flex flex-column text-center justify-content-center">
						<div class="m-auto alert alert-warning" role="alert">
							<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado com o filtro aplicado!
						</div>
						<div>
							<img width="20%" src="<?= site_url('public/assets/img/no-data.png') ?>" alt="alert">
						</div>
					</div>
				</div>
				<div id="content-modal-body">
					<div class="d-flex flex-wrap align-items-center justify-content-between">
						<div class="d-flex flex-wrap align-items-center text-end m-2 gap-1">
							<button id="btn-compartilhar" data-relatorio="recebimento" type="button" class="btn btn-sm btn-outline-success me-auto">
								<span class="fw-bold">Compartilhar <i class="fa-solid fa-share-nodes"></i></span>
							</button>
						</div>
						<div data-bs-nivel="recebimento" class="prev-next rounded d-flex flex-nowrap bg-card-light p-1">
							<button title="Dia anterior" id="prev" class="mr-2 btn-sm btn-orange"><i class="fas fa-long-arrow-alt-left"></i></i></button>
							<div class="text-center overflow-x-hidden" style="min-width: 107px;">
								<p class="text-nowrap surge-da-esquerda m-0 fw-bold" id="input-filtro-dia-recebimento"></p>
							</div>
							<button title="Próxino dia" id="next" class="btn-sm btn-orange"><i class="fas fa-long-arrow-alt-right"></i></button>
						</div>
					</div>
					<div id="alert-dia" style="display: none;">
						<div class="d-flex flex-column text-center justify-content-center">
							<div class="m-auto alert alert-warning" role="alert">
								<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado com o filtro aplicado!
							</div>
							<div>
								<img width="20%" src="<?= site_url('public/assets/img/no-data.png') ?>" alt="alert">
							</div>
						</div>
					</div>
					<div id="tabelas">
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Totalizador</button>
								<button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Documentos</button>
							</div>
						</nav>
						<div class="tab-content" id="nav-tabContent">
							<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
								<div class="container-fluid py-1">
									<table id="tbl-totalizador-documento" class="table first-start table-striped">
										<thead>
											<tr class="table-light">
												<th style="width: 10px;">Documentos</th>
												<th>Total Vencer</th>
												<th>Total Vencido</th>
												<th>Total Geral</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
								<div class="container-fluid py-1">
									<h5 class="fw-bold"></h5>
									<div class="table-responsive">
										<table id="tbl-recebimento" class="table first-start table-striped">
											<thead>
												<tr class="table-light">
													<th>Documentos</th>
													<th>Inclusões</th>
													<th>Liquidações</th>
													<th>Descontos</th>
													<th>Cancelamentos</th>
													<th>Baixa Contábil</th>
													<th>Receitas Financeiras</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-duplicatas" tabindex="-1" aria-labelledby="modal-relatorio-duplicatasLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl bg-light modal-dialog-scrollable rounded">
		<div class="modal-content border-0">
			<div class="modal-header">
				<div>
					<h1 class="modal-title fs-5" id="modal-relatorio-duplicatasLabel"><i class="fas fa-long-arrow-alt-down cl-orange"></i> Portadores duplicatas</h1>
					<span id="filtro-geral" class="badge badge-gray"></span>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="body-modal-duplicatas">
				<div id="content-modal-body">
					<div class="p-1 mb-4 bg-body-tertiary rounded-3">
						<div class="container-fluid py-1">
							<h5 class="fw-bold"></h5>
							<div class="table-responsive">
								<table id="tbl-duplicatas" class="table first-start table-striped">
									<thead>
										<tr class="table-light">
											<th>Portadores</th>
											<th>Inclusões</th>
											<th>Liquidações</th>
											<th>Descontos</th>
											<th>Cancelamentos</th>
											<th>Baixa Contábil</th>
											<th>Receitas Financeiras</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-cheques" tabindex="-1" aria-labelledby="modal-relatorio-chequesLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl bg-light modal-dialog-scrollable rounded">
		<div class="modal-content border-0">
			<div class="modal-header">
				<div>
					<h1 class="modal-title fs-5" id="modal-relatorio-chequesLabel"><i class="fas fa-money-check cl-orange"></i> Cheques</h1>
					<span id="filtro-geral" class="badge badge-gray"></span>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="body-modal-duplicatas">
				<div id="content-modal-body">
					<div class="p-1 mb-4 bg-body-tertiary rounded-3">
						<div class="container-fluid py-1">
							<h5 class="fw-bold"></h5>
							<div class="table-responsive">
								<table id="tbl-cheques" class="table first-start table-striped">
									<thead>
										<tr class="table-light">
											<th>Portadores</th>
											<th>Inclusões</th>
											<th>Liquidações</th>
											<th>Descontos</th>
											<th>Cancelamentos</th>
											<th>Baixa Contábil</th>
											<th>Receitas Financeiras</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-total-portador-duplicatas" tabindex="-1" aria-labelledby="modal-relatorio-tpduplicatasLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl bg-light modal-dialog-scrollable rounded">
		<div class="modal-content border-0">
			<div class="modal-header">
				<div>
					<h1 class="modal-title fs-5" id="modal-relatorio-tpduplicatasLabel"><i class="fas fa-money-check cl-orange"></i> Totalizador portador duplicatas</h1>
					<span id="filtro-geral" class="badge badge-gray"></span>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="body-modal-tpduplicatas">
				<div id="content-modal-body">
					<div class="p-1 mb-4 bg-body-tertiary rounded-3">
						<div class="container-fluid py-1">
							<h5 class="fw-bold"></h5>
							<div class="table-responsive">
								<table id="tbl-tpduplicatas" class="table first-start table-striped">
									<thead>
										<tr class="table-light">
											<th>Portadores</th>
											<th>A vencer</th>
											<th>Vencido</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-total-portador-cheques" tabindex="-1" aria-labelledby="modal-relatorio-tpchequesLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl bg-light modal-dialog-scrollable rounded">
		<div class="modal-content border-0">
			<div class="modal-header">
				<div>
					<h1 class="modal-title fs-5" id="modal-relatorio-tpchequesLabel"><i class="fas fa-money-check cl-orange"></i> Totalizador portador cheques</h1>
					<span id="filtro-geral" class="badge badge-gray"></span>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="body-modal-tpcheques">
				<div id="content-modal-body">
					<div class="p-1 mb-4 bg-body-tertiary rounded-3">
						<div class="container-fluid py-1">
							<h5 class="fw-bold"></h5>
							<div class="table-responsive">
								<table id="tbl-tpcheques" class="table first-start table-striped">
									<thead>
										<tr class="table-light">
											<th>Portadores</th>
											<th>A vencer</th>
											<th>Vencido</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-periodo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="fa-solid fa-calendar cl-orange"></i> Informe um período</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row d-flex justify-content-center">
					<div class="col col-auto">
						<div class="input-group">
							<span class="m-1 badge bg-grey text-dark">de</span>
							<input type="date" name="data_inicial" min="2023-01-01" id="data_inicial" autofocus class="form-control form-control-md rounded">
							<span class="m-1 rounded badge bg-grey text-dark">até</span>
							<input type="date" min="2023-01-01" name="data_final" id="data_final" class="form-control form-control-md rounded">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" id="btn-filtro-intervalo">Aplicar filtro</button>
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
					<p class="fw-bold" id="modal-detalhes-grupo"></p>
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

<?= $view_modal_compartilhar ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.5.9/lottie.js"></script>
<script src="<?= site_url("public/assets/js/tabelas/tbl-resumo-estoque.js") ?>"></script>
<script src="<?= site_url("public/assets/js/charts/chart-condicao-pg.js") ?>"></script>
<script src="<?= site_url("public/assets/js/cards.js") ?>"></script>
<script src="<?= site_url('public/assets/js/modal/modal-compartilhar.js') ?>"></script>