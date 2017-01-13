<?php

use Illuminate\Database\Eloquent\Model;

class Motivos extends Model
{

    protected $table = 'motivos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'descrição'

    ];
    public $timestamps = FALSE;

}
