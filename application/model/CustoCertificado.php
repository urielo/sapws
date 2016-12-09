<?php


use Illuminate\Database\Eloquent\Model;

class CustoCertificado extends Model
{
    protected $table = 'certificado_custo';
    protected $fillable = [
        'certificado_id',
        'custo_produto_id',
        'custo_anual',
        'custo_mensal',
    ];

    public $timestamps = FALSE;



   
}