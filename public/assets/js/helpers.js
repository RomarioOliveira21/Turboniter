/**
 * Função que retona a url pronta para request
 * @param {string} rota caminho onde se ira fazer a request
 * @returns string
 */
const base_url = (rota = '') => {

	return window.location.protocol + '//' + window.location.host + '/' + rota;
}

/**
 * Formata valores
 * @param {string} number
 * @param {number} decimals
 * @param {string} dec_point
 * @param {string} thousands_sep
 * @returns string
 */
function number_format(number, decimals, dec_point, thousands_sep) {

	number = (number + "").replace(",", "").replace(" ", "");
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
		dec = typeof dec_point === "undefined" ? "." : dec_point,
		s = "",
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return "" + Math.round(n * k) / k;
		};

	s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
	if (s[0].length > 3) {

		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || "").length < prec) {

		s[1] = s[1] || "";
		s[1] += new Array(prec - s[1].length + 1).join("0");
	}
	return s.join(dec);
}

// https://pt.stackoverflow.com/questions/459827/cortar-os-zeros-desnecess%C3%A1rios-de-uma-string-sem-tofixed

const arredondarNumero = (s, maxCasas = 4) => {

	let n =  typeof s === 'number' ? s : parseFloat(s);

	// deixar o número com apenas 'maxCasas' casas decimais, sem arredondar
	let fator = Math.pow(10, maxCasas);
	n = Math.floor(n * fator) / fator;

	return n.toLocaleString('pt-BR', {

		minimumFractionDigits: 2,
		maximumFractionDigits: maxCasas
	});
}

/**
 * Formata a data vindo do sql (0000-00-00) para o padrão BR.
 * @param {string} data
 * @returns string
 */
function date_format(data) {

	const partesDaData = data.split("-");
	const ano = partesDaData[0];
	const mes = partesDaData[1];
	const dia = partesDaData[2];

	return `${dia}-${mes}-${ano}`;
}

/**
 * Função que reseta os dados do gráfico para evitar erros de renderização e sobreposição de informações.
 *
 * @param {string} canvas id do elemento canvas
 * @param {string} content id da div pai do elemento canvas
 */
function resetCanvas(canvas, content) {

	$("#" + canvas).remove(); // this is my <canvas> element
	$("#" + content).append(`<canvas id="${canvas}"></canvas>`);
}

const UtilsBgColors = {
	1: "bg-primary",
	2: "bg-success",
	3: "bg-info",
	4: "bg-purple",
	5: "bg-danger",
	6: "bg-ciano",
	7: "bg-warning",
	8: "bg-blue-dark",
	9: "bg-secondary",
};

const UtilsHexadecimalColors = {
	1: "#0d6efd",
	2: "#198754",
	3: "#0dcaf0",
	4: "#9B59B6",
	5: "#dc3545",
	6: "#64CCC5",
	7: "#ffc107",
	8: "#1F4172",
	9: "#6c757d",
};

const UtilsHexadecimalColorsGeneric = [
	"#0d6efd",
	"#198754",
	"#0dcaf0",
	"#9B59B6",
	"#dc3545",
	"#64CCC5",
	"#ffc107",
	"#86A7FC",
	"#1D2B53",
	"#98E4FF",
	"#0F1035",
	"#BEFFF7",
	"#45FFCA",
	"#FF9800",
	"#FAEF9B",
	"#5FBDFF",
	"#86A7FC",
	"#AAD7D9",
	"#C6DA20",
	"#E4AEC5",
	"#497174",
	"#BE5A83",
	"#7A9D54",
	"#AF2655",
	"#CD5C08",
	"#750E21",
	"#AF2655",
	"#7D0A0A",
	"#4F6F52",
	"#86A789",
	"#BF3131",
	"#FF9800",
	"#CD8D7A",
	"#DBCC95",
	"#43766C",
	"#000000",
	"#B80000",
	"#B19470",
	"#D6D46D",
	"#A367B1"
];

const TipoCondicaoPag = {
	1: "A Vista",
	2: "A Prazo",
	3: "Cheque",
	4: "Cheque Pré",
	5: "Cartão Crédito",
	6: "Outros",
	7: "Cartão Débito",
	8: "Transferência",
	9: "Pagamento Digital"
};

const MESES = {
	1: "JAN",
	2: "FEV",
	3: "MAR",
	4: "MAI",
	5: "ABR",
	6: "JUN",
	7: "JUL",
	8: "AGO",
	9: "SET",
	10: "OUT",
	11: "NOV",
	12: "DEZ"
};

const primeiroUltimoDiaDoMes = (ano, mes) => {
	// Obter o último dia do mês
	const ultimoDia = new Date(ano, mes, 0).getDate();
	let calendario = [];

	for (let index = 1; index <= ultimoDia; index++) {

		calendario.push(`${ano}-${mes.toString().padStart(2, '0')}-${index.toString().padStart(2, '0')}`);
	}

	return calendario;
}


/**
 * Função que retorna a proporção em percentual de determinado valor.
 * @param {number} base
 * @param {number} valorTotal
 * @returns number
 */
const get_percentual = (base, valorTotal) => {

	if (isNaN(base) || base == 0) {

		return "0%";
	}

	let valor = (base * 100) / valorTotal;
	return valor.toFixed(2);
};

/**
 * Classe que gera o componente progress-bar
 * @param {string} descricao
 * @param {string} valor
 * @param {float} valorTotal
 * @param {string} cor
 */
let CardObject = class CardObject {

	constructor(descricao, valor, valorTotal, cor) {

		this.descricao = descricao;
		this.valor = valor;
		this.valorTotal = valorTotal;
		this.cor = cor;
	}

	getCard() {
		let percentual = get_percentual(parseFloat(this.valor), this.valorTotal);

		return `
			<div>
				<div class="d-flex justify-content-between">
					<span class="fw-bolder w-50">${this.descricao.toUpperCase()}</span>
					<span>${number_format(percentual.toString(), 2, ",", ".")}%</span>
					<small class="text-muted">R$ ${number_format(this.valor, 2, ",", ".")}</small>
				</div>
				<div class="progress" style="height: 5px;">
					<div class="progress-bar ${this.cor}" role="progressbar" style="width:${percentual}%" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>`;
	}
};

const formata_data_hora = (data) => {

	const dia = String(data.getDate()).padStart(2, "0");
	const mes = String(data.getMonth() + 1).padStart(2, "0"); // Lembre-se de que os meses em JavaScript são baseados em zero (janeiro é 0)
	const ano = data.getFullYear();
	const horas = String(data.getHours()).padStart(2, "0");
	const minutos = String(data.getMinutes()).padStart(2, "0");
	const segundos = String(data.getSeconds()).padStart(2, "0");

	return `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;
};

/**
 * Função para calcular diferença de dias entre datas.
 * @param {string} dt_init data inicial no formato 'Y-m-d'
 * @param {string} dt_end data inicial no formato 'Y-m-d'
 * @returns integer
 */
const date_diff = (dt_init, dt_end) => {

	// Converte as datas para objetos Date
	const dataInicial = new Date(dt_init);
	const dataFinal = new Date(dt_end);
	// Calcula a diferença em milissegundos,
	// No JavaScript as datas são valoradas conforme a quantidade de milissegundos apartir de 01/01/1970 00:00:00 GMT-0. Ao subtrair uma data de outra você terá a diferença entre as datas em milissegundos.
	const diferencaEmMilissegundos = Math.abs(dataFinal - dataInicial);
	// Converte a diferença em milissegundos para dias
	return parseInt(diferencaEmMilissegundos / (1000 * 60 * 60 * 24));
};

const mascaraMoeda = (event) => {

	const onlyDigits = event.target.value
		.split("")
		.filter((s) => /\d/.test(s))
		.join("")
		.padStart(3, "0");

	const digitsFloat = onlyDigits.slice(0, -2) + "." + onlyDigits.slice(-2);
	event.target.value = maskCurrency(digitsFloat);
};

const mascaraInteiro = (event) => {

	const onlyDigits = event.target.value.replace(/[^0-9]/g, '');
	event.target.value = onlyDigits.toString().substring(0, 6);
};

const maskCurrency = (valor, locale = "pt-BR", currency = "BRL") => {

	return new Intl.NumberFormat(locale, {
		style: "currency",
		currency,
		minimumFractionDigits: 2,
		maximumFractionDigits: 2,
	})
		.format(valor);
};

const valida_periodo = (data_inicial, data_final) => {

	try {
		const dt_init = new Date(data_inicial);
		const dt_end = new Date(data_final);

		if (isNaN(dt_init.getTime()) || isNaN(dt_end.getTime())) {

			throw new Error("Formato de data inválido!");
		}

		if (dt_init > dt_end) {
			return false;
		}

		return true;
	} catch (error) {
		return false;
	}
};

const date_ptBR_to_sql = (date, separador = '/') => {

	const [day, month, year] = date.split(separador);
	return `${year}-${month}-${day}`;
}

const set_icon_accordion = () => {

	$('.header button').each(function () {

		$(this).click(function () {

			let icon = ($(this).find('i'));

			if (icon.hasClass('fa-plus')) {

				icon.removeClass('fa-plus');
				icon.addClass('fa-minus');
			} else {

				icon.removeClass('fa-minus');
				icon.addClass('fa-plus');
			}
		});
	});
}

const showLoading = () => {

	Swal.fire({
		title: "Aguarde...",
		html:
			"<div class='col d-flex justify-content-center'><div class='boxes m-5'>" +
			"<div class='box'><div></div><div></div><div></div><div></div></div>" +
			"<div class='box'><div></div><div></div><div></div><div></div></div>" +
			"<div class='box'><div></div><div></div><div></div><div></div></div>" +
			"<div class='box'><div></div><div></div><div></div><div></div></div>" +
			"</div></div><br>",
		footer: "<spam class='fw-bold'>Carregando informações...</spam>",
		allowOutsideClick: false,
		showConfirmButton: false,
		imageWidth: 70,
	});
}

/**
 * Função que marca o option selecionado no dropdown
 * @param {number} filtro value do option selecionado do dropdown
 * @param {string} seletor selector css do objeto
 */
const markDropdown = (filtro = 5, seletor) => {

	let
		objeto = document.querySelectorAll(seletor),
		objetoPai;

	for (let el = objeto[0].parentNode; el && el.parentNode; el = el.parentNode) {

		if (el.id == 'dropdown-filtro') {

			objetoPai = el;
			break;
		}
	}

	objeto.forEach(function (item) {

		item.classList.remove('selected');

		if (item.dataset.value == filtro) {

			item.innerHTML = `<i class="fa-solid fa-square-caret-right cl-orange me-2"></i>${item.innerText}`;

			item.classList.add('selected');

			if (objetoPai) {

				var btnFiltro = objetoPai.querySelector('button');
				// Verifica se o botão foi encontrado
				if (btnFiltro) {
					// Modifica o texto do botão
					btnFiltro.innerHTML = '<i class="fa-solid cl-orange fa-filter"></i> ' + item.innerText;
				}
			}

		} else {

			item.innerHTML = item.innerText;
		}
	});
}

function verificarURL(str) {
	// Expressão regular para verificar se a string é uma URL
	var regex = /^(ftp|http|https):\/\/[^ "]+$/;
	// Verifica se a string corresponde à expressão regular
	if (regex.test(str)) {

		return str;
	} else {

		return base_url(str);
	}
}

const swalLoader = (title = null, html = null) => {

	Swal.fire({

		allowOutsideClick: false,
		title: title || "Buscando informações!",
		html: html || "Aguarde...",
		didOpen: () => {

			Swal.showLoading();
		},
	});
}

const executaRequestAjax = (rota, postData = {}, extra = {}) => {

	let title = extra.textTitle === undefined ? null : extra.textTitle;

	let beforeSend = () => {

		swalLoader(title);
	}

	if ('loaderBeforeSend' in extra) {

		beforeSend = extra.loaderBeforeSend ? beforeSend : () => { };
	}

	try {

		return new Promise((resolve, reject) => {

			$.ajax({
				Headers: { "Content-Type": "application/json" },
				type: "POST",
				url: verificarURL(rota),
				dataType: "json",
				data: postData,
				crossDomain: true,
				beforeSend: beforeSend,
				success: function (res) {

					resolve(res);
				},
				error: function (error) {

					showError()
					reject(error);
				},
			});
		});

	} catch (error) {

		showError();
	}
}

const alertHTML = () => {

	return `
		<div class="alert alert-warning" role="alert">
			<i class="fa-solid fa-circle-exclamation"></i> Não foi encontrado nenhum resultado com o filtro aplicado!
		</div>
	`;
}

/**
 * Função genérica assíncrona para executar requests via Fetch API, sem loading.
 * @param {string} rota url para requisição
 * @param {object} postData $_POST data
 * @returns any
 */
const executaRequestFetch = async (rota = '', postData = {}) => {

	const options = {
		method: 'POST',
		mode: 'no-cors', // Set mode to 'no-cors' to handle CORS issues
		headers: new Headers({
			'Content-Type': 'application/json'
		}),
		body: new URLSearchParams(postData)
	};

	const response = await fetch(base_url(rota), options);

	if (!response.ok) {

		showError();
	}

	const result = await response.json();
	return result
}

const showError = (message = '') => {

	let text = message.length ? message : "Parece que tivemos um erro, entre em contato com o suporte técnico!";

	Swal.fire({
		allowOutsideClick: false,
		icon: "error",
		title: "Oops!",
		text: text,
	});
}

/**
 * Função que filtra objetos diferentes de um mesmo grupo
 * @param {array} data Dados brutos com os arrays de objetos a serem filtrados
 * @param {string} key 
 * @returns
 */
const filtrarObjetos = (data = [], key = '') => {

	try {

		let
			objetos = [],
			objetosUnicos = [];

		data.forEach(item => {

			if (item.hasOwnProperty(key)) {

				objetos.push(item[key]);
			} else {

				throw new Error("A key informada não existe no objeto.");
			}
		});

		objetos.forEach(obj => {

			if (!objetosUnicos.includes(obj)) {

				objetosUnicos.push(obj);
			}
		});

		objetosUnicos.sort((a, b) => {

			const nomeA = a.toUpperCase();
			const nomeB = b.toUpperCase();

			if (nomeA < nomeB) {
				return -1;
			}
			if (nomeA > nomeB) {
				return 1;
			}
			// Nomes são iguais
			return 0;
		});

		return objetosUnicos;

	} catch (error) {

		console.error(error);
	}
}

// Função para obter o dia anterior a partir de uma string de data no formato "dd-mm-yyyy"
const obterDiaAnterior = (dataString, seletor) => {
	// Divida a string em dia, mês e ano
	var partes = dataString.split("-");
	// Crie um objeto Date usando o formato "ano, mês - 1, dia"
	var data = new Date(partes[2], partes[1] - 1, partes[0]);

	if (isNaN(data)) {

		return '';
	}
	// Subtraia um dia da data
	data.setDate(data.getDate() - 1);

	var dataFormadataSQL = data.toLocaleDateString('fr-CA', {

		year: 'numeric', month: '2-digit', day: '2-digit'
	});

	setTimeout(() => {

		$(seletor).css("transform", "translateX(100%)");

	}, 200);

	setTimeout(() => {

		$(seletor).text("");
		$(seletor).css("transform", "translateX(-100%)");

	}, 400);

	setTimeout(() => {

		$(seletor).text(date_format(dataFormadataSQL));
		$(seletor).css("transform", "translateX(0)");

	}, 600);

	return dataFormadataSQL;
}

const obterDiaPosterior = (dataString, seletor) => {

	// Divida a string em dia, mês e ano
	var partes = dataString.split("-");
	// Crie um objeto Date usando o formato "ano, mês - 1, dia"
	var data = new Date(partes[2], partes[1] - 1, partes[0]);

	if (isNaN(data)) {

		return '';
	}
	// Subtraia um dia da data
	data.setDate(data.getDate() + 1);

	var dataFormadataSQL = data.toLocaleDateString('fr-CA', {

		year: 'numeric', month: '2-digit', day: '2-digit'
	});

	setTimeout(() => {

		$(seletor).css("transform", "translateX(-100%)");

	}, 200);

	setTimeout(() => {

		$(seletor).text("");
		$(seletor).css("transform", "translateX(100%)");

	}, 400);

	setTimeout(() => {

		$(seletor).text(date_format(dataFormadataSQL));
		$(seletor).css("transform", "translateX(0)");

	}, 600);

	return dataFormadataSQL;
}

const getCookie = (chave) => {

	var cookies = " " + document.cookie;
	var key = " " + chave + "=";
	var start = cookies.indexOf(key);

	if (start === -1) return null;

	var pos = start + key.length;
	var last = cookies.indexOf(";", pos);

	if (last !== -1) return cookies.substring(pos, last);

	return cookies.substring(pos);
}

// esta função só criptografa se estiver em um ambiente HTTPS, pois a library "crypto.subtle" não funciona em HTTP
const criptografarSHA256 = async (dados) => {

	try {

		const buffer = new TextEncoder().encode(dados);
		const hashBuffer = await crypto.subtle.digest('SHA-256', buffer);
		const hashArray = Array.from(new Uint8Array(hashBuffer));
		const hashHex = hashArray.map(byte => byte.toString(16).padStart(2, '0')).join('');

		return hashHex;

	} catch (error) {

		console.error('Erro ao calcular o hash SHA-256:', error);
	}
}

function validateEmail(e) {

	let reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	let input = e.target;

	if (input.value == '') {

		$('#feedback-' + input.id).addClass('valid-feedback');
		$('#feedback-' + input.id).text('');
		input.classList.remove('is-invalid');
		input.classList.remove('is-valid');
		return;
	}

	if (reg.test(input.value)) {

		$('#feedback-' + input.id).removeClass('invalid-feedback');
		$('#feedback-' + input.id).addClass('valid-feedback');
		$('#feedback-' + input.id).text('E-mail válido!');
		input.classList.remove('is-invalid');
		input.classList.add('is-valid');

	} else {

		$('#feedback-' + input.id).removeClass('valid-feedback');
		$('#feedback-' + input.id).addClass('invalid-feedback');
		$('#feedback-' + input.id).text('E-mail inválido!');
		input.classList.remove('is-valid');
		input.classList.add('is-invalid');
	}
}
