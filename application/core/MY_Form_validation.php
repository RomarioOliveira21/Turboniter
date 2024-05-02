<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function valid_date($date)
    {
        // Sua lógica de validação de data aqui
        // Por exemplo, verifica se a data está no formato correto
        if (!preg_match('/^(\d{4})-(0[1-9]|1[0-2])-([0-2]\d|3[0-1])$/', $date)) {

            $this->set_message("valid_date", 'O campo {field} deve conter uma data válida no formato YYYY-MM-DD.');
            return FALSE;
        }

        return TRUE;
    }
}
