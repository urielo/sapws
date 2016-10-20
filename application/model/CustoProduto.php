<?php 
//namespace application\model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class CustoProduto extends Eloquent{

    protected $table = 'custo_produto';
    protected $fillable = [
        'idseguradora',
        'idproduto',
        'idprecoproduto',
        'custo_mensal',
        'custo_anual',
        'dt_create',
        'id',
    ];

    public function seguradora_produto()
    {
        return $this->hasMany(SeguradoraProduto::class,'idproduto','idproduto');
    }
}