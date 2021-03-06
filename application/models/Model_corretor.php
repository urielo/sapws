<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_corretor extends MY_Model
{

    public function __construct()
    {
        $this->table = 'corretor';
        $this->primary_key = 'idcorretor';
        $this->return_as = 'array';
        $this->timestamps = false;
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
