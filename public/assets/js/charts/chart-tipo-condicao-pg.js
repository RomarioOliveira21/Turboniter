const processaDatasetCharts = (dataTpCPag, dataGrupos) => {

	let
		datasetTpConPag = [],
		datasetGrupo = [];

	dataTpCPag.forEach(item => {

		datasetTpConPag.push({
			valor: parseFloat(item.dia),
			tipo: item.tipo
		});
	});

	dataGrupos.forEach(item => {

		datasetGrupo.push({

			label: item.grupo,
			valor: item.dia
		});
	});

	renderizaChartGrupoProdutos(datasetGrupo);
	renderizaChartTipoConPag(datasetTpConPag, 1);
	carregaTabelaTpCondicaoPagamento(datasetTpConPag);

	$('#btn-filtro-chart-tcp').html('<i class="cl-orange fa-solid fa-filter"></i> Vendas Dia');
	const OptionsDropdownFilter = document.querySelectorAll('#dropdown-chart-tcp .filter');
	OptionsDropdownFilter.forEach(function (item) {

		item.addEventListener('click', function () {

			$('#btn-filtro-chart-tcp').html('<i class="cl-orange fa-solid fa-filter"></i> ' + item.textContent);
			let id = parseInt(item.dataset.value);
			markDropdown(id, '#dropdown-chart-tcp .filter');
			let
				datasetGrupo = [],
				datasetTpConPag = [];

			switch (id) {
				case 1:
					dataTpCPag.forEach(item => {
						datasetTpConPag.push({

							tipo: item.tipo,
							valor: parseFloat(item.dia)
						});
					});
					dataGrupos.forEach(item => {

						datasetGrupo.push({

							label: item.grupo,
							valor: item.dia
						});
					});
					renderizaChartTipoConPag(datasetTpConPag, id);
					renderizaChartGrupoProdutos(datasetGrupo);
					carregaTabelaTpCondicaoPagamento(datasetTpConPag);
					checkValoresDataset(datasetTpConPag.reduce((total, item) => total + item.valor, 0));
					break;
				case 2:
					dataTpCPag.forEach(item => {
						datasetTpConPag.push({

							tipo: item.tipo,
							valor: parseFloat(item.mes)
						});
					});
					dataGrupos.forEach(item => {

						datasetGrupo.push({

							label: item.grupo,
							valor: item.mes
						});
					});
					renderizaChartTipoConPag(datasetTpConPag, id);
					renderizaChartGrupoProdutos(datasetGrupo);
					carregaTabelaTpCondicaoPagamento(datasetTpConPag);
					checkValoresDataset(datasetTpConPag.reduce((total, item) => total + item.valor, 0));
					break;
				case 3:
					dataTpCPag.forEach(item => {
						datasetTpConPag.push({

							tipo: item.tipo,
							valor: parseFloat(item.mes_anterior)
						});
					});
					dataGrupos.forEach(item => {

						datasetGrupo.push({

							label: item.grupo,
							valor: item.mesAnterior
						});
					});
					renderizaChartTipoConPag(datasetTpConPag, id);
					renderizaChartGrupoProdutos(datasetGrupo);
					carregaTabelaTpCondicaoPagamento(datasetTpConPag);
					checkValoresDataset(datasetTpConPag.reduce((total, item) => total + item.valor, 0));
					break;
				case 4:
					dataTpCPag.forEach(item => {
						datasetTpConPag.push({

							tipo: item.tipo,
							valor: parseFloat(item.ano)
						});
					});
					dataGrupos.forEach(item => {

						datasetGrupo.push({

							label: item.grupo,
							valor: item.ano
						});
					});
					renderizaChartTipoConPag(datasetTpConPag, id);
					renderizaChartGrupoProdutos(datasetGrupo);
					carregaTabelaTpCondicaoPagamento(datasetTpConPag);
					checkValoresDataset(datasetTpConPag.reduce((total, item) => total + item.valor, 0));
					break;
				case 5:
					dataTpCPag.forEach(item => {

						datasetTpConPag.push({

							tipo: item.tipo,
							valor: parseFloat(item.ano_anterior)
						});
					});
					dataGrupos.forEach(item => {

						datasetGrupo.push({

							label: item.grupo,
							valor: item.anoAnterior
						});
					});
					renderizaChartTipoConPag(datasetTpConPag, id);
					carregaTabelaTpCondicaoPagamento(datasetTpConPag);
					renderizaChartGrupoProdutos(datasetGrupo);
					checkValoresDataset(datasetTpConPag.reduce((total, item) => total + item.valor, 0));
					break;
			}
		});
	});
};

const checkValoresDataset = (total) => {

	if (total < 1) {

		const Toast = Swal.mixin({
			toast: true,
			position: "center",
			showConfirmButton: false,
			timer: 4000,
			timerProgressBar: true,
			didOpen: (toast) => {
				toast.onmouseenter = Swal.stopTimer;
				toast.onmouseleave = Swal.resumeTimer;
			}
		});
		Toast.fire({
			icon: "warning",
			title: 'nenhum valor foi encontrado, altere o filtro!'
		});
		$('#content-charts').hide();

	} else {

		$('#content-charts').show();
	}
}

const carregaTabelaTpCondicaoPagamento = (data) => {

	$('#table-grafic-pie').hide();
	let html = templateTabelaTpCondicaoPagamento(data);
	$('#tbody-grafic-pie').html(html);
	$('#table-grafic-pie').show();
}

const templateTabelaTpCondicaoPagamento = (data) => {

	let total = data.reduce((total, item) => total + item.valor, 0);
	template = "";

	data.forEach(function (item, i) {

		template += `
			<tr>
				<td>
					<div class='d-flex align-items-center mt-0 mb-0'>
						<span class="dot me-1" style='background: ${UtilsHexadecimalColors[item.tipo]};'></span><strong>${TipoCondicaoPag[item.tipo]}</strong>
					</div>
					<br>
					<div class='d-flex justify-content-between'>
						<span>R$ ${number_format(item.valor, 2, ',', '.')}</span>
						<span>${item.valor !== 0 ? ((item.valor * 100) / total).toFixed(2) : 0}%</span>
					</div>
				</td>
			</tr>
		`;
	});

	return template;
}

const renderizaChartTipoConPag = (data, filtro) => {

	resetCanvas('chart-tipo-condicao', 'content-chart-tipo-condicao');
	let canvas = document.getElementById("chart-tipo-condicao");
	markDropdown(filtro, '#dropdown-chart-tcp .filter');

	new Chart(canvas, {
		type: "pie",
		data: {
			labels: data.map((row) => TipoCondicaoPag[row.tipo]),
			datasets: [
				{
					label: "Tipo Con. Pag.",
					data: data.map((row) => row.valor),
					backgroundColor: data.map((row) => UtilsHexadecimalColors[row.tipo]),
					borderWidth: 1,
				},
			],
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				tooltip: {
					callbacks: {
						title: function () {

							return "T.C.P.:";
						},
						label: function (context) {

							return context.label + ' - R$ ' + context.formattedValue;
						}
					},
				},
				legend: {

					display: false,
				}
			},
		},
	});
}
