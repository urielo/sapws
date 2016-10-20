<?php


use Illuminate\Database\Eloquent\Model;

class CategoriaFipes extends Model
{

    protected $table = 'fipe_categoria';
    protected $primaryKey = 'codefipe';
    protected $fillable = ['codefipe', 'idcategoria', 'idseguradora',];
    public $timestamps = FALSE;
    public $incrementing = false;
    
}
