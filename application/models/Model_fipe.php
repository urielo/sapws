<?php

class Model_fipe extends MY_Model
{

    public function __construct()
    {
        $this->table = 'fipe';
        $this->primary_key = 'codefipe';
        $this->return_as = 'array';
        parent::__construct();
        $this->has_many['valores']= ['Model_fipeanovalor','codefipe','codefipe'];
    }

    
}
