<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_cotacao extends MY_Model
{

    public function __construct()
    {
        $this->table = 'cotacao';
        $this->primary_key = 'idcotacao';
        $this->return_as = 'array';
        $this->timestamps = FALSE;
        $this->has_one['veiculo'] = ['Model_veiculo','veicid','veicid'];
        $this->has_one['segurado'] = ['Model_cliente','clicpfcnpj','clicpfcnpj'];
        $this->has_one['corretor'] = ['Model_corretor','idcorretor','idcorretor'];
        $this->has_one['parceiro'] = ['Model_parceiro','idparceiro','idparceiro'];
        $this->has_many['produtos'] = ['Model_cotacaoproduto', 'idcotacao','idcotacao'];

        #$this->before_create = array('prep_data_create');
        # $this->after_get = array('prep_data_get');
        parent::__construct();
    }

    protected function prep_data_create($data)
    {
        $modelData = array(
            'idcorretor',
            'comissao',
            'idParceiro',
            'veicid',
            'segCpfCnpj',
            #'segEndCep',
            'premio',
        );

        $modelDataDB = array(
            'idParceiro' => 'idparceiro',
            'idcorretor' => 'idcorretor',
            'segCpfCnpj' => 'clicpfcnpj',
            'veicid' => 'veicid',
            'idsProduto' => 'idproduto',
            'premio' => 'premio',
            #'segEndCep'=> 'segendcep',
            'comissao' => 'comissao',
        );


        foreach ($data as $k => $v):
            foreach ($modelDataDB as $kdb => $vdb):
                if (in_array($k, $modelData) && $k == $kdb):
                    $prepdData[$vdb] = $v;
                endif;
            endforeach;
        endforeach;
        return $prepdData;
    }

    protected function prep_data_update($data)
    {
        $modelData = array(
            'idcorretor',
            #'comissao',
            'idParceiro',
            'veicid',
            'segCpfCnpj',
            #'segEndCep',
            'premio',
        );

        $modelDataDB = array(
            'idParceiro' => 'idparceiro',
            'idcorretor' => 'idcorretor',
            'segCpfCnpj' => 'clicpfcnpj',
            'veicid' => 'veicid',
            'idsProduto' => 'idproduto',
            'premio' => 'premio',
            #'segEndCep'=> 'segendcep',
            #'comissao'=> 'comissao',
        );


        foreach ($data as $k => $v):
            foreach ($modelDataDB as $kdb => $vdb):
                if (in_array($k, $modelData) && $k == $kdb):
                    $prepdData[$vdb] = $v;
                endif;
            endforeach;
        endforeach;
        return $prepdData;
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
