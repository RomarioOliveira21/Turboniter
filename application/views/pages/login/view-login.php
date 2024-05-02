<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Veredas Tecnologia e Informação">
	<title> Web Gerencial </title>
	<link rel="stylesheet" type="text/css" href="<?= site_url('public/assets/css/login/main.css') ?>">
	<link rel="stylesheet" href="<?= site_url('public/assets/css/login/style.css') ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>

	<div class="loader" style="display: none;">
		<div class="justify-content-center jimu-primary-loading"></div>
	</div>

	<div id="form-content">
		<div class="limiter">
			<div class="container-login100" style="background-image: url('<?= site_url('public/assets/img/fundo.jpg') ?>');">
				<div class="wrap-login100 p-t-5 p-b-10">
					<form class="login100-form validate-form p-b-30 p-t-5" action="<?= site_url('login') ?>" method="post">
						<div class="row d-flex justify-content-center">
							<div class="col-auto text-center box-shadow mt-1 rounded">
								<span class="sig">Web-Gerencial</span>
							</div>
						</div>

						<div class="d-flex justify-content-center align-content-center">
							<div class="container-login-animation"> </div>
						</div>

						<div class="mt-2 mb-1 col">
							<label for="senha" class="form-label font-weight-bold">Email:</label>
							<input type="text" maxlength="100" value="<?= set_value('email') ?>" class="form-control form-control-md" id="username" name="email">
							<?= form_error('email') ?>
						</div>

						<div class="mb-1 col">
							<button class="btn-ver-senha" title="Exibir senha" type="button" id="btn_mostrar_senha"><i class="fas fa-eye"></i></button>
							<label for="senha" class="form-label font-weight-bold">Senha:</label>
							<input type="password" maxlength="20" class="form-control form-control-md" value="<?= set_value('senha') ?>" id="password" name="senha">
							<?= form_error('senha') ?>
						</div>

						<div class="row">
							<div class="col m-2">
								<?= mensagem() ?>
							</div>
						</div>

						<div class="row">
							<div class="col-12 text-right">
								<a class="mr-2 font-weight-bold text-dark text-decoration-none" data-toggle="modal" data-target="#exampleModal">esqueceu a senha?</a>
							</div>
						</div>

						<div class="container-login100-form-btn mt-2">
							<button type="submit" class="login100-form-btn">
								Login
							</button>
						</div>
						<div class="row">
							<div class="col text-center mt-2">
								<a class="text-decoration-none badge badge-light" href="https://veredastecnologia.com.br/"> &#169; by Veredas Tecnologia - <?= date("Y") ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="exampleModalLabel">Recuperação de senha</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body bg-light">
					<span class="mb-2">Informe seu email <strong>cadastrado</strong> para receber o link de recuperação de senha.</span><br>
					<div class="input-group flex-nowrap">
						<div class="input-group-prepend">
							<span class="input-group-text" id="addon-wrapping">Email</span>
						</div>
						<input type="email" class="form-control" name="email" id="email" aria-label="Email" aria-describedby="addon-wrapping">
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button id="btn-enviar" onclick="enviar_email()" disabled type="button" class="btn btn-primary">Enviar</button>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.5.9/lottie.js"></script>
	<script>
		jQuery(function() {

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
				path: "public/assets/gif/lottie.json"
			});




			const enviar_email = () => {

				$.ajax({
					type: "POST",
					url: window.location.protocol + "//" + window.location.host + "/recuperacao-senha",
					dataType: "json",
					data: {
						email: $("#email").val()
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Aguarde...',
							didOpen: () => {
								Swal.showLoading();
							}
						});
					},
					success: function(res) {

						Swal.fire({
							icon: "success",
							text: res.msg,
						});
					},
					error: function(error) {
						console.log(error);
						Swal.fire({
							icon: "error",
							title: "Oops!",
							text: "Parece que tivemos um erro, entre em contato com o suporte técnico!",
						});
					},
				});
			}

			function isValidEmail(email) {
				// Utilizamos uma expressão regular para validar o formato do e-mail
				const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
				return emailRegex.test(email);
			}

			function habilita_botao() {
				const emailInput = document.getElementById('email');
				const submitButton = document.getElementById('btn-enviar');

				if (isValidEmail(emailInput.value)) {
					submitButton.disabled = false; // Habilitar o botão se o e-mail for válido
				} else {
					submitButton.disabled = true; // Desabilitar o botão se o e-mail for inválido
				}
			}

			const emailInput = document.getElementById('email');
			emailInput.addEventListener('input', habilita_botao);
			$("#email").val("");

			$('#btn_mostrar_senha').click(function() {

				const campoSenha = document.getElementById("password");
				if (campoSenha.type === "password") {
					campoSenha.type = "text";
				} else {
					campoSenha.type = "password";
				}
			})
		});
	</script>

</body>

</html>
