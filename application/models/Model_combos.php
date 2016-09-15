<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_combos extends MY_Model
{

    public function __construct()
    {
        $this->table = 'produtos_combos';
        $this->return_as = 'array';
        $this->timestamps = FALSE;

        $this->has_many['opcionais'] = ['Model_produto', 'idproduto','idprodutoopcional'];

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
