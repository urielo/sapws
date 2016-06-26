<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Model_condutor extends MY_Model {
    
    public function __construct() {
        $this->table = 'condutor';
        $this->primary_key = 'id';
        $this->return_as = 'array';
        $this->timestamps = false;
        #$this->before_create = array('prep_data');
        parent::__construct();
    }

    protected function prep_data($datas) {
        $prepdData = array();
         $modelData = array('condutNomeRazao',
            'condutCpfCnpj',
            'condutDtNasci',
            'condutCdSexo',
            'condutCdEstCivl',
            'condutProfRamoAtivi');

        $modelDataDB = array('condutNomeRazao' => 'condnomerazao',
            'condutCpfCnpj' => 'condcpfcnpj',
            'condutDtNasci' => 'conddtnasc',
            'condutCdSexo' => 'condcdsexo',
            'condutCdEstCivl' => 'condcdestadocivil',
            'condutProfRamoAtivi' => 'condcdprofiramoatividade',);


        foreach ($datas as $k => $v):
            foreach ($modelDataDB as $kdb=>$vdb):
                if (in_array($k, $modelData) && $k==$kdb):
                    $prepdData[$vdb] = $v;
                endif;
            endforeach;
        endforeach;

        return $prepdData;
    }
    public function update($data = NULL, $column_name_where = NULL, $escape = false)
    {
        $datas = $this->prep_data($data);
        $datas['dtupdate'] = date('Y-m-d h:i:s'); 

        $this->_database->where($this->primary_key, $column_name_where);
        $this->_database->update($this->table, $datas);
        $aff = $this->_database->affected_rows();
        
        if ($aff):
            return $aff;
        else:
            return FALSE;
        endif;
    }
}
