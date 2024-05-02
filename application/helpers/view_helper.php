<?php


function view($page, $data = [])
{

    $templates = new League\Plates\Engine(VIEWPATH);

    $templates->registerFunction('ci', function () {

        return get_instance();
    });

    try {

        echo $templates->render($page, $data);

    } catch (League\Plates\Exception\TemplateNotFound $e) {

        var_dump($e->getMessage());exit;

        // show_404(); // Chama a função para exibir o erro 404
    }
}
