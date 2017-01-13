<?php

class Model_fipe extends MY_Model
{

    public function __construct()
    {
        $this->table = 'fipe';
        $this->primary_key = 'codefipe';
        $this->return_as = 'array';
        $this->has_many['valores']= ['Model_fipeanovalor','codefipe','codefipe'];
        $this->has_one['contigencia']= ['Model_contingencia','ind_idstatus_fipe','idstatus'];
        parent::__construct();

    }

    
}
