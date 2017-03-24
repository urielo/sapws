<?php


use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    protected $fillable = [
            'cpfcnpj',
            'nome_razao',
            'email',
            'ddd_fone',
            'num_fone',
            'ddd_cel',
            'num_cel',
            'cep',
            'numero',
            'id_cotacao'
    ];

    public function prospect(){
        return $this->belongsTo(Cotacoes::class,'id_cotacao','idcotacao');
    }
}
