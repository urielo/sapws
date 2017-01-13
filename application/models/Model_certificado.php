<?php

/**
 * Model_proposta { TYPE }
 * Description
 * @copyright (c) year, Uriel F. Oliveira
 */
class Model_certificado extends MY_Model
{

    public function __construct()
    {
        $this->table = 'certificados';
        $this->primary_key = 'id';
        $this->return_as = 'array';
        $this->timestamps = FALSE;
        $this->has_one['proposta'] = ['Model_proposta', 'idproposta','idproposta'];
        $this->has_many_pivot['custos'] = [
            'foreign_model'=>'Model_custos_produto',
            'pivot_table'=>'certificado_custo',
            'local_key'=>'id',
            'pivot_local_key'=>'certificado_id',
            'pivot_foreign_key'=>'custo_produto_id',
            'foreign_key'=>'id',
            'get_relate'=>true
        ];

        parent::__construct();
    }


//$this->has_many_pivot['posts'] = array(
//'foreign_model'=>'Post_model',
//'pivot_table'=>'posts_users',
//'local_key'=>'id',
//'pivot_local_key'=>'user_id', /* this is the related key in the pivot table to the local key
//                this is an optional key, but if your column name inside the pivot table
//                doesn't respect the format of "singularlocaltable_primarykey", then you must set it. In the next title
//                you will see how a pivot table should be set, if you want to  skip these keys */
//'pivot_foreign_key'=>'post_id', /* this is also optional, the same as above, but for foreign table's keys */
//'foreign_key'=>'id',
//'get_relate'=>FALSE /* another optional setting, which is explained below */
//);


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
