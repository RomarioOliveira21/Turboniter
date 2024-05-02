<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Whoops\Handler\JsonResponseHandler;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

class MY_Exceptions extends CI_Exceptions
{
    private $whoops;

    public function __construct()
    {
        parent::__construct();

        $this->whoops = new Run;
    }

    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        $this->whoops->pushHandler(new PrettyPageHandler);
        $this->whoops->handleException(new \ErrorException($message, 0, $status_code));
        exit;
    }

    public function show_exception($exception) {
       
        $this->whoops->pushHandler(new PrettyPageHandler);
        $this->whoops->handleException($exception);
        exit;
    }

    public function show_ajax_exception($exception){

        $this->whoops->pushHandler(new JsonResponseHandler);
        $this->whoops->handleException($exception);
        exit;
    }
}
