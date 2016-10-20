<?php
use Illuminate\Database\Eloquent\Model;

class PrecoProdutos extends Model
{

    protected $table = 'precoprodutofipe';
    protected $primaryKey = 'idprecoproduto';
    protected $fillable = ['idproduto',
        'idprecoproduto',
        'nomeproduto',
        'caractproduto',
        'descprotudo',
        'dtcreate',
        'premioliquidoproduto',
        'vlrfipeminimo',
        'vlrfipemaximo',
        'vlrminprimparc',
        'lmiproduto',
        'porcentfranquia',
        'porcentfipepremio',
        'vlrminpremio',
        'idadeaceitamin',
        'idadeaceitamax',
        'valorfranquia',
        'indobrigrastreador',
        'idcategoria',];
    public $timestamps = FALSE;
//    public $incrementing = false;

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'idproduto', 'idproduto');
    }
}
