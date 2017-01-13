<?php
use Illuminate\Database\Eloquent\Model;

class SeguradoraProduto extends Model
{
    protected $table = 'seguradora_produto';
    protected $primaryKey = 'idseguradora';
    protected $fillable = ['idproduto',
        'idseguradora',
        'prdotudo_susep',
        'idade_aceitacao_min',
        'idade_aceitacao_max',
        'valor_aceitacao_min',
        'valor_aceitacao_max',
        'ind_exige_vistoria',
        'ind_exige_rastreador',
        'num_parcela_pagto',
        'dia_pgto',
        'id_corretor_master',
        'id_estipulante',
        'vl_franquia',
        'vl_franquia',
        'obg_mesma_seguradora',
        'id_produto_seguradora',
        'descricao_produto_seguradora',
    ];

    public $timestamps = FALSE;
    public $incrementing = false;


    public function seguradora()
    {
        return $this->belongsTo(Seguradora::class, 'idseguradora', 'idseguradora');
    }
}