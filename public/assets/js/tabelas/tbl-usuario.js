const table = new DataTable("#tbl-usuarios", {
	responsive: true,
	processing: true,
	language: {
		url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json",
	},
	ajax: BASE_URL + "get_usuarios",
	columnDefs: [
		{
			data: null,
			defaultContent:
				'<button id="btn_alterar" class="btn btn-secondary btn-sm"><i class="fas fa-pen bell"></i> Alterar</button>',
			targets: -1,
			responsivePriority: 1000,
		},
		{
			data: "status",
			targets: 2,
			render: function (data, type, row, meta) {
				let badge = "";
				badge =
					data == "1"
						? `<span class='badge text-bg-primary'>Ativo</span>`
						: `<span class="badge text-bg-danger">Inativo</span>`;
				return badge;
			},
		},
	],
	columns: [
		{
			data: "nome",
		},
		{
			data: "email",
		},
		{
			data: "status",
		},
		{
			data: null,
		},
	],
});

table.on("click", "#btn_alterar", function (e) {
	let row = table.row($(this).closest("tr")).data();

	$("#data_cadastro").text(
		"Cadastrado em: " + formata_data_hora(new Date(row.data_cadastro))
	);
	$("#id").val(row.id);
	$("#nome").val(row.nome);
	$("#email").val(row.email);

	const selectStatus = document.getElementById("status");
	for (let i = 0; i < selectStatus.options.length; i++) {
		const option = selectStatus.options[i];

		if (option.value === row.status) {
			selectStatus.selectedIndex = i;
			break;
		}
	}

	let container = $("#ckeckbox_empresas");
	let checkbox = "";

	fetch(BASE_URL + "get_empresas")
		.then((response) => {
			// Verifique se a resposta da requisição foi bem-sucedida (código de status 200)
			if (!response.ok) {
				throw new Error("A requisição falhou com status: " + response.status);
			}
			// Parse a resposta como JSON
			return response.json();
		})
		.then((array) => {
			array.forEach((empresa) => {
				if (verifica_acesso_existe(empresa.id_cliente, row.empresas)) {
					checkbox += `
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="empresas[]" checked value="${empresa.id_cliente}" id="${empresa.id_cliente}">
								<label class="form-check-label" for="${empresa.id_cliente}">
									${empresa.nome_cliente}
								</label>
							</div>
						`;
				} else {
					checkbox += `
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="empresas[]" value="${empresa.id_cliente}" id="${empresa.id_cliente}">
								<label class="form-check-label" for="${empresa.id_cliente}">
									${empresa.nome_cliente}
								</label>
							</div>
						`;
				}
			});

			container.html(checkbox);
			$("#usuario_detalhes").modal("show");
			verifica_length_checkboxes();
		})
		.catch((error) => {
			return "Ocorreu um erro na requisição: " + error;
		});
});

const checkboxAlterarSenha = document.getElementById("checkbox_alterar_senha");
checkboxAlterarSenha.addEventListener("change", function () {
	if (checkboxAlterarSenha.checked) {
		$("#input_senha").slideDown("slow");
		$("#senha").prop("disabled", false);
	} else {
		$("#input_senha").slideUp("slow");
		$("#senha").prop("disabled", true);
	}
});

jQuery("#form_usuario_alteracao").submit(function (e) {
	e.preventDefault();
	let dados = jQuery(this).serialize();

	$.ajax({
		Headers: {
			"Content-Type": "application/json",
		},
		type: "POST",
		url: `${BASE_URL}usuario/update`,
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
				location.reload();
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
});

const verifica_acesso_existe = (id = "", data = []) => {
	let achou = false;

	data.forEach((item) => {
		if (parseInt(item.id_cliente) == parseInt(id)) {
			achou = true;
		}
	});

	if (achou) {
		return true;
	}

	return false;
};

function verifica_length_checkboxes() {
	$("button[type='submit']").attr("disabled", false);
	const myTooltipEl = document.getElementById("tooltip_empresas");
	const tooltip = bootstrap.Tooltip.getOrCreateInstance(myTooltipEl);
	// Seleciona todos os checkboxes dentro da div
	let checkboxes = $("#ckeckbox_empresas input[type='checkbox']");

	// Adiciona um ouvinte de evento 'change' para os checkboxes
	checkboxes.on("change", function () {
		if (checkboxes.filter(":checked").length < 1) {
			tooltip.show();
			$("button[type='submit']").attr("disabled", true);
		} else {
			tooltip.hide();
			$("button[type='submit']").attr("disabled", false);
		}
	});
}
