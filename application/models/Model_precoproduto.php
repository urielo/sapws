<?php

class Model_precoproduto extends MY_Model
{

    public function __construct()
    {
        $this->table = 'precoprodutofipe';
        $this->primary_key = 'idprecoproduto';
        $this->return_as = 'array';
        parent::__construct();
    }
}
