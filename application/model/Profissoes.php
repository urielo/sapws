<?php

use Illuminate\Database\Eloquent\Model;

class Profissoes extends Model
{

    protected $table = 'profissao_completa';
    protected $primaryKey = 'id_ocupacao';
    protected $fillable = ['id_ocupacao', 'nm_ocupacao'];
    public $timestamps = FALSE;

}
