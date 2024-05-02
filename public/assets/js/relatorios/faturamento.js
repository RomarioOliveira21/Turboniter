const btnAplicar = document.getElementById("btn_buscar");
const data_filtro = document.getElementById("data");
let data_formatada = new Date().toLocaleDateString('fr-CA', {

	year: 'numeric', month: '2-digit', day: '2-digit'
});

data_filtro.value = data_formatada;

$('#data').change(function () {

	if ($(this).val() == '') {

		$("#empresas").prop("disabled", false);
	} else {
		$("#empresas").prop("disabled", true);
	}
})

btnAplicar.addEventListener("click", async function () {

	$("#empresas").prop("disabled", true);
	$("#data").prop("disabled", true);

	if ($("#data").val() != '') {

		let result = await executaRequestFetch('check-data', {

			dataFinal: data_filtro.value,
			empresa: $('#empresas').val()
		});

		$('#input-filtro-dia').text(date_format(data_filtro.value));

		if (result.error) {

			const Toast = Swal.mixin({

				toast: true,
				position: "center",
				showConfirmButton: false,
				timer: 5000,
				timerProgressBar: true,
				didOpen: (toast) => {

					toast.onmouseenter = Swal.stopTimer;
					toast.onmouseleave = Swal.resumeTimer;
				}
			});
			Toast.fire({

				icon: "error",
				title: `Data informada é maior que a última data de movimento (${result.dataUltExportacao}), altere para a mesma ou informe uma data menor!`
			});

			data_filtro.value = date_ptBR_to_sql(result.dataUltExportacao, '-');

		} else {

			preencheCardsRelatorio();
		}

	} else {

		Swal.fire({
			icon: "error",
			title: "Oops...",
			text: "É necessário definir pelo menos um PERÍODO VÁLIDO.",
		});

		limpar_filtro();
	}
});

document.onkeydown = function (e) {

	if ("Enter" == e.code) {

		btnAplicar.click();
	}
};

const buttonsPrevNext = document.querySelectorAll('#content-prev-next button');
buttonsPrevNext.forEach(function (item) {

	item.addEventListener('click', () => {

		let data = item.id == 'prev' ? obterDiaAnterior($("#input-filtro-dia").text(), "#input-filtro-dia") : obterDiaPosterior($("#input-filtro-dia").text(), "#input-filtro-dia");

		if (data != '') {

			data_filtro.value = data;
			preencheCardsRelatorio(data);
		}
	});
});

const preencheCardsRelatorio = async (data = '') => {

	$('#content-cards-tipo-condicao').slideUp('fast');

	let totais = {
		dia: 0.0,
		mes: 0.0,
		mesAnterior: 0.0,
		ano: 0.0,
		anoAnterior: 0.0
	};

	let result = await executaRequestAjax('Relatorio/getDadosFaturamento', {

		empresa: $('#empresas').val(),
		data: data == '' ? data_filtro.value : data,
	});

	let templateCards = "";

	$("#home-tab").tab("show");

	if (!result.vendas_tipo_condicao.every((item) => parseFloat(item.ano) == 0)) {

		$('#content-tabs').slideDown('fast');
		$("#profile-tab").prop("disabled", false);
		$("#r-grupo-tab").prop("disabled", false);

		result.vendas_tipo_condicao.forEach(element => {

			totais.dia += parseFloat(element.dia);
			totais.mes += parseFloat(element.mes);
			totais.mesAnterior += parseFloat(element.mes_anterior);
			totais.ano += parseFloat(element.ano);
			totais.anoAnterior += parseFloat(element.ano_anterior);

			templateCards += cardTemplate(element);
		});

		processaDatasetCharts(result.vendas_tipo_condicao, processaDadosGrupos(result.vendas_grupo));

		let dadosFiltrados = result.vendas_grupo.filter(item => parseFloat(item.ano) > 0);

		carregaAccordions(dadosFiltrados);

		$('#cards-tipo-condicao').html(templateCards);

		$('#cards-tipo-condicao').append(`
			<div class="col-12">
				<div class="table-responsive">
					<table class="table table-borderless table-striped">
						<thead>
							<tr>
								<th colspan="5"><i class="fas fa-calculator cl-orange"></i> Totalizador Tipos de Condição de Pagamento<hr></th>
							</tr>
							<tr>
								<th class="text-center">Ano Ant.</th>
								<th class="text-center">Ano</th>
								<th class="text-center">Mês Ant.</th>
								<th class="text-center">Mês</th>
								<th class="text-center">Dia</th>
							</tr>
						</thead>
						<tbody>
							<tr class="text-nowrap">
								<td class="text-center">R$ ${number_format(totais.anoAnterior.toString(), 2, ",", ".")}</td>
								<td class="text-center">R$ ${number_format(totais.ano.toString(), 2, ",", ".")}</td>
								<td class="text-center">R$ ${number_format(totais.mesAnterior.toString(), 2, ",", ".")}</td>
								<td class="text-center">R$ ${number_format(totais.mes.toString(), 2, ",", ".")}</td>
								<td class="text-center">R$ ${number_format(totais.dia.toString(), 2, ",", ".")}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		`);

		set_icon_accordion();

	} else {

		$("#profile-tab").prop("disabled", true);
		$("#r-grupo-tab").prop("disabled", true);
		$('#cards-tipo-condicao').html(alertHTML());
	}

	Swal.close();
	$('#content-cards-tipo-condicao').slideDown('slow');
}

const processaDadosGrupos = (data) => {

	let
		dadosFiltrados = data.filter(item => parseFloat(item.ano) > 0),
		grupos = filtrarObjetos(dadosFiltrados, 'grupo'),
		dataGrupos = [],
		dia = 0.0,
		mes = 0.0,
		mesAnterior = 0.0,
		ano = 0.0,
		anoAnterior = 0.0;


	grupos.forEach(grupo => {

		dia = 0.0;
		mes = 0.0;
		mesAnterior = 0.0;
		ano = 0.0;
		anoAnterior = 0.0;

		dadosFiltrados.forEach(item => {

			if (item.grupo == grupo) {

				dia += parseFloat(item.dia);
				mes += parseFloat(item.mes);
				mesAnterior += parseFloat(item.mes_anterior);
				ano += parseFloat(item.ano);
				anoAnterior += parseFloat(item.ano_anterior);
			}
		});

		dataGrupos.push({
			grupo: grupo,
			dia: dia,
			mes: mes,
			mesAnterior: mesAnterior,
			ano: ano,
			anoAnterior: anoAnterior
		});
	});

	return dataGrupos;
}

const getTotalVendasGrupo = (grupo, data) => {

	let totais = {
		dia: 0.0,
		mes: 0.0,
		mesAnterior: 0.0,
		ano: 0.0,
		anoAnterior: 0.0
	};

	data.forEach(element => {

		if (element.grupo == grupo) {

			totais.dia += parseFloat(element.dia);
			totais.mes += parseFloat(element.mes);
			totais.mesAnterior += parseFloat(element.mes_anterior);
			totais.ano += parseFloat(element.ano);
			totais.anoAnterior += parseFloat(element.ano_anterior);
		}
	});

	return totais;
}

const carregaAccordions = (data) => {

	let template = '',
		grupos = filtrarObjetos(data, 'grupo'),
		subgrupos = filtrarObjetos(data, 'subgrupo'),
		dia = 0, mes = 0, mesAnterior = 0, ano = 0, anoAnterior = 0;


	grupos.forEach(function (value, index) {

		let totais = getTotalVendasGrupo(value, data);
		dia += totais.dia;
		mes += totais.mes;
		mesAnterior += totais.mesAnterior;
		ano += totais.ano;
		anoAnterior += totais.anoAnterior;

		template += `
			<div class="col-12">
				<div class="accordion" id="accordion${index}">
					<div class="accordion-item">
						<div class="p-2 d-flex">
							<div class="header me-auto">
								<button class="btn btn-sm btn-light collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${index}" aria-expanded="true" aria-controls="collapse${index}">
									<span class="badge text-bg-secondary"><i class="fas fa-plus"></i></span> ${value}
								</button>
							</div>
							<div class="ms-2 text-end d-flex align-items-center">
								<span class="badge text-bg-success fw-bold">Total Dia R$ ${number_format(totais.dia.toString(), 2, ",", ".")}</span>
							</div>
						</div>

						<div id="collapse${index}" class="accordion-collapse collapse" data-bs-parent="#accordion${index}">
							<div class="accordion-body">
								<div class="table-responsive">
									<table class="table table-borderless table-striped">
										<thead>
											<tr>
												<th colspan="4"><i class="fas fa-calculator cl-orange"></i> Totalizador <hr></th>
											</tr>
											<tr>
												<th class="text-center">Ano Ant.</th>
												<th class="text-center">Ano</th>
												<th class="text-center">Mês Ant.</th>
												<th class="text-center">Mês</th>
											</tr>
										</thead>
										<tbody>
											<tr class="text-nowrap">
												<td class="text-center">R$ ${number_format(totais.anoAnterior.toString(), 2, ",", ".")}</td>
												<td class="text-center">R$ ${number_format(totais.ano.toString(), 2, ",", ".")}</td>
												<td class="text-center">R$ ${number_format(totais.mesAnterior.toString(), 2, ",", ".")}</td>
												<td class="text-center">R$ ${number_format(totais.mes.toString(), 2, ",", ".")}</td>
											</tr>
										</tbody>
									</table>
								</div>
								<span class="fw-bold mb-2 ms-2"><i class="fas fa-shapes cl-orange"></i> Subgrupos</span>
								<hr>
								<div class="d-flex flex-column gap-2" id="${value.replace(/[^a-zA-Z]/g, '')}"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		`;
	});

	$('#accordions-grupo').html(template);

	$('#accordions-grupo').append(`
		<div class="table-responsive">
			<table class="table table-borderless table-striped">
				<thead>
					<tr>
						<th colspan="5"><i class="fas fa-calculator cl-orange"></i> Totalizador Grupos<hr></th>
					</tr>
					<tr>
						<th class="text-center">Ano Ant.</th>
						<th class="text-center">Ano</th>
						<th class="text-center">Mês Ant.</th>
						<th class="text-center">Mês</th>
						<th class="text-center">Dia</th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-nowrap">
						<td class="text-center">R$ ${number_format(anoAnterior.toString(), 2, ",", ".")}</td>
						<td class="text-center">R$ ${number_format(ano.toString(), 2, ",", ".")}</td>
						<td class="text-center">R$ ${number_format(mesAnterior.toString(), 2, ",", ".")}</td>
						<td class="text-center">R$ ${number_format(mes.toString(), 2, ",", ".")}</td>
						<td class="text-center">R$ ${number_format(dia.toString(), 2, ",", ".")}</td>
					</tr>
				</tbody>
			</table>
		</div>
	`);

	grupos.forEach(item => {

		$('#' + item.replace(/[^a-zA-Z]/g, '')).append(accordionSubgrupo(item, subgrupos, data));
	});

	subgrupos.forEach(item => {

		$('#sub-' + item.replace(/[^a-zA-Z]/g, '')).append(tabelaProduto(item, grupos, data));
	});
}

const tabelaProduto = (subgrupo, grupos, data) => {

	let template = `
		<div class="p-2">
			<table class="table table-responsive table-borderless table-striped">
				<thead>
					<tr>
						<th>Produto</th>
						<th class="text-center">Ano Ant.</th>
						<th class="text-center">Ano</th>
						<th class="text-center">Mês Ant.</th>
						<th class="text-center">Mês</th>
						<th class="text-center">Dia</th>
					</tr>
				</thead>
				<tbody>
	`;

	data.sort((a, b) => {

		const nomeA = a.descricao.toUpperCase();
		const nomeB = b.descricao.toUpperCase();

		if (nomeA < nomeB) {
			return -1;
		}
		if (nomeA > nomeB) {
			return 1;
		}
		// Nomes são iguais
		return 0;
	});

	let totais = {
		dia: 0.0,
		mes: 0.0,
		mesAnterior: 0.0,
		ano: 0.0,
		anoAnterior: 0.0
	};

	grupos.forEach(grupo => {

		data.forEach(item => {

			if (item.grupo == grupo & item.subgrupo == subgrupo) {

				template += `
					<tr class="text-nowrap">
						<td>${item.id_produto.padStart(6, '0')} - ${item.descricao}</td>
						<td class="text-center">R$ ${number_format(item.anoAnterior, 2, ",", ".")}</td>
						<td class="text-center">R$ ${number_format(item.ano, 2, ",", ".")}</td>
						<td class="text-center">R$ ${number_format(item.mesAnterior, 2, ",", ".")}</td>
						<td class="text-center">R$ ${number_format(item.mes, 2, ",", ".")}</td>
						<td class="text-center">R$ ${number_format(item.dia, 2, ",", ".")}</td>
					</tr>
				`;

				totais.dia += parseFloat(item.dia);
				totais.mesAnterior += parseFloat(item.mesAnterior);
				totais.mes += parseFloat(item.mes);
				totais.anoAnterior += parseFloat(item.anoAnterior);
				totais.ano += parseFloat(item.ano);
			}
		});
	});

	template += `
		<tr class="text-nowrap">
			<td class="fw-bold">TOTAL</td>
			<td class="text-center fw-bold">R$ ${number_format(totais.anoAnterior.toString(), 2, ",", ".")}</td>
			<td class="text-center fw-bold">R$ ${number_format(totais.ano.toString(), 2, ",", ".")}</td>
			<td class="text-center fw-bold">R$ ${number_format(totais.mesAnterior.toString(), 2, ",", ".")}</td>
			<td class="text-center fw-bold">R$ ${number_format(totais.mes.toString(), 2, ",", ".")}</td>
			<td class="text-center fw-bold">R$ ${number_format(totais.dia.toString(), 2, ",", ".")}</td>
		</tr>
	`;

	template += `</tbody>
			</table>
		</div>
	`;

	return template;
}

const accordionSubgrupo = (grupo, subgrupos, data) => {

	let template = "",
		total = 0.0;

	subgrupos.forEach(function (subgrupo, index) {

		let exists = false;

		data.forEach(item => {

			if (item.grupo == grupo & item.subgrupo == subgrupo) {
				total += parseFloat(item.dia);
				exists = true;
			}
		});

		if (exists) {

			template += `
				<div class="accordion" id="subAccordion${index}">
					<div class="accordion-item">
						<div class="p-2 d-flex">
							<div class="header me-auto">
								<button class="btn btn-sm btn-light collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subcollapse${index}" aria-expanded="true" aria-controls="subcollapse${index}">
									<span class="badge text-bg-secondary"><i class="fas fa-plus"></i></span> ${subgrupo}
								</button>
							</div>
							<div class="ms-2 text-end d-flex align-items-center">
								<span class="badge text-bg-success fw-bold">Total Dia R$ ${number_format(total.toString(), 2, ",", ".")}</span>
							</div>
						</div>
						<div id="subcollapse${index}" class="accordion-collapse collapse table-responsive" data-bs-parent="#subAccordion${index}">
							<div class="accordion-body" id="sub-${subgrupo.replace(/[^a-zA-Z]/g, '')}"></div>
						</div>
					</div>
				</div>
			`;
		}
	});

	return template
}

const cardTemplate = (data) => {

	return `
		<div class="col-lg-auto col-md-auto col-sm-auto m-0 p-1">
			<div class="cookie-card cookie-card__height_relatorio d-flex flex-column">
				<span class="title d-flex align-items-center"><div class="square me-2 rounded ${UtilsBgColors[data.tipo]}"></div> ${TipoCondicaoPag[data.tipo]}</span>
				<hr>
				<div class="mb-1 mt-auto d-flex flex-column gap-2 description-small">
					<div class="d-flex align-items-center justify-content-between">
						<small>Dia</small><small class="badge text-bg-success">R$ ${number_format(data.dia, 2, ",", ".")}</small>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<small>Mês</small><small class="badge text-bg-success">R$ ${number_format(data.mes, 2, ",", ".")}</small>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<small>Mês Anterior</small><small class="badge text-bg-success">R$ ${number_format(data.mes_anterior, 2, ",", ".")}</small>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<small>Ano</small><small class="badge text-bg-success">R$ ${number_format(data.ano, 2, ",", ".")}</small>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<small>Ano Anterior</small><small class="badge text-bg-success">R$ ${number_format(data.ano_anterior, 2, ",", ".")}</small>
					</div>
				</div>
			</div>
		</div>
	`;
}

const limpar_filtro = () => {

	$("#empresas").prop("disabled", false);
	$("#data").prop("disabled", false);
	$("#data").val(data_formatada);
	$('#content-tabs').hide();
}
