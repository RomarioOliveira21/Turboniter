<div class="modal fade" id="modalCompartilhar" tabindex="-1" aria-labelledby="modalCompartilharLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-secondary" id="modalCompartilharLabel"><i class="fa-solid fa-share-nodes"></i> O Link do relatório esta pronto.</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-1 text-end">
                    <button id="btn-copy-link" type="button" class="btn btn-outline-success btn-sm fw-bold"><i class="fa-solid fa-link"></i> Copiar link do relatório</button>
                </div>
                <div class="d-flex justify-content-center align-content-center m-0">
                    <div class="w-50 container-login-animation"> </div>
                </div>

                <form>
                    <div class="mb-3">
                        <label for="email-compartilhar" class="col-form-label text-secondary fw-bold mb-1"><i class="fas fa-envelope"></i> Destinatário</label>
                        <input type="email" oninput="validateEmail(event)" placeholder="ex. usuario@email.com" class="form-control" id="email-compartilhar">
                        <div class="fw-bold" id="feedback-email-compartilhar"></div>
                        <small id="aviso-email" class="text-bg-warning fw-bold badge text-wrap mt-1"><i class="fa-solid fa-circle-exclamation"></i> Não encontramos um e-mail cadastrado para envio do link</small>
                    </div>
                </form>

                <div style="display: none;" class="mb-3" id="content-text-area-link">
                    <label for="link-relatorio" class="form-label fw-bold mb-1">Link Relatório: </label>
                    <textarea readonly autocomplete="off" translate="no" class="form-control" id="link-relatorio" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Descartar</button>
                <a type="button" id="btn-whatsapp" href="#" target="_blank" class="btn btn-success fw-bold d-flex text-center"><i class="fs-4 fab fa-whatsapp"></i></a>
                <button type="button" id="btn-enviar-email" onclick="enviar_link_relatorio(event)" class="btn btn-outline-primary"><i class="fa-solid fa-envelope"></i> Enviar e-mail</button>
            </div>
        </div>
    </div>
</div>