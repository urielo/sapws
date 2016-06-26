<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_estadocivil extends MY_Model {

      
    public function __construct() {
        $this->table = 'estadocivil';
        $this->primary_key = 'idestadocivil';
        $this->return_as = 'array';
       # $this->after_get= array('prep_data');
        parent::__construct();
    }
    
    protected function prep_data($data){
        $prepd_data = array();
        $modelData = array('id_ocupacao'=>'cdprofissao','nm_ocupacao'=>'nmprofissao');
        
        foreach($data as $datak=>$datav):
            foreach($modelData as $modelk=>$modelv):
            if($modelk==$datak):
                $prepd_data[$modelv] = $datav;
            endif;
            endforeach;
       endforeach;
       return $prepd_data;
        
    }
}