<?php

use Illuminate\Database\Eloquent\Model;

class Combustivel extends Model
{
    protected $table = 'combustivel';
    protected $primaryKey = 'id_auto_comb';
    protected $fillable = [

        "id_auto_comb",
        "nm_comb",
        'sigla'

    ];
    public $incrementing = false;

    
}

