const btnAplicar = document.getElementById("btn_buscar");
const data_inicial = document.getElementById("data_inicial");
const data_final = document.getElementById("data_final");
let data_formatada = new Date().toLocaleDateString('fr-CA', {
	year: 'numeric', month: '2-digit', day: '2-digit'
});
data_inicial.value = data_formatada;
data_final.value = data_formatada;

$('#data_inicial, #data_final').each(function () {

	$(this).change(function () {

		if ($(this).val() == '') {

			$("#empresas").prop("disabled", false);
		} else {
			$("#empresas").prop("disabled", true);
		}
	})
});

$("#empresas").change(() => get_frentistas_empresa());

document.onkeydown = function (e) {
	if ("Enter" == e.code) {
		btnAplicar.click();
	}
};

btnAplicar.addEventListener("click", function () {

	$("#empresas").prop("disabled", true);
	$("#data_inicial").prop("disabled", true);
	$("#data_final").prop("disabled", true);
	document.querySelector('#frentistas').disable();

	if ($("#data_inicial").val() != '') {

		let dt_inicial = date_ptBR_to_sql($("#data_inicial").val()),
			dt_final = date_ptBR_to_sql($("#data_final").val());

		if (valida_periodo(dt_inicial, dt_final)) {

			$("#frentistas").val().length == 0 ? document.querySelector('#frentistas').toggleSelectAll() : '';

			get_vendas();

		} else {

			Swal.fire({
				icon: "error",
				title: "Oops...",
				text: "Data inicial não pode ser maior que a data final!",
			});

			limpar_filtro();
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

const set_filtro_aplicado = (mensagem) => {

	$("#filtro_aplicado").html(mensagem);
}

const get_vendas = (filter = 1) => {

	let filtros = {

		chave: $("#empresas").val(),
		frentistas: $("#frentistas").val(),
		data_inicial: $("#data_inicial").val() == "" ? null : $("#data_inicial").val(),
		data_final: $("#data_final").val() == "" ? null : $("#data_final").val(),
	};

	$.ajax({
		type: "POST",
		url: `${BASE_URL}get-vendas-frentista`,
		dataType: "json",
		data: filtros,
		beforeSend: function () {
			Swal.fire({
				title: "Buscando informações!",
				html: "Aguarde...",
				didOpen: () => {
					Swal.showLoading();
				},
			});
		},
		success: function (res) {

			set_filtro_aplicado(res.filtro);

			if (res.data.vendas.length > 0) {

				carrega_accordions(res.data.vendas);

				$('[role="tab"]').each(function () {

					$(this).prop("disabled", false);
				});

				mark_dropdown(filter);
				processa_dados_chart(res.data.vendas, filter);
				set_icon_accordion();

			} else {

				$('#alert_accordions').slideDown("slow");
				Swal.close();
			}
		},
		error: function (error) {
			console.log(error);
			Swal.fire({
				icon: "error",
				title: "Oops!",
				text: "Parece que tivemos um erro, entre em contato com o suporte técnico!",
			});
		},
	});
};

const processa_dados_chart = (data, filter) => {

	let frentistas = filtra_frentistas(data),
		totais = [],
		dataset = [],
		value = 0.0;

	frentistas.forEach(frentista => {

		totais = filtra_total_vendas(frentista, data);

		switch (parseInt(filter)) {
			case 1:
				value = parseFloat(totais[1]);
				break;
			case 2:
				value = parseInt(totais[0]);
				break;
			case 3:
				value = parseFloat(totais[2]);
				break;
			case 4:
				value = parseFloat(totais[1]) + parseFloat(totais[2]);
				break;
		}

		dataset.push({
			nome: frentista,
			value: value
		});
	});

	render_chart_frentista(dataset, filter);
}

const get_frentistas_empresa = () => {

	$("#select_frentistas")
		.html(`
			<label class='input-group-text bg-body-secondary fw-bold' for="frentistas">
				<div class="spinner-border text-primary spinner-border-sm" role="status">
					<span class="visually-hidden">Loading...</span>
				</div>
			</label>
			<select disabled id="frentistas" class='form-select form-select-sm' name="frentista" data-search="false" data-silent-initial-value-set="true">
				<option>Carregando frentistas...</option>
			</select>
		`)
		.slideDown('fast');

	$.ajax({

		type: "POST",
		url: `${BASE_URL}/get-frentistas-empresa`,
		dataType: "json",
		data: { chave: $("#empresas").val() },
		success: function (res) {

			if (res.frentistas.length > 0) {

				$('#frentistas').html('');

				res.frentistas.forEach(frentista => {

					$('#frentistas').append($('<option>', {

						value: frentista.id,
						text: frentista.nome
					}));
				});

				$('#frentistas').prop('multiple', true);
				$("#frentistas").prop("disabled", false);

				VirtualSelect.init({

					ele: '#frentistas',
					multiple: true,
					placeholder: 'Selecione o(s) frentista(s).',
					optionsSelectedText: 'Opções selecionadas.',
					optionSelectedText: 'Opção selecionada.',
					allOptionsSelectedText: 'Todos',
					maxWidth: '600px',
					searchFormLabel: 'Buscar',
					clearButtonText: 'Limpar',
					selectAllText: 'Selecionar todos.',
					noSearchResultsTex: 'Nenhum resultado encontrado.',
					noOptionsText: 'Nenhum resultado encontrado.',
				});

				document.querySelector('#frentistas').reset();

				document.querySelector('#frentistas')
					.addEventListener('change', function () {

						this.value.length > 0 ? $("#empresas").prop("disabled", true) : $("#empresas").prop("disabled", false);
					});

			} else {

				$('#frentistas').html('<option class="fw-bold">Nenhum frentista encontrado</option>');
			}

			$('label[for="frentistas"]').html('Frentistas');
		},
		error: function (error) {
			console.log(error);
			Swal.fire({
				icon: "error",
				title: "Oops!",
				text: "Parece que tivemos um erro, entre em contato com o suporte técnico!",
			});
		},
	});
};

get_frentistas_empresa();

const carrega_accordions = (data) => {

	let template = '',
		frentistas = filtra_frentistas(data);

	frentistas.forEach(function (value, index) {

		let vendas = filtra_total_vendas(value, data);

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
							<div class="ms-2 info-badges d-flex flex-wrap align-items-center">
								<span class="badge text-bg-light">Total Abast. ${number_format(vendas[0], 0, ",", ".")}</span>
								<span class="badge text-bg-light">Vlr Comb. R$ ${number_format(vendas[1], 2, ",", ".")}</span>
								<span class="badge text-bg-light">Vlr Outros R$ ${number_format(vendas[2], 2, ",", ".")}</span>
								<span class="badge text-bg-success fw-bold">Total R$ ${number_format((parseFloat(vendas[1]) + parseFloat(vendas[2])).toString(), 2, ",", ".")}</span>
							</div>
						</div>

						<div id="collapse${index}" class="accordion-collapse collapse table-responsive" data-bs-parent="#accordion${index}">
							<div class="accordion-body">
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Data Venda</th>
											<th>Abastecimentos</th>
											<th class="text-end">Valor Combustível</th>
											<th class="text-end">Valor Outros</th>
											<th class="text-end">Total</th>
										</tr>
									</thead>
									<tbody id="${value.replace(/[^a-zA-Z]/g, '')}"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		`;
	});

	$('#accordion_grupos').html(template);

	let vendas = [],
		tbody = '',
		totalVendasF = '';

	frentistas.forEach(frentista => {

		vendas = [];
		tbody = '';

		data.forEach(item => {

			if (item.nome == frentista) {
				vendas.push(item);
			}
		});

		vendas.forEach(item => {

			tbody += `
				<tr>
					<td>${date_format(item.data)}</td>
					<td>${number_format(item.qtd_abastecimento, 0, ",", ".")}</td>
					<td class="text-end">R$ ${number_format(item.vlr_abastecimento, 2, ",", ".")}</td>
					<td class="text-end">R$ ${number_format(item.vlr_outros, 2, ",", ".")}</td>
					<td class="text-end">R$ ${number_format((parseFloat(item.vlr_outros) + parseFloat(item.vlr_abastecimento)).toString(), 2, ",", ".")}</td>
				</tr>
			`;
		});

		totalVendasF = filtra_total_vendas(frentista, vendas);

		tbody += `
			<tr class="text-nowrap">
				<td class="fw-bold">TOTAL GERAL</td>
				<td class="fw-bold">${number_format(totalVendasF[0], 0, ",", ".")}</td>
				<td class="text-end fw-bold">R$ ${number_format(totalVendasF[1], 2, ",", ".")}</td>
				<td class="text-end fw-bold">R$ ${number_format(totalVendasF[2], 2, ",", ".")}</td>
				<td class="text-end fw-bold">R$ ${number_format((parseFloat(totalVendasF[1]) + parseFloat(totalVendasF[2])).toString(), 2, ",", ".")}</td>
			</tr>
		`;

		$('#' + frentista.replace(/[^a-zA-Z]/g, '')).html(tbody);
	});

	Swal.fire({
		position: 'center',
		icon: 'success',
		title: 'Dados carregados com sucesso!',
		showConfirmButton: false,
		timer: 1500
	})

	let tt_qtd_abastecimentos = data.reduce((total, item) => total + parseFloat(item.qtd_abastecimento), 0),
		tt_valor_outros = data.reduce((total, item) => total + parseFloat(item.vlr_outros), 0),
		tt_vlr_abastecimentos = data.reduce((total, item) => total + parseFloat(item.vlr_abastecimento), 0),
		tt_Geral = tt_vlr_abastecimentos + tt_valor_outros;

	$('#content_totais').html(`
		<div class="d-flex flex-column justify-content-center">
			<div class="d-flex align-items-center">
				<button class="card-info-totais my-2 me-auto">Quantidade total de abastecimentos</button>
				<span class="badge bg-secondary">${number_format(tt_qtd_abastecimentos, 0, ",", ".")}</span>
			</div>
			<div class="d-flex align-items-center">
				<button class="card-info-totais my-2 me-auto">Valor total de abastecimentos </button>
				<span class="badge bg-secondary">RS ${number_format(tt_vlr_abastecimentos, 2, ",", ".")}</span>
			</div>
			<div class="d-flex align-items-center">
				<button class="card-info-totais my-2 me-auto">Valor total outros </button>
				<span class="badge bg-secondary">R$ ${number_format(tt_valor_outros, 2, ",", ".")}</span>
			</div>
			<div class="d-flex align-items-center">
				<button class="card-info-totais my-2 me-auto">Somatório valor abastecimentos e outros </button>
				<span class="badge bg-secondary">R$ ${number_format(tt_Geral, 2, ",", ".")}</span>
			</div>
		</div>
	`);

	$("#totalizadores").slideDown("slow");
	$("#accordion_grupos").slideDown("slow");
}

/**
 * Função que filtra os frentistas diferentes.
 * @param array data array de objetos com as vendas filtradas
 * @returns array de frentistas
 */
const filtra_frentistas = (data) => {

	const frentistasUnicos = [];

	data.filter(obj => {

		if (!frentistasUnicos.includes(obj.nome)) {

			frentistasUnicos.push(obj.nome);
		}
	});

	return frentistasUnicos;
}

const filtra_total_vendas = (frentista, data) => {

	let tt_qtd_abastecimentos = 0.0,
		tt_vlr_abastecimentos = 0.0,
		tt_valor_outros = 0.0;

	data.forEach(element => {

		if (element.nome == frentista) {
			tt_qtd_abastecimentos += parseFloat(element.qtd_abastecimento);
			tt_vlr_abastecimentos += parseFloat(element.vlr_abastecimento);
			tt_valor_outros += parseFloat(element.vlr_outros);
		}

	});

	return [
		(tt_qtd_abastecimentos.toFixed(2)).toString(),
		(tt_vlr_abastecimentos.toFixed(2)).toString(),
		(tt_valor_outros.toFixed(2)).toString()
	];
}

const limpar_filtro = () => {

	document.querySelector('#frentistas').enable();
	document.querySelector('#frentistas').reset();
	$('[role="tab"]').each(function () {

		$(this).prop("disabled", true);
	});
	$("#empresas").prop("disabled", false);
	$("#data_inicial").prop("disabled", false);
	$("#data_final").prop("disabled", false);
	$("#data_inicial").val(data_formatada);
	$("#data_final").val(data_formatada);
	$("#filtro_aplicado").html("");
	$("#totalizadores").hide();
	$('#accordion_grupos').html("");
	$('#content_frentista').html("");
	$('#alert_accordions').slideUp("fast");
	$("#home-tab").tab("show");
}

const OptionsDropdownFilter = document.querySelectorAll('.dropdown-item, .filter');
OptionsDropdownFilter.forEach(function (item) {

	item.addEventListener('click', () => {

		get_vendas(item.dataset.value);
		mark_dropdown(item.dataset.value);
	});
});

const mark_dropdown = (id) => {
	// <i class="fa-solid fa-check"></i>
	const OptionsDropdownFilter = document.querySelectorAll('.dropdown-item, .filter');
	OptionsDropdownFilter.forEach(function (item) {

		if (item.dataset.value == id) {

			$('#filtro_selecionado').text('Filtrando por: ' + item.innerText);
			item.innerHTML = `<i class="fa-solid fa-check me-1 fw-bold text-primary"></i><span class="text-primary">${item.innerText}</span>`;
		} else {

			item.innerHTML = item.innerText;
		}
	});
}

