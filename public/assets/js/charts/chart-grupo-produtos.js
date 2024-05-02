const renderizaChartGrupoProdutos = (data) => {

	resetCanvas('chart-grupos', 'content-chart-grupos');
	let canvas = document.getElementById("chart-grupos");
	new Chart(canvas, {
		type: "pie",
		data: {
			labels: data.map((row) => row.label),
			datasets: [
				{
					label: "Grupos de Produtos",
					data: data.map((row) => row.valor),
					backgroundColor: data.map((row, i) => UtilsHexadecimalColorsGeneric[i]),
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

							return "Grupos:";
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

	carregaTabelaGrupos(data);
};

const carregaTabelaGrupos = (data) => {

	$('#table-grupos').hide();
	let html = templateTabelaGrupos(data);
	$('#tbody-grupos').html(html);
	$('#table-grupos').show();
}

const templateTabelaGrupos = (data) => {

	let total = data.reduce((total, item) => total + item.valor, 0);
	template = "";

	data.forEach(function (item, i) {

		template += `
			<tr>
				<td>
					<div class='d-flex align-items-center mt-0 mb-0'>
						<span class="dot me-1" style='background: ${UtilsHexadecimalColorsGeneric[i]};'></span><strong>${item.label}</strong>
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
