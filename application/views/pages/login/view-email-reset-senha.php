<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Link recuperação senha</title>
</head>

<style>
	* {
		font-family: Arial, Helvetica, sans-serif;
	}

	.btn {
		text-decoration: none;
		border-radius: 8px;
		padding: 15px;
		color: white;
		background-color: #1D5D9B;
	}

	.bg-dark {
		background-color: #354649;
	}

	.d-flex {
		display: flex;
	}

	.flex-column {
		flex-direction: column;
	}

	.rounded {
		border-radius: 6px;
	}

	.mt-3 {
		margin-top: 3%;
	}

	.mb-3 {
		margin-bottom: 3%;
	}

	.text-light {
		color: #fff;
	}

	.justify-content-between {
		justify-content: space-between;
	}

	.text-center {
		text-align: center;
	}

	.container {
		display: flex;
		/* Use flexbox para centralizar */
		justify-content: center;
		/* Centralize horizontalmente */
		align-items: center;
		justify-self: center;
		margin: auto;
	}

	.text-warning {
		color: #FFB000;
	}

	.mb-0 {
		margin-bottom: 0%;
	}

	body {
		background-color: #F8F9FA;
	}

	.footer>span {
		color: cadetblue;
	}

	.fst-italic {
		font-style: italic;
	}

	.text-muted {
		font-size: smaller;
		color: #A9907E;
	}

	.justify-content-center {
		align-self: center;
		margin: auto;
	}
</style>

<body class="bg-body-tertiary d-flex flex-column justify-content-between">
	<div class="d-flex flex-column justify-content-between">

		<nav class="navbar bg-body-tertiary">
			<div>
				<h1 class="navbar-brand mb-0 text-warning">POSTO</h1>
			</div>
		</nav>
		<div class="justify-content-center">
			<div class="row mt-3">
				<div class="col">
					<p class="fw-bold border-bottom">Seu link para recuperação de senha chegou!</p>
				</div>
			</div>
		</div>

		<div class="mt-3">
			<div class="text-center">
				<a class="btn btn-primary btn-lg" href="<?= $link ?>">Recuperar senha</a>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col text-center">
				<p class="fw-bold fst-italic">Se você não solicitou a alteração de sua senha, desconsidere
					este e-mail.
				</p>
			</div>
		</div>
	</div>
	<footer class="footer">
		<div>
			<span class="text-muted">Essa mensagem foi enviada pela equipe Veredas Tecnologia e informação
				LTDA.</span>
		</div>
	</footer>
</body>

</html>
