<?php


use Illuminate\Database\Eloquent\Model;

class TipoVeiculos extends Model
{

    protected $table = 'tipoveiculo';
    protected $primaryKey = 'idtipoveiculo';
    protected $fillable = ['desc', 'idtipoveiculo','codigo'];
    public $timestamps = FALSE;
    public $incrementing = false;

    
}
