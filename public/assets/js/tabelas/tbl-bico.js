check_buttom("novo_preco", "btn-submit");
const tooltipTriggerList = document.querySelectorAll(
	'[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
	(tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

const verifica_chave_session = () => {
	// Verifica se o item existe na sessionStorage
	let itemNaSessionStorage = sessionStorage.getItem("chave_empresa");

	if (itemNaSessionStorage) {
		seleciona_option_select();
	} else {
		sessionStorage.setItem("chave_empresa", $("#empresas").val());
		seleciona_option_select();
	}
};

if (typeof Storage !== "undefined") {
	verifica_chave_session();
}

document.getElementById("empresas").addEventListener("change", function () {
	if (typeof Storage !== "undefined") {
		sessionStorage.setItem("chave_empresa", $("#empresas").val());
	}

	table.ajax.reload();
});

const table = new DataTable("#tbl-usuarios", {
	responsive: true,
	processing: true,
	language: {
		url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json",
	},
	ajax: {
		url: BASE_URL + "get_bicos",
		type: "POST",
		data: function (d) {
			d.chave = $("#empresas").val();
		},
	},
	columnDefs: [
		{
			responsivePriority: 4,
			data: "bico",
			targets: 0,
			render: function (data, type, row, meta) {
				return `<span class="badge-light fw-bold">${data}</span>`;
			},
		},
		{
			responsivePriority: 6,
			targets: 3,
		},
		{
			data: "preco",
			targets: 2,
			render: function (data, type, row, meta) {
				if (row.pendente == 2) {
					return (
						"<div class='text-end'><spam class='text-danger text-end me-3'><i class='fas fa-exclamation-triangle pulse'></i> " +
						number_format(data, 4, ",", ".") +
						"</spam></div>"
					);
				}

				return (
					"<div class='text-end me-3'>" +
					number_format(data, 4, ",", ".") +
					"</div>"
				);
			},
			responsivePriority: 10,
		},
		{
			data: "status",
			targets: 3,
			render: function (data, type, row, meta) {
				let badge = "";
				badge =
					data == "1"
						? `<span class='badge text-bg-primary'>Ativo</span>`
						: `<span class="badge text-bg-danger">Inativo</span>`;
				return badge;
			},
			responsivePriority:11,
		},
		{
			data: null,
			render: function (data, type, row, meta) {

				return `<button id="btn_alterar" ${row.status == 1 ? "" : "disabled"} class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i> Alterar</button>`;
			},
			targets: -1,
			responsivePriority: 1,
		}
	],
	columns: columns,
});

table.on("click", "#btn_alterar", function (e) {
	let row = table.row($(this).closest("tr")).data();

	$("#data_cadastro").text(
		"Cadastrado em: " + formata_data_hora(new Date(row.data_cadastro))
	);
	$("#id").val(row.bico);
	$("#id_bico").text(row.bico);
	$("#produto").val(row.descricao);
	$("#id_produto").val(row.produto);
	$("#status_alteracao").html(
		row.pendente == "2"
			? "<span class='p-2 fst-italic badge bg-warning text-dark'><i class='pulse fas fa-exclamation-triangle'></i> Aguardando confirmação de alteração!</span>"
			: ""
	);
	$("#preco_atual").val("R$ " + number_format(Math.floor(row.preco * 100) / 100, 2, ",", "."));
	$("#preco").val(row.preco);
	$("#novo_preco").val("");
	$("#chave").val($("#empresas").val());

	$("#home-tab").tab("show");
	$("#bico_detalhes").modal("show");
});

jQuery("#form_bico_alteracao").submit(function (e) {
	e.preventDefault();
	let dados = jQuery(this).serialize();

	let msg_alert = "";

	if (document.getElementById("bcs_produto").checked) {
		msg_alert =
			"<span>Atenção! Deseja realmente prosseguir com a alteração de preço?</span>";
		msg_alert += `<p class='fw-bold'>A rotina irá alterar os preços de todos os bicos do produto ${$(
			"#produto"
		).val()} para ${$("#novo_preco").val()}.</p>`;
	} else {
		msg_alert = `<span>Atenção! Deseja realmente alterar o preço do bico <br> <b> ${$(
			"#id"
		).val()} - ${$("#produto").val()}</b> para ${$(
			"#novo_preco"
		).val()}?</span>`;
	}

	Swal.fire({
		icon: "question",
		html: msg_alert,
		showDenyButton: true,
		showCancelButton: false,
		confirmButtonText: "Continuar",
		denyButtonText: `Cancelar`,
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				Headers: {
					"Content-Type": "application/json",
				},
				type: "POST",
				url: `${BASE_URL}altera-preco`,
				crossDomain: true,
				dataType: "json",
				data: dados,
				beforeSend: function () {
					Swal.fire({
						title: "Enviando informações!",
						html: "Aguarde...",
						didOpen: () => {
							Swal.showLoading();
						},
					});
				},
				success: function (res) {
					console.log(res);

					if (res.error) {
						Swal.fire({
							icon: "error",
							title: "Oops...",
							text: res.message,
						});
					} else {
						Swal.fire({
							icon: "success",
							text: res.message,
						});
						$("#bico_detalhes").modal("hide");
						table.ajax.reload();
					}
				},
				error: function (error) {
					console.log(error);
					Swal.fire({
						allowOutsideClick: false,
						icon: "error",
						title: "Oops!",
						text: "Parece que tivemos um erro, entre em contato com o suporte técnico!",
					});
				},
			});
		} else if (result.isDenied) {
			Swal.fire("Operação cancelada!", "", "info");
		}
	});
});
