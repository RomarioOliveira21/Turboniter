

const renderizaTabelaResumoEstoque = (data, idColumn, totalizador) => {

	if (idColumn == 1) {

		colunas = [

			{
				data: "grupo",
			},
			{
				data: "eiCustoMedio",
			},
			{
				data: "entradaCustoMedio",
			},
			{
				data: "saidaCustoMedio",
			},
			{
				data: "efCustoMedio",
			}
		];

	} else if (idColumn == 2) {

		colunas = [
			{
				data: "grupo",
			},
			{
				data: "eiPrecoReposicao",
			},
			{
				data: "entradaReposicao",
			},
			{
				data: "saidaReposicao",
			},
			{
				data: "efPrecoReposicao",
			}
		];

	} else {

		colunas = [
			{
				data: "grupo",
			},
			{
				data: "eiQuantidade",
			},
			{
				data: "entradaQuantidade",
			},
			{
				data: "saidaQuantidade",
			},
			{
				data: "efQuantidade",
			}
		];
	}

	if ($.fn.DataTable.isDataTable('#tbl-resumo-estoque')) {

		$('#tbl-resumo-estoque').off('click', '#btn-entradas');
		$('#tbl-resumo-estoque').off('click', '#btn-saidas');
		$('#tbl-resumo-estoque').DataTable().destroy();
	}

	setTimeout(() => {

		const table = new DataTable("#tbl-resumo-estoque", {
			retrieve: true,
			responsive: false,
			processing: true,
			language: {
				url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json",
			},
			data: data,
			columnDefs: [
				{
					targets: [1, 4],
					render: function (data, type, row, meta) {

						return (idColumn != 3 ? 'R$ ' : '') + arredondarNumero(data.toString());
					},
					className: 'dt-body-right'
				},
				{
					targets: [2],
					render: function (data, type, row, meta) {

						return data > 0 ? `<a type="buttom" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Visualizar entradas" class="text-decoration-none m-0 p-0 btn text-primary fw-bold" id="btn-entradas">${(idColumn != 3 ? 'R$ ' : '')} ${arredondarNumero(data.toString())}</a>` : (idColumn != 3 ? 'R$ ' : '') + arredondarNumero(data.toString());
					},
					className: 'dt-body-right'
				},
				{
					targets: [3],
					render: function (data, type, row, meta) {

						return data > 0 ? `<a type="buttom" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Visualizar saídas" class="text-decoration-none btn text-primary m-0 p-0 fw-bold" id="btn-saidas">${(idColumn != 3 ? 'R$ ' : '')} ${arredondarNumero(data.toString())}</a>` : (idColumn != 3 ? 'R$ ' : '') + arredondarNumero(data.toString());
					},
					className: 'dt-body-right'
				}
			],
			columns: colunas
		});

		table.on("click", "#btn-entradas", function () {

			exibirEntradasDetalhadas(table, this);
		});

		table.on("click", "#btn-saidas", function () {

			exibirSaidasDetalhadas(table, this);
		});

		$('#tbl-resumo-estoque').on('draw.dt', function () {

			const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
			const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
		});

	}, 10);

	let template = `
		<tr class="text-nowrap fw-bold">
			<td class="text-end">${(idColumn != 3 ? 'R$ ' : '')}${arredondarNumero(totalizador.totalEstoqueInicial.toString())}</td>
			<td class="text-end">${(idColumn != 3 ? 'R$ ' : '')}${arredondarNumero(totalizador.totalEntradas.toString())}</td>
			<td class="text-end">${(idColumn != 3 ? 'R$ ' : '')}${arredondarNumero(totalizador.totalSaidas.toString())}</td>
			<td class="text-end">${(idColumn != 3 ? 'R$ ' : '')}${arredondarNumero(totalizador.totalEstoqueFinal.toString())}</td>
		</tr>
	`;

	$('#total-resumo-estoque').html(template);
}

const exibirEntradasDetalhadas = async (table, target) => {

	let row = table.row($(target).closest("tr")).data();

	let data = await executaRequestAjax('Home/entradasDetalhadas', {

		grupo: row.grupo.substring(0, 2),
		chave: $("#empresas").val(),
		dia: $('#input-filtro-dia-estoque').text() == '' ? '' : date_ptBR_to_sql($('#input-filtro-dia-estoque').text(), '-'),
		filtro: $('#dropdown-principal .selected').data('value'),
		periodoInicial: data_inicial.value,
		periodoFinal: data_final.value
	});

	$('#modal-detalhesLabel').html('<i class="fas fa-search-dollar cl-orange"></i> Entradas Detalhadas');
	$('#modal-detalhes-grupo').html(row.grupo);

	let
		html = '',
		totalEntradaCustoMedio = 0,
		totalEntradaReposicao = 0,
		totalEntradaQuantidade = 0;

	data.forEach(element => {

		html += `
			<tr class="text-nowrap">
				<td class="fw-bold text-start">${element.id_produto.padStart(6, '0')}</td>
				<td class="text-start">${element.descricao}</td>
				<td class="text-end">${arredondarNumero(element.entrada_quantidade)}</td>
				<td class="text-end">R$ ${arredondarNumero(element.entrada_customedio)}</td>
				<td class="text-end">R$ ${arredondarNumero(element.entrada_reposicao)}</td>
			</tr>
		`;

		totalEntradaCustoMedio += parseFloat(element.entrada_customedio);
		totalEntradaReposicao += parseFloat(element.entrada_reposicao);
		totalEntradaQuantidade += parseFloat(element.entrada_quantidade);
	});

	html += `
		<tr class="text-nowrap fw-bold">
			<td colspan="2" class="text-end">TOTAL</td>
			<td class="text-end">${arredondarNumero(totalEntradaQuantidade.toString())}</td>
			<td class="text-end">R$ ${arredondarNumero(totalEntradaCustoMedio.toString())}</td>
			<td class="text-end">R$ ${arredondarNumero(totalEntradaReposicao.toString())}</td>
		</tr>
	`;

	$('#tbl-estoque-detalhes tbody').html(html);
	$('#modal-detalhes-estoque').modal('show');
	Swal.close();
}

const exibirSaidasDetalhadas = async (table, target) => {

	let row = table.row($(target).closest("tr")).data();

	let data = await executaRequestAjax('Home/saidasDetalhadas', {

		grupo: row.grupo.substring(0, 2),
		chave: $("#empresas").val(),
		dia: $('#input-filtro-dia-estoque').text() == '' ? '' : date_ptBR_to_sql($('#input-filtro-dia-estoque').text(), '-'),
		filtro: $('#dropdown-principal .selected').data('value'),
		periodoInicial: data_inicial.value,
		periodoFinal: data_final.value
	});

	let
		html = '',
		totalSaidaCustoMedio = 0,
		totalSaidaReposicao = 0,
		totalSaidaQuantidade = 0;

	data.forEach(element => {

		html += `
			<tr class="text-nowrap">
				<td class="fw-bold text-start">${element.id_produto.padStart(6, '0')}</td>
				<td class="text-start">${element.descricao}</td>
				<td class="text-end">${arredondarNumero(element.saida_quantidade)}</td>
				<td class="text-end">${arredondarNumero(element.saida_customedio)}</td>
				<td class="text-end">${arredondarNumero(element.saida_reposicao)}</td>
			</tr>
		`;

		totalSaidaCustoMedio += parseFloat(element.saida_customedio);
		totalSaidaReposicao += parseFloat(element.saida_reposicao);
		totalSaidaQuantidade += parseFloat(element.saida_quantidade);
	});

	html += `
		<tr class="text-nowrap fw-bold">
			<td colspan="2" class="text-end">TOTAL</td>
			<td class="text-end">${arredondarNumero(totalSaidaQuantidade.toString())}</td>
			<td class="text-end">${arredondarNumero(totalSaidaCustoMedio.toString())}</td>
			<td class="text-end">${arredondarNumero(totalSaidaReposicao.toString())}</td>
		</tr>
	`;


	$('#modal-detalhesLabel').html('<i class="fas fa-search-dollar cl-orange"></i> Saídas Detalhadas');
	$('#modal-detalhes-grupo').html(row.grupo);
	$('#tbl-estoque-detalhes tbody').html(html);
	$('#modal-detalhes-estoque').modal('show');
	Swal.close();
}

const buscaDadosEstoque = async (dia) => {

	let data = await executaRequestAjax('Home/getResumoEstoque', {

		dia: dia,
		empresa: $('#empresas').val()
	});

	Swal.close();

	if (!data.resumo_estoque.length) {

		$('#tabelas').hide();
		$('#alert-filtro-dia').show();
		return;
	}

	$('#alert-filtro-dia').hide();
	$('#tabelas').show();

	carregaTabelaResumoEstoque(data.resumo_estoque);
}

const alternaTiposValorEstoque = function (event, data) {

	let id = $(event.target).data('value');

	markDropdown(id, '#dropdown-resumo-estoque .filter');

	let totalizador = [];

	if (id == 1) {

		totalizador = {

			totalEstoqueInicial: data.reduce((total, item) => total + parseFloat(item.eiCustoMedio), 0),
			totalEntradas: data.reduce((total, item) => total + parseFloat(item.entradaCustoMedio), 0),
			totalSaidas: data.reduce((total, item) => total + parseFloat(item.saidaCustoMedio), 0),
			totalEstoqueFinal: data.reduce((total, item) => total + parseFloat(item.efCustoMedio), 0)
		};

	} else if (id == 2) {

		totalizador = {

			totalEstoqueInicial: data.reduce((total, item) => total + parseFloat(item.eiPrecoReposicao), 0),
			totalEntradas: data.reduce((total, item) => total + parseFloat(item.entradaReposicao), 0),
			totalSaidas: data.reduce((total, item) => total + parseFloat(item.saidaReposicao), 0),
			totalEstoqueFinal: data.reduce((total, item) => total + parseFloat(item.efPrecoReposicao), 0)
		};

	} else {

		totalizador = {

			totalEstoqueInicial: data.reduce((total, item) => total + parseFloat(item.eiQuantidade), 0),
			totalEntradas: data.reduce((total, item) => total + parseFloat(item.entradaQuantidade), 0),
			totalSaidas: data.reduce((total, item) => total + parseFloat(item.saidaQuantidade), 0),
			totalEstoqueFinal: data.reduce((total, item) => total + parseFloat(item.efQuantidade), 0)
		};
	}

	renderizaTabelaResumoEstoque(data, id, totalizador);
}

const controleTabelaResumoEstoque = (data) => {

	markDropdown(1, '#dropdown-resumo-estoque .filter');

	let totalizador = {

		totalEstoqueInicial: data.reduce((total, item) => total + parseFloat(item.eiCustoMedio), 0),
		totalEntradas: data.reduce((total, item) => total + parseFloat(item.entradaCustoMedio), 0),
		totalSaidas: data.reduce((total, item) => total + parseFloat(item.saidaCustoMedio), 0),
		totalEstoqueFinal: data.reduce((total, item) => total + parseFloat(item.efCustoMedio), 0)
	};

	renderizaTabelaResumoEstoque(data, 1, totalizador);

	const OptionsDropdownFilter = document.querySelectorAll('#dropdown-resumo-estoque .filter');
	OptionsDropdownFilter.forEach(function (item) {

		item.removeEventListener('click', (e) => alternaTiposValorEstoque(e, data), false)

		item.addEventListener('click', (e) => alternaTiposValorEstoque(e, data), false);
	});
}

const modalDetalhes = document.getElementById('modal-detalhes-estoque')
modalDetalhes.addEventListener('hidden.bs.modal', event => {

	$('#modal-relatorio-estoque').modal('show');
})

const modalEstoque = document.getElementById('modal-detalhes-estoque')
modalEstoque.addEventListener('show.bs.modal', event => {

	$('#modal-relatorio-estoque').modal('hide');
})



