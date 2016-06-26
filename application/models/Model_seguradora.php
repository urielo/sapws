<?php

class Model_seguradora extends MY_Model
{

    public function __construct()
    {
        $this->table = 'seguradora';
        $this->primary_key = 'idseguradora';
        $this->return_as = 'array';
        parent::__construct();
    }
}
