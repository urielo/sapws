<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_custos_produto extends MY_Model
{

    public function __construct()
    {
        $this->table = 'custo_produto';
        $this->return_as = 'id';
        $this->timestamps = FALSE;
        $this->has_one['produto'] = [
            'Model_produto_seguradora','idproduto','idproduto'
        ];
        
        parent::__construct();
    }

   

    public function update($data = NULL, $column_name_where = NULL, $escape = false)
    {
        
        $data['dtupdate'] = date('Y-m-d h:i:s');

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
