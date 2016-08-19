<?php

class Model_contingencia extends MY_Model
{

    public function __construct()
    {
        $this->table = 'contingencia';
        $this->primary_key = 'id';
        $this->return_as = 'array';
        parent::__construct();
    }

    
}
