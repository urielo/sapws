<?php

/**
 * Model_parceiro { TYPE }
 * Description
 * @copyright (c) year, Uriel F. Oliveira
 */
class Model_parceiro extends MY_Model
{
    public function __construct()
    {
        $this->table = 'parceiro';
        $this->primary_key = 'idparceiro';
        $this->return_as = 'array';
        $this->has_one['key'] = ['Model_key','user_id','idparceiro'];
        parent::__construct();
    }
}
