
const getDados = async () => {

    try {

        let data = await executaRequestAjax('RelatorioCompartilhado/getContasAPagar');

        Swal.close();
        $('#filtro_aplicado').html('<i class="fa-solid fa-filter"></i> ' + data.filtro);

        if (!data.pagamentos.length) {

            $('#tabelas').hide();
            $('#alert-filtro-dia').show();
            return;
        }

        $('#alert-filtro-dia').hide();
        $('#tabelas').show();

        setDados(data.pagamentos);

    } catch (error) {

        console.log(error);
        showError();
    }
}

const setDados = (data) => {

    $('#modal-pagamento #content-modal-body').show();
    $('#body-modal-pagamento #alert').hide();

    let
        aVencerCP = data.reduce((total, item) => total + parseFloat(item.saldo_vencer), 0),
        vencidoCP = data.reduce((total, item) => total + parseFloat(item.saldo_vencido), 0);

    $('#cp-a-vencer').text('R$ ' + number_format(aVencerCP.toString(), 2, ",", "."));
    $('#cp-vencido').text('R$ ' + number_format(vencidoCP.toString(), 2, ",", "."));
    $('#cp-total').text('R$ ' + number_format((aVencerCP + vencidoCP).toString(), 2, ",", "."));

    montaTabelaPagamentos(data);
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

getDados();