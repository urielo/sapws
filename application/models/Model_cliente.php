<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_cliente extends MY_Model
{

    public function __construct()
    {
        $this->table = 'segurado';
        $this->primary_key = 'clicpfcnpj';
        $this->return_as = 'array';
        $this->timestamps = FALSE;
//        $this->after_get[] = 'set_sexo';
        $this->has_one['uf']=['Model_uf','cd_uf','clicduf'];
        $this->has_one['rg_uf']=['Model_uf','cd_uf','clicdufemissaorg'];
        $this->has_one['ramoatividade']=['Model_ramoatividade','id_ramo_atividade','clicdprofiramoatividade'];
        $this->has_one['profissao']=['Model_profissao','id_ocupacao','clicdprofiramoatividade'];
        $this->has_one['estadocivl']=['Model_estadocivil','idestadocivil','clicdestadocivil'];
        parent::__construct();
    }

    

    public function update($data = NULL, $column_name_where = NULL, $escape = false)
    {
       $data['dtupdate'] = date('Y-m-d H:i:s'); 

        $this->_database->where($this->primary_key, $column_name_where);
        $this->_database->update($this->table, $data);
        $aff = $this->_database->affected_rows();
        
        if ($aff):
            return $aff;
        else:
            return FALSE;
        endif;
    }
    public function uf(){
        return $this->has_one['uf']=['Model_uf','cd_uf','clicduf'];
    }
    
    protected function set_sexo($data){
         $data['clicdsexo'] = ($data['clicdsexo'] == 1 ? 'Masculino' : 'Feminio');
        return $data;
    }
}
