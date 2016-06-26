<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_parcela extends MY_Model
{

    public
        function __construct()
    {
        $this->table = 'parcelapgto';
        $this->primary_key = 'idformapgto';
        $this->return_as = 'array';
        parent::__construct();
    }

    
}
