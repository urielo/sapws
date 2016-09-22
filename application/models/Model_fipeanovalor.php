<?php

class Model_fipeanovalor extends MY_Model
{

    public function __construct()
    {
        $this->table = 'fipeanovalor';
        $this->primary_key = 'codefipe';
        $this->return_as = 'array';
        $this->before_create = array('prep_data');
        $this->has_one['fipe']= ['Model_fipe','codefipe','codefipe'];

        parent::__construct();
    }

    protected function prep_data($datas)
    {
        $prepdData = array();
        $modelData = array('codefipe',
            'idcombustivel',
            'ano',
            'valor',);

        $modelDataDB = array('codefipe' => 'codefipe',
            'idcombustivel' => 'idcombustivel',
            'ano' => 'ano',
            'valor' => 'valor',);

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
