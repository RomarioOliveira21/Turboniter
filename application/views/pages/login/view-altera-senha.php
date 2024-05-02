<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Alteração de senha</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="<?= site_url('public/css/login/util.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= site_url('public/css/login/main.css') ?>">
	<link rel="stylesheet" href="<?= site_url('public/css/login/style.css') ?>">
</head>


<body>
	<div class="limiter">
		<div class="container-login100 d-flex flex-column" style="background-image: url('<?= site_url('public/img/posto-combustivel.jpg') ?>');">
			<main class="bg-light form-signin w-100 shadow-lg rounded" style="max-width: 400px; padding: 1rem;">
				<form action="<?= site_url("Login/gravar_nova_senha") ?>" method="post">

					<div class="row d-flex justify-content-center">
						<div class="col-6 text-center mt-1 box-shadow rounded fs-30">
							<span class="sig">POSTO</span>
						</div>
					</div>
					
					
					<input type="hidden" name="email" value="<?= set_value('email', $email ?? "") ?>">
					<input type="hidden" name="tipo" value="<?= set_value('tipo', $tipo ?? "") ?>">
					<input type="hidden" name="token" value="<?= set_value('token', $token ?? "") ?>">
					
					<div class="text-center rounded">
						<img width="200" src="<?= site_url("public/svg/logo-reset-senha.svg") ?>" alt="reset senha">
					</div>
					<div class="text-center mb-3">
						<span class="h5 mt-5 border-bottom">Alteração de senha</span>
					</div>

					<?php echo validation_errors(); ?>

					<div class="text-center" id="alert" style="display: none;">
						<span class="fw-bold text-danger">&#9888; Senhas não coincidem!</span>
					</div>
					<div>
						<div class="mb-3">
							<label for="senha" class="form-label fw-bold mb-0">Nova senha:</label>
							<input type="password" minlength="8" class="form-control form-control-lg" name="senha" id="senha">
						</div>
					</div>
					<div>
						<div class="mb-1">
							<label for="confir-senha" class="form-label fw-bold mb-0">Confirme sua nova senha:</label>
							<input type="password" minlength="8" class="form-control form-control-lg" name="confir-senha" id="confir-senha">
						</div>
					</div>
					<div class="container-login100-form-btn m-t-20">
						<button type="submit" id="btn-enviar" class="login100-form-btn">Gravar</button>
					</div>
					<div class="row">
						<div class="col text-center mt-2">
							<a class="text-decoration-none text-black badge" href="https://veredastecnologia.com.br/"> &#169; by Veredas Tecnologia - <?= date("Y") ?></a>
						</div>
					</div>
				</form>
			</main>
		</div>
	</div>

	<script>
		function habilita_botao() {
			const senhaInput = document.getElementById('senha');
			const confirSenhaInput = document.getElementById('confir-senha');
			const submitButton = document.getElementById('btn-enviar');
			const alert = document.getElementById('alert');

			if (senhaInput.value == confirSenhaInput.value) {
				alert.style.display = "none";
				submitButton.disabled = false; // Habilitar o botão se o e-mail for válido
			} else {
				alert.style.display = "block";
				submitButton.disabled = true; // Desabilitar o botão se o e-mail for inválido
			}
		}

		document.getElementById('btn-enviar').disabled = true;
		const senhaInput = document.getElementById('senha');
		const confirSenhaInput = document.getElementById('confir-senha');
		senhaInput.addEventListener('input', habilita_botao);
		confirSenhaInput.addEventListener('input', habilita_botao);
	</script>
</body>

</html>
