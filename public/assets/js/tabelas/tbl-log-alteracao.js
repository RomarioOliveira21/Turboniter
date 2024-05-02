const carrega_tbl_log = () => {
	const table = new DataTable("#tbl-log", {
		retrieve: true,
		responsive: true,
		processing: true,
		language: {
			url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json",
		},
		ajax: {
			url: BASE_URL + "get_log_bico",
			type: "POST",
			data: function (d) {
				d.chave = $("#empresas").val();
				d.bico = $("#id").val();
			},
		},
		columnDefs: [
			{
				responsivePriority: 1000,
				data: "dt_alteracao",
				targets: 3,
				render: function (data, type, row, meta) {
					return formata_data_hora(new Date(data));
				},
			},
			{
				targets: 2,
				responsivePriority: 900,
			},
			{
				targets: [1, 2],
				render: function (data, type, row, meta) {
					return number_format(data, 4, ",", ".");
				},
			},
			{
				data: "bico",
				targets: 0,
				render: function (data, type, row, meta) {
					return `<span class="badge-light fw-bold">${data}</span>`;
				},
			},
		],
		columns: [
			{
				data: "bico",
			},
			{
				data: "preco_antigo",
			},
			{
				data: "preco_novo",
			},
			{
				data: "dt_alteracao",
			},
		],
	});

	table.ajax.reload();
};
