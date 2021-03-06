<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_produto extends MY_Model
{

    public function __construct()
    {
        $this->table = 'produto';
        $this->primary_key = 'idproduto';
        $this->return_as = 'array';
        $this->has_many['precos'] = ['Model_precoproduto', 'idproduto', 'idproduto'];
        $this->has_many['opcionais'] = ['Model_combos', 'idprodutomaster', 'idproduto'];
        $this->has_many['seguradoras'] = ['Model_produto_seguradora','idproduto','idproduto'];
        
        
     
        parent::__construct();
    }

    protected function prep_data($data)
    {
        $prepd_data = array();
        $modelData = array('cd_uf' => 'cduf', 'nm_uf' => 'nmuf');

        foreach ($data as $datak => $datav):
            foreach ($modelData as $modelk => $modelv):
                if ($modelk == $datak):
                    $prepd_data[$modelv] = $datav;
                endif;
            endforeach;
        endforeach;
        return $prepd_data;
    }
}
