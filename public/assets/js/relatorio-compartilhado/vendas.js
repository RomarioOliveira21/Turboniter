const getDados = async () => {

	try {

		let result = await executaRequestAjax('relatorio-compartilhado/vendas');

		Swal.close();

        if (!result.vendas.length) {

            $('#grafico').hide();
            $('#alert-filtro').show();
            return;
        }
    
        $('#alert-filtro').hide();
        $('#grafico').show();
        $('#filtro_aplicado').html('<i class="fa-solid fa-filter"></i> ' + result.filtro);

		setDados(result.vendas);

	} catch (error) {

        showError();
		console.log(error);
	}
}

const setDados = (data) => {

	let template = "",
		valorTotal = data.reduce((total, item) => total + parseFloat(item.vlr_venda), 0);

	data.forEach(item => {

		template += new CardObject(

			TipoCondicaoPag[parseInt(item.tipo)],
			item.vlr_venda,
			valorTotal,
			UtilsBgColors[parseInt(item.tipo)]
			
		).getCard();

	});

	$('#chart-condicao-pg').html(template);
	$('#content_totais span, #card-vlr-total-vendas').text('R$ ' + number_format(valorTotal.toString(), 2, ",", "."));
}

getDados();

