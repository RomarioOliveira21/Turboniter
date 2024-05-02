jQuery(function () {

	const OptionsDropdownFilter = document.querySelectorAll('#dropdown-principal .filter');
	OptionsDropdownFilter.forEach(function (item) {

		item.addEventListener('click', function () {

			let id = item.dataset.value;

			if (id == 6) {

				document.getElementById('data_inicial').focus();
				$("#modal-periodo").modal("show")
			} else {

				getDadosCards(id);
			}

			if (id == 5) {

				$('.prev-next').each((index, target) => {

					$(target).addClass("d-flex").removeClass("d-none");
				});

			} else {

				$('.prev-next').each((index, target) => {

					$(target).removeClass("d-flex").addClass("d-none");
				});
			}
		});
	});

	$('#content-cards').slideToggle('slow');

	valida_filtro_periodo();

	getDadosCards();

	setDataMovimento();
});

const buttonsPrevNext = document.querySelectorAll('div[data-bs-nivel="principal"] button');
buttonsPrevNext.forEach(function (item) {

	item.addEventListener('click', () => {

		let data = item.id == 'prev'
			? obterDiaAnterior($('div[data-bs-nivel="principal"] p').text(), 'div[data-bs-nivel="principal"] p')
			: obterDiaPosterior($('div[data-bs-nivel="principal"] p').text(), 'div[data-bs-nivel="principal"] p');

		if (data != '') {

			$('.prev-next').each(function () {

				if ($(this).data('bs-nivel') !== 'principal') {

					$(this).find('p').text(date_format(data));
				};
			});

			getDadosCards(6, data);
		}
	});
});

const buttonsPrevNextPagamento = document.querySelectorAll('div[data-bs-nivel="pagamento"] button');
buttonsPrevNextPagamento.forEach(function (item) {

	item.addEventListener('click', () => {

		let data = item.id == 'prev'
			? obterDiaAnterior($('div[data-bs-nivel="pagamento"] p').text(), 'div[data-bs-nivel="pagamento"] p')
			: obterDiaPosterior($('div[data-bs-nivel="pagamento"] p').text(), 'div[data-bs-nivel="pagamento"] p');

		if (data != '') {

			$('#modal-pagamento .modal-header span').html('<i class="fas fa-filter"></i> Dia ' + date_format(data));

			buscaDadosPagamento(data);
		}
	});
});

const buttonsPrevNextRecebimento = document.querySelectorAll('div[data-bs-nivel="recebimento"] button');
buttonsPrevNextRecebimento.forEach(function (item) {

	item.addEventListener('click', () => {

		let data = item.id == 'prev'
			? obterDiaAnterior($('div[data-bs-nivel="recebimento"] p').text(), 'div[data-bs-nivel="recebimento"] p')
			: obterDiaPosterior($('div[data-bs-nivel="recebimento"] p').text(), 'div[data-bs-nivel="recebimento"] p');

		if (data != '') {

			$('#modal-recebimento .modal-header span').html('<i class="fas fa-filter"></i> Dia ' + date_format(data));

			buscaDadosRecebimento(data);
		}
	});
});

const buttonsPrevNextEstoque = document.querySelectorAll('div[data-bs-nivel="estoque"] button');
buttonsPrevNextEstoque.forEach(function (item) {

	item.addEventListener('click', () => {

		let data = item.id == 'prev'
			? obterDiaAnterior($('div[data-bs-nivel="estoque"] p').text(), 'div[data-bs-nivel="estoque"] p')
			: obterDiaPosterior($('div[data-bs-nivel="estoque"] p').text(), 'div[data-bs-nivel="estoque"] p');

		if (data != '') {

			$('.header-modal-estoque div span').html('<i class="fas fa-filter"></i> Dia ' + date_format(data));

			buscaDadosEstoque(data);
		}
	});
});

const buttonsPrevNextVenda = document.querySelectorAll('div[data-bs-nivel="venda"] button');
buttonsPrevNextVenda.forEach(function (item) {

	item.addEventListener('click', () => {

		let data = item.id == 'prev'
			? obterDiaAnterior($('div[data-bs-nivel="venda"] p').text(), 'div[data-bs-nivel="venda"] p')
			: obterDiaPosterior($('div[data-bs-nivel="venda"] p').text(), 'div[data-bs-nivel="venda"] p');

		if (data != '') {

			$('.header-modal-venda div span').html('<i class="fas fa-filter"></i> Dia ' + date_format(data));

			buscaDadosVenda(data);
		}
	});
});

const buscaDadosPagamento = async (dia) => {

	let data = await executaRequestAjax('Home/getPagamentos', {

		dia: dia,
		empresa: $('#empresas').val()
	});

	Swal.close();

	if (!data.pagamentos.length) {

		$('#modal-pagamento #tabelas').hide();
		$('#body-modal-pagamento #alert-dia').show();
		$('#modal-pagamento #cp-a-vencer').text('R$ 0,00');
		$('#modal-pagamento #cp-vencido').text('R$ 0,00');
		$('#modal-pagamento #cp-total').text('R$ 0,00');
		return;
	}

	$('#modal-pagamento #tabelas').show();
	$('#body-modal-pagamento #alert-dia').hide();

	let
		aVencerCP = data.pagamentos.reduce((total, item) => total + parseFloat(item.saldo_vencer), 0),
		vencidoCP = data.pagamentos.reduce((total, item) => total + parseFloat(item.saldo_vencido), 0);

	$('#modal-pagamento #cp-a-vencer').text('R$ ' + number_format(aVencerCP.toString(), 2, ",", "."));
	$('#modal-pagamento #cp-vencido').text('R$ ' + number_format(vencidoCP.toString(), 2, ",", "."));
	$('#modal-pagamento #cp-total').text('R$ ' + number_format((aVencerCP + vencidoCP).toString(), 2, ",", "."));

	montaTabelaPagamentos(data.pagamentos);
}

const buscaDadosRecebimento = async (dia) => {

	let data = await executaRequestAjax('Home/getRecebimentos', {

		dia: dia,
		empresa: $('#empresas').val()
	});

	Swal.close();

	if (!data.recebimentos.length) {

		$('#modal-recebimento #tabelas').hide();
		$('#body-modal-recebimento #alert-dia').show();
		return;
	}

	$('#modal-recebimento #tabelas').show();
	$('#body-modal-recebimento #alert-dia').hide();
	montaTabelaRecebimentos(data.recebimentos);
}

const buscaDadosVenda = async (dia) => {

	let data = await executaRequestAjax('Home/getVendas', {

		dia: dia,
		empresa: $('#empresas').val()
	});

	Swal.close();

	if (data.vendas.length == 0) {

		$('#modal-relatorio-vendas #content-modal-body').hide();
		$('#modal-relatorio-vendas #alert').show();
		return;
	}

	carregaChartCondicaoPag(data.vendas);
	$('#modal-relatorio-vendas #content-modal-body').show();
	$('#modal-relatorio-vendas #alert').hide();
	$('#modal-relatorio-vendas #filtro-geral').html(data.periodo);
}

const setDataMovimento = async () => {

	let data = await executaRequestFetch('Home/getDataUltimoMovimento', { empresa: $('#empresas').val() });

	if (data) {

		$('.prev-next p').each((index, target) => {


			$(target).text(date_format(data));
		});

	} else {

		Swal.fire({

			icon: 'warning',
			title: 'Oops...',
			text: 'Não foi encontrado nenhum registro na base de dados, verifique se já houve alguma exportação pelo SIG.',
		});
	}
}

const valida_filtro_periodo = () => {

	const btnAplicar = document.getElementById('btn-filtro-intervalo');
	const data_inicial = document.getElementById("data_inicial");
	const data_final = document.getElementById("data_final");
	let data_formatada = new Date().toLocaleDateString('fr-CA', {

		year: 'numeric', month: '2-digit', day: '2-digit'
	});
	data_inicial.value = data_formatada;
	data_final.value = data_formatada;

	btnAplicar.addEventListener('click', async function () {

		if (valida_periodo($('#data_inicial').val(), $('#data_final').val())) {

			let result = await executaRequestFetch('check-data', {

				dataFinal: data_final.value,
				empresa: $('#empresas').val()
			});

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
					title: `Data final informada é maior que a última data de movimento (${result.dataUltExportacao}), altere para a mesma ou informe uma data menor!`
				});

			} else {

				$("#modal-periodo").modal("hide");

				if ($('#data_inicial').val() === $('#data_final').val()) {

					$('.prev-next').each((index, target) => {

						$(target).addClass("d-flex").removeClass("d-none");
						$(target).find('p').text(date_format(data_final.value));
					});

				} else {

					$('.prev-next').each((index, target) => {

						$(target).removeClass("d-flex").addClass("d-none");
					});
				}

				getDadosCards(6);
			}

		} else {

			Swal.fire({

				icon: 'error',
				title: 'Oops...',
				text: 'Data inicial não pode ser maior que a data final!',
			});
		}
	});
}

const getLinkRelatorio = async (idTargetDia) => {

	let
		dia = $(`div[data-bs-nivel="${idTargetDia}"] p`).text() == ''
			? ''
			: date_ptBR_to_sql($(`div[data-bs-nivel="${idTargetDia}"] p`).text(), '-'),

		filtro = dia == date_ptBR_to_sql($('div[data-bs-nivel="principal"] p').text(), '-')
			? $('#dropdown-principal .selected').data('value')
			: 7;

	let url = await executaRequestAjax('Relatorio/linkRelatorio',
		{
			tipo: idTargetDia,
			origem: base_url(),
			rota: 'relatorio-compartilhado',
			chave: $("#empresas").val(),
			dia: dia,
			filtro: filtro,
			periodoInicial: data_inicial.value,
			periodoFinal: data_final.value
		},
		{
			textTitle: 'Gerando link.',
		}
	);

	return url;
}

const getDadosCards = async (filtro = 5, diaSelecionado = '') => {

	try {

		markDropdown(filtro, '#dropdown-principal .filter');

		let data = {

			dia: diaSelecionado,
			filtro: filtro,
			empresa: $('#empresas').val()
		};

		if (filtro == 6) {

			data.periodoInicial = data_inicial.value;
			data.periodoFinal = data_final.value
		}

		let result = await executaRequestAjax('get-dados-cards', data);

		Swal.close();

		setDadosCards(result);

	} catch (error) {

		console.log(error);
	}
}

const setDadosCards = (data) => {

	const spans = document.querySelectorAll('#filtro-geral');

	spans.forEach(element => {

		element.innerHTML = `<i class="fa-solid fa-filter"></i> ${data.periodo}`;
	});

	// card estoque -----------------------------------------------------------------------

	if (data.resumo_estoque.length) {

		$('#modal-relatorio-estoque #content-modal-body').show();
		$('#modal-relatorio-estoque #alert').hide();

		let
			custoMedio = data.resumo_estoque.reduce((total, item) => total + parseFloat(item.estoque_final_customedio), 0),
			custoReposicao = data.resumo_estoque.reduce((total, item) => total + parseFloat(item.estoque_final_reposicao), 0);

		$('#custo-medio').text(`R$ ${number_format(custoMedio.toString(), 2, ",", ".")}`);
		$('#custo-reposicao').text(`R$ ${number_format(custoReposicao.toString(), 2, ",", ".")}`);
		carregaTabelaResumoEstoque(data.resumo_estoque);

	} else {

		$('#modal-relatorio-estoque #content-modal-body').hide();
		$('#modal-relatorio-estoque #alert').show();
		$('#custo-medio').text('R$ 0,00');
		$('#custo-reposicao').text('R$ 0,00');
	}


	// card vendas -----------------------------------------------------------------------

	if (data.total_vendas.length) {

		$('#modal-relatorio-vendas #content-modal-body').show();
		$('#modal-relatorio-vendas #alert').hide();
		carregaChartCondicaoPag(data.total_vendas);

	} else {

		$('#modal-relatorio-vendas #content-modal-body').hide();
		$('#modal-relatorio-vendas #alert').show();
		$('#card-vlr-total-vendas').text('R$ 0,00');
	}


	// card contas a pagar -----------------------------------------------------------------------

	let
		vlrAVencerContasPagar = null,
		vlrVencidoContasPagar = null,
		vlrTotalContasPagar = null;

	if (data.pagamentos.length) {

		$('#modal-pagamento #content-modal-body').show();
		$('#body-modal-pagamento #alert').hide();

		let
			aVencerCP = data.pagamentos.reduce((total, item) => total + parseFloat(item.saldo_vencer), 0),
			vencidoCP = data.pagamentos.reduce((total, item) => total + parseFloat(item.saldo_vencido), 0);

		vlrAVencerContasPagar = 'R$ ' + number_format(aVencerCP.toString(), 2, ",", ".");
		vlrVencidoContasPagar = 'R$ ' + number_format(vencidoCP.toString(), 2, ",", ".");
		vlrTotalContasPagar = 'R$ ' + number_format((aVencerCP + vencidoCP).toString(), 2, ",", ".");

		montaTabelaPagamentos(data.pagamentos);

	} else {

		$('#modal-pagamento #content-modal-body').hide();
		$('#body-modal-pagamento #alert').show();
	}

	document.querySelectorAll('#cp-a-vencer').forEach(function (item) {

		$(item).text(vlrAVencerContasPagar ?? 'R$ 0,00');
	});

	document.querySelectorAll('#cp-vencido').forEach(function (item) {

		$(item).text(vlrVencidoContasPagar ?? 'R$ 0,00');
	});

	document.querySelectorAll('#cp-total').forEach(function (item) {

		$(item).text(vlrTotalContasPagar ?? 'R$ 0,00');
	});

	// card contas a receber -----------------------------------------------------------------------

	if (data.recebimentos.length) {

		$('#modal-recebimento #content-modal-body').show();
		$('#body-modal-recebimento #alert').hide();

		montaTabelaRecebimentos(data.recebimentos);

	} else {

		$('#modal-recebimento #content-modal-body').hide();
		$('#body-modal-recebimento #alert').show();
		$('#cr-a-vencer').text('R$ 0,00');
		$('#cr-vencido').text('R$ 0,00');
		$('#cr-total').text('R$ 0,00');
	}
}

const montaTabelaRecebimentos = (data) => {

	const DocumentosDescricao = {

		1: '<span><i class="fas fa-sticky-note cl-orange"></i> Duplicatas</span><button data-bs-toggle="tooltip" id="btn-modal-duplicata" data-bs-placement="top" data-bs-title="Visualizar Portadores" class="btn btn-bd-primary btn-sm fw-bold m-0"><i class="fas fa-file-alt"></i></button>',
		2: '<i class="fas fa-scroll cl-orange"></i> Cupons',
		3: '<i class="fas fa-sticky-note cl-orange"></i> Notas',
		4: '<i class="fas fa-credit-card cl-orange"></i> Cartão',
		5: '<span><i class="fas fa-money-check cl-orange"></i> Cheques</span><button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Visualizar Portadores" id="btn-modal-cheques" class="btn btn-bd-primary btn-sm fw-bold m-0"><i class="fas fa-file-alt"></i></button>'
	};

	function somarValoresPorChave(data, chave, index, chaveIndex) {

		try {

			// Utilizando reduce para somar os valores da chave especificada
			return data.reduce((acumulador, obj) => {

				// se a chave não existir no objeto estoura uma exception
				if (!obj.hasOwnProperty(chave) || !obj.hasOwnProperty(chaveIndex)) {

					throw new Error("property informada é inválida para o objeto!");
				}

				// soma mediante o index informado
				if (obj[chaveIndex] == index) {
					// Soma o valor da chave
					return acumulador + parseFloat(obj[chave]);
				} else {
					// Se a chave não existir, retorna o acumulador sem alterações
					return acumulador;
				}

			}, 0); // Valor inicial do acumulador é 0

		} catch (error) {

			console.error(error);
			return 0;
		}
	}

	/*
		Adicionando informações a tabela documentos
	*/

	let template = '';

	for (let index = 1; index <= 5; index++) {

		template += `
			<tr>
				<td ${[1, 5].includes(index) ? 'class="d-flex justify-content-between gap-1 align-items-center"' : ''} >
					${DocumentosDescricao[index]}
				</td>
				<td> R$ ${arredondarNumero(somarValoresPorChave(data, 'incluido', index, 'tipo_documento'))}</td>
				<td> R$ ${arredondarNumero(somarValoresPorChave(data, 'liquidado', index, 'tipo_documento'))}</td>
				<td> ${4 == index ? '--' : 'R$ ' + arredondarNumero(somarValoresPorChave(data, 'desconto', index, 'tipo_documento'))}</td >
				<td> R$ ${arredondarNumero(somarValoresPorChave(data, 'cancelado', index, 'tipo_documento'))}</td>
				<td> ${4 == index ? '--' : 'R$ ' + arredondarNumero(somarValoresPorChave(data, 'baixado', index, 'tipo_documento'))}</td>
				<td> ${[2, 3, 4].includes(index) ? '--' : 'R$ ' + arredondarNumero(somarValoresPorChave(data, 'receita', index, 'tipo_documento'))}</td>
			</tr >
		`;
	}

	template += `

			<tr class="fw-bold">
				<td class="text-end">TOTAL</td>
				<td>R$ ${arredondarNumero(data.reduce((total, item) => total + parseFloat(item.incluido), 0))}</td>
				<td>R$ ${arredondarNumero(data.reduce((total, item) => total + parseFloat(item.liquidado), 0))}</td>
				<td>R$ ${arredondarNumero(data.reduce((total, item) => total + parseFloat(item.desconto), 0))}</td>
				<td>R$ ${arredondarNumero(data.reduce((total, item) => total + parseFloat(item.cancelado), 0))}</td>
				<td>R$ ${arredondarNumero(data.reduce((total, item) => total + parseFloat(item.baixado), 0))}</td>
				<td>R$ ${arredondarNumero(data.reduce((total, item) => total + parseFloat(item.receita), 0))}</td>
			</tr>
		`;

	/*
		Adicionando informações ao modal portadores duplicatas
	*/

	let
		portadorDuplicatas = filtrarObjetos(data.filter((item) => item.portador != '0' && item.tipo_documento == '1'), 'portador'),
		templateDuplicatas = '',
		totalizadorPortadorDuplicatas = [];

	portadorDuplicatas.forEach(portador => {

		let
			incluido = 0,
			liquidado = 0,
			desconto = 0,
			cancelado = 0,
			baixado = 0,
			receita = 0,
			saldo_vencer = 0,
			saldo_vencido = 0;

		data.forEach(item => {

			if (item.portador == portador && item.tipo_documento == '1') {

				incluido += parseFloat(item.incluido);
				liquidado += parseFloat(item.liquidado);
				desconto += parseFloat(item.desconto);
				cancelado += parseFloat(item.cancelado);
				baixado += parseFloat(item.baixado);
				receita += parseFloat(item.receita);
				saldo_vencer += parseFloat(item.saldo_vencer);
				saldo_vencido += parseFloat(item.saldo_vencido);
			}
		});

		totalizadorPortadorDuplicatas.push({

			portador: portador,
			saldo_vencer: saldo_vencer,
			saldo_vencido: saldo_vencido,
			total: (parseFloat(saldo_vencer) + parseFloat(saldo_vencido))
		});

		templateDuplicatas += `

			<tr>
				<td>${portador}</td>
				<td>R$ ${arredondarNumero(incluido)}</td>
				<td>R$ ${arredondarNumero(liquidado)}</td>
				<td>R$ ${arredondarNumero(desconto)}</td>
				<td>R$ ${arredondarNumero(cancelado)}</td>
				<td>R$ ${arredondarNumero(baixado)}</td>
				<td>R$ ${arredondarNumero(receita)}</td>
			</tr>
		`;
	});

	$('#tbl-recebimento tbody').html(template);
	$('#tbl-duplicatas tbody').html(templateDuplicatas);

	/*
		Adicionando informações ao modal portadores cheques
	*/

	let
		portadorCheques = filtrarObjetos(data.filter((item) => item.portador != '0' && item.tipo_documento == '5'), 'portador'),
		templateCheques = '',
		totalizadorPortadorCheques = [];

	if (!portadorCheques.length)
		$('#btn-modal-cheques').hide();

	if (!portadorDuplicatas.length)
		$('#btn-modal-duplicata').hide();

	console.log();

	portadorCheques.forEach(portador => {

		let
			incluido = 0,
			liquidado = 0,
			desconto = 0,
			cancelado = 0,
			baixado = 0,
			receita = 0,
			saldo_vencer = 0,
			saldo_vencido = 0;

		data.forEach(item => {

			if (item.portador == portador && item.tipo_documento == '5') {

				incluido += parseFloat(item.incluido);
				liquidado += parseFloat(item.liquidado);
				desconto += parseFloat(item.desconto);
				cancelado += parseFloat(item.cancelado);
				baixado += parseFloat(item.baixado);
				receita += parseFloat(item.receita);
				saldo_vencer += parseFloat(item.saldo_vencer);
				saldo_vencido += parseFloat(item.saldo_vencido);
			}
		});

		totalizadorPortadorCheques.push({

			portador: portador,
			saldo_vencer: saldo_vencer,
			saldo_vencido: saldo_vencido,
			total: (parseFloat(saldo_vencer) + parseFloat(saldo_vencido))
		});

		templateCheques += `

			<tr>
				<td>${portador}</td>
				<td>R$ ${arredondarNumero(incluido)}</td>
				<td>R$ ${arredondarNumero(liquidado)}</td>
				<td>R$ ${arredondarNumero(desconto)}</td>
				<td>R$ ${arredondarNumero(cancelado)}</td>
				<td>R$ ${arredondarNumero(baixado)}</td>
				<td>R$ ${arredondarNumero(receita)}</td>
			</tr>
		`;
	});

	$('#tbl-cheques tbody').html(templateCheques);

	/*
		Adicionando informações a tabela totilizadora de documentos
	*/

	let tbodyDocumentosTotais = "";

	DocumentosDescricao[1] = '<span><i class="fas fa-sticky-note cl-orange"></i> Duplicatas</span><button data-bs-toggle="tooltip" id="btn-modal-totalizador-portador-duplicata" data-bs-placement="top" data-bs-title="Visualizar Portadores" class="btn btn-bd-primary btn-sm fw-bold m-0"><i class="fas fa-file-alt"></i></button>';
	DocumentosDescricao[5] = '<span><i class="fas fa-money-check cl-orange"></i> Cheques</span><button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Visualizar Portadores" id="btn-modal-totalizador-portador-cheques" class="btn btn-bd-primary btn-sm fw-bold m-0"><i class="fas fa-file-alt"></i></button>';

	for (let index = 1; index <= 5; index++) {

		const total = data.reduce(function (total, item) {

			if (item.tipo_documento == index)
				return total + parseFloat(item.baixado) + parseFloat(item.incluido) + parseFloat(item.liquidado) + parseFloat(item.desconto) + parseFloat(item.cancelado) + parseFloat(item.receita);

			return total;

		}, 0);

		const totalVencer = data.reduce(function (total, item) {

			if (item.tipo_documento == index)
				return total + parseFloat(item.saldo_vencer);

			return total;

		}, 0);

		const totalVencido = data.reduce(function (total, item) {

			if (item.tipo_documento == index)
				return total + parseFloat(item.saldo_vencido);

			return total;

		}, 0);

		tbodyDocumentosTotais += `
			<tr>
				<td ${[1, 5].includes(index) ? 'class="d-flex justify-content-between gap-1 align-items-center"' : ''}>
					${DocumentosDescricao[index]}
				</td>
				<td>R$ ${arredondarNumero(totalVencer)}</td>
				<td>R$ ${arredondarNumero(totalVencido)}</td>
				<td>R$ ${arredondarNumero(total)}</td>
			</tr>
		`;
	}

	let
		totalVencer = data.reduce((total, item) => total + parseFloat(item.saldo_vencer), 0),
		totalVencido = data.reduce((total, item) => total + parseFloat(item.saldo_vencido), 0);

	tbodyDocumentosTotais += `
		<tr class="fw-bold">
			<td class="text-end">TOTAL</td>
			<td><spam class="rounded p-1 my-2 text-uppercase text-bg-warning">R$ ${arredondarNumero(totalVencer)}</spam></td>
			<td><spam class="rounded p-1 my-2 text-uppercase text-bg-danger">R$ ${arredondarNumero(totalVencido)}</spam></td>
			<td><spam class="rounded p-1 my-2 text-uppercase text-bg-secondary">R$ ${arredondarNumero(totalVencer + totalVencido)}</spam></td>
		</tr>
	`;

	let templatePTDuplicatas = "";

	totalizadorPortadorDuplicatas.forEach(item => {

		templatePTDuplicatas += `
			<tr>
				<td>R$ ${item.portador}</td>
				<td>R$ ${arredondarNumero(item.saldo_vencer)}</td>
				<td>R$ ${arredondarNumero(item.saldo_vencido)}</td>
				<td>R$ ${arredondarNumero(item.total)}</td>
			</tr>
		`;
	});

	$('#tbl-tpduplicatas tbody').html(templatePTDuplicatas);

	let templatePTCheques = "";

	totalizadorPortadorCheques.forEach(item => {

		templatePTCheques += `
			<tr>
				<td>R$ ${item.portador}</td>
				<td>R$ ${arredondarNumero(item.saldo_vencer)}</td>
				<td>R$ ${arredondarNumero(item.saldo_vencido)}</td>
				<td>R$ ${arredondarNumero(item.total)}</td>
			</tr>
		`;
	});

	$('#tbl-tpcheques tbody').html(templatePTCheques);

	$('#cr-a-vencer').text('R$ ' + number_format(totalVencer.toString(), 2, ",", "."));
	$('#cr-vencido').text('R$ ' + number_format(totalVencido.toString(), 2, ",", "."));
	$('#cr-total').text('R$ ' + number_format((totalVencer + totalVencido).toString(), 2, ",", "."));

	$('#tbl-totalizador-documento tbody').html(tbodyDocumentosTotais);

	$('[data-bs-toggle="tooltip"]').tooltip();

	$('#btn-modal-duplicata').click(function (e) {

		e.preventDefault();
		$('#modal-duplicatas').modal('show');
	});

	$('#btn-modal-cheques').click(function (e) {

		e.preventDefault();
		$('#modal-cheques').modal('show');
	});

	$('#btn-modal-totalizador-portador-duplicata').click(function (e) {

		e.preventDefault();
		$('#modal-total-portador-duplicatas').modal('show');
	});

	$('#btn-modal-totalizador-portador-cheques').click(function (e) {

		e.preventDefault();
		$('#modal-total-portador-cheques').modal('show');
	});

	if (!totalizadorPortadorDuplicatas.length)
		$('#btn-modal-totalizador-portador-duplicata').hide();

	if (!totalizadorPortadorCheques.length)
		$('#btn-modal-totalizador-portador-cheques').hide();
}

const montaTabelaPagamentos = (data) => {

	// Função para verificar se pelo menos um valor é maior que zero
	function verificaValoresMaioresQueZero(objeto) {

		// Obtém todas as chaves do objeto
		const chaves = Object.keys(objeto);
		// Filtra as chaves, removendo "saldo_vencer" e "saldo_vencido"
		const chavesParaVerificar = chaves.filter(chave => chave !== "saldo_vencer" && chave !== "saldo_vencido");
		// Verifica se pelo menos um valor é maior que zero para as chaves restantes
		return chavesParaVerificar.some(chave => parseFloat(objeto[chave]) > 0);
	}

	// Filtra os objetos com pelo menos um valor maior que zero
	const objetosFiltrados = data.filter(verificaValoresMaioresQueZero);

	let
		template = "",
		totalIncluido = 0,
		totalDescontado = 0,
		totalLiquidado = 0,
		totalCancelado = 0,
		totalBaixado = 0,
		totalDespesas = 0;

	objetosFiltrados.forEach(item => {

		template += `
			<tr class="text-nowrap">
				<td class="text-start fw-bold">${item.descricao}</td>
				<td class="text-end">${arredondarNumero(item.incluido)}</td>
				<td class="text-end">${arredondarNumero(item.liquidado)}</td>
				<td class="text-end">${arredondarNumero(item.desconto)}</td>
				<td class="text-end">${arredondarNumero(item.cancelado)}</td>
				<td class="text-end">${arredondarNumero(item.baixado)}</td>
				<td class="text-end">${arredondarNumero(item.despesa)}</td>
			</tr>
		`;

		totalIncluido += parseFloat(item.incluido);
		totalDescontado += parseFloat(item.desconto);
		totalLiquidado += parseFloat(item.liquidado);
		totalCancelado += parseFloat(item.cancelado);
		totalBaixado += parseFloat(item.baixado);
		totalDespesas += parseFloat(item.despesa);
	});

	$('#tbl-pagamento tbody').html(template);

	$('#tbl-totalizador-pagamento tbody').html(`
	
		<tr class="text-nowrap">
			<td class="text-end">${arredondarNumero(totalIncluido.toString())}</td>
			<td class="text-end">${arredondarNumero(totalLiquidado.toString())}</td>
			<td class="text-end">${arredondarNumero(totalDescontado.toString())}</td>
			<td class="text-end">${arredondarNumero(totalCancelado.toString())}</td>
			<td class="text-end">${arredondarNumero(totalBaixado.toString())}</td>
			<td class="text-end">${arredondarNumero(totalDespesas.toString())}</td>
		</tr>
	`);

	if (!objetosFiltrados.length) {

		$('#tbl-pagamento tbody').html('<tr><td class="text-center text-muted fst-italic" colspan="7">Sem registros</td></td>');
		$('#tbl-totalizador-pagamento tbody').html('<tr><td class="text-center text-muted fst-italic" colspan="7">Sem registros</td></td>');
	}
}

const carregaTabelaResumoEstoque = (data = []) => {

	let
		grupos = filtrarObjetos(data, 'grupo'),
		rows = [],
		eiCustoMedio,
		eiPrecoReposicao,
		eiQuantidade,
		entradaCustoMedio,
		entradaReposicao,
		entradaQuantidade,
		saidaCustoMedio,
		saidaReposicao,
		saidaQuantidade,
		efCustoMedio,
		efPrecoReposicao,
		efQuantidade;

	grupos.forEach(grupo => {

		eiCustoMedio = 0;
		eiPrecoReposicao = 0;
		eiQuantidade = 0;
		entradaCustoMedio = 0;
		entradaReposicao = 0;
		entradaQuantidade = 0;
		saidaCustoMedio = 0;
		saidaReposicao = 0;
		saidaQuantidade = 0;
		efCustoMedio = 0;
		efPrecoReposicao = 0;
		efQuantidade = 0;

		data.forEach(item => {

			if (item.grupo == grupo) {

				eiCustoMedio += parseFloat(item.estoque_inicial_customedio);
				eiPrecoReposicao += parseFloat(item.estoque_inicial_reposicao);
				eiQuantidade += parseFloat(item.estoque_inicial_quantidade);
				entradaCustoMedio += parseFloat(item.entrada_customedio);
				entradaReposicao += parseFloat(item.entrada_reposicao);
				entradaQuantidade += parseFloat(item.entrada_quantidade);
				saidaCustoMedio += parseFloat(item.saida_customedio);
				saidaReposicao += parseFloat(item.saida_reposicao);
				saidaQuantidade += parseFloat(item.saida_quantidade);
				efCustoMedio += parseFloat(item.estoque_final_customedio);
				efPrecoReposicao += parseFloat(item.estoque_final_reposicao);
				efQuantidade += parseFloat(item.estoque_final_quantidade);
			}
		});

		rows.push({

			grupo: grupo,
			eiCustoMedio: eiCustoMedio,
			eiPrecoReposicao: eiPrecoReposicao,
			eiQuantidade: eiQuantidade,
			entradaCustoMedio: entradaCustoMedio,
			entradaReposicao: entradaReposicao,
			entradaQuantidade: entradaQuantidade,
			saidaCustoMedio: saidaCustoMedio,
			saidaReposicao: saidaReposicao,
			saidaQuantidade: saidaQuantidade,
			efCustoMedio: efCustoMedio,
			efPrecoReposicao: efPrecoReposicao,
			efQuantidade: efQuantidade
		});
	});

	controleTabelaResumoEstoque(rows);
}
