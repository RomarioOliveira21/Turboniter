<div class="container py-4">

	<div class="row align-items-md-stretch">
		<div class="col">
			<div class="h-100 p-5 bg-body-tertiary border rounded-3">
				<h2 class="border-bottom">Meus dados</h2>

				<p><span class="badge text-bg-primary"><i class="fa-solid fa-user"></i></span> <?= $this->session->userdata('usuario')['nome'] ?></p>
				<p><span class="badge text-bg-primary"><i class="fa-solid fa-envelope"></i></span> <?= $this->session->userdata('usuario')['email'] ?></p>

				<div class="row text-end mt-3">
					<p><button type="button" data-bs-toggle="modal" data-bs-target="#modal_alterar_senha" class="btn btn-outline-secondary rounded p-2"><i class="fa-solid fa-key bell"></i> Alterar senha</button></p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_alterar_senha" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="fa-solid fa-lock"></i> Alterando senha</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-floating mb-2">
					<input type="password" class="form-control" name="senha_atual" id="senha_atual">
					<label for="senha_atual">Senha atual</label>
				</div>
				<div class="form-floating mb-2">
					<input type="password" class="form-control" name="nova_senha" id="nova_senha">
					<label for="nova_senha">Nova senha</label>
					<small class="text-danger fw-bold" style="display: none;"><i class="fa-solid fa-circle-exclamation"></i> senhas não coincidem</small>
				</div>
				<div class="form-floating">
					<input type="password" class="form-control" name="confirme_senha" id="confirme_senha">
					<label for="confirme_senha">Confirme a nova senha</label>
					<small class="text-danger fw-bold" style="display: none;"><i class="fa-solid fa-circle-exclamation"></i> senhas não coincidem</small>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" onclick="alterar_senha()" class="btn btn-primary">Alterar</button>
			</div>
		</div>
	</div>
</div>

<script>
	const alterar_senha = () => {

		let alerts = $(".modal-body small");

		if ($("#nova_senha").val() != $("#confirme_senha").val() || $("#nova_senha").val() == '' || $("#confirme_senha").val() == '') {

			alerts.each(function() {
				$(this).show();
			});
			return false;
		} else {

			alerts.each(function() {
				$(this).hide();
			});

			$.ajax({
				type: "POST",
				url: `${BASE_URL}alterar-senha`,
				dataType: "json",
				data: {
					senha_atual: $("#senha_atual").val(),
					nova_senha: $("#confirme_senha").val()
				},
				success: function(res) {

					if (res.error) {
						Swal.fire({
							icon: "error",
							title: "Oops!",
							text: "Verifique os dados informados e tente novamente!",
						});
					} else {
						Swal.fire({
							icon: "success",
							text: "Senha alterada com sucesso!",
						});
					}

					$("#nova_senha").val("");
					$("#senha_atual").val("");
					$("#confirme_senha").val("");
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
	}
</script>
