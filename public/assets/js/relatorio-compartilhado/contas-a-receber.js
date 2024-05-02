
const getDados = async () => {

    try {

        let data = await executaRequestAjax('RelatorioCompartilhado/getContasAReceber');

        Swal.close();
        $('#filtro_aplicado').html('<i class="fa-solid fa-filter"></i> ' + data.filtro);

        if (!data.recebimentos.length) {

            $('#tabelas').hide();
            $('#alert-filtro-dia').show();
            return;
        }

        $('#alert-filtro-dia').hide();
        $('#tabelas').show();

        montaTabelaRecebimentos(data.recebimentos);

    } catch (error) {

        console.log(error);
        showError();
    }
}

const montaTabelaRecebimentos = (data) => {

    const DocumentosDescricao = {

        1: '<span><i class="fas fa-sticky-note cl-orange"></i> Duplicatas</span><button data-bs-toggle="modal" data-bs-target="#modal-duplicatas" class="btn btn-bd-primary btn-sm fw-bold m-0"><i class="fas fa-file-alt"></i></button>',
        2: '<i class="fas fa-scroll cl-orange"></i> Cupons',
        3: '<i class="fas fa-file-invoice-dollar cl-orange"></i> Notas',
        4: '<i class="fas fa-credit-card cl-orange"></i> Cartão',
        5: '<i class="fas fa-money-check cl-orange"></i> Cheque'
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

    let template = '';

    for (let index = 1; index <= 5; index++) {

        template += `

			<tr>
				<td ${index == 1 ? 'class="d-flex justify-content-between gap-1 align-items-center"' : ''} >
					${DocumentosDescricao[index]}
				</td>
				<td>R$ ${arredondarNumero(somarValoresPorChave(data, 'incluido', index, 'tipo_documento'))}</td>
				<td>R$ ${arredondarNumero(somarValoresPorChave(data, 'liquidado', index, 'tipo_documento'))}</td>
				<td>R$ ${arredondarNumero(somarValoresPorChave(data, 'desconto', index, 'tipo_documento'))}</td>
				<td>R$ ${arredondarNumero(somarValoresPorChave(data, 'cancelado', index, 'tipo_documento'))}</td>
				<td>R$ ${arredondarNumero(somarValoresPorChave(data, 'baixado', index, 'tipo_documento'))}</td>
				<td>R$ ${arredondarNumero(somarValoresPorChave(data, 'receita', index, 'tipo_documento'))}</td>
			</tr>
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

    let
        portadores = filtrarObjetos(data.filter((item) => item.portador !== '0' && item.tipo_documento == '1'), 'portador'),
        templateDuplicatas = '';

    portadores.forEach(portador => {

        let
            incluido = 0,
            liquidado = 0,
            desconto = 0,
            cancelado = 0,
            baixado = 0,
            receita = 0;

        data.forEach(item => {


            if (item.portador == portador && item.tipo_documento == '1') {

                incluido += parseFloat(item.incluido);
                liquidado += parseFloat(item.liquidado);
                desconto += parseFloat(item.desconto);
                cancelado += parseFloat(item.cancelado);
                baixado += parseFloat(item.baixado);
                receita += parseFloat(item.receita);
            }
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
}

getDados();