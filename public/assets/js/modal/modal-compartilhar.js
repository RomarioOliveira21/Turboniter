const btnsCompartilhar = document.querySelectorAll('#btn-compartilhar');
btnsCompartilhar.forEach(element => {

	$(element).click(function () {

		getLink($(this).data('relatorio'));
	});
});

const enviar_link_relatorio = async (e) => {

	try {

		if (!$('#email-compartilhar').hasClass('is-valid')) {

			Swal.fire({
				position: "center",
				icon: "error",
				title: 'Informe um e-mail válido!',
				showConfirmButton: false,
				returnFocus: false,
				timer: 2000
			});

			$('#email-compartilhar').focus();

			return;
		}

		e.target.disabled = true;

		$("#modalCompartilhar").modal("hide");

		let link = $('#link-relatorio').val();

		let hash = await criptografarSHA256($('#empresas').val());

		if (hash === undefined) {

			throw new Error("A hash não pode ser gerada e ambiente HTTP.");
		}

		let response = await fetchRequest('https://login.veredastecnologia.com.br/API/enviar_link_relatorio',
			{
				chave: $('#empresas').val(),
				destinatario: $('#email-compartilhar').val(),
				link: link
			},
			{
				headers: {

					"empresa": hash
				}
			}
		);

		if (response.status) {

			Swal.fire({
				position: "center",
				icon: "success",
				title: "Link enviado com sucesso!",
				showConfirmButton: false,
				timer: 2000
			});
		} else {

			Swal.fire({
				position: "center",
				icon: "error",
				title: "Falha ao tentar enviar link, verifique os dados de cadastro do e-mail e tente novamente.",
				showConfirmButton: false,
				timer: 2000
			});
		}

	} catch (error) {

		console.log(error);
		showError(error.toString());
	}
}

const getLink = async (relatorio) => {

	$(`#modal-${relatorio} #btn-compartilhar`).prop('disabled', true);
	$('#btn-enviar-email').prop('disabled', true);
	$('#email-compartilhar').prop('disabled', true);

	try {

		// a função get_link_relatorio deve esta contida no arquivo js do relatorio com os tipos de filtros aplicados naquele relatorio
		let response = await getLinkRelatorio(relatorio);

		Swal.close();

		if (response.error) {

			showError('Oops! Não conseguimos gerar o link, tente novamente.');
			$(`#modal-${relatorio} #btn-compartilhar`).prop('disabled', false);
			return;
		}

		if (response.email_cadastrado) {

			$('#aviso-email').hide();
			$('#email-compartilhar').prop('disabled', false);
			$('#btn-enviar-email').prop('disabled', false);
		} else {

			$('#aviso-email').show();
		}

		$('#modalCompartilhar').modal('show');

		$('#link-relatorio').val(response.url_short);

		let mensagem = new URLSearchParams({

			text: `Relatório Posto Acesse o link para visualizar o relatório \n ${response.url_short} \n criado por VTI.`
		});

		$('#btn-whatsapp').attr('href', 'https://wa.me/?' + mensagem);

		$('#content-text-area-link').slideDown('slow');

		if (window.isSecureContext && navigator.clipboard) {

			$('#btn-copy-link').show();

			$('#btn-copy-link').click(function () {

				navigator.clipboard.writeText(response.url_short);

				const Toast = Swal.mixin({

					toast: true,
					position: "top-end",
					showConfirmButton: false,
					timer: 2000,
					timerProgressBar: true,
					didOpen: (toast) => {

						toast.onmouseenter = Swal.stopTimer;
						toast.onmouseleave = Swal.resumeTimer;
					}
				});
				Toast.fire({

					icon: "success",
					title: 'Link copiado para área de transferência com sucesso.'
				});
			});
		} else {

			$('#btn-copy-link').hide();
		}

	} catch (error) {

		showError('Parece que tivemos um problema ao buscar informações do filtro, tente novamente!');
		console.error(error);
	}
}

const modalCompartilhar = document.getElementById('modalCompartilhar')
modalCompartilhar.addEventListener('hidden.bs.modal', event => {

	$('#link-relatorio').val("");
	$('#content-text-area-link').hide();

	document.querySelectorAll('#btn-compartilhar').forEach(function(item){

		$(item).prop('disabled', false);
	})

	$('#btn-enviar-email').prop('disabled', false);
})

modalCompartilhar.addEventListener('show.bs.modal', event => {

	var animation_lottie = null;
	var container_login_animation = $(".container-login-animation");

	container_login_animation.html("");

	if (animation_lottie) {
		animation_lottie.destroy();
	};

	animation_lottie = lottie.loadAnimation({
		loop: true,
		autoplay: true,
		renderer: "svg",
		container: container_login_animation.get(0),
		path: "../public/assets/gif/link-animation.json"
	});
})

/**
 * Função que executa requests usando FetchAPI
 * @param {string} rota url para requisição
 * @param {object} data dados a serem enviados no corpo da requisição
 * @param {object} extra parametros adicionais como headers
 * @returns any
 */
const fetchRequest = async (rota, data, extra = {}) => {

	swalLoader("Enviando e-mail.");

	let headers = new Headers();

	headers.set('Content-Type', 'application/json');

	if ('headers' in extra) {

		headers = new Headers();

		for (const key in extra.headers) {

			headers.set(key, extra.headers[key]);
		}
	}

	const options = {

		method: extra.method === undefined ? 'POST' : extra.method.toUpperCase(),
		headers: headers,
		body: new URLSearchParams(data)
	};

	const response = await fetch(verificarURL(rota), options);

	if (!response.ok) {

		showError();
	}

	const result = await response.json();
	Swal.close();
	return result
}
