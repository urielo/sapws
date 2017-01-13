<?php


use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{

    protected $table = 'parcelapgto';
    protected $primaryKey = 'idformapgto';
    protected $fillable = ['idformapgto',
        'descformapgto',
        'numparcsemjuros',
        'nummaxparc',
        'taxamesjuros',
        'idmeiopgto'];
    public $timestamps = FALSE;

}
