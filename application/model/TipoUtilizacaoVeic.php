<?php


use Illuminate\Database\Eloquent\Model;

class TipoUtilizacaoVeic extends Model
{

    protected $table = 'tipoutilizacaoveiculo';
    protected $primaryKey = 'idutilveiculo';
    protected $fillable = ['idutilveiculo', 'descutilveiculo'];
    public $timestamps = FALSE;

}
