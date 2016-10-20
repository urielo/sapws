<?php

use Illuminate\Database\Eloquent\Model;

class CotacaoProdutos extends Model
{

    protected $table = 'cotacaoproduto';
    protected $primaryKey = 'idcotacaoproduto';
    protected $fillable = ["idproduto", "idprecoproduto", "idcotacao"];
    public $incrementing = false;

    public function produto()
    {
        return $this->hasOne(Produtos::class, 'idproduto', 'idproduto');
    }

    public function cotacao()
    {
        return $this->belongsTo(Cotacoes::class, 'idcotacao', 'idcotacao');
    }
    
  
}
