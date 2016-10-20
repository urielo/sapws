<?php

use Illuminate\Database\Eloquent\Model;

class Contingencia extends Model
{
    protected $table = 'contingencia';
    protected $primaryKey = 'id';
    protected $fillable = [

        "idseguradora",
        "valor",

    ];



}

