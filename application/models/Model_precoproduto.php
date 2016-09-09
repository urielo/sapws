<?php

class Model_precoproduto extends MY_Model
{

    public function __construct()
    {
        $this->table = 'precoprodutofipe';
        $this->primary_key = 'idprecoproduto';
        $this->return_as = 'array';
        $this->has_one['produto'] = ['Model_produto','idproduto','idproduto'];

        parent::__construct();
    }
}
