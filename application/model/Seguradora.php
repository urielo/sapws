<?php

use Illuminate\Database\Eloquent\Model;

class Seguradora extends Model
{

    protected $table = 'seguradora';
    protected $primaryKey = 'idseguradora';
    protected $fillable = [
        'idseguradora',
        'segcnpj',
        'segnome',
        'segcodsusep',
        'segmail',
        'segdddcel',
        'segnumcel',
        'segdddfixo',
        'segnumfixo',
        'segendereco',
        'segendnum',
        'segendcomplemento',
        'segcep',
        'segcidade',
        'segcduf',
    ];
    public $timestamps = FALSE;
    public $incrementing = false;

    /**
     * @return array
     */
    
    public function produtos()
    {
        return $this->hasManyThrough(Produtos::class, SeguradoraProduto::class,'idseguradora','idproduto','idproduto');
    }

}
