<?php


use Illuminate\Database\Eloquent\Model;

class Cartoes extends Model
{

    protected $table = 'cartoes';
    protected $primaryKey = 'id';
    protected $fillable = [

        'id',
        'bandeira',
        'numero',
        'validade',
        'cvv',
        'clicpfcnpj',
        'nome',

    ];

    public $timestamps = FALSE;

    public function segurado()
    {
        $this->belongsTo(Segurado::class, 'clicpfcnpj', 'clicpfcnpj');
    }

    public function cobranca()
    {
        $this->belongsToMany(Cobranca::class, 'idcartao', 'id');
    }




}
