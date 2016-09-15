<?php

/**
 * Model_cotacaoproduto { TYPE }
 * Description
 * @copyright (c) year, Uriel F. Oliveira
 */
class Model_produto_seguradora extends MY_Model
{

    public function __construct()
    {
        $this->table = 'seguradora_produto';
        $this->return_as = 'array';
        $this->before_create = array('prep_data');
        $this->timestamps = FALSE;
        $this->has_one['produto'] = ['Model_produto','idproduto','idproduto'];
        $this->has_one['seguradora'] = ['Model_seguradora','idseguradora','idseguradora'];
        parent::__construct();
    }

   
}
