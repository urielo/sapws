<?php

class Model_fipecategoria extends MY_Model
{

    public function __construct()
    {
        $this->table = 'fipe_categoria';
        $this->primary_key = 'codefipe';
        $this->return_as = 'array';
        parent::__construct();
    }

    
}
