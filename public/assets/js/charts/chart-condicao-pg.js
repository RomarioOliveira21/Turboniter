const carregaChartCondicaoPag = (data) => {

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




