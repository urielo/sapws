<?php

/**
 * Model_proposta { TYPE }
 * Description
 * @copyright (c) year, Uriel F. Oliveira
 */
class Model_proposta extends MY_Model
{

    public function __construct()
    {
        $this->table = 'proposta';
        $this->primary_key = 'idproposta';
        $this->return_as = 'array';
        $this->has_one['veiculo'] = ['Model_veiculo','veicid','veiculo_id'];
        $this->timestamps = FALSE;
        $this->has_one['cotacao'] = ['Model_cotacao', 'idcotacao','idcotacao'];
        $this->has_one['forma_pagamento'] = ['Model_parcela', 'idformapgto','idformapg'];

        parent::__construct();
    }

    protected function prep_data($data)
    {
        $modelData = array(
            'cdCotacao',
            'cdFormaPgt',
            'qtParcela',
        );

        $modelDataDB = array(
            'cdCotacao' => 'idcotacao',
            'cdFormaPgt' => 'idformapg',
            'qtParcela' => 'quantparc',
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
