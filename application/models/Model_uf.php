<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_uf extends MY_Model
{

    public function __construct()
    {
        $this->table = 'uf';
        $this->primary_key = 'cd_uf';
        $this->return_as = 'array';
        #$this->after_get = array('prep_data');
        parent::__construct();
    }

    protected function prep_data($data)
    {
        $prepd_data = array();
        $modelData = array('cd_uf' => 'cduf', 'nm_uf' => 'nmuf', 'premio'=>'premio');

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
