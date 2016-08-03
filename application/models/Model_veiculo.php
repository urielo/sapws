<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_veiculo extends MY_Model
{

    public function __construct()
    {
        $this->table = 'veiculosegurado';
        $this->primary_key = 'veicid';
        $this->return_as = 'array';
        $this->timestamps = FALSE;
        parent::__construct();
    }
 
  

    public function update($data = NULL, $column_name_where = NULL, $escape = false)
    {
        
        $data['dtupdate']= date('Y-m-d H:i:s');

        $this->_database->where($this->primary_key, $column_name_where);
        $this->_database->update($this->table, $data);
        $aff = $this->_database->affected_rows();

        if ($aff):
            return $aff;
        else:
            return FALSE;
        endif;
    }
}
