<?php


use Illuminate\Database\Eloquent\Model;

class RamoAtividades extends Model
{

    protected $table = 'ramoatividade_completa';
    protected $primaryKey = 'id_ramo_atividade';
    protected $fillable = ['id_ramo_atividade', 'nome_atividade'];
    public $timestamps = FALSE;

}
