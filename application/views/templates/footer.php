<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelLogout" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabelLogout">Obrigado por utilizar nossos serviços, volte
					sempre!</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Deseja realmente sair?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
				<a href="<?= site_url('logout') ?>" class="btn btn-primary">Logout</a>
			</div>
		</div>
	</div>
</div>
</main>
<footer class="bg-white p-1">
	<div class="copyright text-center">
		<span>copyright &copy;
			<script>
				document.write(new Date().getFullYear());
			</script> - desenvolvido por
			<b><a href="https://veredastecnologia.com.br" target="_blank">
					<img src="<?= site_url("public/assets/img/vti.png") ?>" width="90px" alt="logo">
				</a></b>
		</span>
	</div>
</footer>
</div>
</div>

<!-- javascript library -->
<script src="<?= site_url("public/assets/vendor/sidebar-skeleton-compostrap/dist/js/sidebar.js") ?>"></script>
<script src="<?= site_url("public/assets/vendor/perfect-scrollbar/dist/perfect-scrollbar.js") ?>"></script>
<script src="<?= site_url("public/assets/vendor/nanobar/nanobar.js") ?>"></script>
<script src="<?= site_url("public/assets/vendor/sidebar/sidebar.menu.js") ?>"></script>

<script>
	document.addEventListener('DOMContentLoaded', () => {

		$('#btn-logout').click(async function() {

			if (decodeURIComponent(getCookie('HTTP_ORIGIN')) != window.location.protocol + '//' + window.location.host) {

				await fetch(base_url('Login/delete_session')).then(response => {

					console.log(response);

				}).catch(error => {

					console.log(error);
					showError();
				});

				window.close();
			} else {

				window.location.href = base_url('logout')
			};
		});

		new Nanobar().go(100);
		new PerfectScrollbar('.scrollbar', {
			wheelSpeed: 0.3
		});
	});
</script>

</body>

</html>