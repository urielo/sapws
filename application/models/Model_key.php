<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_key extends MY_Model
{

    public function __construct()
    {
        $this->table = 'keys';
        $this->primary_key = 'id';
        $this->return_as = 'array';
        parent::__construct();
    }


}
