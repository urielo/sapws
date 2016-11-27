<?php

use Illuminate\Database\Eloquent\Model;

class Descontos extends Model
{

    protected $table = 'desconto';
    protected $fillable = [
        'descricao',
        'valor',
        'tipo',
    ];
    public $timestamps = FALSE;
    
   
    

}
