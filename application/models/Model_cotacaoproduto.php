<?php

/**
 * Model_cotacaoproduto { TYPE }
 * Description
 * @copyright (c) year, Uriel F. Oliveira
 */
class Model_cotacaoproduto extends MY_Model
{

    public function __construct()
    {
        $this->table = 'cotacaoproduto';
        $this->primary_key = 'idcotacaoproduto';
        $this->return_as = 'array';
        $this->before_create = array('prep_data');
        $this->timestamps = FALSE;
        $this->has_one['produto'] = ['Model_produto','idproduto','idproduto'];
        $this->has_one['preco_produto'] = ['Model_precoproduto','idprecoproduto','idprecoproduto'];

        
        
        parent::__construct();
    }

    protected function prep_data($datas)
    {
        $modelData = array(
            'idproduto',
            'idprecoproduto',
            'idcotacao',
        );

        $modelDataDB = array(
            'idproduto' => 'idproduto',
            'idprecoproduto' => 'idprecoproduto',
            'idcotacao' => 'idcotacao',
        );


        foreach ($datas as $k => $v):
            foreach ($modelDataDB as $kdb => $vdb):
                if (in_array($k, $modelData) && $k == $kdb):
                    $prepdData[$vdb] = $v;
                endif;
            endforeach;
        endforeach;
        return $prepdData;
    }
}
