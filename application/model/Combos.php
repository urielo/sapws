<?php

use Illuminate\Database\Eloquent\Model;

class Combos extends Model
{
    protected $table = 'produtos_combos';
    protected $primaryKey = 'idprodutomaster';
    protected $fillable = [

        "idprodutoopcional",
        "idprodutomaster",

    ];
    public $incrementing = false;

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'idprodutomaster', 'idproduto');
    }
}

