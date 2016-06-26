<?php

/**
 * Model_parceiro { TYPE }
 * Description
 * @copyright (c) year, Uriel F. Oliveira
 */
class Model_perfilsegurado extends MY_Model
{
    public function __construct()
    {
        $this->table = 'parceiro';
        $this->primary_key = 'idparceiro';
        $this->return_as = 'array';
        parent::__construct();
    }
}
