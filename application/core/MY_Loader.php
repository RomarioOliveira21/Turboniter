<?php
defined('BASEPATH') or exit('No direct script access allowed');

use League\Plates\Engine;

class MY_Loader extends CI_Loader
{

    protected $templates;

    private $CI;

    public function __construct()
    {
        parent::__construct();

        $this->CI = get_instance();

        $this->templates = new Engine(APPPATH . 'views'); // Diretório onde suas views estão localizadas
    }

    public function view($view, $vars = array(), $return = FALSE)
    {
        $output = $this->templates->render($view, $vars);

        if ($return === TRUE) {

            return $output;
        }

        return $this->CI->output->append_output($output);
    }
}
