<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Model_proprietario extends MY_Model {

    public function __construct() {
        $this->table = 'proprietario';
        $this->primary_key = 'id';
        $this->return_as = 'array';
        $this->timestamps= false;
        #$this->after_create = array();
        #$this->before_create = array('prep_data');
        parent::__construct();
    }
    protected function prep_data($datas) {
        $prepdData = array();
        $modelData = array(
            'proprNomeRazao',
            'proprCpfCnpj',
            'proprDtNasci',
            'proprCdSexo',
            'proprCdEstCivl',
            'proprProfRamoAtivi',
            'proprEmail',
            'proprCelDdd',
            'proprCelNum',
            'proprFoneDdd',
            'proprFoneNum',
            'proprEnd',
            'proprEndNum',
            'proprEndCompl',
            'proprEndCep',
            'proprEndCidade',
            'proprEndCdUf'

            );

        $modelDataDB = array(
            'proprNomeRazao' => 'proprnomerazao',
            'proprCpfCnpj' => 'proprcpfcnpj',
            'proprDtNasci' => 'proprdtnasc',
            'proprCdSexo' => 'proprcdsexo',
            'proprCdEstCivl' => 'proprcdestadocivil',
            'proprProfRamoAtivi' => 'proprcdprofiramoatividade',
            'proprEmail' => 'propremail',
            'proprCelDdd' => 'proprdddcel',
            'proprCelNum' => 'proprnmcel',
            'proprFoneDdd' => 'proprdddfone',
            'proprFoneNum' => 'proprnmfone',
            'proprEnd' => 'proprnmend',
            'proprEndNum' => 'proprnumero',
            'proprEndCompl' => 'proprendcomplet',
            'proprEndCep' => 'proprcep',
            'proprEndCidade' => 'proprnmcidade',
            'proprEndCdUf' => 'proprcduf',
            );

        foreach ($datas as $k => $v):
            foreach ($modelDataDB as $kdb=>$vdb):
                if (in_array($k, $modelData) && $k==$kdb):
                    $prepdData[$vdb] = $v;
                endif;
            endforeach;
        endforeach;

        return $prepdData;
    }
    
    protected function getid($id)
    {
        
    }


    public function update($data = NULL, $column_name_where = NULL, $escape = false)
    {
        $#datas = $this->prep_data($data);
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
