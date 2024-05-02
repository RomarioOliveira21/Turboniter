<?php $this->layout('layouts/comum'); ?>

<div class="container merriweather-regular py-4">
    <header class="pb-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center text-body-emphasis text-decoration-none">
            <img src="<?= site_url('public/assets/img/logo.png') ?>" width="40rem" alt="logo">
            <span class="fs-4 fw-bold ms-2">Turboniter</span>
        </a>
    </header>

    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <div class="container-fluid py-5">
            <p class="fs-6">O framework foi customizado para se obter a maior eficiência em adição de novas funcionalidades, foi adicionada a pasta core <code>(aplication/core)</code> classes que estendem (herdam) todos os métodos das classes principais de <strong>Exception, Validation e Loader</strong>, com intuito de exiber melhor os erros da aplicação, adicionar de forma mais pratica validações aos formulários e melhorar a forma de montagem dos templates nas views. </p>
        </div>
    </div>

    <div class="row align-items-md-stretch">
        <div class="col-md-6">
            <div class="h-100 p-5 text-bg-dark rounded-3">
                <h2>Libs já incluídas</h2>
                <hr>
                <ul class="list-group-dark">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <img width="40rem" src="https://getbootstrap.com/docs/5.3/assets/brand/bootstrap-logo-shadow.png" alt="">
                        <span class="badge text-bg-secondary rounded-pill"><a class="text-decoration-none text-light" target="_blank" href="https://getbootstrap.com/">Bootstrap 5</a></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <img width="40rem" src="https://www.gstatic.com/images/branding/product/1x/google_fonts_64dp.png" alt="">
                        <span class="badge text-bg-secondary rounded-pill"><a class="text-decoration-none text-light" target="_blank" href="https://fonts.google.com/">Google fonts</a></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <img width="40rem" src="https://platesphp.com/images/logo.png" alt="">
                        <span class="badge text-bg-secondary rounded-pill"><a class="text-decoration-none text-light" target="_blank" href="https://platesphp.com/">League Plates</a></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <img width="40rem" src="https://codeigniter.com/assets/icons/ci-logo.png" alt="">
                        <span class="badge text-bg-secondary rounded-pill"><a class="text-decoration-none text-light" target="_blank" href="https://codeigniter.com/">Codeigniter 3</a></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <img width="40rem" src="https://sweetalert2.github.io/images/favicon.png" alt="">
                        <span class="badge text-bg-secondary rounded-pill"><a class="text-decoration-none text-light" target="_blank" href="https://sweetalert2.github.io/">Sweet Alert 2</a></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <img width="40rem" src="https://jquery.com/wp-content/themes/jquery.com/i/favicon.ico" alt="">
                        <span class="badge text-bg-secondary rounded-pill"><a class="text-decoration-none text-light" target="_blank" href="https://jquery.com/">JQuery</a></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="h-100 p-5 bg-body-tertiary border rounded-3">
                <h2>Mais informações...</h2>
                <p>A aplicação contem diversas configurações descritas na documentação do codeigniter 3, tradução de mensagems, adição de helpers.</p>
                <p>Para debug utilize a função <code>dump()</code></p>
                <p>As Exceptions estão sendo exebidas atravez da lib filp/whoops <a href="https://github.com/filp/whoops">site</a></p>
            </div>
        </div>
    </div>
</div>