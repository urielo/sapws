<?php

use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{

    protected $table = 'produto';
    protected $primaryKey = 'idproduto';
    protected $fillable =
        [
            'idproduto',
            'nomeproduto',
            'descproduto',
            'caractproduto',
            'idtipoveiculo',
            'porcentindenizfipe',
            'vlrfipemaximo',
            'vlrfipeminimo',
            'qtdemaxparcelas',
            'indtabprecofipe',
            'indtabprecocategorianobre',
            'numparcelsemjuros',
            'jurosmesparcelamento',
            'ind_exige_vistoria',
            'codstatus',
            'idseguradora',
            'procsusepproduto',
            'codramoproduto',
            'cobertura',
            'tipoproduto',
            'tipodeseguro',
            'ind_exige_rastreador',

        ];
    public $timestamps = FALSE;

//    public $incrementing = false;


    public function precoproduto()
    {
        return $this->hasMany(PrecoProdutos::class, 'idproduto', 'idproduto');
    }

    public function combos()
    {
        return $this->hasMany(Combos::class, 'idprodutomaster', 'idproduto');
    }


    public function seguradoras()
    {
        return $this->hasMany(SeguradoraProduto::class,'idproduto','idproduto');
    }
}
